<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        /*
         * =====================================================
         * MONTH
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
         * VISITS FOR LOGGED-IN SALES REP
         * =====================================================
         */
        $visits = Visit::query()
            ->with(['customer', 'visitPurpose',])->where('sales_rep_id', auth()->id())

            ->orderBy('scheduled_at')->orderBy('created_at')->get();

        /*
         * =====================================================
         * GROUP BY DATE
         * =====================================================
         */
        $visitsByDate = $visits->groupBy(function ($visit) {

                return $visit->scheduled_at->format('Y-m-d');
            });


        /*
         * =====================================================
         * DAYS
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
         * DATA FOR DAY MODAL
         * =====================================================
         */
        $calendarVisits = $days->mapWithKeys(function ($day) {

                $dateKey = $day['date']->format('Y-m-d');


                $items = $day['visits']->map(function ($visit) {

                        /*
                         * Missed is calculated dynamically.
                         */
                        $isMissed = $visit->status === 'scheduled' && $visit->scheduled_at->lt(today()) && !$visit->check_in_at;


                        return ['id' => $visit->id,

                            'customer' => $visit->customer?->name ?? 'Customer',

                            'purpose' => $visit->visitPurpose?->name ?? $visit->purpose_other ?? 'Visit',

                            'status' => $isMissed ? 'missed' : $visit->status,

                            'check_in_at' => $visit->check_in_at?->format('H:i'),

                            'check_out_at' => $visit->check_out_at?->format('H:i'),

                            /*
                             * Customer page.
                             */ 'url' => route('customers.show', $visit->customer_id),];
                    })->values()->all();


                return [$dateKey => $items,];
            });


        /*
         * =====================================================
         * KPIs
         * =====================================================
         */
        $totalVisits = $visits->count();


        $completedCount = $visits->where('status', 'completed')->count();


        $checkedInCount = $visits->where('status', 'checked_in')->count();

        $canceledInCount = $visits->where('status', 'cancelled')->count();




        /*
         * Past scheduled visit
         * without check-in.
         */
        $missedCount = $visits->filter(function ($visit) {

                return $visit->status === 'scheduled' && $visit->scheduled_at->lt(today()) && !$visit->check_in_at;
            })->count();


        /*
         * Today and future scheduled visits.
         */
        $scheduledCount = $visits->filter(function ($visit) {

                return $visit->status === 'scheduled' && $visit->scheduled_at->gte(today());
            })->count();


        /*
         * =====================================================
         * NAVIGATION
         * =====================================================
         */
        $previousMonth = $month->copy()->subMonth()->format('Y-m');


        $nextMonth = $month->copy()->addMonth()->format('Y-m');


        return view('calendar.index', compact('month', 'days', 'calendarVisits',

                'totalVisits', 'completedCount', 'checkedInCount', 'missedCount', 'scheduledCount',

                'previousMonth', 'nextMonth','canceledInCount'));
    }
}
