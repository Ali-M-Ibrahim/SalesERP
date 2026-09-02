<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Resource;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitSample;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
         * =====================================================
         * SALES REPRESENTATIVES
         * =====================================================
         */
        $salesReps = User::role('sales_rep')->orderBy('name')->get();


        /*
         * Selected rep.
         *
         * null = all reps
         */
        $selectedRepId = $request->filled('sales_rep_id') ? $request->sales_rep_id : null;


        /*
         * Validate selected rep.
         */
        if ($selectedRepId) {

            $validRep = User::role('sales_rep')->where('id', $selectedRepId)->exists();

            if (!$validRep) {
                $selectedRepId = null;
            }
        }


        /*
         * =====================================================
         * TODAY'S VISITS
         * =====================================================
         */
        $todayVisitsQuery = Visit::query()->with(['customer', 'salesRep', 'visitPurpose', 'visitSamples.sample',])->whereDate('scheduled_at', today());


        if ($selectedRepId) {

            $todayVisitsQuery->where('sales_rep_id', $selectedRepId);
        }


        $todayVisits = $todayVisitsQuery->orderByRaw("
                CASE
                    WHEN status = 'checked_in' THEN 1
                    WHEN status = 'scheduled' THEN 2
                    WHEN status = 'completed' THEN 3
                    WHEN status = 'missed' THEN 4
                    WHEN status = 'cancelled' THEN 5
                    ELSE 6
                END
            ")->orderByDesc('created_at')->get();


        /*
         * =====================================================
         * KPI BASE QUERY
         * =====================================================
         */
        $visitQuery = Visit::query();

        if ($selectedRepId) {
            $visitQuery->where('sales_rep_id', $selectedRepId);
        }


        $todayVisitCount = (clone $visitQuery)->whereDate('scheduled_at', today())->count();


        $completedTodayCount = (clone $visitQuery)->whereDate('scheduled_at', today())->where('status', 'completed')->count();


        $checkedInTodayCount = (clone $visitQuery)->whereDate('scheduled_at', today())->where('status', 'checked_in')->count();


        /*
         * Samples given today.
         */
        $samplesGivenToday = VisitSample::query()->whereHas('visit', function ($query) use (
            $selectedRepId
        ) {

            $query->whereDate('scheduled_at', today());


            if ($selectedRepId) {

                $query->where('sales_rep_id', $selectedRepId);
            }

        })->sum('quantity');


        /*
         * =====================================================
         * CUSTOMER COUNT
         * =====================================================
         */
        $customerQuery = Customer::query();


        if ($selectedRepId) {

            $customerQuery->whereHas('currentAssignment', function ($query) use (
                $selectedRepId
            ) {

                $query->where('sales_rep_id', $selectedRepId);

            });
        }


        $customerCount = (clone $customerQuery)->where('type', 'customer')->count();


        $leadCount = (clone $customerQuery)->where('type', 'lead')->count();


        /*
         * =====================================================
         * NEEDS ATTENTION
         * =====================================================
         */

        $customersNeverVisitedQuery = Customer::query();


        $customersWithoutSamplesQuery = Customer::query();


        if ($selectedRepId) {

            $customersNeverVisitedQuery->whereHas('currentAssignment', fn($query) => $query->where('sales_rep_id', $selectedRepId));


            $customersWithoutSamplesQuery->whereHas('currentAssignment', fn($query) => $query->where('sales_rep_id', $selectedRepId));
        }


        $customersNeverVisitedCount = $customersNeverVisitedQuery->whereDoesntHave('visits')->count();


        $customersWithoutSamplesCount = $customersWithoutSamplesQuery->whereDoesntHave('visits.visitSamples')->count();


        /*
         * Unassigned only makes sense globally.
         */
        $unassignedCustomerCount = $selectedRepId ? 0 : Customer::query()->whereDoesntHave('currentAssignment')->count();


        /*
         * =====================================================
         * RECENT RESOURCES
         * =====================================================
         */
        $recentResources = Resource::query()->with(['resourceCategory', 'creator',])->where('is_active', true)->latest()->limit(5)->get();


        return view('admin.dashboard', compact('salesReps', 'selectedRepId',

            'todayVisits',

            'todayVisitCount', 'completedTodayCount', 'checkedInTodayCount', 'samplesGivenToday',

            'customerCount', 'leadCount',

            'customersNeverVisitedCount', 'customersWithoutSamplesCount', 'unassignedCustomerCount',

            'recentResources'));
    }

    private function customerQuery(Request $request)
    {
        $query = Customer::query()->with(['currentAssignment.salesRep', 'visits',])->withCount(['visits',

            'visits as completed_visits_count' => function ($query) {
                $query->where('status', 'completed');
            },

            'resourceShares',]);


        /*
         * Search
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($query) use ($search) {

                $query->where('name', 'like', "%{$search}%")->orWhere('customer_number', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");

            });
        }


        /*
         * Customer type
         */
        if ($request->filled('type')) {

            $query->where('type', $request->type);

        }


        /*
         * Sales Representative
         */
        if ($request->filled('sales_rep_id')) {

            $query->whereHas('currentAssignment', function ($query) use ($request) {

                $query->where('sales_rep_id', $request->sales_rep_id);

            });
        }


        /*
         * Quick Status Filters
         */
        if ($request->status === 'unassigned') {

            $query->whereDoesntHave('currentAssignment');

        }


        if ($request->status === 'never_visited') {

            $query->whereDoesntHave('visits', function ($query) {

                $query->whereNotNull('check_in_at');

            });

        }


        if ($request->status === 'no_samples') {

            $query->whereDoesntHave('visits.visitSamples');

        }


        return $query->orderBy('name');
    }

    public function export(Request $request)
    {
        $customers = $this->customerQuery($request)->get();

        return Excel::download(new CustomersExport($customers), 'customers-' . now()->format('Y-m-d-His') . '.xlsx');
    }

}
