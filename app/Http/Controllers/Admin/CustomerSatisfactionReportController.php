<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerSatisfactionAnswer;
use App\Models\CustomerSatisfactionInvitation;
use App\Models\CustomerSatisfactionSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSatisfactionReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : now()->startOfMonth();

        $toDate = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : now()->endOfDay();


        /*
         * Base invitations query.
         */
        $invitationQuery =
            CustomerSatisfactionInvitation::query()
                ->whereBetween(
                    'created_at',
                    [
                        $fromDate,
                        $toDate,
                    ]
                );


        if ($request->filled('sales_rep_id')) {

            $invitationQuery->where(
                'sales_rep_id',
                $request->sales_rep_id
            );

        }


        /*
         * KPI counts.
         */
        $totalInvitations =
            (clone $invitationQuery)
                ->count();

        $sentInvitations =
            (clone $invitationQuery)
                ->whereNotNull('sent_at')
                ->count();

        $responses =
            (clone $invitationQuery)
                ->whereNotNull('completed_at')
                ->count();


        $responseRate =
            $sentInvitations > 0
                ? round(
                (
                    $responses
                    /
                    $sentInvitations
                ) * 100,
                1
            )
                : 0;


        /*
         * IDs of invitations currently included
         * by the filters.
         */
        $invitationIds =
            (clone $invitationQuery)
                ->pluck('id');


        /*
         * Rating averages by category.
         */
        $ratingQuery =
            CustomerSatisfactionAnswer::query()
                ->join(
                    'customer_satisfaction_questions',
                    'customer_satisfaction_questions.id',
                    '=',
                    'customer_satisfaction_answers.question_id'
                )
                ->whereIn(
                    'customer_satisfaction_answers.invitation_id',
                    $invitationIds
                )
                ->whereNotNull(
                    'customer_satisfaction_answers.rating'
                );


        $overallRating =
            round(
                (clone $ratingQuery)
                    ->where(
                        'customer_satisfaction_questions.category',
                        'general'
                    )
                    ->avg(
                        'customer_satisfaction_answers.rating'
                    ) ?? 0,
                2
            );


        $salesmanRating =
            round(
                (clone $ratingQuery)
                    ->where(
                        'customer_satisfaction_questions.category',
                        'salesman'
                    )
                    ->avg(
                        'customer_satisfaction_answers.rating'
                    ) ?? 0,
                2
            );


        $productRating =
            round(
                (clone $ratingQuery)
                    ->where(
                        'customer_satisfaction_questions.category',
                        'product'
                    )
                    ->avg(
                        'customer_satisfaction_answers.rating'
                    ) ?? 0,
                2
            );


        $meetingRating =
            round(
                (clone $ratingQuery)
                    ->where(
                        'customer_satisfaction_questions.category',
                        'meeting'
                    )
                    ->avg(
                        'customer_satisfaction_answers.rating'
                    ) ?? 0,
                2
            );


        $settings =CustomerSatisfactionSetting::firstOrFail();

        $lowRatingThreshold =
            $settings?->low_rating_threshold ?? 2;

        /*
         * Low-rating responses.
         *
         * For now <= 2.
         * Later use your settings table threshold.
         */
        $lowRatingResponses =
            CustomerSatisfactionAnswer::query()
                ->whereIn(
                    'invitation_id',
                    $invitationIds
                )
                ->whereNotNull('rating')
                ->where('rating', '<=', $lowRatingThreshold)
                ->distinct()
                ->count('invitation_id');


        /*
         * Sales representative performance.
         */
        $salesRepPerformance =
            User::role('sales_rep')
                ->get()
                ->map(
                    function ($salesRep) use (
                        $fromDate,
                        $toDate
                    ) {

                        $query =
                            CustomerSatisfactionInvitation::query()
                                ->where(
                                    'sales_rep_id',
                                    $salesRep->id
                                )
                                ->whereBetween(
                                    'created_at',
                                    [
                                        $fromDate,
                                        $toDate,
                                    ]
                                );


                        $sent =
                            (clone $query)
                                ->whereNotNull('sent_at')
                                ->count();

                        $responses =
                            (clone $query)
                                ->whereNotNull(
                                    'completed_at'
                                )
                                ->count();


                        $ids =
                            (clone $query)
                                ->pluck('id');


                        $averageRating =
                            CustomerSatisfactionAnswer::query()
                                ->whereIn(
                                    'invitation_id',
                                    $ids
                                )
                                ->whereNotNull('rating')
                                ->avg('rating');


                        return [
                            'sales_rep' =>
                                $salesRep,

                            'sent' =>
                                $sent,

                            'responses' =>
                                $responses,

                            'response_rate' =>
                                $sent > 0
                                    ? round(
                                    (
                                        $responses
                                        /
                                        $sent
                                    ) * 100,
                                    1
                                )
                                    : 0,

                            'average_rating' =>
                                round(
                                    $averageRating ?? 0,
                                    2
                                ),
                        ];
                    }
                );


        /*
         * Rating distribution.
         */
        $ratingDistribution =
            CustomerSatisfactionAnswer::query()
                ->whereIn(
                    'invitation_id',
                    $invitationIds
                )
                ->whereNotNull('rating')
                ->select(
                    'rating',
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy('rating')
                ->pluck(
                    'total',
                    'rating'
                );


        /*
         * Recent completed responses.
         */
        $recentResponses =
            CustomerSatisfactionInvitation::with([
                'customer',
                'salesRep',
                'answers.question',
                'visit',
            ])
                ->whereIn(
                    'id',
                    $invitationIds
                )
                ->whereNotNull('completed_at')
                ->orderByDesc('completed_at')
                ->paginate(15)
                ->withQueryString();


        /*
         * Sales reps for filter.
         */
        $salesReps =
            User::role('sales_rep')
                ->orderBy('name')
                ->get();


        return view(
            'admin.satisfaction-reports.index',
            compact(
                'fromDate',
                'toDate',
                'salesReps',
                'totalInvitations',
                'sentInvitations',
                'responses',
                'responseRate',
                'overallRating',
                'salesmanRating',
                'productRating',
                'meetingRating',
                'lowRatingResponses',
                'salesRepPerformance',
                'ratingDistribution',
                'recentResponses'
            )
        );
    }
}
