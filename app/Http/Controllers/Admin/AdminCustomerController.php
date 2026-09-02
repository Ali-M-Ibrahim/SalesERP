<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCustomerController extends Controller
{
    /*
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index(Request $request)
    {
        $query = Customer::query()->with(['currentAssignment.salesRep',])->withCount(['visits',

                'visits as completed_visits_count' => function ($query) {

                    $query->where('status', 'completed');

                },

                'resourceShares',]);


        /*
         * =====================================================
         * SEARCH
         * =====================================================
         */
        if ($request->filled('search')) {

            $search = trim($request->search);


            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('customer_number', 'like', "%{$search}%");

            });
        }


        /*
         * =====================================================
         * CUSTOMER TYPE
         * =====================================================
         */
        if ($request->filled('type') && in_array($request->type, ['lead', 'customer',])) {

            $query->where('type', $request->type);
        }


        /*
         * =====================================================
         * SALES REPRESENTATIVE
         * =====================================================
         */
        if ($request->filled('sales_rep_id')) {

            $salesRepId = $request->sales_rep_id;


            $query->whereHas('currentAssignment', function ($q) use ($salesRepId) {

                $q->where('sales_rep_id', $salesRepId);

            });
        }


        /*
         * =====================================================
         * SPECIAL STATUS FILTERS
         * =====================================================
         */
        if ($request->filled('status')) {

            switch ($request->status) {

                /*
                 * No current sales rep.
                 */ case 'unassigned':

                $query->whereDoesntHave('currentAssignment');

                break;


                /*
                 * Customer has never had a visit.
                 */ case 'never_visited':

                $query->whereDoesntHave('visits');

                break;


                /*
                 * Customer has visits but no sample
                 * has ever been given.
                 */ case 'no_samples':

                $query->whereDoesntHave('visits.visitSamples');

                break;
            }
        }


        /*
         * =====================================================
         * SORT
         * =====================================================
         */
        $customers = $query->orderBy('name')->paginate(20)->withQueryString();


        /*
         * Sales reps for filters/assignment.
         */
        $salesReps = User::role('sales_rep')->orderBy('name')->get(['id', 'name', 'email',]);


        return view('admin.customers.index', compact('customers', 'salesReps'));
    }


    /*
     * =========================================================
     * ASSIGN / REASSIGN
     * =========================================================
     */
    public function assign(Request $request, Customer $customer)
    {
        $validated = $request->validate([

            'sales_rep_id' => ['required', 'exists:users,id',],

        ]);


        /*
         * Make sure selected user is actually
         * a sales representative.
         */
        $salesRep = User::findOrFail($validated['sales_rep_id']);


        abort_unless($salesRep->hasRole('sales_rep'), 422, 'Selected user is not a sales representative.');


        DB::transaction(function () use (
            $customer, $salesRep
        ) {

            /*
             * Current assignment.
             */
            $currentAssignment = $customer->currentAssignment()->first();


            /*
             * Already assigned to this rep.
             */
            if ($currentAssignment && $currentAssignment->sales_rep_id === $salesRep->id) {

                return;
            }


            /*
             * Close old assignment.
             */
            if ($currentAssignment) {

                $currentAssignment->update(['ended_at' => now(),]);
            }


            /*
             * Create new assignment.
             */
            CustomerAssignment::create([

                'customer_id' => $customer->id,

                'sales_rep_id' => $salesRep->id,

                'assigned_at' => now(),

                'ended_at' => null,

            ]);

        });


        return redirect()->back()->with('success', 'Customer assigned successfully.');
    }
}
