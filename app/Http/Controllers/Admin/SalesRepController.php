<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitSample;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesRepController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('sales_rep')->withCount(['assignedCustomers', 'salesVisits',]);


        if ($request->filled('search')) {

            $search = trim($request->search);


            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");

            });
        }


        $salesReps = $query->orderBy('name')->paginate(15)->withQueryString();


        return view('admin.sales-reps.index', compact('salesReps'));
    }


    public function show(Request $request, User $user)
    {
        abort_unless($user->hasRole('sales_rep'), 404);


        /*
         * Selected month.
         */
        try {

            $month = Carbon::createFromFormat('Y-m', $request->input('month', now()->format('Y-m')))->startOfMonth();

        } catch (\Exception $exception) {

            $month = now()->startOfMonth();
        }


        $monthEnd = $month->copy()->endOfMonth();


        /*
         * Assigned Customers.
         */
        $assignedCustomers = $user->assignedCustomers()->orderBy('name')->limit(10)->get();


        $assignedCustomerCount = $user->assignedCustomers()->count();


        /*
         * Monthly visits.
         */
        $monthlyVisitsQuery = Visit::query()->where('sales_rep_id', $user->id)

            ->whereBetween('scheduled_at', [
                $month->copy()->startOfDay(),
                $monthEnd->copy()->endOfDay(),
            ]);


        $monthlyVisitCount = (clone $monthlyVisitsQuery)->count();


        $completedCount = (clone $monthlyVisitsQuery)->where('status', 'completed')->count();

        $canceledCount = (clone $monthlyVisitsQuery)->where('status', 'cancelled')->count();



        $missedCount = (clone $monthlyVisitsQuery)->where('status', 'scheduled')->whereDate('scheduled_at', '<', today()) ->whereNull('check_in_at')->count();


        $scheduledCount = (clone $monthlyVisitsQuery)->where('status', 'scheduled')->count();


        /*
         * Samples during month.
         */
        $samplesGiven = VisitSample::query()->whereHas('visit', function ($query) use (
                $user, $month, $monthEnd
            ) {

                $query->where('sales_rep_id', $user->id)

                    ->whereBetween('scheduled_at', [
                        $month->copy()->startOfDay(),
                        $monthEnd->copy()->endOfDay(),
                    ]);




            })->sum('quantity');


        /*
         * Recent visits.
         */
        $recentVisits = Visit::query()->with(['customer', 'visitPurpose', 'visitSamples.sample',])->where('sales_rep_id', $user->id)->orderByDesc('scheduled_at')->orderByDesc('created_at')->limit(10)->get();


        return view('admin.sales-reps.show', compact('user', 'month', 'assignedCustomers', 'assignedCustomerCount', 'monthlyVisitCount', 'completedCount', 'missedCount', 'scheduledCount', 'samplesGiven', 'recentVisits','canceledCount'));
    }
}
