<?php

namespace App\Http\Controllers;

use App\Models\AdminNote;
use App\Models\Customer;
use App\Models\Visit;
use App\Models\VisitSample;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
         * =====================================================
         * SALES REPRESENTATIVE DASHBOARD
         * =====================================================
         */
        if ($user->hasRole('sales_rep')) {

            /*
             * Assigned customers.
             */
            $assignedCustomerIds = Customer::query()->whereHas('currentAssignment', function ($query) use ($user) {

                    $query->where('sales_rep_id', $user->id);

                })->pluck('id');


            $assignedCustomersCount = $assignedCustomerIds->count();


            /*
             * Today's visits.
             */
            $todayVisits = Visit::query()->with(['customer', 'visitPurpose', 'visitSamples.sample',])->where('sales_rep_id', $user->id)->whereDate('scheduled_at', today())->orderByRaw("
                    CASE
                        WHEN status = 'checked_in' THEN 1
                        WHEN status = 'scheduled' THEN 2
                        WHEN status = 'completed' THEN 3
                        ELSE 4
                    END
                ")->orderBy('created_at')->get();


            /*
             * Dashboard counters.
             */
            $todayVisitCount = $todayVisits->count();


            $completedTodayCount = $todayVisits->where('status', 'completed')->count();


            $checkedInTodayCount = $todayVisits->where('status', 'checked_in')->count();


            /*
             * Total quantity of samples given today.
             */
            $samplesGivenToday = VisitSample::query()->whereHas('visit', function ($query) use ($user) {

                    $query->where('sales_rep_id', $user->id)->whereDate('scheduled_at', today());

                })->sum('quantity');


            /*
             * Upcoming visits.
             *
             * Do not include today because today already
             * has its own section.
             */
            $upcomingVisits = Visit::query()->with(['customer', 'visitPurpose',])->where('sales_rep_id', $user->id)->where('status', 'scheduled')->whereDate('scheduled_at', '>', today())->orderBy('scheduled_at')->limit(5)->get();


            /*
             * Customers not yet visited.
             */
            $customersNotVisited = Customer::query()->whereIn('id', $assignedCustomerIds)->whereDoesntHave('visits', function ($query) use ($user) {

                    $query->where('sales_rep_id', $user->id);

                })->orderBy('name')->limit(5)->get();


            $adminNotes = AdminNote::query()
                ->with([
                    'creator',
                    'noteable',
                ])
                ->where(
                    'sales_rep_id',
                    auth()->id()
                )
                ->orderByRaw(
                    'CASE WHEN read_at IS NULL THEN 0 ELSE 1 END'
                )
                ->orderByDesc('is_important')
                ->latest()
                ->limit(20)
                ->get();

            return view('dashboard', compact('todayVisits', 'adminNotes','todayVisitCount', 'assignedCustomersCount', 'completedTodayCount', 'checkedInTodayCount', 'samplesGivenToday', 'upcomingVisits', 'customersNotVisited'));
        }

        return view('dashboard', ['todayVisits' => collect(),

            'todayVisitCount' => 0,

            'assignedCustomersCount' => Customer::count(),

            'completedTodayCount' => Visit::whereDate('scheduled_at', today())->where('status', 'completed')->count(),

            'checkedInTodayCount' => Visit::whereDate('scheduled_at', today())->where('status', 'checked_in')->count(),

            'samplesGivenToday' => VisitSample::query()->whereHas('visit', fn($query) => $query->whereDate('scheduled_at', today()))->sum('quantity'),

            'upcomingVisits' => collect(),

            'customersNotVisited' => collect(),]);
    }
}
