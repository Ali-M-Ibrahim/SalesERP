<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminCalendarController extends Controller
{
    public function index(Request $request)
    {
        /*
         * =====================================================
         * SELECTED MONTH
         * =====================================================
         */
        try {

            $month = $request->filled('month') ? Carbon::createFromFormat('Y-m', $request->month)->startOfMonth() : now()->startOfMonth();

        } catch (\Exception $exception) {

            $month = now()->startOfMonth();
        }


        $monthStart = $month->copy()->startOfMonth();

        $monthEnd = $month->copy()->endOfMonth();


        /*
         * =====================================================
         * SALES REP FILTER
         * =====================================================
         */
        $salesRepId = $request->filled('sales_rep_id') ? $request->sales_rep_id : null;


        /*
         * Validate selected rep.
         */
        if ($salesRepId) {

            $validRep = User::role('sales_rep')->where('id', $salesRepId)->exists();


            if (!$validRep) {

                $salesRepId = null;
            }
        }


        /*
         * =====================================================
         * SALES REPS
         * =====================================================
         */
        $salesReps = User::role('sales_rep')->orderBy('name')->get(['id', 'name', 'email',]);


        /*
         * =====================================================
         * VISITS
         * =====================================================
         */
        $query = Visit::query()->with(['customer', 'salesRep', 'visitPurpose',])
            ->whereBetween('scheduled_at', [$monthStart, $monthEnd])
        ;


        if ($salesRepId) {

            $query->where('sales_rep_id', $salesRepId);
        }


        $visits = $query->orderBy('scheduled_at')->orderBy('created_at')->get();



        /*
         * =====================================================
         * GROUP VISITS BY DATE
         * =====================================================
         */
        $visitsByDate = $visits->groupBy(function ($visit) {

            return $visit->scheduled_at->format('Y-m-d');
        });


        /*
         * =====================================================
         * DAYS OF SELECTED MONTH
         *
         * Only actual days of the month.
         * No previous/next month overlap.
         * =====================================================
         */
        $days = collect();

        $cursor = $monthStart->copy();


        while ($cursor->lte($monthEnd)) {

            $dateKey = $cursor->format('Y-m-d');


            $days->push(['date' => $cursor->copy(),

                'visits' => $visitsByDate->get($dateKey, collect()),]);


            $cursor->addDay();
        }


        /*
         * =====================================================
         * JSON DATA FOR DAY MODAL
         *
         * Build this here rather than inside Blade.
         * =====================================================
         */
        $calendarVisits = $days->mapWithKeys(function ($day) {

            $dateKey = $day['date']->format('Y-m-d');


            $items = $day['visits']->map(function ($visit) {

                    /*
                     * Calculate missed dynamically.
                     */
                    $isMissed = $visit->status === 'scheduled' && $visit->scheduled_at->lt(today()) && !$visit->check_in_at;


                    return ['id' => $visit->id,

                        'customer' => $visit->customer?->name ?? 'Customer',

                        'sales_rep' => $visit->salesRep?->name ?? '—',

                        'purpose' => $visit->visitPurpose?->name ?? $visit->purpose_other ?? 'Visit',

                        'status' => $isMissed ? 'missed' : $visit->status,

                        'check_in_at' => $visit->check_in_at?->format('H:i'),

                        'check_out_at' => $visit->check_out_at?->format('H:i'),

                        'url' => route('admin.visits.show', $visit),];
                })->values()->all();


            return [$dateKey => $items,];
        });


        /*
         * =====================================================
         * SUMMARY
         * =====================================================
         */
        $totalVisits = $visits->count();


        $completedCount = $visits->where('status', 'completed')->count();


        $checkedInCount = $visits->where('status', 'checked_in')->count();

        $cancelledCount = $visits->where('status', 'cancelled')->count();




        /*
         * Missed:
         * - scheduled date already passed
         * - status still scheduled
         * - no check-in
         */
        $missedCount = $visits->filter(function ($visit) {

                return $visit->status === 'scheduled' && $visit->scheduled_at->lt(today()) && !$visit->check_in_at;
            })->count();


        /*
         * Upcoming / still scheduled.
         *
         * Today is included because the day
         * has not necessarily ended.
         */
        $scheduledCount = $visits->filter(function ($visit) {

                return $visit->status === 'scheduled' && $visit->scheduled_at->gte(today());
            })->count();


        /*
         * =====================================================
         * MONTH NAVIGATION
         * =====================================================
         */
        $previousMonth = $month->copy()->subMonth()->format('Y-m');


        $nextMonth = $month->copy()->addMonth()->format('Y-m');


        return view('admin.calendar.index', compact('month', 'days', 'calendarVisits', 'salesReps', 'salesRepId',

                'totalVisits', 'completedCount', 'checkedInCount', 'missedCount', 'scheduledCount',

                'previousMonth', 'nextMonth','cancelledCount'));
    }
}
