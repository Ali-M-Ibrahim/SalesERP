@extends('layouts.app')

@section('title', 'Sales Rep Performance')

@section('content')

    <div class="p-4 sm:p-6 lg:p-8">

        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="mb-6">

            <h1 class="text-2xl
                   font-bold
                   text-gray-900">

                Sales Rep Performance

            </h1>

            <p class="mt-1 text-sm text-gray-500">

                Evaluate field performance for a selected
                sales representative and period.

            </p>

        </div>


        {{-- =========================================================
             FILTERS
        ========================================================== --}}

        <div class="mb-6
                rounded-xl
                border
                border-gray-200
                bg-white
                p-5">

            <form
                method="GET"
                action="{{ route('admin.reports.sales-rep-performance') }}"
                class="grid
                   grid-cols-1
                   gap-4
                   md:grid-cols-4"
            >

                {{-- Sales Rep --}}
                <div>

                    <label class="mb-1.5
                              block
                              text-sm
                              font-medium
                              text-gray-700">

                        Sales Representative

                    </label>

                    <select
                        name="sales_rep_id"
                        required
                        class="w-full
                           rounded-lg
                           border-gray-300
                           text-sm
                           focus:border-black
                           focus:ring-black"
                    >

                        <option value="">
                            Select Sales Representative
                        </option>

                        @foreach($salesReps as $salesRep)

                            <option
                                value="{{ $salesRep->id }}"
                                @selected(
                                    request('sales_rep_id')
                                    == $salesRep->id
                                )
                            >

                                {{ $salesRep->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- From --}}
                <div>

                    <label class="mb-1.5
                              block
                              text-sm
                              font-medium
                              text-gray-700">

                        From Date

                    </label>

                    <input
                        type="date"
                        name="from_date"
                        value="{{ request(
                        'from_date',
                        $from->format('Y-m-d')
                    ) }}"
                        required
                        class="w-full
                           rounded-lg
                           border-gray-300
                           text-sm
                           focus:border-black
                           focus:ring-black"
                    >

                </div>


                {{-- To --}}
                <div>

                    <label class="mb-1.5
                              block
                              text-sm
                              font-medium
                              text-gray-700">

                        To Date

                    </label>

                    <input
                        type="date"
                        name="to_date"
                        value="{{ request(
                        'to_date',
                        $to->format('Y-m-d')
                    ) }}"
                        required
                        class="w-full
                           rounded-lg
                           border-gray-300
                           text-sm
                           focus:border-black
                           focus:ring-black"
                    >

                </div>


                {{-- Submit --}}
                <div class="flex items-end">

                    <button
                        type="submit"
                        class="flex
                           w-full
                           items-center
                           justify-center
                           gap-2
                           rounded-lg
                           bg-black
                           px-5
                           py-2.5
                           text-sm
                           font-semibold
                           text-white
                           hover:bg-gray-800"
                    >

                        <i class="fa-solid fa-chart-line"></i>

                        Generate Report

                    </button>

                </div>

            </form>

        </div>


        {{-- =========================================================
             NO REP SELECTED
        ========================================================== --}}

        @if(!$selectedSalesRep)

            <div class="rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-10
                    text-center">

                <i class="fa-solid
                      fa-user-chart
                      text-3xl
                      text-gray-300">
                </i>

                <h2 class="mt-4
                       text-lg
                       font-semibold
                       text-gray-900">

                    Select a Sales Representative

                </h2>

                <p class="mt-1 text-sm text-gray-500">

                    Select a sales representative and date range
                    to generate the performance report.

                </p>

            </div>

        @else

            {{-- =====================================================
                 SALES REP / PERIOD
            ====================================================== --}}

            <div class="mb-6
                    flex
                    flex-col
                    gap-3
                    rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between">

                <div>

                    <p class="text-xs
                          font-medium
                          uppercase
                          tracking-wide
                          text-gray-500">

                        Sales Representative

                    </p>

                    <h2 class="mt-1
                           text-xl
                           font-bold
                           text-gray-900">

                        {{ $selectedSalesRep->name }}

                    </h2>

                </div>


                <div class="text-sm text-gray-500">

                    {{ $from->format('d M Y') }}

                    <span class="mx-2">→</span>

                    {{ $to->format('d M Y') }}

                </div>

            </div>


            {{-- =====================================================
                 PERFORMANCE SCORE
            ====================================================== --}}

            <div class="mb-6
                    rounded-xl
                    bg-black
                    p-6
                    text-white">

                <div class="grid
                        grid-cols-1
                        gap-6
                        lg:grid-cols-3
                        lg:items-center">

                    <div>

                        <p class="text-sm text-gray-300">
                            Field Performance Score
                        </p>

                        <div class="mt-2
                                flex
                                items-end
                                gap-2">

                        <span class="text-5xl
                                     font-bold">

                            {{
                                number_format(
                                    $report[
                                        'field_performance_score'
                                    ],
                                    1
                                )
                            }}

                        </span>

                            <span class="pb-1
                                     text-lg
                                     text-gray-400">

                            / 100

                        </span>

                        </div>

                        <p class="mt-3
                              text-lg
                              font-semibold">

                            {{
                                $report[
                                    'performance_label'
                                ]
                            }}

                        </p>

                    </div>


                    <div class="lg:col-span-2">

                        <p class="mb-3
                              text-sm
                              font-medium
                              text-gray-300">

                            Score Breakdown

                        </p>


                        <div class="grid
                                grid-cols-1
                                gap-3
                                sm:grid-cols-2">

                            @foreach(
                                $report['score_breakdown']
                                as $item
                            )

                                <div class="rounded-lg
                                        bg-white/10
                                        p-4">

                                    <div class="flex
                                            justify-between
                                            gap-3">

                                    <span class="text-sm">
                                        {{ $item['label'] }}
                                    </span>

                                        <span class="text-sm
                                                 font-semibold">

                                        {{ $item['weight'] }}%

                                    </span>

                                    </div>


                                    <div class="mt-2
                                            flex
                                            items-end
                                            justify-between">

                                    <span class="text-2xl
                                                 font-bold">

                                        {{
                                            number_format(
                                                $item['score'],
                                                1
                                            )
                                        }}%

                                    </span>

                                        <span class="text-xs
                                                 text-gray-300">

                                        +
                                        {{
                                            number_format(
                                                $item[
                                                    'weighted_value'
                                                ],
                                                2
                                            )
                                        }}

                                    </span>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 MAIN KPI CARDS
            ====================================================== --}}

            <div class="mb-6
                    grid
                    grid-cols-2
                    gap-4
                    lg:grid-cols-4">

                {{-- Completion --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white
                        p-5">

                    <p class="text-xs
                          font-medium
                          uppercase
                          text-gray-500">

                        Completion

                    </p>

                    <p class="mt-2
                          text-3xl
                          font-bold
                          text-gray-900">

                        {{
                            number_format(
                                $report['completion_rate'],
                                1
                            )
                        }}%

                    </p>

                </div>


                {{-- Coverage --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white
                        p-5">

                    <p class="text-xs
                          font-medium
                          uppercase
                          text-gray-500">

                        Coverage

                    </p>

                    <p class="mt-2
                          text-3xl
                          font-bold
                          text-gray-900">

                        {{
                            number_format(
                                $report['coverage_rate'],
                                1
                            )
                        }}%

                    </p>

                </div>


                {{-- Satisfaction --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white
                        p-5">

                    <p class="text-xs
                          font-medium
                          uppercase
                          text-gray-500">

                        Satisfaction

                    </p>

                    <p class="mt-2
                          text-3xl
                          font-bold
                          text-gray-900">

                        {{
                            number_format(
                                $report[
                                    'average_satisfaction'
                                ],
                                1
                            )
                        }}

                        <span class="text-base
                                 font-normal
                                 text-gray-400">

                        / 5

                    </span>

                    </p>

                </div>


                {{-- GPS --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white
                        p-5">

                    <p class="text-xs
                          font-medium
                          uppercase
                          text-gray-500">

                        GPS Verified

                    </p>

                    <p class="mt-2
                          text-3xl
                          font-bold
                          text-gray-900">

                        {{
                            number_format(
                                $report[
                                    'gps_verification_rate'
                                ],
                                1
                            )
                        }}%

                    </p>

                </div>

            </div>


            {{-- =====================================================
                 VISITS + CUSTOMERS
            ====================================================== --}}

            <div class="mb-6
                    grid
                    grid-cols-1
                    gap-6
                    xl:grid-cols-2">


                {{-- Visits --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white">

                    <div class="border-b
                            border-gray-200
                            px-5
                            py-4">

                        <h3 class="font-semibold
                               text-gray-900">

                            Visit Performance

                        </h3>

                    </div>


                    <div class="grid
                            grid-cols-2
                            gap-px
                            bg-gray-200
                            sm:grid-cols-3">

                        @php

                            $visitMetrics = [

                                [
                                    'label' => 'Total Visits',
                                    'value' =>
                                        $report[
                                            'total_visits'
                                        ],
                                ],

                                [
                                    'label' => 'Completed',
                                    'value' =>
                                        $report[
                                            'completed_visits'
                                        ],
                                ],

                                [
                                    'label' => 'Checked In',
                                    'value' =>
                                        $report[
                                            'checked_in_visits'
                                        ],
                                ],

                                [
                                    'label' => 'Scheduled',
                                    'value' =>
                                        $report[
                                            'scheduled_visits'
                                        ],
                                ],

                                [
                                    'label' => 'Missed',
                                    'value' =>
                                        $report[
                                            'missed_visits'
                                        ],
                                ],

                                [
                                    'label' => 'Missed Rate',
                                    'value' =>
                                        number_format(
                                            $report[
                                                'missed_rate'
                                            ],
                                            1
                                        ) . '%',
                                ],
                            ];

                        @endphp


                        @foreach($visitMetrics as $metric)

                            <div class="bg-white p-5">

                                <p class="text-xs
                                      text-gray-500">

                                    {{ $metric['label'] }}

                                </p>

                                <p class="mt-1
                                      text-2xl
                                      font-bold
                                      text-gray-900">

                                    {{ $metric['value'] }}

                                </p>

                            </div>

                        @endforeach

                    </div>

                </div>


                {{-- Customers --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white">

                    <div class="border-b
                            border-gray-200
                            px-5
                            py-4">

                        <h3 class="font-semibold
                               text-gray-900">

                            Customer Coverage

                        </h3>

                    </div>


                    <div class="grid
                            grid-cols-2
                            gap-px
                            bg-gray-200">

                        <div class="bg-white p-5">

                            <p class="text-xs text-gray-500">
                                Assigned Customers
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'assigned_customers'
                                    ]
                                }}

                            </p>

                        </div>


                        <div class="bg-white p-5">

                            <p class="text-xs text-gray-500">
                                Customers Visited
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'customers_visited'
                                    ]
                                }}

                            </p>

                        </div>


                        <div class="bg-white p-5">

                            <p class="text-xs text-gray-500">
                                Coverage
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    number_format(
                                        $report[
                                            'coverage_rate'
                                        ],
                                        1
                                    )
                                }}%

                            </p>

                        </div>


                        <div class="bg-white p-5">

                            <p class="text-xs text-gray-500">
                                Completed Visits / Working Day
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'visit_frequency'
                                    ]
                                }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ENGAGEMENT + SATISFACTION
            ====================================================== --}}

            <div class="mb-6
                    grid
                    grid-cols-1
                    gap-6
                    xl:grid-cols-2">


                {{-- Engagement --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white
                        p-5">

                    <h3 class="font-semibold
                           text-gray-900">

                        Product Engagement

                    </h3>


                    <div class="mt-5
                            grid
                            grid-cols-3
                            gap-4">

                        <div>

                            <p class="text-xs text-gray-500">
                                Visits With Samples
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'visits_with_samples'
                                    ]
                                }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Samples Shared
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'total_samples'
                                    ]
                                }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Engagement
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    number_format(
                                        $report[
                                            'product_engagement_rate'
                                        ],
                                        1
                                    )
                                }}%

                            </p>

                        </div>

                    </div>

                </div>


                {{-- Satisfaction --}}
                <div class="rounded-xl
                        border
                        border-gray-200
                        bg-white
                        p-5">

                    <h3 class="font-semibold
                           text-gray-900">

                        Customer Feedback

                    </h3>


                    <div class="mt-5
                            grid
                            grid-cols-2
                            gap-4
                            sm:grid-cols-4">

                        <div>

                            <p class="text-xs text-gray-500">
                                Surveys Sent
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'surveys_sent'
                                    ]
                                }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Responses
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'survey_responses'
                                    ]
                                }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Response Rate
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    number_format(
                                        $report[
                                            'survey_response_rate'
                                        ],
                                        1
                                    )
                                }}%

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Low Ratings
                            </p>

                            <p class="mt-1
                                  text-2xl
                                  font-bold">

                                {{
                                    $report[
                                        'low_rating_responses'
                                    ]
                                }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 GPS DETAILS
            ====================================================== --}}

            <div class="mb-6
                    rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5">

                <div class="flex
                        flex-col
                        gap-2
                        sm:flex-row
                        sm:items-center
                        sm:justify-between">

                    <div>

                        <h3 class="font-semibold
                               text-gray-900">

                            Location Verification

                        </h3>

                        <p class="mt-1
                              text-xs
                              text-gray-500">

                            Verified when check-in is within
                            {{ $report['gps_threshold'] }}
                            meters of the customer location.

                        </p>

                    </div>


                    <div class="text-right">

                        <p class="text-2xl font-bold">

                            {{
                                $report[
                                    'gps_verified_visits'
                                ]
                            }}

                            /

                            {{
                                $report[
                                    'gps_eligible_visits'
                                ]
                            }}

                        </p>

                        <p class="text-xs text-gray-500">
                            verified visits
                        </p>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 RECENT VISITS
            ====================================================== --}}

            <div class="rounded-xl
                    border
                    border-gray-200
                    bg-white">

                <div class="border-b
                        border-gray-200
                        px-5
                        py-4">

                    <h3 class="font-semibold text-gray-900">
                        Recent Visits
                    </h3>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full
                              divide-y
                              divide-gray-200">

                        <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5
                                   py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   text-gray-500">

                                Customer

                            </th>

                            <th class="px-5
                                   py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   text-gray-500">

                                Date

                            </th>

                            <th class="px-5
                                   py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   text-gray-500">

                                Purpose

                            </th>

                            <th class="px-5
                                   py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   text-gray-500">

                                Status

                            </th>

                        </tr>

                        </thead>


                        <tbody class="divide-y
                                  divide-gray-100">

                        @forelse(
                            $report['recent_visits']
                            as $visit
                        )

                            <tr>

                                <td class="px-5
                                       py-4
                                       text-sm
                                       font-medium
                                       text-gray-900">

                                    {{
                                        $visit->customer?->name
                                        ?? '—'
                                    }}

                                </td>


                                <td class="px-5
                                       py-4
                                       text-sm
                                       text-gray-600">

                                    {{
                                        $visit
                                            ->scheduled_at
                                            ?->format(
                                                'd M Y H:i'
                                            )
                                        ?? '—'
                                    }}

                                </td>


                                <td class="px-5
                                       py-4
                                       text-sm
                                       text-gray-600">

                                    {{
                                        $visit
                                            ->visitPurpose
                                            ?->name
                                        ??
                                        $visit
                                            ->purpose_other
                                        ??
                                        '—'
                                    }}

                                </td>


                                <td class="px-5 py-4">

                                <span class="rounded-full
                                             bg-gray-100
                                             px-2.5
                                             py-1
                                             text-xs
                                             font-medium
                                             text-gray-700">

                                    {{
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $visit->status
                                            )
                                        )
                                    }}

                                </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="px-5
                                       py-10
                                       text-center
                                       text-sm
                                       text-gray-500">

                                    No visits found for this period.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </div>

@endsection
