<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerVisitReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'customer_id' => [
                'nullable',
                'uuid',
                'exists:customers,id',
            ],

            'sales_rep_id' => [
                'nullable',
                'uuid',
                'exists:users,id',
            ],

            'from_date' => [
                'nullable',
                'date',
            ],

            'to_date' => [
                'nullable',
                'date',
                'after_or_equal:from_date',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $from = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : now()->startOfYear();

        $to = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : now()->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | Filter dropdowns
        |--------------------------------------------------------------------------
        */

        $customers = Customer::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);


        $salesReps = User::role('sales_rep')
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Customers query
        |--------------------------------------------------------------------------
        |
        | Important:
        |
        | We start from customers, not visits.
        |
        | Therefore customers with ZERO visits will still appear in
        | the report.
        |--------------------------------------------------------------------------
        */

        $customerQuery = Customer::query()
            ->with([
                'currentAssignment',
                'currentAssignment.salesRep',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Customer filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('customer_id')) {

            $customerQuery->where(
                'id',
                $request->customer_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sales rep filter
        |--------------------------------------------------------------------------
        |
        | This assumes your customer has:
        |
        | currentAssignment
        |
        | and the assignment contains:
        |
        | sales_rep_id
        |--------------------------------------------------------------------------
        */

        if ($request->filled('sales_rep_id')) {

            $customerQuery->whereHas(
                'currentAssignment',
                function ($query) use ($request) {

                    $query->where(
                        'sales_rep_id',
                        $request->sales_rep_id
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get customers
        |--------------------------------------------------------------------------
        */

        $customerList = $customerQuery
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Load visits in ONE query
        |--------------------------------------------------------------------------
        |
        | Avoid querying visits separately for every customer.
        |--------------------------------------------------------------------------
        */

        $customerIds = $customerList
            ->pluck('id');


        $visits = Visit::query()
            ->whereIn('customer_id', $customerIds)
            ->where(function ($query) {
                $query
                    ->whereNotNull('check_in_at')
                    ->orWhere('status', 'completed');
            })
            ->whereBetween(
                'scheduled_at',
                [$from, $to]
            )
            ->when(
                $request->filled('sales_rep_id'),
                function ($query) use ($request) {

                    $query->where(
                        'sales_rep_id',
                        $request->sales_rep_id
                    );
                }
            )
            ->orderBy('scheduled_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Group visits by customer
        |--------------------------------------------------------------------------
        */

        $visitsByCustomer = $visits
            ->groupBy('customer_id');


        /*
        |--------------------------------------------------------------------------
        | Build report
        |--------------------------------------------------------------------------
        */

        $reportRows = collect();


        foreach ($customerList as $customer) {

            /*
             * Visits for this customer.
             */

            $customerVisits =
                $visitsByCustomer->get(
                    $customer->id,
                    collect()
                );


            /*
            |--------------------------------------------------------------------------
            | Total visits
            |--------------------------------------------------------------------------
            */

            $totalVisits =
                $customerVisits->count();


            /*
            |--------------------------------------------------------------------------
            | Completed visits
            |--------------------------------------------------------------------------
            */

            $completedVisits =
                $customerVisits
                    ->where(
                        'status',
                        'completed'
                    )
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Visits within 7 days
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | Jan 1
            | Jan 5   -> counted
            | Jan 20
            | Jan 25  -> counted
            |
            | Result = 2
            |--------------------------------------------------------------------------
            */

            $visitsWithin7Days = 0;


            /*
             * Only compare visits if we have at least two.
             */

            if ($customerVisits->count() > 1) {

                $sortedVisits =
                    $customerVisits
                        ->sortBy('scheduled_at')
                        ->values();


                for (
                    $i = 1;
                    $i < $sortedVisits->count();
                    $i++
                ) {

                    $previousVisit =
                        $sortedVisits[$i - 1];

                    $currentVisit =
                        $sortedVisits[$i];


                    if (
                        !$previousVisit->scheduled_at ||
                        !$currentVisit->scheduled_at
                    ) {
                        continue;
                    }


                    /*
                     * Difference between consecutive visits.
                     */

                    $differenceInDays =
                        $previousVisit
                            ->scheduled_at
                            ->diffInDays(
                                $currentVisit
                                    ->scheduled_at
                            );


                    /*
                     * Current visit happened within
                     * 7 days of the previous visit.
                     */

                    if ($differenceInDays <= 7) {

                        $visitsWithin7Days++;
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Percentage of repeat visits within 7 days
            |--------------------------------------------------------------------------
            */

            $possibleRepeatVisits =
                max(
                    $totalVisits - 1,
                    0
                );


            $within7DaysRate =
                $possibleRepeatVisits > 0
                    ? round(
                    (
                        $visitsWithin7Days
                        / $possibleRepeatVisits
                    ) * 100,
                    1
                )
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | First / Last visit
            |--------------------------------------------------------------------------
            */

            $firstVisit =
                $customerVisits
                    ->sortBy('scheduled_at')
                    ->first();


            $lastVisit =
                $customerVisits
                    ->sortByDesc('scheduled_at')
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | Days since last visit
            |--------------------------------------------------------------------------
            */

            $daysSinceLastVisit = null;


            if (
                $lastVisit &&
                $lastVisit->scheduled_at
            ) {

                $daysSinceLastVisit =
                    $lastVisit
                        ->scheduled_at
                        ->copy()
                        ->startOfDay()
                        ->diffInDays(
                            today()
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | Add row
            |--------------------------------------------------------------------------
            */

            $reportRows->push([

                'customer' =>
                    $customer,

                'sales_rep' =>
                    $customer
                        ->currentAssignment
                        ?->salesRep,

                'total_visits' =>
                    $totalVisits,

                'completed_visits' =>
                    $completedVisits,

                'visits_within_7_days' =>
                    $visitsWithin7Days,

                'within_7_days_rate' =>
                    $within7DaysRate,

                'first_visit' =>
                    $firstVisit,

                'last_visit' =>
                    $lastVisit,

                'days_since_last_visit' =>
                    $daysSinceLastVisit,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Summary KPIs
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total_customers' =>
                $reportRows->count(),

            'customers_with_visits' =>
                $reportRows
                    ->where(
                        'total_visits',
                        '>',
                        0
                    )
                    ->count(),

            'customers_without_visits' =>
                $reportRows
                    ->where(
                        'total_visits',
                        0
                    )
                    ->count(),

            'total_visits' =>
                $reportRows
                    ->sum(
                        'total_visits'
                    ),

            'visits_within_7_days' =>
                $reportRows
                    ->sum(
                        'visits_within_7_days'
                    ),
        ];


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = 25;

        $currentPage =
            LengthAwarePaginator::resolveCurrentPage();


        $currentItems =
            $reportRows
                ->slice(
                    ($currentPage - 1)
                    * $perPage,
                    $perPage
                )
                ->values();


        $reportRows =
            new LengthAwarePaginator(
                $currentItems,
                $reportRows->count(),
                $perPage,
                $currentPage,
                [
                    'path' =>
                        $request->url(),

                    'query' =>
                        $request->query(),
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.customer-visits',
            compact(
                'customers',
                'salesReps',
                'reportRows',
                'summary',
                'from',
                'to'
            )
        );
    }
}
