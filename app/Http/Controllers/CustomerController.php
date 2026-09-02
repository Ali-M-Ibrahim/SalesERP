<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAssignment;
use App\Models\CustomerLocation;
use App\Models\CustomerStandHistory;
use App\Models\Sample;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitPurpose;
use App\Models\VisitSample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('customers.view'), 403);
        $user = auth()->user();
        $customers = Customer::query()->with(['currentAssignment.salesRep',])->when($user->hasRole('sales_rep'), function ($query) use ($user) {
            $query->whereHas('currentAssignment', function ($q) use ($user) {
                $q->where('sales_rep_id', $user->id);
            });
        })->when($request->filled('search'), function ($query) use ($request) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")->orWhere('customer_number', 'like', "%{$search}%");
            });
        })->when($request->filled('type'), fn($query) => $query->where('type', $request->type))->where('is_active', true)->latest()->paginate(15)->withQueryString();

        return view('customers.index', compact('customers'));
    }


    public function create()
    {

        abort_unless(auth()->user()->can('customers.create'), 403);

        $salesReps = User::role('sales_rep')->where('is_active', true)->orderBy('name')->get();

        return view('customers.create', compact('salesReps'));
    }


    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('customers.create'), 403);

        $validated = $request->validate(['type' => 'required|in:lead,customer', 'name' => 'required|string|max:255', 'phone' => 'nullable|string|max:50', 'email' => 'nullable|email|max:255', 'address' => 'nullable|string|max:1000', 'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90',], 'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180',], 'has_stand' => 'nullable|boolean', 'sales_rep_id' => 'nullable|exists:users,id',]);

        DB::transaction(function () use ($validated, &$customer) {

            $customer = Customer::create(['customer_number' => $this->generateCustomerNumber(), 'type' => $validated['type'], 'name' => $validated['name'], 'phone' => $validated['phone'] ?? null, 'email' => $validated['email'] ?? null, 'address' => $validated['address'] ?? null, 'latitude' => $validated['latitude'] ?? null, 'longitude' => $validated['longitude'] ?? null,

                'location_updated_at' => !empty($validated['latitude']) && !empty($validated['longitude']) ? now() : null,

                'location_updated_by' => !empty($validated['latitude']) && !empty($validated['longitude']) ? auth()->id() : null,

                'has_stand' => $validated['has_stand'] ?? false,

                'stand_last_updated_at' => ($validated['has_stand'] ?? false) ? now() : null,

                'created_by' => auth()->id(),

                'converted_at' => $validated['type'] === 'customer' ? now() : null,

                'is_active' => true,]);

            if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
                CustomerLocation::create(['customer_id' => $customer->id, 'latitude' => $validated['latitude'], 'longitude' => $validated['longitude'], 'address' => $validated['address'] ?? null, 'updated_by' => auth()->id(),]);
            }

            $salesRepId = auth()->user()->hasRole('sales_rep') ? auth()->id() : ($validated['sales_rep_id'] ?? null);

            if ($salesRepId) {
                CustomerAssignment::create(['customer_id' => $customer->id, 'sales_rep_id' => $salesRepId, 'assigned_by' => auth()->id(), 'assigned_at' => now(), 'is_active' => true,]);
            }
        });

        return redirect()->route('customers.show', $customer)->with('success', 'Customer created successfully.');
    }


    public function show(Customer $customer)
    {
        abort_unless(auth()->user()->can('customers.view'), 403);

        /*
         * Sales rep can only view customers
         * currently assigned to them.
         */
        if (auth()->user()->hasRole('sales_rep')) {

            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();

            abort_unless($allowed, 403);
        }


        /*
         * Customer basic relations.
         */
        $customer->load(['currentAssignment.salesRep', 'creator',

            'standHistories' => function ($query) {
                $query->latest();
            },]);


        /*
         * Total counts.
         */
        $visitCount = $customer->visits()->count();

        $resourceShareCount = $customer->resourceShares()->count();


        /*
         * Initial 10 visits.
         */
        $visits = $customer->visits()->with(['visitPurpose', 'salesRep', 'visitSamples.sample',])->latest('scheduled_at')->take(3)->get();


        /*
         * Initial 10 shared resources.
         */
        $resourceShares = $customer->resourceShares()->with(['resource.resourceCategory', 'sharedBy',])->latest('shared_at')->take(3)->get();

        $visitPurposes = VisitPurpose::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();


        $samples = Sample::query()->with('sampleCategory')->where('is_active', true)->orderBy('name')->get();

        $adminNotes = $customer->adminNotes()->with(['creator', 'salesRep',])->latest()->get();

        return view('customers.show', compact('adminNotes', 'customer', 'samples', 'visitPurposes', 'visits', 'resourceShares', 'visitCount', 'resourceShareCount'));
    }


    public function loadMoreVisits(Request $request, Customer $customer)
    {
//        abort_unless(auth()->user()->can('customers.view'), 403);


//        if (auth()->user()->hasRole('sales_rep')) {
//
//            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();
//
//            abort_unless($allowed, 403);
//        }


        $validated = $request->validate(['offset' => 'required|integer|min:0',]);


        $limit = 3;


        $visits = $customer->visits()->with(['visitPurpose', 'salesRep', 'visitSamples.sample',])->latest('scheduled_at')->skip($validated['offset'])->take($limit)->get();


        $total = $customer->visits()->count();

        $newOffset = $validated['offset'] + $visits->count();


        return response()->json(['html' => view('customers.partials.visits', compact('visits'))->render(),

            'loaded' => $visits->count(),

            'new_offset' => $newOffset,

            'has_more' => $newOffset < $total,]);
    }


    public function loadMoreResources(Request $request, Customer $customer)
    {
//        abort_unless(auth()->user()->can('customers.view'), 403);


//        if (auth()->user()->hasRole('sales_rep')) {
//
//            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();
//
//            abort_unless($allowed, 403);
//        }


        $validated = $request->validate(['offset' => 'required|integer|min:0',]);


        $limit = 3;


        $resourceShares = $customer->resourceShares()->with(['resource.resourceCategory', 'sharedBy',])->latest('shared_at')->skip($validated['offset'])->take($limit)->get();


        $total = $customer->resourceShares()->count();


        $newOffset = $validated['offset'] + $resourceShares->count();


        return response()->json(['html' => view('customers.partials.resource-shares', compact('resourceShares'))->render(),

            'loaded' => $resourceShares->count(),

            'new_offset' => $newOffset,

            'has_more' => $newOffset < $total,]);
    }


    public function edit(Customer $customer)
    {
        abort_unless(auth()->user()->can('customers.update'), 403);

        if (auth()->user()->hasRole('sales_rep')) {
            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();

            abort_unless($allowed, 403);
        }

        $salesReps = User::role('sales_rep')->where('is_active', true)->orderBy('name')->get();

        $customer->load('currentAssignment');

        return view('customers.edit', compact('customer', 'salesReps'));
    }


    public function update(Request $request, Customer $customer)
    {
        abort_unless(auth()->user()->can('customers.update'), 403);

        if (auth()->user()->hasRole('sales_rep')) {
            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();

            abort_unless($allowed, 403);
        }

        $validated = $request->validate(['type' => 'required|in:lead,customer', 'name' => 'required|string|max:255', 'phone' => 'nullable|string|max:50', 'email' => 'nullable|email|max:255', 'address' => 'nullable|string|max:1000', 'latitude' => 'nullable|numeric|between:-90,90', 'longitude' => 'nullable|numeric|between:-180,180', 'has_stand' => 'nullable|boolean', 'sales_rep_id' => 'nullable|exists:users,id',]);

        $wasLead = $customer->type === 'lead';

        $locationChanged = (string)$customer->latitude !== (string)($validated['latitude'] ?? null) || (string)$customer->longitude !== (string)($validated['longitude'] ?? null);


        DB::transaction(function () use (
            $customer, $validated, $wasLead, $locationChanged
        ) {

            $customer->update([

                'type' => $validated['type'],

                'name' => $validated['name'],

                'phone' => $validated['phone'] ?? null,

                'email' => $validated['email'] ?? null,

                'address' => $validated['address'] ?? null,

                'latitude' => $validated['latitude'] ?? null,

                'longitude' => $validated['longitude'] ?? null,

                'location_updated_at' => $locationChanged ? now() : $customer->location_updated_at,

                'location_updated_by' => $locationChanged ? auth()->id() : $customer->location_updated_by,

                'has_stand' => $validated['has_stand'] ?? false,

                'converted_at' => $wasLead && $validated['type'] === 'customer' ? now() : $customer->converted_at,

            ]);


            /*
             * Location history.
             */
            if ($locationChanged && isset($validated['latitude'], $validated['longitude'])) {

                CustomerLocation::create([

                    'customer_id' => $customer->id,

                    'latitude' => $validated['latitude'],

                    'longitude' => $validated['longitude'],

                    'address' => $validated['address'] ?? null,

                    'updated_by' => auth()->id(),

                ]);

            }

        });


        return redirect()->route('customers.show', $customer)->with('success', 'Customer updated successfully.');
    }


    private function generateCustomerNumber(): string
    {
        $last = Customer::query()->orderByDesc('created_at')->first();

        $number = 1;

        if ($last && preg_match('/(\d+)$/', $last->customer_number, $matches)) {
            $number = ((int)$matches[1]) + 1;
        }

        return 'CUS-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function updateStandDate(Customer $customer)
    {
        abort_unless(auth()->user()->can('customers.update'), 403);

        if (auth()->user()->hasRole('sales_rep')) {

            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();

            abort_unless($allowed, 403);
        }


        abort_unless($customer->has_stand, 422, 'Customer does not currently have a stand.');


        DB::transaction(function () use ($customer) {

            $customer->update(['stand_last_updated_at' => now(),]);


            CustomerStandHistory::create(['customer_id' => $customer->id, 'has_stand' => true, 'notes' => 'Stand update date refreshed.', 'updated_by' => auth()->id(),]);

        });


        return back()->with('success', 'Stand update date updated successfully.');
    }

    public function scheduleVisit(Request $request, Customer $customer)
    {
        abort_unless(auth()->user()->can('visits.create'), 403);

        if (auth()->user()->hasRole('sales_rep')) {

            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();

            abort_unless($allowed, 403);
        }

        if ($request->visit_purpose_id === 'other') {
            $request->merge(['visit_purpose_id' => null,]);
        }

        $validated = $request->validate([

            'scheduled_at' => ['required', 'date'],

            'visit_purpose_id' => ['nullable', 'exists:visit_purposes,id',],

            'purpose_other' => ['nullable', 'string', 'max:255',],

            'notes' => ['nullable', 'string', 'max:2000',],]);

        /*
         * If "Other" is selected, purpose_other
         * should be provided.
         */
        if (!$validated['visit_purpose_id'] && empty($validated['purpose_other'])) {
            return back()->withErrors(['visit_purpose_id' => 'Please select a visit purpose or enter another purpose.',])->withInput();
        }

        Visit::create(['customer_id' => $customer->id,

            'sales_rep_id' => auth()->user()->hasRole('sales_rep') ? auth()->id() : $customer->currentAssignment?->sales_rep_id,

            'visit_purpose_id' => $validated['visit_purpose_id'] ?? null,

            'purpose_other' => $validated['purpose_other'] ?? null,

            'scheduled_at' => $validated['scheduled_at'],

            'status' => 'scheduled',

            'visit_notes' => $validated['notes'] ?? null,

            'created_by' => auth()->id(),]);

        return back()->with('success', 'Visit scheduled successfully.');
    }

    public function logVisit(Request $request, Customer $customer)
    {
        abort_unless(auth()->user()->can('visits.create'), 403);

        /*
         * Sales rep can only log visits
         * for customers assigned to them.
         */
        if (auth()->user()->hasRole('sales_rep')) {

            $allowed = $customer->currentAssignment()->where('sales_rep_id', auth()->id())->exists();

            abort_unless($allowed, 403);
        }


        /*
         * Handle "Other" purpose.
         */
        $isOtherPurpose = $request->visit_purpose_id === 'other';

        if ($isOtherPurpose) {
            $request->merge(['visit_purpose_id' => null,]);
        }


        $validated = $request->validate([

            'visit_purpose_id' => ['nullable', 'exists:visit_purposes,id',],

            'purpose_other' => [$isOtherPurpose ? 'required' : 'nullable', 'string', 'max:255',],

            'visit_notes' => ['nullable', 'string', 'max:3000',],

            'client_requests' => ['nullable', 'string', 'max:3000',],

            'samples' => ['nullable', 'array',],

            'samples.*.selected' => ['nullable', 'boolean',],

            'samples.*.quantity' => ['nullable', 'integer', 'min:1', 'max:999',], 'contact_point' => ['required', 'string', 'max:255',], 'contact_position' => ['required', 'string', 'max:255',],

        ]);


        if (empty($validated['visit_purpose_id']) && empty($validated['purpose_other'])) {

            return back()->withErrors(['visit_purpose_id' => 'Please select a visit purpose.',])->withInput();
        }


        DB::transaction(function () use (
            $customer, $validated, &$visit
        ) {

            $visit = Visit::create([

                'customer_id' => $customer->id,

                'sales_rep_id' => auth()->user()->hasRole('sales_rep') ? auth()->id() : $customer->currentAssignment?->sales_rep_id,

                'visit_purpose_id' => $validated['visit_purpose_id'] ?? null,

                'purpose_other' => $validated['purpose_other'] ?? null,

                /*
                 * Actual logged visit.
                 * Default date is today.
                 */ 'scheduled_at' => now(),

                /*
                 * It is not completed yet.
                 * User still needs to check in.
                 */ 'status' => 'scheduled',

                'visit_notes' => $validated['visit_notes'] ?? null,

                'client_requests' => $validated['client_requests'] ?? null,

                'created_by' => auth()->id(), 'contact_point' => $validated['contact_point'] ?? null, 'contact_point_position' => $validated['contact_position'] ?? null,


            ]);


            /*
             * Samples with quantities.
             */
            foreach ($validated['samples'] ?? [] as $sampleId => $sampleData) {

                if (empty($sampleData['selected'])) {
                    continue;
                }


                VisitSample::create([

                    'visit_id' => $visit->id,

                    'sample_id' => $sampleId,

                    'quantity' => $sampleData['quantity'] ?? 1,

                ]);
            }

        });


        return response()->json(['success' => true,

            'message' => 'Visit created successfully.',

            'visit' => ['id' => $visit->id,],]);
    }

    public function checkInVisit(Request $request, Visit $visit)
    {
        abort_unless(auth()->user()->can('visits.checkin'), 403);

        /*
         * Sales representative can only check in
         * to their own visit.
         */
        if (auth()->user()->hasRole('sales_rep')) {
            abort_unless($visit->sales_rep_id === auth()->id(), 403);
        }


        if ($visit->check_in_at) {
            return response()->json(['success' => false, 'message' => 'This visit has already been checked in.',], 422);
        }


        /*
         * Visit must be scheduled for today.
         */
        if (!$visit->scheduled_at?->isToday()) {
            return response()->json(['success' => false, 'message' => 'You can only check in to a visit scheduled for today.',], 422);
        }


        $validated = $request->validate(['latitude' => ['required', 'numeric', 'between:-90,90',],

            'longitude' => ['required', 'numeric', 'between:-180,180',],

            'accuracy' => ['nullable', 'numeric', 'min:0',],]);


        $visit->update([/*
             * Server timestamp for audit purposes.
             */ 'check_in_at' => now(),

            'check_in_latitude' => $validated['latitude'],

            'check_in_longitude' => $validated['longitude'],

            'check_in_accuracy' => $validated['accuracy'] ?? null,

            'status' => 'checked_in',]);


        return response()->json(['success' => true, 'message' => 'Check-in completed successfully.',

            'visit' => ['id' => $visit->id,

                'status' => $visit->status,

                'check_in_at' => $visit->check_in_at?->format('H:i'),

                'check_in_latitude' => $visit->check_in_latitude,

                'check_in_longitude' => $visit->check_in_longitude,

                'check_in_accuracy' => $visit->check_in_accuracy,],]);
    }


    public function checkOutVisit(Request $request, Visit $visit)
    {
        abort_unless(auth()->user()->can('visits.checkout'), 403);


        /*
         * Sales representative can only check out
         * from their own visit.
         */
        if (auth()->user()->hasRole('sales_rep')) {
            abort_unless($visit->sales_rep_id === auth()->id(), 403);
        }


        /*
         * Must check in first.
         */
        if (!$visit->check_in_at) {
            return response()->json(['success' => false, 'message' => 'You must check in before checking out.',], 422);
        }


        /*
         * Prevent duplicate checkout.
         */
        if ($visit->check_out_at) {
            return response()->json(['success' => false, 'message' => 'This visit has already been checked out.',], 422);
        }


        $validated = $request->validate(['latitude' => ['required', 'numeric', 'between:-90,90',],

            'longitude' => ['required', 'numeric', 'between:-180,180',],

            'accuracy' => ['nullable', 'numeric', 'min:0',],]);


        $visit->update([/*
             * Server timestamp for audit purposes.
             */ 'check_out_at' => now(),

            'check_out_latitude' => $validated['latitude'],

            'check_out_longitude' => $validated['longitude'],

            'check_out_accuracy' => $validated['accuracy'] ?? null,

            'status' => 'completed',]);

        app(
            \App\Services\CustomerSatisfactionService::class
        )->scheduleForVisit(
            $visit->fresh()
        );


        return response()->json(['success' => true, 'message' => 'Visit completed successfully.',

            'visit' => ['id' => $visit->id,

                'status' => $visit->status,

                'check_in_at' => $visit->check_in_at?->format('H:i'),

                'check_out_at' => $visit->check_out_at?->format('H:i'),

                'check_out_latitude' => $visit->check_out_latitude,

                'check_out_longitude' => $visit->check_out_longitude,

                'check_out_accuracy' => $visit->check_out_accuracy,],]);
    }

    public function getVisit(Visit $visit)
    {
        abort_unless(auth()->user()->can('visits.view'), 403);

        if (auth()->user()->hasRole('sales_rep')) {
            abort_unless($visit->sales_rep_id === auth()->id(), 403);
        }

        $visit->load(['visitPurpose', 'visitSamples.sample', 'customer',]);

        return response()->json(['success' => true,

            'visit' => ['id' => $visit->id,

                'customer_id' => $visit->customer_id,

                'scheduled_at' => $visit->scheduled_at?->format('Y-m-d'),

                'visit_purpose_id' => $visit->visit_purpose_id,

                'contact_point' => $visit->contact_point,

                'contact_point_position' => $visit->contact_point_position,


                'purpose_other' => $visit->purpose_other,

                'visit_notes' => $visit->visit_notes,

                'client_requests' => $visit->client_requests,

                'status' => $visit->status,

                'check_in_at' => $visit->check_in_at?->format('H:i'),

                'check_out_at' => $visit->check_out_at?->format('H:i'),

                'samples' => $visit->visitSamples->mapWithKeys(function ($item) {
                    return [$item->sample_id => ['quantity' => $item->quantity,],];
                }),],]);
    }

    public function updateVisit(Request $request, Visit $visit)
    {
        abort_unless(auth()->user()->can('visits.create'), 403);

        if (auth()->user()->hasRole('sales_rep')) {
            abort_unless($visit->sales_rep_id === auth()->id(), 403);
        }

        /*
         * Do not allow editing a completed visit.
         */
        if ($visit->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Completed visits cannot be edited.',], 422);
        }

        $isOtherPurpose = $request->visit_purpose_id === 'other';

        if ($isOtherPurpose) {
            $request->merge(['visit_purpose_id' => null,]);
        }

        $validated = $request->validate(['visit_purpose_id' => ['nullable', 'exists:visit_purposes,id',],

            'purpose_other' => [$isOtherPurpose ? 'required' : 'nullable', 'string', 'max:255',],

            'visit_notes' => ['nullable', 'string', 'max:3000',],

            'client_requests' => ['nullable', 'string', 'max:3000',],

            'samples' => ['nullable', 'array',],

            'samples.*.selected' => ['nullable', 'boolean',],

            'samples.*.quantity' => ['nullable', 'integer', 'min:1', 'max:999',], 'contact_point' => ['required', 'string', 'max:255',], 'contact_position' => ['required', 'string', 'max:255',],

        ]);

        DB::transaction(function () use (
            $visit, $validated
        ) {

            $visit->update(['visit_purpose_id' => $validated['visit_purpose_id'] ?? null,

                'purpose_other' => $validated['purpose_other'] ?? null,

                'visit_notes' => $validated['visit_notes'] ?? null,

                'client_requests' => $validated['client_requests'] ?? null,

                'contact_point' => $validated['contact_point'] ?? null, 'contact_point_position' => $validated['contact_position'] ?? null,

            ]);

            /*
             * Replace samples.
             */
            $visit->visitSamples()->delete();

            foreach ($validated['samples'] ?? [] as $sampleId => $sampleData) {

                if (empty($sampleData['selected'])) {
                    continue;
                }

                VisitSample::create(['visit_id' => $visit->id, 'sample_id' => $sampleId, 'quantity' => $sampleData['quantity'] ?? 1,]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Visit updated successfully.',]);
    }

    public function cancel(Visit $visit)
    {
        /*
         * Sales rep can only cancel
         * his own visit.
         */
        if (auth()->user()->hasRole('sales_rep') && $visit->sales_rep_id !== auth()->id()) {
            abort(403);
        }


        /*
         * Only scheduled visits
         * can be cancelled.
         */
        if ($visit->status !== 'scheduled') {

            return response()->json(['message' => 'Only scheduled visits can be cancelled.',], 422);
        }


        /*
         * Extra protection:
         * cannot cancel after check-in.
         */
        if ($visit->check_in_at) {

            return response()->json(['message' => 'A checked-in visit cannot be cancelled.',], 422);
        }


        $visit->update(['status' => 'cancelled',]);


        return response()->json(['success' => true, 'message' => 'Visit cancelled successfully.',]);
    }

    public function reschedule(Request $request, Visit $visit)
    {
        /*
         * Sales rep can only modify
         * his own visit.
         */
        if (auth()->user()->hasRole('sales_rep') && $visit->sales_rep_id !== auth()->id()) {
            abort(403);
        }

        /*
         * Only scheduled visits
         * may be rescheduled.
         */
        if ($visit->status !== 'scheduled') {

            return response()->json(['message' => 'Only scheduled visits can be rescheduled.',], 422);
        }

        /*
         * A visit already checked in
         * cannot be rescheduled.
         */
        if ($visit->check_in_at) {
            return response()->json(['message' => 'A checked-in visit cannot be rescheduled.',], 422);
        }

        $validated = $request->validate(['scheduled_at' => ['required', 'date', 'after_or_equal:now',],]);
        $visit->update(['scheduled_at' => $validated['scheduled_at'],]);
        return response()->json(['success' => true,

            'message' => 'Visit rescheduled successfully.',

            'scheduled_at' => $visit->scheduled_at->format('d M Y H:i'),]);
    }

}
