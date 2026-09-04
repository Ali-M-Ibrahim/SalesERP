@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

    @php

        $checkInOutDistance =
            $visit->checkInCheckOutDistance();

        $checkInCustomerDistance =
            $visit->checkInCustomerDistance();

        $checkOutCustomerDistance =
            $visit->checkOutCustomerDistance();

    @endphp


    <div class="p-4 md:p-6">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="flex
                flex-col
                sm:flex-row
                sm:items-center
                sm:justify-between
                gap-4
                mb-5">

            <div>

                <div class="flex
                        items-center
                        gap-2
                        text-xs
                        text-[#62685F]
                        mb-2">

                    <a href="{{ route('admin.visits.index') }}"
                       class="hover:text-[#1E4B43]">

                        Visits

                    </a>

                    <i class="fa-solid
                          fa-chevron-right
                          text-[8px]">
                    </i>

                    <span>
                    Visit Details
                </span>

                </div>


                <h1 class="text-2xl
                       md:text-3xl
                       font-bold">

                    Visit Details

                </h1>


                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    {{ $visit->customer?->name ?? 'Customer' }}

                    @if($visit->scheduled_at)

                        · {{ $visit->scheduled_at?->format('d M Y h:i A') }}

                    @endif

                </p>

            </div>


            <a href="{{ route('admin.visits.index') }}"
               class="inline-flex
                  items-center
                  justify-center
                  gap-2
                  px-4
                  py-2.5
                  rounded-lg
                  border
                  border-[#DAD4C3]
                  bg-white
                  text-sm
                  font-medium">

                <i class="fa-solid fa-arrow-left"></i>

                Back to Visits

            </a>

        </div>



        {{-- =========================================================
             MAIN CARD
        ========================================================== --}}
        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                overflow-hidden">


            {{-- =====================================================
                 MAIN INFO
            ====================================================== --}}
            <div class="p-5">

                <div class="grid
                        sm:grid-cols-2
                        lg:grid-cols-4
                        gap-5">


                    {{-- Customer --}}
                    <div>

                        <p class="text-xs
                              text-[#62685F]">

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


                    {{-- Sales Representative --}}
                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Sales Representative

                        </p>


                        <p class="font-medium mt-1">

                            {{ $visit->salesRep?->name ?? '—' }}

                        </p>

                    </div>


                    {{-- Visit Date --}}
                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Visit Date

                        </p>


                        <p class="font-medium mt-1">

                            {{ $visit->scheduled_at
                                ?->format('d M Y h:i A')
                                ?? '—' }}

                        </p>

                    </div>


                    {{-- Status --}}
                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Status

                        </p>


                        <span class="inline-flex
                                 mt-1
                                 rounded-full
                                 px-2.5
                                 py-1
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
                            ||
                            $visit->status === 'cancelled'
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


                    {{-- Purpose --}}
                    <div class="sm:col-span-2">

                        <p class="text-xs
                              text-[#62685F]">

                            Purpose

                        </p>


                        <p class="font-medium mt-1">

                            {{ $visit->visitPurpose?->name
                                ?? $visit->purpose_other
                                ?? '—' }}

                        </p>

                    </div>


                    {{-- Contact Point --}}
                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Contact Point

                        </p>


                        <p class="font-medium mt-1">

                            {{ $visit->contact_point ?: '—' }}

                        </p>

                    </div>


                    {{-- Contact Position --}}
                    <div>

                        <p class="text-xs
                              text-[#62685F]">

                            Contact Position

                        </p>


                        <p class="font-medium mt-1">

                            {{ $visit->contact_point_position ?: '—' }}

                        </p>

                    </div>

                </div>

            </div>



            {{-- =====================================================
                 NOTES
            ====================================================== --}}
            <div class="border-t
                    border-[#DAD4C3]
                    p-5">

                <h2 class="font-semibold mb-4">

                    Visit Information

                </h2>


                <div class="grid
                        md:grid-cols-2
                        gap-4">


                    {{-- Visit Notes --}}
                    <div class="rounded-lg
                            border
                            border-[#DAD4C3]
                            p-4">

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


                    {{-- Client Requests --}}
                    <div class="rounded-lg
                            border
                            border-[#DAD4C3]
                            p-4">

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

            </div>



            {{-- =====================================================
                 GPS AUDIT
            ====================================================== --}}
            <div class="border-t
                    border-[#DAD4C3]
                    p-5">

                <div class="mb-4">

                    <h2 class="font-semibold">
                        Visit Audit
                    </h2>

                    <p class="text-xs
                          text-[#62685F]
                          mt-1">

                        Check-in and check-out location information.

                    </p>

                </div>


                <div class="grid
                        md:grid-cols-2
                        gap-4">


                    {{-- Check In --}}
                    <div class="rounded-lg
                            border
                            border-[#DAD4C3]
                            p-4">

                        <div class="flex
                                items-center
                                justify-between
                                gap-3">

                            <p class="font-medium">
                                Check In
                            </p>


                            @if($visit->check_in_at)

                                <span class="rounded-full
                                         bg-[#E3ECE7]
                                         text-[#1E4B43]
                                         px-2
                                         py-1
                                         text-[10px]">

                                Recorded

                            </span>

                            @endif

                        </div>


                        @if($visit->check_in_at)

                            <p class="text-sm mt-3">

                                {{ $visit
                                    ->check_in_at
                                    ->format('d M Y H:i:s') }}

                            </p>


                            @if(
                                $visit->check_in_latitude
                                &&
                                $visit->check_in_longitude
                            )

                                <p class="text-xs
                                      text-[#62685F]
                                      mt-2">

                                    {{ $visit->check_in_latitude }},
                                    {{ $visit->check_in_longitude }}

                                </p>


                                @if($visit->check_in_accuracy)

                                    <p class="text-xs
                                          text-[#62685F]
                                          mt-1">

                                        Accuracy:

                                        {{ number_format(
                                            $visit->check_in_accuracy,
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

                                    <i class="fa-solid
                                          fa-map-location-dot">
                                    </i>

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

                            <p class="font-medium">
                                Check Out
                            </p>


                            @if($visit->check_out_at)

                                <span class="rounded-full
                                         bg-[#E3ECE7]
                                         text-[#1E4B43]
                                         px-2
                                         py-1
                                         text-[10px]">

                                Recorded

                            </span>

                            @endif

                        </div>


                        @if($visit->check_out_at)

                            <p class="text-sm mt-3">

                                {{ $visit
                                    ->check_out_at
                                    ->format('d M Y H:i:s') }}

                            </p>


                            @if(
                                $visit->check_out_latitude
                                &&
                                $visit->check_out_longitude
                            )

                                <p class="text-xs
                                      text-[#62685F]
                                      mt-2">

                                    {{ $visit->check_out_latitude }},
                                    {{ $visit->check_out_longitude }}

                                </p>


                                @if($visit->check_out_accuracy)

                                    <p class="text-xs
                                          text-[#62685F]
                                          mt-1">

                                        Accuracy:

                                        {{ number_format(
                                            $visit->check_out_accuracy,
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

                                    <i class="fa-solid
                                          fa-map-location-dot">
                                    </i>

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



            {{-- =====================================================
                 LOCATION VERIFICATION
            ====================================================== --}}
            <div class="border-t
                    border-[#DAD4C3]
                    p-5">

                <div class="mb-4">

                    <h2 class="font-semibold">

                        Location Verification

                    </h2>


                    <p class="text-xs
                          text-[#62685F]
                          mt-1">

                        GPS consistency between customer,
                        check-in and check-out.

                    </p>

                </div>


                <div class="grid
                        md:grid-cols-3
                        gap-4">


                    {{-- Check In vs Customer --}}
                    <div class="rounded-lg
                            border
                            border-[#DAD4C3]
                            p-4">

                        <p class="text-sm font-medium">

                            Check-in → Customer

                        </p>


                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            Distance from customer location

                        </p>


                        @if(
                            $checkInCustomerDistance
                            !== null
                        )

                            <p class="text-xl
                                  font-bold
                                  mt-3">

                                {{ number_format(
                                    $checkInCustomerDistance,
                                    0
                                ) }} m

                            </p>


                            @if(
                                $checkInCustomerDistance
                                <= 100
                            )

                                <span class="inline-flex
                                         items-center
                                         gap-1
                                         mt-2
                                         text-xs
                                         font-semibold
                                         text-green-700">

                                <i class="fa-solid
                                          fa-circle-check">
                                </i>

                                Nearby

                            </span>

                            @else

                                <span class="inline-flex
                                         items-center
                                         gap-1
                                         mt-2
                                         text-xs
                                         font-semibold
                                         text-red-700">

                                <i class="fa-solid
                                          fa-triangle-exclamation">
                                </i>

                                Far Away

                            </span>

                            @endif

                        @else

                            <p class="text-sm
                                  text-[#62685F]
                                  mt-3">

                                Not available

                            </p>

                        @endif

                    </div>



                    {{-- Checkout vs Customer --}}
                    <div class="rounded-lg
                            border
                            border-[#DAD4C3]
                            p-4">

                        <p class="text-sm font-medium">

                            Check-out → Customer

                        </p>


                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            Distance from customer location

                        </p>


                        @if(
                            $checkOutCustomerDistance
                            !== null
                        )

                            <p class="text-xl
                                  font-bold
                                  mt-3">

                                {{ number_format(
                                    $checkOutCustomerDistance,
                                    0
                                ) }} m

                            </p>


                            @if(
                                $checkOutCustomerDistance
                                <= 100
                            )

                                <span class="inline-flex
                                         items-center
                                         gap-1
                                         mt-2
                                         text-xs
                                         font-semibold
                                         text-green-700">

                                <i class="fa-solid
                                          fa-circle-check">
                                </i>

                                Nearby

                            </span>

                            @else

                                <span class="inline-flex
                                         items-center
                                         gap-1
                                         mt-2
                                         text-xs
                                         font-semibold
                                         text-red-700">

                                <i class="fa-solid
                                          fa-triangle-exclamation">
                                </i>

                                Far Away

                            </span>

                            @endif

                        @else

                            <p class="text-sm
                                  text-[#62685F]
                                  mt-3">

                                Not available

                            </p>

                        @endif

                    </div>



                    {{-- In vs Out --}}
                    <div class="rounded-lg
                            border
                            border-[#DAD4C3]
                            p-4">

                        <p class="text-sm font-medium">

                            Check-in → Check-out

                        </p>


                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            Movement between audit points

                        </p>


                        @if(
                            $checkInOutDistance
                            !== null
                        )

                            <p class="text-xl
                                  font-bold
                                  mt-3">

                                {{ number_format(
                                    $checkInOutDistance,
                                    0
                                ) }} m

                            </p>


                            @if(
                                $checkInOutDistance
                                <= 100
                            )

                                <span class="inline-flex
                                         items-center
                                         gap-1
                                         mt-2
                                         text-xs
                                         font-semibold
                                         text-green-700">

                                <i class="fa-solid
                                          fa-circle-check">
                                </i>

                                Consistent

                            </span>

                            @else

                                <span class="inline-flex
                                         items-center
                                         gap-1
                                         mt-2
                                         text-xs
                                         font-semibold
                                         text-[#D6772F]">

                                <i class="fa-solid
                                          fa-triangle-exclamation">
                                </i>

                                Location Changed

                            </span>

                            @endif

                        @else

                            <p class="text-sm
                                  text-[#62685F]
                                  mt-3">

                                Not available

                            </p>

                        @endif

                    </div>

                </div>

            </div>



            {{-- =====================================================
                 SAMPLES
            ====================================================== --}}
            <div class="border-t
                    border-[#DAD4C3]
                    p-5">

                <div class="flex
                        items-center
                        justify-between
                        gap-3
                        mb-4">

                    <h2 class="font-semibold">

                        Samples Given

                    </h2>


                    <span class="text-xs
                             text-[#62685F]">

                    {{ $visit
                        ->visitSamples
                        ->sum('quantity') }}

                    total

                </span>

                </div>


                @if(
                    $visit
                        ->visitSamples
                        ->isNotEmpty()
                )

                    <div class="grid
                            sm:grid-cols-2
                            lg:grid-cols-3
                            gap-3">

                        @foreach(
                            $visit->visitSamples
                            as $visitSample
                        )

                            <div class="rounded-lg
                                    border
                                    border-[#DAD4C3]
                                    px-4
                                    py-3
                                    flex
                                    items-center
                                    justify-between
                                    gap-3">

                                <p class="text-sm
                                      font-medium">

                                    {{ $visitSample
                                        ->sample
                                        ?->name
                                        ?? 'Unknown sample' }}

                                </p>


                                <span class="rounded-full
                                         bg-[#FBEAD9]
                                         text-[#D6772F]
                                         px-2.5
                                         py-1
                                         text-xs
                                         font-semibold">

                                × {{ $visitSample->quantity }}

                            </span>

                            </div>

                        @endforeach

                    </div>

                @else

                    <p class="text-sm
                          text-[#62685F]">

                        No samples were recorded for this visit.

                    </p>

                @endif

            </div>



            {{-- =====================================================
                 META
            ====================================================== --}}
            <div class="border-t
                    border-[#DAD4C3]
                    bg-[#FAF9F5]
                    px-5
                    py-4
                    text-xs
                    text-[#62685F]">

                Created

                {{ $visit
                    ->created_at
                    ->format('d M Y H:i') }}

                @if($visit->creator)

                    · by {{ $visit->creator->name }}

                @endif

            </div>

        </div>

    </div>

@endsection
