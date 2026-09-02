<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCatalogueShare;
use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /*
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('resources.view'), 403);

        $query = Resource::query()->with(['resourceCategory', 'creator',])->where('is_active', true);


        /*
         * Search.
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");

            });
        }


        /*
         * Category filter.
         */
        if ($request->filled('category')) {

            $query->where('resource_category_id', $request->category);
        }


        /*
         * File type filter.
         */
        if ($request->filled('type') && in_array($request->type, ['pdf', 'image'])) {

            $query->where('file_type', $request->type);
        }


        $resources = $query->latest()->paginate(12)->withQueryString();


        $categories = ResourceCategory::query()->where('is_active', true)->orderBy('name')->get();


        /*
         * Customers available for WhatsApp sharing.
         */
        $customersQuery = Customer::query()->select(['id', 'name', 'phone',])->orderBy('name');


        if (auth()->user()->hasRole('sales_rep')) {

            $userId = auth()->id();

            $customersQuery->whereHas('currentAssignment', function ($query) use ($userId) {

                $query->where('sales_rep_id', $userId);

            });
        }


        $customers = $customersQuery->get();


        return view('resources.index', compact('resources', 'categories', 'customers'));
    }


    /*
     * =========================================================
     * CREATE
     * =========================================================
     */
    public function create()
    {
        abort_unless(auth()->user()->can('resources.create'), 403);


        $categories = ResourceCategory::query()->where('is_active', true)->orderBy('name')->get();


        return view('resources.create', compact('categories'));
    }


    /*
     * =========================================================
     * STORE
     * =========================================================
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('resources.create'), 403);


        $validated = $request->validate([

            'resource_category_id' => ['required', 'exists:resource_categories,id',],

            'name' => ['required', 'string', 'max:255',],

            'description' => ['nullable', 'string', 'max:3000',],

            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240',],

        ]);


        $file = $request->file('file');
        $path = $file->store('resources', 'public');
        $extension = strtolower($file->getClientOriginalExtension());
        $fileType = $extension === 'pdf' ? 'pdf' : 'image';

        $resource = Resource::create([

            'resource_category_id' => $validated['resource_category_id'],

            'name' => $validated['name'],

            'description' => $validated['description'] ?? null,

            'file_path' => $path,

            'file_type' => $fileType,

            'is_active' => true,

            'created_by' => auth()->id(),

        ]);


        return redirect()->route('resources.show', $resource)->with('success', 'Resource created successfully.');
    }


    /*
     * =========================================================
     * SHOW
     * =========================================================
     */
    public function show(Resource $resource)
    {
        abort_unless(auth()->user()->can('resources.view'), 403);


        abort_unless($resource->is_active, 404);


        $resource->load(['resourceCategory', 'creator',]);


        /*
         * Customers available for sharing.
         */
        $customersQuery = Customer::query()->select(['id', 'name', 'phone',])->orderBy('name');


        if (auth()->user()->hasRole('sales_rep')) {

            $userId = auth()->id();


            $customersQuery->whereHas('currentAssignment', function ($query) use ($userId) {

                $query->where('sales_rep_id', $userId);

            });
        }


        $customers = $customersQuery->get();


        return view('resources.show', compact('resource', 'customers'));
    }


    /*
     * =========================================================
     * EDIT
     * =========================================================
     */
    public function edit(Resource $resource)
    {
        $this->authorizeUpdate($resource);


        $categories = ResourceCategory::query()->where('is_active', true)->orderBy('name')->get();


        return view('resources.edit', compact('resource', 'categories'));
    }


    /*
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update(Request $request, Resource $resource)
    {
        $this->authorizeUpdate($resource);


        $validated = $request->validate([

            'resource_category_id' => ['required', 'exists:resource_categories,id',],

            'name' => ['required', 'string', 'max:255',],

            'description' => ['nullable', 'string', 'max:3000',],

            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240',],

        ]);


        $data = [

            'resource_category_id' => $validated['resource_category_id'],

            'name' => $validated['name'],

            'description' => $validated['description'] ?? null,

        ];


        /*
         * Replace file only if another
         * file was uploaded.
         */
        if ($request->hasFile('file')) {

            /*
             * Delete old file.
             */
            if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {

                Storage::disk('public')->delete($resource->file_path);
            }


            $file = $request->file('file');


            $path = $file->store('resources', 'public');


            $extension = strtolower($file->getClientOriginalExtension());


            $data['file_path'] = $path;


            $data['file_type'] = $extension === 'pdf' ? 'pdf' : 'image';
        }


        $resource->update($data);


        return redirect()->route('resources.show', $resource)->with('success', 'Resource updated successfully.');
    }


    /*
     * =========================================================
     * DELETE / DEACTIVATE
     *
     * Admin only.
     * =========================================================
     */
    public function destroy(Resource $resource)
    {
        abort_unless(auth()->user()->can('resources.delete'), 403);


        abort_if(auth()->user()->hasRole('sales_rep'), 403);


        /*
         * Prefer deactivation because
         * the resource may already appear
         * in customer sharing history.
         */
        $resource->update(['is_active' => false,]);


        return redirect()->route('resources.index')->with('success', 'Resource removed successfully.');
    }


    /*
     * =========================================================
     * SHARE THROUGH WHATSAPP
     * =========================================================
     */
    public function share(Request $request, Resource $resource)
    {
        abort_unless(auth()->user()->can('resources.share'), 403);


        abort_unless($resource->is_active, 404);


        $validated = $request->validate([

            'customer_id' => ['required', 'exists:customers,id',],

        ]);


        $customer = Customer::findOrFail($validated['customer_id']);


        /*
         * Sales rep can share only to
         * assigned customers.
         */
        if (auth()->user()->hasRole('sales_rep')) {

            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();


            abort_unless($allowed, 403);
        }


        /*
         * Record share BEFORE opening WhatsApp.
         */
        CustomerCatalogueShare::create([

            'customer_id' => $customer->id,

            'resource_id' => $resource->id,

            'shared_by' => auth()->id(),

            'method' => 'whatsapp',

            'shared_at' => now(),

        ]);


        /*
         * Laravel public resource URL.
         */
        $resourceUrl = route('resources.public', $resource);


        $message = "Hello {$customer->name},\n\n" . "Please check the following resource:\n\n" . "{$resource->name}\n" . "{$resourceUrl}";


        /*
         * Prepare customer phone.
         */
        $phone = preg_replace('/\D+/', '', $customer->phone ?? '');


        if ($phone) {

            $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

        } else {

            $whatsappUrl = 'https://wa.me/?text=' . urlencode($message);
        }


        return response()->json([

            'success' => true,

            'message' => 'Resource share recorded successfully.',

            'whatsapp_url' => $whatsappUrl,

        ]);
    }


    /*
     * =========================================================
     * PUBLIC RESOURCE
     *
     * No login required because customer
     * receives this link through WhatsApp.
     * =========================================================
     */
    public function publicResource(Resource $resource)
    {
        abort_unless($resource->is_active, 404);


        abort_unless(Storage::disk('public')->exists($resource->file_path), 404);


        // for server
        return redirect(
            Storage::disk('public')->url($resource->file_path)
        );

        // for local
//        return response()->file(Storage::disk('public')->path($resource->file_path));
    }


    /*
     * =========================================================
     * RESOURCE UPDATE AUTHORIZATION
     * =========================================================
     */
    private function authorizeUpdate(Resource $resource): void
    {

        $user = auth()->user();


        abort_unless($user->can('resources.update'), 403);


        /*
         * Sales representative can only
         * edit resources they created.
         */
        if ($user->hasRole('sales_rep')) {

            abort_unless($resource->created_by === $user->id, 403);
        }
    }
}
