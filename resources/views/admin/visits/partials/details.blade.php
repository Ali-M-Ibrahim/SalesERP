@php

    $checkInOutDistance =
        $visit->checkInCheckOutDistance();

    $checkInCustomerDistance =
        $visit->checkInCustomerDistance();

    $checkOutCustomerDistance =
        $visit->checkOutCustomerDistance();

@endphp


<div class="p-5 space-y-5">

    {{-- =========================================================
         MAIN INFO
    ========================================================== --}}
    <div class="grid
                sm:grid-cols-2
                gap-4">

        <div>

            <p class="text-xs text-[#62685F]">
                Customer
            </p>

            <a href="{{ route(
                'customers.show',
                $visit->customer_id
            ) }}"
               class="font-semibold
                      text-[#1E4B43]
                      mt-1
                      inline-block">

                {{ $visit->customer?->name ?? '—' }}

            </a>

        </div>


        <div>

            <p class="text-xs text-[#62685F]">
                Sales Representative
            </p>

            <p class="font-medium mt-1">

                {{ $visit->salesRep?->name ?? '—' }}

            </p>

        </div>


        <div>

            <p class="text-xs text-[#62685F]">
                Visit Date
            </p>

            <p class="font-medium mt-1">

                {{ $visit
                    ->scheduled_at
                    ?->format('d M Y h:i A') }}

            </p>

        </div>


        <div>

            <p class="text-xs text-[#62685F]">
                Status
            </p>

            <span class="inline-flex
                         mt-1
                         rounded-full
                         px-2.5 py-1
                         text-[10px]
                         uppercase
                         font-semibold

                @if($visit->status === 'completed')

                    bg-[#E3ECE7]
                    text-[#1E4B43]

                @elseif($visit->status === 'checked_in')

                    bg-blue-100
                    text-blue-700

                @elseif(
                    $visit->status === 'missed'
                    || $visit->status === 'cancelled'
                )

                    bg-red-100
                    text-red-700

                @else

                    bg-[#FBEAD9]
                    text-[#D6772F]

                @endif
            ">

                {{ str_replace(
                    '_',
                    ' ',
                    $visit->status
                ) }}

            </span>

        </div>


        <div class="sm:col-span-2">

            <p class="text-xs text-[#62685F]">
                Purpose
            </p>

            <p class="font-medium mt-1">

                {{ $visit
                    ->visitPurpose
                    ?->name
                    ?? $visit->purpose_other
                    ?? '—' }}

            </p>

        </div>

    </div>


    {{-- =========================================================
         NOTES
    ========================================================== --}}
    @if(
        $visit->visit_notes
        || $visit->client_requests
    )

        <div class="grid
                    sm:grid-cols-2
                    gap-4
                    pt-4
                    border-t
                    border-[#DAD4C3]">

            <div>

                <p class="text-xs
                          font-medium
                          text-[#62685F]">

                    Visit Notes

                </p>

                <p class="text-sm
                          mt-2
                          whitespace-pre-line">

                    {{ $visit->visit_notes ?: '—' }}

                </p>

            </div>


            <div>

                <p class="text-xs
                          font-medium
                          text-[#62685F]">

                    Client Requests

                </p>

                <p class="text-sm
                          mt-2
                          whitespace-pre-line">

                    {{ $visit->client_requests ?: '—' }}

                </p>

            </div>

        </div>

    @endif


    {{-- =========================================================
         GPS AUDIT
    ========================================================== --}}
    <div class="pt-4
                border-t
                border-[#DAD4C3]">

        <h4 class="font-semibold
                   text-sm
                   mb-3">

            Visit Audit

        </h4>


        <div class="grid
                    md:grid-cols-2
                    gap-3">

            {{-- Check In --}}
            <div class="rounded-lg
                        border
                        border-[#DAD4C3]
                        p-4">

                <div class="flex
                            items-center
                            justify-between
                            gap-3">

                    <p class="font-medium text-sm">

                        Check In

                    </p>

                    @if($visit->check_in_at)

                        <span class="rounded-full
                                     bg-[#E3ECE7]
                                     text-[#1E4B43]
                                     px-2 py-1
                                     text-[10px]">

                            Recorded

                        </span>

                    @endif

                </div>


                @if($visit->check_in_at)

                    <p class="text-sm mt-3">

                        {{ $visit
                            ->check_in_at
                            ->format(
                                'd M Y H:i:s'
                            ) }}

                    </p>


                    @if(
                        $visit->check_in_latitude
                        && $visit->check_in_longitude
                    )

                        <p class="text-xs
                                  text-[#62685F]
                                  mt-2">

                            {{ $visit
                                ->check_in_latitude }},

                            {{ $visit
                                ->check_in_longitude }}

                        </p>


                        @if($visit->check_in_accuracy)

                            <p class="text-xs
                                      text-[#62685F]
                                      mt-1">

                                Accuracy:

                                {{ number_format(
                                    $visit
                                        ->check_in_accuracy,
                                    0
                                ) }} m

                            </p>

                        @endif


                        <a href="https://www.google.com/maps/search/?api=1&query={{ $visit->check_in_latitude }},{{ $visit->check_in_longitude }}"
                           target="_blank"
                           rel="noopener"
                           class="inline-flex
                                  items-center
                                  gap-1
                                  mt-3
                                  text-xs
                                  font-medium
                                  text-[#1E4B43]">

                            <i class="fa-solid fa-map-location-dot"></i>

                            View on Map

                        </a>

                    @endif

                @else

                    <p class="text-sm
                              text-[#62685F]
                              mt-3">

                        No check-in recorded.

                    </p>

                @endif

            </div>


            {{-- Check Out --}}
            <div class="rounded-lg
                        border
                        border-[#DAD4C3]
                        p-4">

                <div class="flex
                            items-center
                            justify-between
                            gap-3">

                    <p class="font-medium text-sm">

                        Check Out

                    </p>

                    @if($visit->check_out_at)

                        <span class="rounded-full
                                     bg-[#E3ECE7]
                                     text-[#1E4B43]
                                     px-2 py-1
                                     text-[10px]">

                            Recorded

                        </span>

                    @endif

                </div>


                @if($visit->check_out_at)

                    <p class="text-sm mt-3">

                        {{ $visit
                            ->check_out_at
                            ->format(
                                'd M Y H:i:s'
                            ) }}

                    </p>


                    @if(
                        $visit->check_out_latitude
                        && $visit->check_out_longitude
                    )

                        <p class="text-xs
                                  text-[#62685F]
                                  mt-2">

                            {{ $visit
                                ->check_out_latitude }},

                            {{ $visit
                                ->check_out_longitude }}

                        </p>


                        @if($visit->check_out_accuracy)

                            <p class="text-xs
                                      text-[#62685F]
                                      mt-1">

                                Accuracy:

                                {{ number_format(
                                    $visit
                                        ->check_out_accuracy,
                                    0
                                ) }} m

                            </p>

                        @endif


                        <a href="https://www.google.com/maps/search/?api=1&query={{ $visit->check_out_latitude }},{{ $visit->check_out_longitude }}"
                           target="_blank"
                           rel="noopener"
                           class="inline-flex
                                  items-center
                                  gap-1
                                  mt-3
                                  text-xs
                                  font-medium
                                  text-[#1E4B43]">

                            <i class="fa-solid fa-map-location-dot"></i>

                            View on Map

                        </a>

                    @endif

                @else

                    <p class="text-sm
                              text-[#62685F]
                              mt-3">

                        No check-out recorded.

                    </p>

                @endif

            </div>

        </div>

    </div>

    <div class="pt-4
            border-t
            border-[#DAD4C3]">

        <div class="flex
                items-center
                justify-between
                mb-4">

            <div>

                <h4 class="font-semibold text-sm">
                    Location Verification
                </h4>

                <p class="text-xs
                      text-[#62685F]
                      mt-1">

                    GPS consistency between customer,
                    check-in and check-out.

                </p>

            </div>

        </div>


        <div class="space-y-3">

            {{-- =============================================
                 CHECK IN VS CUSTOMER
            ============================================== --}}
            <div class="flex
                    items-center
                    justify-between
                    gap-3
                    border
                    border-[#DAD4C3]
                    rounded-lg
                    p-3">

                <div>

                    <p class="text-sm font-medium">
                        Check-in → Customer
                    </p>

                    <p class="text-xs
                          text-[#62685F]
                          mt-1">

                        Distance from pinned customer location

                    </p>

                </div>


                @if($checkInCustomerDistance !== null)

                    <div class="text-right">

                        <p class="font-semibold">

                            {{ number_format(
                                $checkInCustomerDistance,
                                0
                            ) }} m

                        </p>


                        @if($checkInCustomerDistance <= 100)

                            <span class="text-[10px]
                                     font-semibold
                                     text-green-700">

                            <i class="fa-solid
                                      fa-circle-check
                                      mr-1">
                            </i>

                            Nearby

                        </span>

                        @else

                            <span class="text-[10px]
                                     font-semibold
                                     text-red-700">

                            <i class="fa-solid
                                      fa-triangle-exclamation
                                      mr-1">
                            </i>

                            Far Away

                        </span>

                        @endif

                    </div>

                @else

                    <span class="text-xs
                             text-[#62685F]">

                    Not available

                </span>

                @endif

            </div>


            {{-- =============================================
                 CHECK OUT VS CUSTOMER
            ============================================== --}}
            <div class="flex
                    items-center
                    justify-between
                    gap-3
                    border
                    border-[#DAD4C3]
                    rounded-lg
                    p-3">

                <div>

                    <p class="text-sm font-medium">
                        Check-out → Customer
                    </p>

                    <p class="text-xs
                          text-[#62685F]
                          mt-1">

                        Distance from pinned customer location

                    </p>

                </div>


                @if($checkOutCustomerDistance !== null)

                    <div class="text-right">

                        <p class="font-semibold">

                            {{ number_format(
                                $checkOutCustomerDistance,
                                0
                            ) }} m

                        </p>


                        @if($checkOutCustomerDistance <= 100)

                            <span class="text-[10px]
                                     font-semibold
                                     text-green-700">

                            <i class="fa-solid
                                      fa-circle-check
                                      mr-1">
                            </i>

                            Nearby

                        </span>

                        @else

                            <span class="text-[10px]
                                     font-semibold
                                     text-red-700">

                            <i class="fa-solid
                                      fa-triangle-exclamation
                                      mr-1">
                            </i>

                            Far Away

                        </span>

                        @endif

                    </div>

                @else

                    <span class="text-xs
                             text-[#62685F]">

                    Not available

                </span>

                @endif

            </div>


            {{-- =============================================
                 CHECK IN VS CHECK OUT
            ============================================== --}}
            <div class="flex
                    items-center
                    justify-between
                    gap-3
                    border
                    border-[#DAD4C3]
                    rounded-lg
                    p-3">

                <div>

                    <p class="text-sm font-medium">

                        Check-in → Check-out

                    </p>

                    <p class="text-xs
                          text-[#62685F]
                          mt-1">

                        Movement between both audit points

                    </p>

                </div>


                @if($checkInOutDistance !== null)

                    <div class="text-right">

                        <p class="font-semibold">

                            {{ number_format(
                                $checkInOutDistance,
                                0
                            ) }} m

                        </p>


                        @if($checkInOutDistance <= 100)

                            <span class="text-[10px]
                                     font-semibold
                                     text-green-700">

                            <i class="fa-solid
                                      fa-circle-check
                                      mr-1">
                            </i>

                            Consistent

                        </span>

                        @else

                            <span class="text-[10px]
                                     font-semibold
                                     text-[#D6772F]">

                            <i class="fa-solid
                                      fa-triangle-exclamation
                                      mr-1">
                            </i>

                            Location Changed

                        </span>

                        @endif

                    </div>

                @else

                    <span class="text-xs
                             text-[#62685F]">

                    Not available

                </span>

                @endif

            </div>

        </div>

    </div>



    {{-- =========================================================
         SAMPLES
    ========================================================== --}}
    <div class="pt-4
                border-t
                border-[#DAD4C3]">

        <div class="flex
                    items-center
                    justify-between
                    gap-3
                    mb-3">

            <h4 class="font-semibold text-sm">

                Samples Given

            </h4>

            <span class="text-xs text-[#62685F]">

                {{ $visit
                    ->visitSamples
                    ->sum('quantity') }}

                total

            </span>

        </div>


        @if($visit->visitSamples->isNotEmpty())

            <div class="space-y-2">

                @foreach($visit->visitSamples as $visitSample)

                    <div class="rounded-lg
                                border
                                border-[#DAD4C3]
                                px-3 py-2
                                flex
                                items-center
                                justify-between
                                gap-3">

                        <div>

                            <p class="text-sm font-medium">

                                {{ $visitSample
                                    ->sample
                                    ?->name
                                    ?? 'Unknown sample' }}

                            </p>

                        </div>


                        <span class="rounded-full
                                     bg-[#FBEAD9]
                                     text-[#D6772F]
                                     px-2.5 py-1
                                     text-xs
                                     font-semibold">

                            × {{ $visitSample->quantity }}

                        </span>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-sm text-[#62685F]">

                No samples were recorded for this visit.

            </p>

        @endif

    </div>


    {{-- =========================================================
         CREATED BY
    ========================================================== --}}
    <div class="pt-4
                border-t
                border-[#DAD4C3]
                text-xs
                text-[#62685F]">

        Created

        {{ $visit
            ->created_at
            ->format(
                'd M Y H:i'
            ) }}

        @if($visit->creator)

            · by

            {{ $visit->creator->name }}

        @endif

    </div>


</div>
