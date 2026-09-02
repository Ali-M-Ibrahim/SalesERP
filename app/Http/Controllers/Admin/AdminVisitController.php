<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminVisitController extends Controller
{
    /*
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index(Request $request)
    {
        $query = Visit::query()->with(['customer', 'salesRep', 'visitPurpose', 'visitSamples.sample',]);


        /*
         * =====================================================
         * DATE FROM
         * =====================================================
         */
        if ($request->filled('from_date')) {

            $query->whereDate('scheduled_at', '>=', $request->from_date);
        }


        /*
         * =====================================================
         * DATE TO
         * =====================================================
         */
        if ($request->filled('to_date')) {

            $query->whereDate('scheduled_at', '<=', $request->to_date);
        }


        /*
         * =====================================================
         * SALES REP
         * =====================================================
         */
        if ($request->filled('sales_rep_id')) {

            $query->where('sales_rep_id', $request->sales_rep_id);
        }


        /*
         * =====================================================
         * CUSTOMER
         * =====================================================
         */
        if ($request->filled('customer_id')) {

            $query->where('customer_id', $request->customer_id);
        }


        /*
         * =====================================================
         * STATUS
         * =====================================================
         */
        if ($request->filled('status') && in_array($request->status, ['scheduled', 'checked_in', 'completed', 'missed', 'cancelled',])) {

            $query->where('status', $request->status);
        }


        /*
         * =====================================================
         * SEARCH
         * =====================================================
         */
        if ($request->filled('search')) {

            $search = trim($request->search);


            $query->where(function ($q) use ($search) {

                $q->where('purpose_other', 'like', "%{$search}%")->orWhere('visit_notes', 'like', "%{$search}%")->orWhere('client_requests', 'like', "%{$search}%")->orWhereHas('customer', function ($customerQuery) use ($search) {

                    $customerQuery->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");

                })->orWhereHas('salesRep', function ($salesQuery) use ($search) {

                    $salesQuery->where('name', 'like', "%{$search}%");

                });

            });
        }


        /*
         * =====================================================
         * SORT
         * =====================================================
         */
        $visits = $query->orderByDesc('scheduled_at')->orderByDesc('created_at')->paginate(20)->withQueryString();


        /*
         * Filters.
         */
        $salesReps = User::role('sales_rep')->orderBy('name')->get(['id', 'name',]);


        $customers = Customer::query()->orderBy('name')->get(['id', 'name', 'phone',]);


        /*
         * =====================================================
         * SUMMARY FOR CURRENT FILTER
         * =====================================================
         */

        $summaryQuery = Visit::query();


        if ($request->filled('from_date')) {

            $summaryQuery->whereDate('scheduled_at', '>=', $request->from_date);
        }


        if ($request->filled('to_date')) {

            $summaryQuery->whereDate('scheduled_at', '<=', $request->to_date);
        }


        if ($request->filled('sales_rep_id')) {

            $summaryQuery->where('sales_rep_id', $request->sales_rep_id);
        }


        if ($request->filled('customer_id')) {

            $summaryQuery->where('customer_id', $request->customer_id);
        }


        $totalCount = (clone $summaryQuery)->count();


        $scheduledCount = (clone $summaryQuery)->where('status', 'scheduled')->count();


        $checkedInCount = (clone $summaryQuery)->where('status', 'checked_in')->count();


        $completedCount = (clone $summaryQuery)->where('status', 'completed')->count();


        $missedCount = (clone $summaryQuery)->where('status', 'scheduled')->whereDate('scheduled_at', '<', today())->whereNull('check_in_at')->count();


        return view('admin.visits.index', compact('visits', 'salesReps', 'customers', 'totalCount', 'scheduledCount', 'checkedInCount', 'completedCount', 'missedCount'));
    }


    /*
     * =========================================================
     * SHOW / AJAX DETAILS
     * =========================================================
     */
    public function show(Visit $visit)
    {
        $visit->load(['customer', 'salesRep', 'visitPurpose', 'visitSamples.sample', 'creator',]);
        return view('admin.visits.show', compact('visit'));
    }
}
