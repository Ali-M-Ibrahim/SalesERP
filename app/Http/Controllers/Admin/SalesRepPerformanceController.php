<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerSatisfactionAnswer;
use App\Models\CustomerSatisfactionInvitation;
use App\Models\CustomerSatisfactionSetting;
use App\Models\SalesPerformanceSetting;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitSample;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesRepPerformanceController extends Controller
{

    public function settings()
    {
        $settings = SalesPerformanceSetting::query()
            ->orderBy('sort_order')
            ->get();

        return view(
            'admin.settings.sales-performance',
            compact('settings')
        );
    }


    public function updateSettings(Request $request)
    {
        $validated = $request->validate([

            'weights' => [
                'required',
                'array',
            ],

            'weights.*' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'active' => [
                'nullable',
                'array',
            ],
        ]);


        /*
         * Calculate only active weights.
         */

        $activeIds =
            array_keys(
                $request->input(
                    'active',
                    []
                )
            );


        $totalWeight = 0;


        foreach (
            $validated['weights']
            as $id => $weight
        ) {

            if (
                in_array(
                    $id,
                    $activeIds
                )
            ) {

                $totalWeight +=
                    (float) $weight;
            }
        }


        /*
         * Require active weights to equal 100%.
         */

        if (
            abs(
                $totalWeight - 100
            ) > 0.01
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'weights' =>
                        'The total weight of active KPIs must equal 100%. Current total: '
                        . $totalWeight
                        . '%.',
                ]);
        }


        foreach (
            $validated['weights']
            as $id => $weight
        ) {

            $setting =
                SalesPerformanceSetting::findOrFail(
                    $id
                );


            $setting->update([

                'weight' =>
                    $weight,

                'is_active' =>
                    in_array(
                        $id,
                        $activeIds
                    ),
            ]);
        }


        return back()->with(
            'success',
            'Performance weights updated successfully.'
        );
    }

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
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
        | Sales representatives
        |--------------------------------------------------------------------------
        */

        $salesReps = User::role('sales_rep')
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        $from = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : now()->endOfDay();


        $selectedSalesRep = null;

        $report = null;


        /*
        |--------------------------------------------------------------------------
        | Performance weights
        |--------------------------------------------------------------------------
        */

        $weights = SalesPerformanceSetting::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Don't calculate until a sales rep is selected
        |--------------------------------------------------------------------------
        */

        if (!$request->filled('sales_rep_id')) {

            return view(
                'admin.reports.sales-rep-performance',
                compact(
                    'salesReps',
                    'selectedSalesRep',
                    'report',
                    'weights',
                    'from',
                    'to'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Selected sales rep
        |--------------------------------------------------------------------------
        */

        $selectedSalesRep = User::role('sales_rep')
            ->findOrFail($request->sales_rep_id);


        /*
        |--------------------------------------------------------------------------
        | Base visit query
        |--------------------------------------------------------------------------
        */

        $visits = Visit::query()
            ->where(
                'sales_rep_id',
                $selectedSalesRep->id
            )
            ->whereBetween(
                'scheduled_at',
                [$from, $to]
            );


        /*
        |--------------------------------------------------------------------------
        | Visit KPIs
        |--------------------------------------------------------------------------
        */

        $totalVisits = (clone $visits)->count();


        $completedVisits = (clone $visits)
            ->where('status', 'completed')
            ->count();


        $checkedInVisits = (clone $visits)
            ->whereNotNull('check_in_at')
            ->count();


        /*
         * Missed is dynamic in your system.
         *
         * A visit becomes missed only when:
         *
         * - status = scheduled
         * - no check-in
         * - scheduled date is before today
         */

        $missedVisits = (clone $visits)
            ->where('status', 'scheduled')
            ->whereNull('check_in_at')
            ->whereDate(
                'scheduled_at',
                '<',
                today()
            )
            ->count();


        $dueVisits =
            $completedVisits
            + $missedVisits;


        $completionRate = $dueVisits > 0
            ? round(
                ($completedVisits / $dueVisits) * 100,
                1
            )
            : 0;


        $missedRate = $dueVisits > 0
            ? round(
                ($missedVisits / $dueVisits) * 100,
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Scheduled / upcoming
        |--------------------------------------------------------------------------
        */

        $scheduledVisits = (clone $visits)
            ->where('status', 'scheduled')
            ->whereDate(
                'scheduled_at',
                '>=',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Customer KPIs
        |--------------------------------------------------------------------------
        */

        $customersVisited = (clone $visits)
            ->whereNotNull('check_in_at')
            ->distinct()
            ->count('customer_id');


        /*
         * Current assigned customer portfolio.
         *
         * This assumes your existing currentAssignment relation.
         */

        $assignedCustomers = Customer::query()
            ->whereHas(
                'currentAssignment',
                function ($query) use ($selectedSalesRep) {

                    $query->where(
                        'sales_rep_id',
                        $selectedSalesRep->id
                    );
                }
            )
            ->count();


        $coverageRate = $assignedCustomers > 0
            ? round(
                ($customersVisited / $assignedCustomers) * 100,
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Working days
        |--------------------------------------------------------------------------
        */

        $workingDays = 0;

        $currentDate = $from->copy()->startOfDay();

        $lastDate = $to->copy()->startOfDay();


        while ($currentDate->lte($lastDate)) {

            if (!$currentDate->isWeekend()) {
                $workingDays++;
            }

            $currentDate->addDay();
        }


        $visitFrequency = $workingDays > 0
            ? round(
                $completedVisits / $workingDays,
                2
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Samples / Product engagement
        |--------------------------------------------------------------------------
        */

        $visitsWithSamples = (clone $visits)
            ->whereHas('visitSamples')
            ->count();


        $totalSamples = VisitSample::query()
            ->whereHas(
                'visit',
                function ($query) use (
                    $selectedSalesRep,
                    $from,
                    $to
                ) {

                    $query
                        ->where(
                            'sales_rep_id',
                            $selectedSalesRep->id
                        )
                        ->whereBetween(
                            'scheduled_at',
                            [$from, $to]
                        );
                }
            )
            ->sum('quantity');


        $productEngagementRate = $completedVisits > 0
            ? round(
                (
                    $visitsWithSamples
                    / $completedVisits
                ) * 100,
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Customer satisfaction
        |--------------------------------------------------------------------------
        */

        /*
         * I recommend using the visit date for period attribution rather than
         * invitation created_at. That keeps the survey tied to the visit that
         * actually occurred during the selected performance period.
         */

        $invitations = CustomerSatisfactionInvitation::query()
            ->where(
                'sales_rep_id',
                $selectedSalesRep->id
            )
            ->whereHas(
                'visit',
                function ($query) use ($from, $to) {

                    $query->whereBetween(
                        'scheduled_at',
                        [$from, $to]
                    );
                }
            );


        $surveysSent = (clone $invitations)
            ->whereNotNull('sent_at')
            ->count();


        $surveyResponses = (clone $invitations)
            ->whereNotNull('completed_at')
            ->count();


        $surveyResponseRate = $surveysSent > 0
            ? round(
                ($surveyResponses / $surveysSent) * 100,
                1
            )
            : 0;


        /*
         * Get invitation IDs that belong to this performance period.
         */

        $completedInvitationIds = (clone $invitations)
            ->whereNotNull('completed_at')
            ->pluck('id');


        /*
         * Overall/general rating.
         *
         * Uses rating questions from the "general" category.
         */

        $averageSatisfaction =
            CustomerSatisfactionAnswer::query()
                ->whereIn(
                    'invitation_id',
                    $completedInvitationIds
                )
                ->whereNotNull('rating')
                ->whereHas(
                    'question',
                    function ($query) {

                        $query
                            ->where(
                                'category',
                                'general'
                            )
                            ->where(
                                'type',
                                'rating'
                            );
                    }
                )
                ->avg('rating');


        $averageSatisfaction = $averageSatisfaction
            ? round($averageSatisfaction, 2)
            : 0;


        /*
         * Convert 1-5 rating to 0-100.
         */

        $satisfactionScore =
            $averageSatisfaction > 0
                ? round(
                (
                    $averageSatisfaction
                    / 5
                ) * 100,
                1
            )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Low ratings
        |--------------------------------------------------------------------------
        */

        $satisfactionSettings =
            CustomerSatisfactionSetting::first();


        $lowRatingThreshold =
            $satisfactionSettings
                ?->low_rating_threshold
            ?? 2;


        $lowRatingResponses =
            CustomerSatisfactionInvitation::query()
                ->whereIn(
                    'id',
                    $completedInvitationIds
                )
                ->whereHas(
                    'answers',
                    function ($query) use (
                        $lowRatingThreshold
                    ) {

                        $query
                            ->whereNotNull('rating')
                            ->where(
                                'rating',
                                '<=',
                                $lowRatingThreshold
                            );
                    }
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | GPS verification
        |--------------------------------------------------------------------------
        |
        | Your Visit model already has:
        |
        | checkInCustomerDistance()
        |
        | We'll consider <= 100 meters verified.
        |
        */

        $gpsThreshold = 100;


        $gpsVisits = (clone $visits)
            ->whereNotNull('check_in_latitude')
            ->whereNotNull('check_in_longitude')
            ->with('customer')
            ->get();


        $gpsEligibleVisits = 0;

        $gpsVerifiedVisits = 0;


        foreach ($gpsVisits as $visit) {

            /*
             * A customer location is required to verify
             * the visit.
             */

            if (
                !$visit->customer ||
                !$visit->customer->latitude ||
                !$visit->customer->longitude
            ) {
                continue;
            }


            $distance =
                $visit->checkInCustomerDistance();


            if ($distance === null) {
                continue;
            }


            $gpsEligibleVisits++;


            if ($distance <= $gpsThreshold) {
                $gpsVerifiedVisits++;
            }
        }


        $gpsVerificationRate =
            $gpsEligibleVisits > 0
                ? round(
                (
                    $gpsVerifiedVisits
                    / $gpsEligibleVisits
                ) * 100,
                1
            )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Dynamic weighted score
        |--------------------------------------------------------------------------
        |
        | Nothing is hard-coded here.
        |
        | The database determines:
        |
        | - active metrics
        | - weight
        |
        */


        /*
         * Every metric used for scoring must be normalized
         * to a score between 0 and 100.
         */

        $metricScores = [

            'completion' =>
                $dueVisits > 0
                    ? $completionRate
                    : null,

            'coverage' =>
                $assignedCustomers > 0
                    ? min($coverageRate, 100)
                    : null,

            'satisfaction' =>
                $surveyResponses > 0
                    ? $satisfactionScore
                    : null,

            'gps_verification' =>
                $gpsEligibleVisits > 0
                    ? $gpsVerificationRate
                    : null,
        ];


        $weightedTotal = 0;

        $totalWeight = 0;

        $scoreBreakdown = [];


        foreach ($weights as $weightSetting) {

            /*
             * Ignore a DB setting if there is no corresponding
             * metric implemented in the controller.
             */

            if (
                !array_key_exists(
                    $weightSetting->name,
                    $metricScores
                ) ||
                $metricScores[$weightSetting->name] === null
            ) {
                continue;
            }

            $metricScore =
                (float)
                $metricScores[
                $weightSetting->name
                ];


            $weight =
                (float)
                $weightSetting->weight;


            $weightedValue =
                $metricScore
                * ($weight / 100);


            $weightedTotal +=
                $weightedValue;


            $totalWeight +=
                $weight;


            $scoreBreakdown[] = [

                'name' =>
                    $weightSetting->name,

                'label' =>
                    $weightSetting->label,

                'score' =>
                    round(
                        $metricScore,
                        1
                    ),

                'weight' =>
                    $weight,

                'weighted_value' =>
                    round(
                        $weightedValue,
                        2
                    ),
            ];
        }


        /*
         * Normalize the final result.
         *
         * This means that even if active weights accidentally
         * total 80 instead of 100, the score still remains
         * on a 0-100 scale.
         */

        $fieldPerformanceScore =
            $totalWeight > 0
                ? round(
                (
                    $weightedTotal
                    / $totalWeight
                ) * 100,
                1
            )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Performance label
        |--------------------------------------------------------------------------
        */

        $performanceLabel =
            match (true) {

                $fieldPerformanceScore >= 90 =>
                'Excellent',

                $fieldPerformanceScore >= 80 =>
                'Very Good',

                $fieldPerformanceScore >= 70 =>
                'Good',

                $fieldPerformanceScore >= 60 =>
                'Needs Improvement',

                default =>
                'Poor',
            };


        /*
        |--------------------------------------------------------------------------
        | Recent visits
        |--------------------------------------------------------------------------
        */

        $recentVisits = (clone $visits)
            ->with([
                'customer',
                'visitPurpose',
            ])
            ->orderByDesc('scheduled_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Report object
        |--------------------------------------------------------------------------
        */

        $report = [

            /*
             * Main score
             */
            'field_performance_score' =>
                $fieldPerformanceScore,

            'performance_label' =>
                $performanceLabel,

            'score_breakdown' =>
                $scoreBreakdown,

            'total_weight' =>
                $totalWeight,


            /*
             * Visits
             */
            'total_visits' =>
                $totalVisits,

            'completed_visits' =>
                $completedVisits,

            'checked_in_visits' =>
                $checkedInVisits,

            'scheduled_visits' =>
                $scheduledVisits,

            'missed_visits' =>
                $missedVisits,

            'completion_rate' =>
                $completionRate,

            'missed_rate' =>
                $missedRate,


            /*
             * Customers
             */
            'assigned_customers' =>
                $assignedCustomers,

            'customers_visited' =>
                $customersVisited,

            'coverage_rate' =>
                $coverageRate,


            /*
             * Activity
             */
            'working_days' =>
                $workingDays,

            'visit_frequency' =>
                $visitFrequency,


            /*
             * Engagement
             */
            'visits_with_samples' =>
                $visitsWithSamples,

            'total_samples' =>
                $totalSamples,

            'product_engagement_rate' =>
                $productEngagementRate,


            /*
             * Satisfaction
             */
            'surveys_sent' =>
                $surveysSent,

            'survey_responses' =>
                $surveyResponses,

            'survey_response_rate' =>
                $surveyResponseRate,

            'average_satisfaction' =>
                $averageSatisfaction,

            'satisfaction_score' =>
                $satisfactionScore,

            'low_rating_responses' =>
                $lowRatingResponses,


            /*
             * GPS
             */
            'gps_threshold' =>
                $gpsThreshold,

            'gps_eligible_visits' =>
                $gpsEligibleVisits,

            'gps_verified_visits' =>
                $gpsVerifiedVisits,

            'gps_verification_rate' =>
                $gpsVerificationRate,


            /*
             * Visits
             */
            'recent_visits' =>
                $recentVisits,
        ];


        return view(
            'admin.reports.sales-rep-performance',
            compact(
                'salesReps',
                'selectedSalesRep',
                'report',
                'weights',
                'from',
                'to'
            )
        );
    }
}
