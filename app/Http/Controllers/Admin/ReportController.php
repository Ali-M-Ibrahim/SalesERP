<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerCatalogueShare;
use App\Models\Resource;
use App\Models\Sample;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitSample;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /*
     * =========================================================
     * REPORT DASHBOARD
     * =========================================================
     */
    public function index(Request $request)
    {
        [$fromDate, $toDate] = $this->resolveDates($request);

        $salesReps = $this->salesReps();

        $salesRepId = $request->sales_rep_id;


        $visitQuery = Visit::query()->whereBetween('scheduled_at', [$fromDate, $toDate,]);


        if ($salesRepId) {

            $visitQuery->where('sales_rep_id', $salesRepId);
        }


        $totalVisits = (clone $visitQuery)->count();


        $completedVisits = (clone $visitQuery)->where('status', Visit::STATUS_COMPLETED ?? 'completed')->count();


        $missedVisits = (clone $visitQuery)->where('status', Visit::STATUS_SCHEDULED)->whereDate('scheduled_at', '<', today())->whereNull('check_in_at')->count();


        $samplesGiven = VisitSample::query()->whereHas('visit', function ($query) use (
            $fromDate, $toDate, $salesRepId
        ) {

            $query->whereBetween('scheduled_at', [$fromDate, $toDate]);

            if ($salesRepId) {

                $query->where('sales_rep_id', $salesRepId);
            }
        })->sum('quantity');


        $resourceShares = CustomerCatalogueShare::query()->whereBetween('shared_at', [$fromDate, $toDate])->when($salesRepId, fn($query) => $query->where('shared_by', $salesRepId))->count();


        return view('admin.reports.index', compact('fromDate', 'toDate', 'salesReps', 'salesRepId', 'totalVisits', 'completedVisits', 'missedVisits', 'samplesGiven', 'resourceShares'));
    }


    public function salesPerformance(Request $request)
    {
        [$fromDate, $toDate] = $this->resolveDates($request);

        $salesRepId = $request->sales_rep_id;


        /*
         * =========================================================
         * SALES REPRESENTATIVES
         * =========================================================
         */
        $salesRepQuery = User::role('sales_rep');


        if ($salesRepId) {

            $salesRepQuery->where('id', $salesRepId);
        }


        $salesReps = $salesRepQuery->orderBy('name')->get();


        /*
         * =========================================================
         * PERFORMANCE DATA
         * =========================================================
         */
        $performance = $salesReps->map(function ($rep) use (
            $fromDate, $toDate
        ) {

            /*
             * -------------------------------------------------
             * BASE VISIT QUERY
             * -------------------------------------------------
             */
            $visits = Visit::query()->where('sales_rep_id', $rep->id)->whereBetween('scheduled_at', [$fromDate, $toDate]);


            /*
             * -------------------------------------------------
             * TOTAL VISITS
             *
             * All visit records during selected period.
             * Includes:
             * scheduled
             * checked in
             * completed
             * missed calculated visits
             * -------------------------------------------------
             */
            $totalVisits = (clone $visits)->count();


            /*
             * -------------------------------------------------
             * COMPLETED VISITS
             * -------------------------------------------------
             */
            $completed = (clone $visits)->where('status', Visit::STATUS_COMPLETED)->count();


            /*
             * -------------------------------------------------
             * CURRENTLY CHECKED IN
             * -------------------------------------------------
             */
            $checkedIn = (clone $visits)->where('status', Visit::STATUS_CHECKED_IN)->count();


            /*
             * -------------------------------------------------
             * UPCOMING / TODAY SCHEDULED
             *
             * These are NOT considered missed.
             * -------------------------------------------------
             */
            $scheduled = (clone $visits)->where('status', Visit::STATUS_SCHEDULED)->whereDate('scheduled_at', '>=', today())->count();


            /*
             * -------------------------------------------------
             * MISSED VISITS
             *
             * A visit is considered missed when:
             *
             * - its scheduled date already passed
             * - salesperson never checked in
             * - status is still scheduled
             *
             * Today's visits are not counted as missed yet.
             * -------------------------------------------------
             */
            $missed = (clone $visits)->where('status', Visit::STATUS_SCHEDULED)->whereDate('scheduled_at', '<', today())->whereNull('check_in_at')->count();


            /*
             * -------------------------------------------------
             * UNIQUE CUSTOMERS VISITED
             *
             * Customer counts only when an actual
             * check-in exists.
             *
             * If ABC Company has 4 visits,
             * it is still 1 customer visited.
             * -------------------------------------------------
             */
            $customersVisited = (clone $visits)->whereNotNull('check_in_at')->whereNotNull('customer_id')->distinct()->count('customer_id');


            /*
             * -------------------------------------------------
             * ASSIGNED CUSTOMERS
             * -------------------------------------------------
             */
            $assignedCustomers = $rep->assignedCustomers()->count();


            /*
             * -------------------------------------------------
             * CUSTOMER COVERAGE %
             *
             * Unique customers visited
             * ------------------------- × 100
             * Assigned customers
             * -------------------------------------------------
             */
            $coverageRate = $assignedCustomers > 0 ? round(($customersVisited / $assignedCustomers) * 100, 1) : 0;


            /*
             * -------------------------------------------------
             * VISITS THAT WERE ACTUALLY DUE
             *
             * Future scheduled visits should NOT reduce
             * completion rate.
             * -------------------------------------------------
             */
            $dueVisits = $completed + $missed;


            /*
             * -------------------------------------------------
             * COMPLETION RATE
             *
             * Completed
             * ------------------ × 100
             * Completed + Missed
             * -------------------------------------------------
             */
            $completionRate = $dueVisits > 0 ? round(($completed / $dueVisits) * 100, 1) : 0;


            /*
             * -------------------------------------------------
             * SAMPLES GIVEN
             * -------------------------------------------------
             */
            $samplesGiven = VisitSample::query()->whereHas('visit', function ($query) use (
                $rep, $fromDate, $toDate
            ) {

                $query->where('sales_rep_id', $rep->id)->whereBetween('scheduled_at', [$fromDate, $toDate,]);

            })->sum('quantity');


            /*
             * -------------------------------------------------
             * RESOURCES SHARED
             * -------------------------------------------------
             */
            $resourcesShared = CustomerCatalogueShare::query()->where('shared_by', $rep->id)->whereBetween('shared_at', [$fromDate,

                $toDate])->count();


            /*
             * -------------------------------------------------
             * RETURN ROW
             * -------------------------------------------------
             */
            return [

                'rep' => $rep,

                'assigned_customers' => $assignedCustomers,

                'customers_visited' => $customersVisited,

                'coverage_rate' => $coverageRate,

                'total_visits' => $totalVisits,

                'completed' => $completed,

                'missed' => $missed,

                'scheduled' => $scheduled,

                'checked_in' => $checkedIn,

                'due_visits' => $dueVisits,

                'completion_rate' => $completionRate,

                'samples_given' => $samplesGiven,

                'resources_shared' => $resourcesShared,];

        });


        /*
         * =========================================================
         * OVERALL TOTALS
         * =========================================================
         */
        $totals = [

            'assigned_customers' => $performance->sum('assigned_customers'),

            'customers_visited' => $performance->sum('customers_visited'),

            'total_visits' => $performance->sum('total_visits'),

            'completed' => $performance->sum('completed'),

            'missed' => $performance->sum('missed'),

            'scheduled' => $performance->sum('scheduled'),

            'checked_in' => $performance->sum('checked_in'),

            'samples_given' => $performance->sum('samples_given'),

            'resources_shared' => $performance->sum('resources_shared'),];


        /*
         * Overall completion rate.
         */
        $totalDue = $totals['completed'] + $totals['missed'];


        $totals['completion_rate'] = $totalDue > 0 ? round(($totals['completed'] / $totalDue) * 100, 1) : 0;


        /*
         * Overall customer coverage.
         */
        $totals['coverage_rate'] = $totals['assigned_customers'] > 0 ? round(($totals['customers_visited'] / $totals['assigned_customers']) * 100, 1) : 0;


        /*
         * Sales reps list for filter.
         */
        $filterSalesReps = $this->salesReps();


        return view('admin.reports.sales-performance', compact('performance', 'totals', 'fromDate', 'toDate', 'filterSalesReps', 'salesRepId'));
    }

    /*
     * =========================================================
     * VISIT AUDIT
     * =========================================================
     */
    public function visits(Request $request)
    {
        return redirect()->route('admin.visits.index', array_filter(['from_date' => $request->from_date,

            'to_date' => $request->to_date,

            'sales_rep_id' => $request->sales_rep_id,

            'customer_id' => $request->customer_id,

            'status' => $request->status,]));
    }


    /*
     * =========================================================
     * CUSTOMER COVERAGE
     * =========================================================
     */
    public function customerCoverage(Request $request)
    {
        [$fromDate, $toDate] = $this->resolveDates($request);


        $salesRepId = $request->sales_rep_id;


        $query = Customer::query()->with(['currentAssignment.salesRep',])->withCount(['visits', 'resourceShares',]);


        /*
         * Sales rep filter.
         */
        if ($salesRepId) {

            $query->whereHas('currentAssignment', fn($q) => $q->where('sales_rep_id', $salesRepId));
        }


        /*
         * Coverage filter.
         */
        switch ($request->coverage) {

            case 'never_visited':

                $query->whereDoesntHave('visits');

                break;


            case 'not_visited_period':

                $query->whereDoesntHave('visits', function ($q) use (
                    $fromDate, $toDate
                ) {

                    $q->whereBetween('scheduled_at', [$fromDate, $toDate]);

                });

                break;


            case 'no_samples':

                $query->whereDoesntHave('visits.visitSamples');

                break;


            case 'no_resources':

                $query->whereDoesntHave('resourceShares');

                break;


            case 'unassigned':

                $query->whereDoesntHave('currentAssignment');

                break;
        }


        $customers = $query->orderBy('name')->paginate(20)->withQueryString();


        /*
         * Calculate last visit separately.
         */
        $customers->getCollection()->transform(function ($customer) {

            $customer->last_visit = $customer->visits()->latest('scheduled_at')->first();

            return $customer;
        });


        $salesReps = $this->salesReps();


        return view('admin.reports.customer-coverage', compact('customers', 'salesReps', 'fromDate', 'toDate', 'salesRepId'));
    }


    /*
     * =========================================================
     * DISTRIBUTION
     * =========================================================
     */
    public function distribution(Request $request)
    {
        [$fromDate, $toDate] = $this->resolveDates($request);


        $salesRepId = $request->sales_rep_id;


        /*
         * Samples.
         */
        $sampleDistribution = VisitSample::query()->selectRaw('
                    sample_id,
                    SUM(quantity) as total_quantity,
                    COUNT(DISTINCT visit_id) as visit_count
                    ')->whereHas('visit', function ($query) use (
            $fromDate, $toDate, $salesRepId
        ) {

            $query->whereBetween('scheduled_at', [$fromDate, $toDate,]);


            if ($salesRepId) {

                $query->where('sales_rep_id', $salesRepId);
            }
        })->with('sample')->groupBy('sample_id')->orderByDesc('total_quantity')->get();


        /*
         * Resources.
         */
        $resourceDistribution = CustomerCatalogueShare::query()->selectRaw('
                    resource_id,
                    COUNT(*) as total_shares,
                    COUNT(DISTINCT customer_id) as customer_count
                    ')->whereBetween('shared_at', [$fromDate,

            $toDate])->when($salesRepId, fn($query) => $query->where('shared_by', $salesRepId))->with('resource')->groupBy('resource_id')->orderByDesc('total_shares')->get();


        $salesReps = $this->salesReps();


        return view('admin.reports.distribution', compact('sampleDistribution', 'resourceDistribution', 'salesReps', 'salesRepId', 'fromDate', 'toDate'));
    }


    /*
     * =========================================================
     * COMMON DATE FILTER
     * =========================================================
     */
    private function resolveDates(Request $request): array
    {

        try {

            $fromDate = $request->filled('from_date') ? Carbon::parse($request->from_date)->startOfDay() : now()->startOfMonth();

            $toDate = $request->filled('to_date') ? Carbon::parse($request->to_date)->endOfDay() : now()->endOfMonth();

        } catch (\Exception $exception) {

            $fromDate = now()->startOfMonth();

            $toDate = now()->endOfMonth();
        }


        /*
         * Prevent invalid range.
         */
        if ($fromDate->gt($toDate)) {

            [$fromDate, $toDate] = [$toDate, $fromDate];
        }


        return [$fromDate, $toDate,];
    }


    private function salesReps()
    {
        return User::role('sales_rep')->orderBy('name')->get(['id', 'name', 'email',]);
    }

    public function locationVerification(Request $request)
    {
        [$fromDate, $toDate] = $this->resolveDates($request);

        $salesRepId = $request->sales_rep_id;

        /*
         * Distance threshold in meters.
         */
        $threshold = 100;


        $query = Visit::query()->with(['customer', 'salesRep', 'visitPurpose',])->whereBetween('scheduled_at', [$fromDate, $toDate]);


        /*
         * Sales Rep filter.
         */
        if ($salesRepId) {

            $query->where('sales_rep_id', $salesRepId);
        }


        /*
         * Customer filter.
         */
        if ($request->filled('customer_id')) {

            $query->where('customer_id', $request->customer_id);
        }


        /*
         * We are interested mainly in visits
         * where some GPS action happened.
         */
        $query->where(function ($q) {

            $q->whereNotNull('check_in_at')->orWhereNotNull('check_out_at');

        });


        $visits = $query->orderByDesc('scheduled_at')->orderByDesc('created_at')->get();


        /*
         * Calculate audit values.
         */
        $verification = $visits->map(function ($visit) use (
            $threshold
        ) {

            $checkInCustomerDistance = $visit->checkInCustomerDistance();

            $checkOutCustomerDistance = $visit->checkOutCustomerDistance();

            $checkInOutDistance = $visit->checkInCheckOutDistance();


            /*
             * Determine status.
             */
            if (!$visit->customer?->latitude || !$visit->customer?->longitude) {

                $status = 'no_customer_location';

            } elseif (!$visit->check_in_latitude || !$visit->check_in_longitude) {

                $status = 'no_gps';

            } elseif ($checkInCustomerDistance !== null && $checkInCustomerDistance <= $threshold && ($checkOutCustomerDistance === null || $checkOutCustomerDistance <= $threshold)) {

                $status = 'verified';

            } else {

                $status = 'review';
            }


            return ['visit' => $visit,

                'check_in_customer_distance' => $checkInCustomerDistance,

                'check_out_customer_distance' => $checkOutCustomerDistance,

                'check_in_out_distance' => $checkInOutDistance,

                'status' => $status,];

        });


        /*
         * Optional status filter.
         */
        if ($request->filled('verification_status')) {

            $verification = $verification->where('status', $request->verification_status)->values();
        }


        /*
         * Summary.
         */
        $summary = [

            'total' => $verification->count(),

            'verified' => $verification->where('status', 'verified')->count(),

            'review' => $verification->where('status', 'review')->count(),

            'no_customer_location' => $verification->where('status', 'no_customer_location')->count(),

            'no_gps' => $verification->where('status', 'no_gps')->count(),

        ];


        $salesReps = $this->salesReps();


        $customers = Customer::query()->orderBy('name')->get(['id', 'name', 'phone',]);


        return view('admin.reports.location-verification', compact('verification', 'summary', 'fromDate', 'toDate', 'salesReps', 'salesRepId', 'customers', 'threshold'));
    }


}
