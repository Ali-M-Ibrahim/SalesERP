@extends('layouts.app')

@section('title', 'Sales Performance')

@section('content')

    <div class="p-4
            md:p-6
            pb-24
            md:pb-6">


        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="flex
                flex-col
                md:flex-row
                md:items-start
                md:justify-between
                gap-4
                mb-5">


            <div>

                <a href="{{ route(
                'admin.reports.index'
            ) }}"
                   class="inline-flex
                      items-center
                      gap-2
                      text-sm
                      text-[#62685F]
                      hover:text-[#1E4B43]">

                    <i class="fa-solid
                          fa-arrow-left">
                    </i>

                    Reports

                </a>


                <h1 class="text-2xl
                       md:text-3xl
                       font-bold
                       mt-3">

                    Sales Performance

                </h1>


                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Sales representative activity and customer coverage.

                </p>

            </div>


            <div class="inline-flex
                    items-center
                    gap-2
                    rounded-lg
                    bg-[#F1EFE7]
                    px-3 py-2
                    text-xs
                    text-[#62685F]">

                <i class="fa-regular
                      fa-calendar">
                </i>

                {{ $fromDate->format('d M Y') }}

                <span>–</span>

                {{ $toDate->format('d M Y') }}

            </div>

        </div>



        {{-- =========================================================
             FILTERS
        ========================================================== --}}
        <form method="GET"
              action="{{ route(
              'admin.reports.sales-performance'
          ) }}"
              class="bg-white
                 border
                 border-[#DAD4C3]
                 rounded-xl
                 p-4
                 mb-5">

            <div class="grid
                    grid-cols-1
                    sm:grid-cols-2
                    lg:grid-cols-4
                    gap-3">


                {{-- From Date --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        From

                    </label>

                    <input type="date"
                           name="from_date"
                           value="{{ request(
                           'from_date',
                           $fromDate->format(
                               'Y-m-d'
                           )
                       ) }}"
                           class="w-full
                              rounded-lg
                              border-[#DAD4C3]">

                </div>


                {{-- To Date --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        To

                    </label>

                    <input type="date"
                           name="to_date"
                           value="{{ request(
                           'to_date',
                           $toDate->format(
                               'Y-m-d'
                           )
                       ) }}"
                           class="w-full
                              rounded-lg
                              border-[#DAD4C3]">

                </div>


                {{-- Sales Rep --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Sales Representative

                    </label>


                    <select  id="sales_rep_id" name="sales_rep_id"
                            class="w-full
                               rounded-lg
                               border-[#DAD4C3]">

                        <option value="">

                            All Sales Representatives

                        </option>


                        @foreach(
                            $filterSalesReps
                            as $rep
                        )

                            <option value="{{ $rep->id }}"
                                @selected(
                                    request(
                                        'sales_rep_id'
                                    )
                                    == $rep->id
                                )>

                                {{ $rep->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Buttons --}}
                <div class="flex
                        items-end
                        gap-2">

                    <button type="submit"
                            class="flex-1
                               inline-flex
                               items-center
                               justify-center
                               gap-2
                               rounded-lg
                               bg-[#1E4B43]
                               text-white
                               px-4 py-2.5
                               text-sm
                               font-medium">

                        <i class="fa-solid
                              fa-filter">
                        </i>

                        Apply

                    </button>


                    <a href="{{ route(
                    'admin.reports.sales-performance'
                ) }}"
                       class="inline-flex
                          items-center
                          justify-center
                          rounded-lg
                          border
                          border-[#DAD4C3]
                          w-11
                          h-[42px]
                          text-[#62685F]">

                        <i class="fa-solid
                              fa-rotate-left">
                        </i>

                    </a>

                </div>

            </div>

        </form>



        {{-- =========================================================
             SUMMARY KPI CARDS
        ========================================================== --}}
        <div class="grid
                grid-cols-2
                lg:grid-cols-4
                gap-3
                mb-5">


            {{-- Assigned Customers --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <div class="flex
                        items-start
                        justify-between
                        gap-3">

                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Assigned Customers

                        </p>

                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $totals[
                                'assigned_customers'
                            ] }}

                        </p>

                    </div>


                    <div class="w-9 h-9
                            rounded-lg
                            bg-[#F1EFE7]
                            text-[#62685F]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid
                              fa-users">
                        </i>

                    </div>

                </div>

            </div>


            {{-- Customers Visited --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <div class="flex
                        items-start
                        justify-between
                        gap-3">

                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Customers Visited

                        </p>

                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $totals[
                                'customers_visited'
                            ] }}

                        </p>


                        <p class="text-xs
                              text-[#1E4B43]
                              mt-1">

                            {{ number_format(
                                $totals[
                                    'coverage_rate'
                                ],
                                1
                            ) }}%

                            coverage

                        </p>

                    </div>


                    <div class="w-9 h-9
                            rounded-lg
                            bg-[#E3ECE7]
                            text-[#1E4B43]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid
                              fa-user-check">
                        </i>

                    </div>

                </div>

            </div>


            {{-- Completed Visits --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <div class="flex
                        items-start
                        justify-between
                        gap-3">

                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Completed Visits

                        </p>

                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $totals[
                                'completed'
                            ] }}

                        </p>


                        <p class="text-xs
                              text-[#1E4B43]
                              mt-1">

                            {{ number_format(
                                $totals[
                                    'completion_rate'
                                ],
                                1
                            ) }}%

                            completion

                        </p>

                    </div>


                    <div class="w-9 h-9
                            rounded-lg
                            bg-[#E3ECE7]
                            text-[#1E4B43]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid
                              fa-circle-check">
                        </i>

                    </div>

                </div>

            </div>


            {{-- Missed Visits --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <div class="flex
                        items-start
                        justify-between
                        gap-3">

                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Missed Visits

                        </p>

                        <p class="text-2xl
                              font-bold
                              text-red-700
                              mt-2">

                            {{ $totals[
                                'missed'
                            ] }}

                        </p>


                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            Past visits without check-in

                        </p>

                    </div>


                    <div class="w-9 h-9
                            rounded-lg
                            bg-red-50
                            text-red-700
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid
                              fa-triangle-exclamation">
                        </i>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
             SECONDARY KPI
        ========================================================== --}}
        <div class="grid
                grid-cols-2
                sm:grid-cols-4
                gap-3
                mb-6">

            <div class="rounded-xl
                    bg-[#F1EFE7]
                    p-3">

                <p class="text-xs
                      text-[#62685F]">

                    Total Visits

                </p>

                <p class="text-xl
                      font-bold
                      mt-1">

                    {{ $totals[
                        'total_visits'
                    ] }}

                </p>

            </div>


            <div class="rounded-xl
                    bg-[#F1EFE7]
                    p-3">

                <p class="text-xs
                      text-[#62685F]">

                    Upcoming

                </p>

                <p class="text-xl
                      font-bold
                      mt-1">

                    {{ $totals[
                        'scheduled'
                    ] }}

                </p>

            </div>


            <div class="rounded-xl
                    bg-[#F1EFE7]
                    p-3">

                <p class="text-xs
                      text-[#62685F]">

                    Samples Given

                </p>

                <p class="text-xl
                      font-bold
                      mt-1">

                    {{ $totals[
                        'samples_given'
                    ] }}

                </p>

            </div>


            <div class="rounded-xl
                    bg-[#F1EFE7]
                    p-3">

                <p class="text-xs
                      text-[#62685F]">

                    Resources Shared

                </p>

                <p class="text-xl
                      font-bold
                      mt-1">

                    {{ $totals[
                        'resources_shared'
                    ] }}

                </p>

            </div>

        </div>



        {{-- =========================================================
             DESKTOP TABLE
        ========================================================== --}}
        <div class="hidden
                lg:block
                bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full
                          whitespace-nowrap">

                    <thead class="bg-[#F1EFE7]">

                    <tr class="text-xs
                               text-left
                               text-[#62685F]">

                        <th class="px-4 py-3">

                            Sales Representative

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Assigned
                            <span class="block
                                         font-normal
                                         text-[9px]">

                                Customers

                            </span>

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Customers
                            <span class="block
                                         font-normal
                                         text-[9px]">

                                Visited

                            </span>

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Coverage

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Total
                            <span class="block
                                         font-normal
                                         text-[9px]">

                                Visits

                            </span>

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Completed

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Missed

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Completion

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Samples

                        </th>


                        <th class="px-4 py-3
                                   text-center">

                            Resources

                        </th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $performance
                        as $row
                    )

                        <tr class="border-t
                                   border-[#DAD4C3]
                                   hover:bg-[#FAF9F5]">

                            {{-- Rep --}}
                            <td class="px-4 py-4">

                                <a href="{{ route(
                                    'admin.sales-reps.show',
                                    $row['rep']
                                ) }}"
                                   class="font-semibold
                                          text-sm
                                          hover:text-[#1E4B43]">

                                    {{ $row[
                                        'rep'
                                    ]->name }}

                                </a>


                                <p class="text-[10px]
                                          text-[#62685F]
                                          mt-1">

                                    {{ $row[
                                        'rep'
                                    ]->email }}

                                </p>

                            </td>


                            {{-- Assigned --}}
                            <td class="px-4 py-4
                                       text-center
                                       font-medium">

                                {{ $row[
                                    'assigned_customers'
                                ] }}

                            </td>


                            {{-- Customers Visited --}}
                            <td class="px-4 py-4
                                       text-center
                                       font-medium">

                                {{ $row[
                                    'customers_visited'
                                ] }}

                            </td>


                            {{-- Coverage --}}
                            <td class="px-4 py-4
                                       text-center">

                                @php

                                    $coverage =
                                        $row[
                                            'coverage_rate'
                                        ];

                                @endphp


                                <div class="min-w-[85px]">

                                    <div class="w-full
                                                h-1.5
                                                rounded-full
                                                bg-[#E9E4D6]
                                                overflow-hidden">

                                        <div class="h-full
                                                    bg-[#1E4B43]"
                                             style="width:
                                                {{ min(
                                                    100,
                                                    $coverage
                                                ) }}%">
                                        </div>

                                    </div>


                                    <p class="text-xs
                                              font-semibold
                                              mt-1">

                                        {{ number_format(
                                            $coverage,
                                            1
                                        ) }}%

                                    </p>

                                </div>

                            </td>


                            {{-- Total Visits --}}
                            <td class="px-4 py-4
                                       text-center
                                       font-semibold">

                                {{ $row[
                                    'total_visits'
                                ] }}

                            </td>


                            {{-- Completed --}}
                            <td class="px-4 py-4
                                       text-center">

                                <span class="inline-flex
                                             min-w-8
                                             justify-center
                                             rounded-full
                                             bg-[#E3ECE7]
                                             text-[#1E4B43]
                                             px-2 py-1
                                             text-xs
                                             font-semibold">

                                    {{ $row[
                                        'completed'
                                    ] }}

                                </span>

                            </td>


                            {{-- Missed --}}
                            <td class="px-4 py-4
                                       text-center">

                                @if(
                                    $row['missed']
                                    > 0
                                )

                                    <span class="inline-flex
                                                 min-w-8
                                                 justify-center
                                                 rounded-full
                                                 bg-red-50
                                                 text-red-700
                                                 px-2 py-1
                                                 text-xs
                                                 font-semibold">

                                        {{ $row[
                                            'missed'
                                        ] }}

                                    </span>

                                @else

                                    <span class="text-xs
                                                 text-[#62685F]">

                                        0

                                    </span>

                                @endif

                            </td>


                            {{-- Completion Rate --}}
                            <td class="px-4 py-4
                                       text-center">

                                @php

                                    $completion =
                                        $row[
                                            'completion_rate'
                                        ];

                                @endphp


                                <span class="inline-flex
                                             rounded-full
                                             px-2.5 py-1
                                             text-xs
                                             font-semibold

                                    @if(
                                        $completion >= 90
                                    )

                                        bg-[#E3ECE7]
                                        text-[#1E4B43]

                                    @elseif(
                                        $completion >= 70
                                    )

                                        bg-[#FBEAD9]
                                        text-[#D6772F]

                                    @else

                                        bg-red-50
                                        text-red-700

                                    @endif
                                ">

                                    {{ number_format(
                                        $completion,
                                        1
                                    ) }}%

                                </span>

                            </td>


                            {{-- Samples --}}
                            <td class="px-4 py-4
                                       text-center">

                                {{ $row[
                                    'samples_given'
                                ] }}

                            </td>


                            {{-- Resources --}}
                            <td class="px-4 py-4
                                       text-center">

                                {{ $row[
                                    'resources_shared'
                                ] }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="py-14
                                       text-center">

                                <i class="fa-solid
                                          fa-chart-line
                                          text-2xl
                                          text-[#62685F]">
                                </i>

                                <p class="text-sm
                                          text-[#62685F]
                                          mt-3">

                                    No sales activity found for this period.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- =========================================================
             MOBILE / TABLET
        ========================================================== --}}
        <div class="lg:hidden
                space-y-4">

            @forelse(
                $performance
                as $row
            )

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        overflow-hidden">


                    {{-- Rep --}}
                    <div class="p-4
                            border-b
                            border-[#DAD4C3]">

                        <div class="flex
                                items-center
                                justify-between
                                gap-3">

                            <div class="flex
                                    items-center
                                    gap-3
                                    min-w-0">

                                <div class="w-10 h-10
                                        rounded-full
                                        bg-[#1E4B43]
                                        text-white
                                        flex
                                        items-center
                                        justify-center
                                        shrink-0
                                        font-semibold">

                                    {{ strtoupper(
                                        substr(
                                            $row[
                                                'rep'
                                            ]->name,
                                            0,
                                            1
                                        )
                                    ) }}

                                </div>


                                <div class="min-w-0">

                                    <a href="{{ route(
                                    'admin.sales-reps.show',
                                    $row['rep']
                                ) }}"
                                       class="font-semibold
                                          truncate
                                          block">

                                        {{ $row[
                                            'rep'
                                        ]->name }}

                                    </a>


                                    <p class="text-xs
                                          text-[#62685F]
                                          truncate">

                                        {{ $row[
                                            'rep'
                                        ]->email }}

                                    </p>

                                </div>

                            </div>


                            <a href="{{ route(
                            'admin.sales-reps.show',
                            $row['rep']
                        ) }}"
                               class="w-8 h-8
                                  rounded-lg
                                  border
                                  border-[#DAD4C3]
                                  flex
                                  items-center
                                  justify-center">

                                <i class="fa-solid
                                      fa-chevron-right
                                      text-xs">
                                </i>

                            </a>

                        </div>

                    </div>


                    {{-- Customer Coverage --}}
                    <div class="p-4">

                        <div class="flex
                                items-center
                                justify-between
                                mb-2">

                            <div>

                                <p class="text-xs
                                      text-[#62685F]">

                                    Customer Coverage

                                </p>

                                <p class="text-sm
                                      font-medium
                                      mt-0.5">

                                    {{ $row[
                                        'customers_visited'
                                    ] }}

                                    of

                                    {{ $row[
                                        'assigned_customers'
                                    ] }}

                                    customers visited

                                </p>

                            </div>


                            <span class="text-sm
                                     font-bold
                                     text-[#1E4B43]">

                            {{ number_format(
                                $row[
                                    'coverage_rate'
                                ],
                                1
                            ) }}%

                        </span>

                        </div>


                        <div class="w-full
                                h-2
                                rounded-full
                                bg-[#E9E4D6]
                                overflow-hidden">

                            <div class="h-full
                                    bg-[#1E4B43]"
                                 style="width:
                                {{ min(
                                    100,
                                    $row[
                                        'coverage_rate'
                                    ]
                                ) }}%">
                            </div>

                        </div>

                    </div>


                    {{-- Main metrics --}}
                    <div class="grid
                            grid-cols-3
                            gap-px
                            bg-[#DAD4C3]
                            border-t
                            border-[#DAD4C3]">

                        <div class="bg-white
                                p-3
                                text-center">

                            <p class="text-lg
                                  font-bold">

                                {{ $row[
                                    'total_visits'
                                ] }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]">

                                Total Visits

                            </p>

                        </div>


                        <div class="bg-white
                                p-3
                                text-center">

                            <p class="text-lg
                                  font-bold
                                  text-[#1E4B43]">

                                {{ $row[
                                    'completed'
                                ] }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]">

                                Completed

                            </p>

                        </div>


                        <div class="bg-white
                                p-3
                                text-center">

                            <p class="text-lg
                                  font-bold

                            {{ $row['missed'] > 0
                                ? 'text-red-700'
                                : '' }}">

                                {{ $row[
                                    'missed'
                                ] }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]">

                                Missed

                            </p>

                        </div>

                    </div>


                    {{-- Completion --}}
                    <div class="px-4 py-3
                            flex
                            items-center
                            justify-between
                            border-t
                            border-[#DAD4C3]">

                    <span class="text-xs
                                 text-[#62685F]">

                        Visit Completion Rate

                    </span>


                        <span class="font-bold
                                 text-sm

                        @if(
                            $row[
                                'completion_rate'
                            ] >= 90
                        )

                            text-[#1E4B43]

                        @elseif(
                            $row[
                                'completion_rate'
                            ] >= 70
                        )

                            text-[#D6772F]

                        @else

                            text-red-700

                        @endif
                    ">

                        {{ number_format(
                            $row[
                                'completion_rate'
                            ],
                            1
                        ) }}%

                    </span>

                    </div>


                    {{-- Distribution --}}
                    <div class="grid
                            grid-cols-2
                            border-t
                            border-[#DAD4C3]">

                        <div class="p-3
                                text-center
                                border-r
                                border-[#DAD4C3]">

                            <p class="font-semibold">

                                {{ $row[
                                    'samples_given'
                                ] }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]
                                  mt-0.5">

                                Samples Given

                            </p>

                        </div>


                        <div class="p-3
                                text-center">

                            <p class="font-semibold">

                                {{ $row[
                                    'resources_shared'
                                ] }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]
                                  mt-0.5">

                                Resources Shared

                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        py-14
                        text-center">

                    <i class="fa-solid
                          fa-chart-line
                          text-2xl
                          text-[#62685F]">
                    </i>

                    <p class="text-sm
                          text-[#62685F]
                          mt-3">

                        No sales activity found for this period.

                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection

@push('scripts')

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                 * Customer Select2.
                 */
                if (
                    typeof $ !== 'undefined'
                    &&
                    $.fn.select2
                ) {


                    $('#sales_rep_id')
                        .select2({

                            width:
                                '100%',



                            allowClear:
                                true,

                        });


                    $('#admin-visit-customer')
                        .select2({

                            width:
                                '100%',

                            placeholder:
                                'Search customer...',

                            allowClear:
                                true,

                        });
                }




            }
        );

    </script>

@endpush
