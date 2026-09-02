@extends('layouts.app')

@section('title', 'Location Verification')

@section('content')

    <div class="p-4 md:p-6 pb-24 md:pb-6">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="mb-5">

            <a href="{{ route(
            'admin.reports.index'
        ) }}"
               class="inline-flex
                  items-center
                  gap-2
                  text-sm
                  text-[#62685F]">

                <i class="fa-solid fa-arrow-left"></i>

                Reports

            </a>


            <h1 class="text-2xl
                   md:text-3xl
                   font-bold
                   mt-3">

                Location Verification

            </h1>


            <p class="text-sm
                  text-[#62685F]
                  mt-1">

                Verify check-in and check-out locations against the customer's pinned location.

            </p>

        </div>


        {{-- =========================================================
             FILTERS
        ========================================================== --}}
        <form method="GET"
              action="{{ route(
              'admin.reports.location-verification'
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
                    lg:grid-cols-5
                    gap-3">


                {{-- From --}}
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
                           $fromDate->format('Y-m-d')
                       ) }}"
                           class="w-full
                              rounded-lg
                              border-[#DAD4C3]">

                </div>


                {{-- To --}}
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
                           $toDate->format('Y-m-d')
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

                        @foreach($salesReps as $rep)

                            <option value="{{ $rep->id }}"
                                @selected(
                                    request('sales_rep_id')
                                    == $rep->id
                                )>

                                {{ $rep->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Customer --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Customer

                    </label>

                    <select name="customer_id"
                            id="location-report-customer"
                            class="w-full">

                        <option value="">
                            All Customers
                        </option>

                        @foreach($customers as $customer)

                            <option value="{{ $customer->id }}"
                                    data-phone="{{ $customer->phone }}"
                                @selected(
                                    request('customer_id')
                                    == $customer->id
                                )>

                                {{ $customer->name }}

                                @if($customer->phone)
                                    - {{ $customer->phone }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Verification --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Verification

                    </label>

                    <select name="verification_status"
                            class="w-full
                               rounded-lg
                               border-[#DAD4C3]">

                        <option value="">
                            All
                        </option>

                        <option value="verified"
                            @selected(
                                request('verification_status')
                                === 'verified'
                            )>

                            Verified

                        </option>

                        <option value="review"
                            @selected(
                                request('verification_status')
                                === 'review'
                            )>

                            Needs Review

                        </option>

                        <option value="no_customer_location"
                            @selected(
                                request('verification_status')
                                === 'no_customer_location'
                            )>

                            No Customer Location

                        </option>

                        <option value="no_gps"
                            @selected(
                                request('verification_status')
                                === 'no_gps'
                            )>

                            No GPS

                        </option>

                    </select>

                </div>

            </div>


            <div class="flex
                    justify-end
                    gap-2
                    mt-4">

                <a href="{{ route(
                'admin.reports.location-verification'
            ) }}"
                   class="px-4 py-2.5
                      rounded-lg
                      border
                      border-[#DAD4C3]
                      text-sm">

                    Reset

                </a>


                <button type="submit"
                        class="inline-flex
                           items-center
                           gap-2
                           px-4 py-2.5
                           rounded-lg
                           bg-[#1E4B43]
                           text-white
                           text-sm
                           font-medium">

                    <i class="fa-solid fa-filter"></i>

                    Apply

                </button>

            </div>

        </form>


        {{-- =========================================================
             SUMMARY
        ========================================================== --}}
        <div class="grid
                grid-cols-2
                lg:grid-cols-5
                gap-3
                mb-5">

            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Checked Visits
                </p>

                <p class="text-2xl font-bold mt-2">

                    {{ $summary['total'] }}

                </p>

            </div>


            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Verified
                </p>

                <p class="text-2xl
                      font-bold
                      text-[#1E4B43]
                      mt-2">

                    {{ $summary['verified'] }}

                </p>

            </div>


            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Needs Review
                </p>

                <p class="text-2xl
                      font-bold
                      text-red-700
                      mt-2">

                    {{ $summary['review'] }}

                </p>

            </div>


            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Customer GPS Missing
                </p>

                <p class="text-2xl font-bold mt-2">

                    {{ $summary[
                        'no_customer_location'
                    ] }}

                </p>

            </div>


            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Visit GPS Missing
                </p>

                <p class="text-2xl font-bold mt-2">

                    {{ $summary['no_gps'] }}

                </p>

            </div>

        </div>


        {{-- =========================================================
             EXPLANATION
        ========================================================== --}}
        <div class="rounded-xl
                bg-[#F1EFE7]
                p-4
                mb-5">

            <p class="text-sm font-medium">

                Verification threshold:
                {{ $threshold }} meters

            </p>

            <p class="text-xs
                  text-[#62685F]
                  mt-1">

                A visit is marked Verified when the check-in is within
                {{ $threshold }} meters of the customer's pinned location
                and, when available, check-out is also within the threshold.

            </p>

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
                            Date
                        </th>

                        <th class="px-4 py-3">
                            Customer
                        </th>

                        <th class="px-4 py-3">
                            Sales Rep
                        </th>

                        <th class="px-4 py-3 text-center">
                            Check-In → Customer
                        </th>

                        <th class="px-4 py-3 text-center">
                            Check-Out → Customer
                        </th>

                        <th class="px-4 py-3 text-center">
                            In → Out
                        </th>

                        <th class="px-4 py-3 text-center">
                            GPS Accuracy
                        </th>

                        <th class="px-4 py-3 text-center">
                            Result
                        </th>

                        <th class="px-4 py-3"></th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $verification
                        as $row
                    )

                        @php
                            $visit =
                                $row['visit'];
                        @endphp

                        <tr class="border-t
                                   border-[#DAD4C3]
                                   hover:bg-[#FAF9F5]">


                            {{-- Date --}}
                            <td class="px-4 py-4">

                                {{ $visit
                                    ->scheduled_at
                                   ?->format('d M Y h:i A') }}

                            </td>


                            {{-- Customer --}}
                            <td class="px-4 py-4">

                                <a href="{{ route(
                                    'customers.show',
                                    $visit->customer
                                ) }}"
                                   class="font-semibold
                                          text-sm">

                                    {{ $visit
                                        ->customer
                                        ?->name
                                        ?? '—' }}

                                </a>

                            </td>


                            {{-- Rep --}}
                            <td class="px-4 py-4">

                                {{ $visit
                                    ->salesRep
                                    ?->name
                                    ?? '—' }}

                            </td>


                            {{-- Check-In Customer --}}
                            <td class="px-4 py-4 text-center">

                                @if(
                                    $row[
                                        'check_in_customer_distance'
                                    ] !== null
                                )

                                    <span class="
                                        {{ $row[
                                            'check_in_customer_distance'
                                        ] <= $threshold
                                            ? 'text-[#1E4B43]'
                                            : 'text-red-700' }}
                                        font-semibold
                                    ">

                                        {{ number_format(
                                            $row[
                                                'check_in_customer_distance'
                                            ],
                                            0
                                        ) }} m

                                    </span>

                                @else

                                    <span class="text-[#62685F]">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Check-Out Customer --}}
                            <td class="px-4 py-4 text-center">

                                @if(
                                    $row[
                                        'check_out_customer_distance'
                                    ] !== null
                                )

                                    <span class="
                                        {{ $row[
                                            'check_out_customer_distance'
                                        ] <= $threshold
                                            ? 'text-[#1E4B43]'
                                            : 'text-red-700' }}
                                        font-semibold
                                    ">

                                        {{ number_format(
                                            $row[
                                                'check_out_customer_distance'
                                            ],
                                            0
                                        ) }} m

                                    </span>

                                @else

                                    <span class="text-[#62685F]">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- In Out --}}
                            <td class="px-4 py-4 text-center">

                                @if(
                                    $row[
                                        'check_in_out_distance'
                                    ] !== null
                                )

                                    {{ number_format(
                                        $row[
                                            'check_in_out_distance'
                                        ],
                                        0
                                    ) }} m

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Accuracy --}}
                            <td class="px-4 py-4 text-center">

                                <div class="text-xs">

                                    @if(
                                        $visit
                                            ->check_in_accuracy
                                    )

                                        <p>

                                            In:

                                            {{ number_format(
                                                $visit
                                                    ->check_in_accuracy,
                                                0
                                            ) }} m

                                        </p>

                                    @endif


                                    @if(
                                        $visit
                                            ->check_out_accuracy
                                    )

                                        <p class="mt-1">

                                            Out:

                                            {{ number_format(
                                                $visit
                                                    ->check_out_accuracy,
                                                0
                                            ) }} m

                                        </p>

                                    @endif

                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-4 py-4 text-center">

                                @if(
                                    $row['status']
                                    === 'verified'
                                )

                                    <span class="inline-flex
                                                 items-center
                                                 gap-1
                                                 rounded-full
                                                 bg-[#E3ECE7]
                                                 text-[#1E4B43]
                                                 px-2.5 py-1
                                                 text-[10px]
                                                 font-semibold">

                                        <i class="fa-solid
                                                  fa-circle-check">
                                        </i>

                                        Verified

                                    </span>


                                @elseif(
                                    $row['status']
                                    === 'review'
                                )

                                    <span class="inline-flex
                                                 items-center
                                                 gap-1
                                                 rounded-full
                                                 bg-red-50
                                                 text-red-700
                                                 px-2.5 py-1
                                                 text-[10px]
                                                 font-semibold">

                                        <i class="fa-solid
                                                  fa-triangle-exclamation">
                                        </i>

                                        Review

                                    </span>


                                @elseif(
                                    $row['status']
                                    ===
                                    'no_customer_location'
                                )

                                    <span class="inline-flex
                                                 rounded-full
                                                 bg-[#F1EFE7]
                                                 text-[#62685F]
                                                 px-2.5 py-1
                                                 text-[10px]
                                                 font-semibold">

                                        Customer GPS Missing

                                    </span>

                                @else

                                    <span class="inline-flex
                                                 rounded-full
                                                 bg-[#F1EFE7]
                                                 text-[#62685F]
                                                 px-2.5 py-1
                                                 text-[10px]
                                                 font-semibold">

                                        Visit GPS Missing

                                    </span>

                                @endif

                            </td>


                            {{-- View --}}
                            <td class="px-4 py-4 text-right">

                                <a href="{{ route(
                                    'admin.visits.index',
                                    [
                                        'customer_id'
                                            => $visit
                                                ->customer_id,

                                        'sales_rep_id'
                                            => $visit
                                                ->sales_rep_id,

                                        'from_date'
                                            => $visit
                                                ->scheduled_at
                                               ?->format('d M Y h:i A'),

                                        'to_date'
                                            => $visit
                                                ->scheduled_at
                                                ?->format('d M Y h:i A')
                                    ]
                                ) }}"
                                   class="w-8 h-8
                                          rounded-lg
                                          border
                                          border-[#DAD4C3]
                                          inline-flex
                                          items-center
                                          justify-center">

                                    <i class="fa-regular
                                              fa-eye
                                              text-xs">
                                    </i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="py-14
                                       text-center
                                       text-sm
                                       text-[#62685F]">

                                No visit location data found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
             MOBILE
        ========================================================== --}}
        <div class="lg:hidden
                space-y-3">

            @forelse(
                $verification
                as $row
            )

                @php
                    $visit =
                        $row['visit'];
                @endphp


                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        overflow-hidden">


                    <div class="p-4">

                        <div class="flex
                                items-start
                                justify-between
                                gap-3">

                            <div>

                                <p class="text-xs
                                      text-[#62685F]">

                                    {{ $visit
                                        ->scheduled_at
                                        ?->format('d M Y h:i A')}}

                                </p>

                                <a href="{{ route(
                                'customers.show',
                                $visit->customer
                            ) }}"
                                   class="font-semibold
                                      block
                                      mt-1">

                                    {{ $visit
                                        ->customer
                                        ?->name
                                        ?? '—' }}

                                </a>

                                <p class="text-xs
                                      text-[#62685F]
                                      mt-1">

                                    {{ $visit
                                        ->salesRep
                                        ?->name
                                        ?? '—' }}

                                </p>

                            </div>


                            @if(
                                $row['status']
                                === 'verified'
                            )

                                <span class="rounded-full
                                         bg-[#E3ECE7]
                                         text-[#1E4B43]
                                         px-2 py-1
                                         text-[9px]
                                         font-semibold">

                                Verified

                            </span>

                            @elseif(
                                $row['status']
                                === 'review'
                            )

                                <span class="rounded-full
                                         bg-red-50
                                         text-red-700
                                         px-2 py-1
                                         text-[9px]
                                         font-semibold">

                                Review

                            </span>

                            @else

                                <span class="rounded-full
                                         bg-[#F1EFE7]
                                         text-[#62685F]
                                         px-2 py-1
                                         text-[9px]
                                         font-semibold">

                                Missing GPS

                            </span>

                            @endif

                        </div>

                    </div>


                    <div class="grid
                            grid-cols-3
                            border-t
                            border-[#DAD4C3]">

                        <div class="p-3
                                text-center
                                border-r
                                border-[#DAD4C3]">

                            <p class="font-semibold text-sm">

                                @if(
                                    $row[
                                        'check_in_customer_distance'
                                    ] !== null
                                )

                                    {{ number_format(
                                        $row[
                                            'check_in_customer_distance'
                                        ],
                                        0
                                    ) }}m

                                @else

                                    —

                                @endif

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]
                                  mt-1">

                                Check-In

                            </p>

                        </div>


                        <div class="p-3
                                text-center
                                border-r
                                border-[#DAD4C3]">

                            <p class="font-semibold text-sm">

                                @if(
                                    $row[
                                        'check_out_customer_distance'
                                    ] !== null
                                )

                                    {{ number_format(
                                        $row[
                                            'check_out_customer_distance'
                                        ],
                                        0
                                    ) }}m

                                @else

                                    —

                                @endif

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]
                                  mt-1">

                                Check-Out

                            </p>

                        </div>


                        <div class="p-3
                                text-center">

                            <p class="font-semibold text-sm">

                                @if(
                                    $row[
                                        'check_in_out_distance'
                                    ] !== null
                                )

                                    {{ number_format(
                                        $row[
                                            'check_in_out_distance'
                                        ],
                                        0
                                    ) }}m

                                @else

                                    —

                                @endif

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]
                                  mt-1">

                                In → Out

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

                    <p class="text-sm
                          text-[#62685F]">

                        No visit location data found.

                    </p>

                </div>

            @endforelse

        </div>

    </div>


    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

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

                        $('#location-report-customer')
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

@endsection
