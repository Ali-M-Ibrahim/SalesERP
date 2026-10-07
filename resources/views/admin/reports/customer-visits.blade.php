@extends('layouts.app')

@section('title', 'Customer Visit Report')

@section('content')

    <div class="p-4 sm:p-6 lg:p-8">

        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="mb-6">

            <h1 class="text-2xl font-bold text-gray-900">
                Customer Visit Report
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Analyze customer visit frequency and identify
                repeat visits occurring within seven days.
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
                action="{{ route('admin.reports.customer-visits') }}"
                class="grid
                   grid-cols-1
                   gap-4
                   md:grid-cols-2
                   xl:grid-cols-5"
            >

                {{-- Customer --}}
                <div>

                    <label class="mb-1.5
                              block
                              text-sm
                              font-medium
                              text-gray-700">

                        Customer

                    </label>

                    <select
                        name="customer_id"
                        class="w-full
                           rounded-lg
                           border-gray-300
                           text-sm
                           focus:border-black
                           focus:ring-black"
                    >

                        <option value="">
                            All Customers
                        </option>

                        @foreach($customers as $customer)

                            <option
                                value="{{ $customer->id }}"
                                @selected(
                                    request('customer_id')
                                    == $customer->id
                                )
                            >
                                {{ $customer->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


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
                        class="w-full
                           rounded-lg
                           border-gray-300
                           text-sm
                           focus:border-black
                           focus:ring-black"
                    >

                        <option value="">
                            All Sales Representatives
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
                        value="{{
                        request(
                            'from_date',
                            $from->format('Y-m-d')
                        )
                    }}"
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
                        value="{{
                        request(
                            'to_date',
                            $to->format('Y-m-d')
                        )
                    }}"
                        class="w-full
                           rounded-lg
                           border-gray-300
                           text-sm
                           focus:border-black
                           focus:ring-black"
                    >

                </div>


                {{-- Actions --}}
                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="flex
                           flex-1
                           items-center
                           justify-center
                           gap-2
                           rounded-lg
                           bg-black
                           px-4
                           py-2.5
                           text-sm
                           font-semibold
                           text-white
                           hover:bg-gray-800"
                    >

                        <i class="fa-solid fa-filter"></i>

                        Filter

                    </button>


                    <a
                        href="{{
                        route(
                            'admin.reports.customer-visits'
                        )
                    }}"
                        class="flex
                           h-[42px]
                           w-[42px]
                           items-center
                           justify-center
                           rounded-lg
                           border
                           border-gray-300
                           text-gray-600
                           hover:bg-gray-50"
                        title="Reset"
                    >

                        <i class="fa-solid fa-rotate-left"></i>

                    </a>

                </div>

            </form>

        </div>


        {{-- =========================================================
             PERIOD
        ========================================================== --}}

        <div class="mb-4 text-sm text-gray-500">

            Report period:

            <span class="font-semibold text-gray-800">
            {{ $from->format('d M Y') }}
        </span>

            <span class="mx-1">→</span>

            <span class="font-semibold text-gray-800">
            {{ $to->format('d M Y') }}
        </span>

        </div>


        {{-- =========================================================
             SUMMARY CARDS
        ========================================================== --}}

        <div class="mb-6
                grid
                grid-cols-2
                gap-4
                lg:grid-cols-5">

            {{-- Customers --}}
            <div class="rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5">

                <p class="text-xs
                      font-medium
                      uppercase
                      text-gray-500">

                    Customers

                </p>

                <p class="mt-2
                      text-3xl
                      font-bold
                      text-gray-900">

                    {{ $summary['total_customers'] }}

                </p>

            </div>


            {{-- With Visits --}}
            <div class="rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5">

                <p class="text-xs
                      font-medium
                      uppercase
                      text-gray-500">

                    With Visits

                </p>

                <p class="mt-2
                      text-3xl
                      font-bold
                      text-gray-900">

                    {{ $summary['customers_with_visits'] }}

                </p>

            </div>


            {{-- Without Visits --}}
            <div class="rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5">

                <p class="text-xs
                      font-medium
                      uppercase
                      text-gray-500">

                    Without Visits

                </p>

                <p class="mt-2
                      text-3xl
                      font-bold
                      text-gray-900">

                    {{ $summary['customers_without_visits'] }}

                </p>

            </div>


            {{-- Total Visits --}}
            <div class="rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5">

                <p class="text-xs
                      font-medium
                      uppercase
                      text-gray-500">

                    Total Visits

                </p>

                <p class="mt-2
                      text-3xl
                      font-bold
                      text-gray-900">

                    {{ $summary['total_visits'] }}

                </p>

            </div>


            {{-- Within 7 Days --}}
            <div class="rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5">

                <p class="text-xs
                      font-medium
                      uppercase
                      text-gray-500">

                    Within 7 Days

                </p>

                <p class="mt-2
                      text-3xl
                      font-bold
                      text-gray-900">

                    {{
                        $summary[
                            'visits_within_7_days'
                        ]
                    }}

                </p>

            </div>

        </div>


        {{-- =========================================================
             TABLE
        ========================================================== --}}

        <div class="overflow-hidden
                rounded-xl
                border
                border-gray-200
                bg-white">

            <div class="border-b
                    border-gray-200
                    px-5
                    py-4">

                <h2 class="font-semibold text-gray-900">
                    Customer Visit Frequency
                </h2>

                <p class="mt-1 text-xs text-gray-500">

                    "Within 7 days" counts a visit when it occurred
                    seven days or less after the customer's previous
                    visit in the selected period.

                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full
                          divide-y
                          divide-gray-200 w-full">

                    <thead class="bg-gray-50">

                    <tr>

                        <th class="px-5
                                   py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            Customer

                        </th>


                        <th class="px-5
                                   py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            Sales Rep

                        </th>


                        <th class="px-5
                                   py-3
                                   text-center
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            Total Visits

                        </th>


                        <th class="px-5
                                   py-3
                                   text-center
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            Completed

                        </th>


                        <th class="px-5
                                   py-3
                                   text-center
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            Within 7 Days

                        </th>


                        <th class="px-5
                                   py-3
                                   text-center
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            7-Day Rate

                        </th>


                        <th class="px-5
                                   py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            Last Visit

                        </th>


                        <th class="px-5
                                   py-3
                                   text-center
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wide
                                   text-gray-500">

                            Days Since Visit

                        </th>

                    </tr>

                    </thead>


                    <tbody class="divide-y
                              divide-gray-100">

                    @forelse($reportRows as $row)

                        <tr class="hover:bg-gray-50">

                            {{-- Customer --}}
                            <td class="px-5 py-4">

                                <div class="font-medium
                                            text-gray-900">

                                    {{
                                        $row['customer']->name
                                    }}

                                </div>

                            </td>


                            {{-- Sales Rep --}}
                            <td class="px-5
                                       py-4
                                       text-sm
                                       text-gray-600">

                                {{
                                    $row['sales_rep']?->name
                                    ?? 'Unassigned'
                                }}

                            </td>


                            {{-- Total --}}
                            <td class="px-5
                                       py-4
                                       text-center">

                                <span class="text-lg
                                             font-bold
                                             text-gray-900">

                                    {{
                                        $row[
                                            'total_visits'
                                        ]
                                    }}

                                </span>

                            </td>


                            {{-- Completed --}}
                            <td class="px-5
                                       py-4
                                       text-center">

                                {{
                                    $row[
                                        'completed_visits'
                                    ]
                                }}

                            </td>


                            {{-- Within 7 days --}}
                            <td class="px-5
                                       py-4
                                       text-center">

                                @if(
                                    $row[
                                        'visits_within_7_days'
                                    ] > 0
                                )

                                    <span class="inline-flex
                                                 min-w-[32px]
                                                 items-center
                                                 justify-center
                                                 rounded-full
                                                 bg-amber-100
                                                 px-2.5
                                                 py-1
                                                 text-xs
                                                 font-bold
                                                 text-amber-800">

                                        {{
                                            $row[
                                                'visits_within_7_days'
                                            ]
                                        }}

                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        0
                                    </span>

                                @endif

                            </td>


                            {{-- Rate --}}
                            <td class="px-5
                                       py-4
                                       text-center
                                       text-sm">

                                {{
                                    number_format(
                                        $row[
                                            'within_7_days_rate'
                                        ],
                                        1
                                    )
                                }}%

                            </td>


                            {{-- Last visit --}}
                            <td class="px-5
                                       py-4
                                       text-sm
                                       text-gray-600">

                                @if($row['last_visit'])

                                    {{
                                        $row[
                                            'last_visit'
                                        ]
                                        ->scheduled_at
                                        ?->format(
                                            'd M Y H:i'
                                        )
                                    }}

                                @else

                                    <span class="text-gray-400">
                                        Never
                                    </span>

                                @endif

                            </td>


                            {{-- Days since --}}
                            <td class="px-5
                                       py-4
                                       text-center
                                       text-sm">

                                @if(
                                    $row[
                                        'days_since_last_visit'
                                    ] !== null
                                )

                                    {{
                                        $row[
                                            'days_since_last_visit'
                                        ]
                                    }}

                                    days

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-5
                                       py-12
                                       text-center
                                       text-sm
                                       text-gray-500"
                            >

                                No customers found for the
                                selected filters.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($reportRows->hasPages())

                <div class="border-t
                        border-gray-200
                        px-5
                        py-4">

                    {{ $reportRows->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection
