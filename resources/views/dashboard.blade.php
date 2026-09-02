@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="p-4 md:p-6">


        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="mb-6">

            <p class="text-sm text-[#62685F]">
                {{ now()->format('l, d M Y') }}
            </p>


            <h1 class="text-2xl
                   md:text-3xl
                   font-bold
                   mt-1">

                Welcome,
                {{ auth()->user()->name }}

            </h1>


            @role('sales_rep')

            <p class="text-sm
                      text-[#62685F]
                      mt-1">

                Here is your sales activity for today.

            </p>

            @endrole

        </div>



        {{-- =========================================================
             KPI CARDS
        ========================================================== --}}
        <div class="grid
                grid-cols-2
                lg:grid-cols-4
                gap-3
                md:gap-4
                mb-6">


            {{-- Today's Visits --}}
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

                            Today's Visits

                        </p>


                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $todayVisitCount }}

                        </p>

                    </div>


                    <div class="w-10 h-10
                            rounded-lg
                            bg-[#E3ECE7]
                            text-[#1E4B43]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-regular fa-calendar-check"></i>

                    </div>

                </div>

            </div>


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

                            Customers

                        </p>


                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $assignedCustomersCount }}

                        </p>

                    </div>


                    <div class="w-10 h-10
                            rounded-lg
                            bg-[#F1EFE7]
                            text-[#1E4B43]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid fa-users"></i>

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

                            Completed

                        </p>


                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $completedTodayCount }}

                        </p>

                    </div>


                    <div class="w-10 h-10
                            rounded-lg
                            bg-[#E3ECE7]
                            text-[#1E4B43]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                </div>

            </div>


            {{-- Samples --}}
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

                            Samples Given

                        </p>


                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $samplesGivenToday }}

                        </p>

                    </div>


                    <div class="w-10 h-10
                            rounded-lg
                            bg-[#FBEAD9]
                            text-[#D6772F]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid fa-box-open"></i>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
             ACTIVE VISIT NOTICE
        ========================================================== --}}
        @if($checkedInTodayCount > 0)

            @php
                $activeVisit =
                    $todayVisits
                        ->firstWhere(
                            'status',
                            'checked_in'
                        );
            @endphp


            @if($activeVisit)

                <div class="bg-[#E3ECE7]
                        border
                        border-[#BFD3C8]
                        rounded-xl
                        p-4
                        mb-6">

                    <div class="flex
                            flex-col
                            sm:flex-row
                            sm:items-center
                            justify-between
                            gap-3">

                        <div>

                            <div class="flex
                                    items-center
                                    gap-2">

                            <span class="w-2.5 h-2.5
                                         rounded-full
                                         bg-[#1E4B43]">
                            </span>


                                <p class="font-semibold
                                      text-[#1E4B43]">

                                    Visit in Progress

                                </p>

                            </div>


                            <p class="text-sm mt-2">

                                {{ $activeVisit->customer?->name }}

                            </p>


                            <p class="text-xs
                                  text-[#62685F]
                                  mt-1">

                                Checked in at

                                {{ $activeVisit
                                    ->check_in_at
                                    ?->format('H:i') }}

                            </p>

                        </div>


                        <div class="flex gap-2">

                            <a href="{{ route(
                            'customers.show',
                            $activeVisit->customer_id
                        ) }}"
                               class="inline-flex
                                  items-center
                                  justify-center
                                  px-4 py-2.5
                                  rounded-lg
                                  border
                                  border-[#1E4B43]
                                  text-[#1E4B43]
                                  text-sm
                                  font-medium">

                                Open Visit

                            </a>


                            <button type="button"
                                    class="dashboard-check-out
                                       inline-flex
                                       items-center
                                       justify-center
                                       gap-2
                                       px-4 py-2.5
                                       rounded-lg
                                       bg-[#D6772F]
                                       text-white
                                       text-sm
                                       font-medium"
                                    data-url="{{ route(
                                    'visits.checkOut',
                                    $activeVisit
                                ) }}">

                                <i class="fa-solid fa-location-arrow"></i>

                                Check Out

                            </button>

                        </div>

                    </div>

                </div>

            @endif

        @endif



        {{-- =========================================================
             MAIN GRID
        ========================================================== --}}
        <div class="grid
                xl:grid-cols-3
                gap-5">


            {{-- =====================================================
                 TODAY VISITS
            ====================================================== --}}
            <div class="xl:col-span-2">

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl">


                    <div class="px-5 py-4
                            border-b
                            border-[#DAD4C3]
                            flex
                            items-center
                            justify-between">

                        <div>

                            <h2 class="font-semibold">

                                Today's Visits

                            </h2>


                            <p class="text-xs
                                  text-[#62685F]
                                  mt-0.5">

                                {{ $todayVisitCount }}
                                scheduled for today

                            </p>

                        </div>

                    </div>



                    @forelse($todayVisits as $visit)

                        <div class="px-5 py-4
                                border-b
                                last:border-b-0
                                border-[#DAD4C3]">


                            <div class="flex
                                    flex-col
                                    sm:flex-row
                                    sm:items-center
                                    justify-between
                                    gap-3">


                                {{-- Visit Information --}}
                                <a href="{{ route(
                                'customers.show',
                                $visit->customer_id
                            ) }}"
                                   class="flex-1
                                      min-w-0">


                                    <div class="flex
                                            flex-wrap
                                            items-center
                                            gap-2">


                                        {{-- Status --}}
                                        <span class="text-[10px]
                                                 uppercase
                                                 font-semibold
                                                 px-2 py-1
                                                 rounded-full

                                        @if(
                                            $visit->status ===
                                            'completed'
                                        )

                                            bg-[#E3ECE7]
                                            text-[#1E4B43]

                                        @elseif(
                                            $visit->status ===
                                            'checked_in'
                                        )

                                            bg-blue-100
                                            text-blue-700

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


                                        <span class="font-semibold">

                                        {{ $visit->customer?->name }}

                                    </span>

                                    </div>


                                    <p class="text-sm
                                          text-[#62685F]
                                          mt-2">

                                        <i class="fa-solid
                                              fa-bullseye
                                              mr-1">
                                        </i>


                                        {{ $visit
                                            ->visitPurpose
                                            ?->name
                                            ?? $visit->purpose_other
                                            ?? 'Visit' }}

                                    </p>


                                    @if($visit->customer?->address)

                                        <p class="text-xs
                                              text-[#62685F]
                                              mt-1
                                              truncate">

                                            <i class="fa-solid
                                                  fa-location-dot
                                                  mr-1">
                                            </i>

                                            {{ $visit
                                                ->customer
                                                ->address }}

                                        </p>

                                    @endif


                                    @if(
                                        $visit
                                            ->visitSamples
                                            ->isNotEmpty()
                                    )

                                        <p class="text-xs
                                              text-[#D6772F]
                                              mt-2">

                                            <i class="fa-solid fa-box mr-1"></i>

                                            {{ $visit
                                                ->visitSamples
                                                ->sum('quantity') }}

                                            samples

                                        </p>

                                    @endif

                                </a>



                                {{-- Actions --}}
                                <div class="flex
                                        items-center
                                        gap-2
                                        shrink-0">


                                    {{-- Check In --}}
                                    @if(!$visit->check_in_at)

                                        <button type="button"
                                                class="dashboard-check-in
                                                   inline-flex
                                                   items-center
                                                   justify-center
                                                   gap-2
                                                   px-4 py-2.5
                                                   rounded-lg
                                                   bg-[#1E4B43]
                                                   text-white
                                                   text-sm
                                                   font-medium"
                                                data-url="{{ route(
                                                'visits.checkIn',
                                                $visit
                                            ) }}">

                                            <i class="fa-solid
                                                  fa-location-dot">
                                            </i>

                                            Check In

                                        </button>


                                        {{-- Check Out --}}
                                    @elseif(!$visit->check_out_at)

                                        <button type="button"
                                                class="dashboard-check-out
                                                   inline-flex
                                                   items-center
                                                   justify-center
                                                   gap-2
                                                   px-4 py-2.5
                                                   rounded-lg
                                                   bg-[#D6772F]
                                                   text-white
                                                   text-sm
                                                   font-medium"
                                                data-url="{{ route(
                                                'visits.checkOut',
                                                $visit
                                            ) }}">

                                            <i class="fa-solid
                                                  fa-location-arrow">
                                            </i>

                                            Check Out

                                        </button>


                                        {{-- Completed --}}
                                    @else

                                        <span class="inline-flex
                                                 items-center
                                                 gap-1
                                                 text-xs
                                                 text-[#1E4B43]
                                                 bg-[#E3ECE7]
                                                 px-3 py-2
                                                 rounded-lg">

                                        <i class="fa-solid fa-check"></i>

                                        Completed

                                    </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                    @empty

                        <div class="py-14 px-5 text-center">

                            <div class="w-12 h-12
                                    mx-auto
                                    rounded-full
                                    bg-[#F1EFE7]
                                    flex
                                    items-center
                                    justify-center
                                    mb-3">

                                <i class="fa-regular
                                      fa-calendar
                                      text-[#62685F]">
                                </i>

                            </div>


                            <p class="font-medium">

                                No visits today

                            </p>


                            <p class="text-sm
                                  text-[#62685F]
                                  mt-1">

                                You don't have any visits scheduled for today.

                            </p>

                        </div>

                    @endforelse

                </div>

            </div>



            {{-- =====================================================
                 RIGHT COLUMN
            ====================================================== --}}
            <div class="space-y-5">


                {{-- Upcoming Visits --}}
                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl">


                    <div class="px-5 py-4
                            border-b
                            border-[#DAD4C3]">

                        <h2 class="font-semibold">

                            Upcoming Visits

                        </h2>

                    </div>


                    @forelse($upcomingVisits as $visit)

                        <a href="{{ route(
                        'customers.show',
                        $visit->customer_id
                    ) }}"
                           class="block
                              px-5 py-3
                              border-b
                              last:border-b-0
                              border-[#DAD4C3]
                              hover:bg-[#FAF9F5]">


                            <p class="font-medium text-sm">

                                {{ $visit->customer?->name }}

                            </p>


                            <div class="flex
                                    items-center
                                    justify-between
                                    gap-2
                                    mt-1">

                                <p class="text-xs
                                      text-[#62685F]">

                                    {{ $visit
                                        ->visitPurpose
                                        ?->name
                                        ?? $visit
                                            ->purpose_other
                                        ?? 'Visit' }}

                                </p>


                                <span class="text-xs
                                         font-medium
                                         text-[#1E4B43]">

                                {{ $visit
                                    ->scheduled_at
                                    ?->format('d M Y h:i A') }}

                            </span>

                            </div>

                        </a>


                    @empty

                        <div class="p-5
                                text-sm
                                text-center
                                text-[#62685F]">

                            No upcoming visits.

                        </div>

                    @endforelse

                </div>



                {{-- Customers not visited --}}
                @role('sales_rep')

                <div class="bg-white
                            border
                            border-[#DAD4C3]
                            rounded-xl">


                    <div class="px-5 py-4
                                border-b
                                border-[#DAD4C3]">

                        <h2 class="font-semibold">

                            Not Yet Visited

                        </h2>

                        <p class="text-xs
                                  text-[#62685F]
                                  mt-0.5">

                            Assigned customers with no visits.

                        </p>

                    </div>


                    @forelse($customersNotVisited as $customer)

                        <a href="{{ route(
                            'customers.show',
                            $customer
                        ) }}"
                           class="flex
                                  items-center
                                  justify-between
                                  gap-3
                                  px-5 py-3
                                  border-b
                                  last:border-b-0
                                  border-[#DAD4C3]
                                  hover:bg-[#FAF9F5]">


                            <div class="min-w-0">

                                <p class="text-sm
                                          font-medium
                                          truncate">

                                    {{ $customer->name }}

                                </p>


                                @if($customer->phone)

                                    <p class="text-xs
                                              text-[#62685F]
                                              mt-1">

                                        {{ $customer->phone }}

                                    </p>

                                @endif

                            </div>


                            <i class="fa-solid
                                      fa-chevron-right
                                      text-xs
                                      text-[#62685F]">
                            </i>

                        </a>


                    @empty

                        <div class="p-5
                                    text-sm
                                    text-center
                                    text-[#62685F]">

                            All assigned customers have been visited.

                        </div>

                    @endforelse

                </div>

                @endrole

            </div>

            {{-- =========================================================
     ADMIN NOTES
========================================================== --}}
            <div class="bg-white
            border
            border-[#DAD4C3]
            rounded-xl
            overflow-hidden
            mb-6">

                <div class="px-4 md:px-5
                py-4
                border-b
                border-[#DAD4C3]
                flex
                items-center
                justify-between
                gap-3">

                    <div>

                        <div class="flex
                        items-center
                        gap-2">

                            <h2 class="font-semibold">
                                Admin Notes
                            </h2>



                                <span class="inline-flex
                                 items-center
                                 justify-center
                                 min-w-5
                                 h-5
                                 rounded-full
                                 bg-red-600
                                 text-white
                                 px-1.5
                                 text-[10px]
                                 font-semibold">

                        {{ count($adminNotes) }}

                    </span>



                        </div>

                        <p class="text-xs
                      text-[#62685F]
                      mt-1">

                            Notes and instructions from management.

                        </p>

                    </div>

                    <i class="fa-regular
                  fa-note-sticky
                  text-[#1E4B43]">
                    </i>

                </div>


                @forelse($adminNotes as $adminNote)

                    @php

                        $noteable =
                            $adminNote->noteable;

                        $isCustomer =
                            $noteable instanceof
                            \App\Models\Customer;

                        $isVisit =
                            $noteable instanceof
                            \App\Models\Visit;

                    @endphp


                    <div class="px-4 md:px-5
                    py-4
                    border-b
                    last:border-b-0
                    border-[#DAD4C3]

            {{ $adminNote->is_important
                ? 'bg-red-50'
                : ($adminNote->read_at
                    ? 'bg-white'
                    : 'bg-[#FAF9F5]') }}">


                        <div class="flex
                        items-start
                        justify-between
                        gap-4">

                            <div class="min-w-0 flex-1">

                                {{-- Header --}}
                                <div class="flex
                                flex-wrap
                                items-center
                                gap-2">

                                    <p class="text-xs
                                  font-semibold">

                                        {{ $adminNote
                                            ->creator
                                            ?->name
                                            ?? 'Admin' }}

                                    </p>


                                    @if(!$adminNote->read_at)

                                        <span class="inline-flex
                                         rounded-full
                                         bg-blue-100
                                         text-blue-700
                                         px-2 py-0.5
                                         text-[9px]
                                         font-semibold">

                                NEW

                            </span>

                                    @endif


                                    @if($adminNote->is_important)

                                        <span class="inline-flex
                                         items-center
                                         gap-1
                                         rounded-full
                                         bg-red-100
                                         text-red-700
                                         px-2 py-0.5
                                         text-[9px]
                                         font-semibold">

                                <i class="fa-solid
                                          fa-triangle-exclamation">
                                </i>

                                IMPORTANT

                            </span>

                                    @endif

                                </div>


                                {{-- Note --}}
                                <p class="text-sm
                              mt-2
                              whitespace-pre-line">

                                    {{ $adminNote->note }}

                                </p>


                                {{-- Related record --}}
                                <div class="flex
                                flex-wrap
                                items-center
                                gap-x-3
                                gap-y-1
                                mt-3
                                text-[10px]
                                text-[#62685F]">

                                    @if($isCustomer)

                                        <span>

                                <i class="fa-solid
                                          fa-user
                                          mr-1">
                                </i>

                                Customer:

                                {{ $noteable->name }}

                            </span>

                                    @elseif($isVisit)

                                        <span>

                                <i class="fa-solid
                                          fa-calendar-check
                                          mr-1">
                                </i>

                                Visit:

                                {{ $noteable
                                    ->scheduled_at
                                    ?->format('d M Y h:i A') }}

                                            @if($noteable->customer)

                                                ·
                                                {{ $noteable
                                                    ->customer
                                                    ->name }}

                                            @endif

                            </span>

                                    @endif


                                    <span>

                            <i class="fa-regular
                                      fa-clock
                                      mr-1">
                            </i>

                            {{ $adminNote
                                ->created_at
                                ->format(
                                    'd M Y H:i'
                                ) }}

                        </span>

                                </div>

                            </div>


                            {{-- Open --}}
                            @if($isCustomer)

                                <a href="{{ route(
                        'customers.show',
                        $noteable
                    ) }}"
                                   class="w-9 h-9
                              shrink-0
                              rounded-lg
                              border
                              border-[#DAD4C3]
                              flex
                              items-center
                              justify-center
                              text-[#62685F]">

                                    <i class="fa-solid
                                  fa-chevron-right
                                  text-xs">
                                    </i>

                                </a>

                            @elseif($isVisit)

                                <a href="{{ route(
                        'customers.show',
                        $noteable->customer_id
                    ) }}"
                                   class="w-9 h-9
                              shrink-0
                              rounded-lg
                              border
                              border-[#DAD4C3]
                              flex
                              items-center
                              justify-center
                              text-[#62685F]">

                                    <i class="fa-solid
                                  fa-chevron-right
                                  text-xs">
                                    </i>

                                </a>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="py-10
                    px-5
                    text-center">

                        <div class="w-11 h-11
                        rounded-full
                        bg-[#F1EFE7]
                        flex
                        items-center
                        justify-center
                        mx-auto">

                            <i class="fa-regular
                          fa-note-sticky
                          text-[#62685F]">
                            </i>

                        </div>

                        <p class="text-sm
                      font-medium
                      mt-3">

                            No admin notes

                        </p>

                        <p class="text-xs
                      text-[#62685F]
                      mt-1">

                            Notes from management will appear here.

                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- =========================================================
         GPS CHECK-IN / CHECK-OUT
    ========================================================== --}}
    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    const csrfToken =
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            ?.content;


                    /*
                     * =====================================================
                     * Get current GPS position
                     * =====================================================
                     */
                    function getCurrentPosition() {

                        return new Promise(
                            function (
                                resolve,
                                reject
                            ) {

                                if (
                                    !navigator.geolocation
                                ) {

                                    reject(
                                        new Error(
                                            'Location is not supported by this device.'
                                        )
                                    );

                                    return;
                                }


                                navigator
                                    .geolocation
                                    .getCurrentPosition(

                                        resolve,

                                        function (error) {

                                            let message =
                                                'Unable to get your location.';


                                            if (
                                                error.code ===
                                                error.PERMISSION_DENIED
                                            ) {

                                                message =
                                                    'Location permission was denied.';

                                            }
                                            else if (
                                                error.code ===
                                                error.POSITION_UNAVAILABLE
                                            ) {

                                                message =
                                                    'Your location is currently unavailable.';

                                            }
                                            else if (
                                                error.code ===
                                                error.TIMEOUT
                                            ) {

                                                message =
                                                    'Getting your location timed out.';

                                            }


                                            reject(
                                                new Error(
                                                    message
                                                )
                                            );
                                        },

                                        {
                                            enableHighAccuracy:
                                                true,

                                            timeout:
                                                20000,

                                            maximumAge:
                                                0
                                        }

                                    );

                            }
                        );

                    }


                    /*
                     * =====================================================
                     * Send GPS to Laravel
                     * =====================================================
                     */
                    async function sendGpsAction(
                        url,
                        button
                    ) {

                        const originalHTML =
                            button.innerHTML;


                        button.disabled =
                            true;


                        button.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin"></i>
                Getting Location...
            `;


                        try {

                            const position =
                                await getCurrentPosition();


                            button.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Saving...
                `;


                            const response =
                                await fetch(
                                    url,
                                    {
                                        method:
                                            'POST',

                                        headers: {

                                            'Accept':
                                                'application/json',

                                            'Content-Type':
                                                'application/json',

                                            'X-Requested-With':
                                                'XMLHttpRequest',

                                            'X-CSRF-TOKEN':
                                            csrfToken
                                        },

                                        body:
                                            JSON.stringify({

                                                latitude:
                                                position
                                                    .coords
                                                    .latitude,

                                                longitude:
                                                position
                                                    .coords
                                                    .longitude,

                                                accuracy:
                                                position
                                                    .coords
                                                    .accuracy
                                            })
                                    }
                                );


                            const contentType =
                                response.headers.get(
                                    'content-type'
                                );


                            if (
                                !contentType ||
                                !contentType.includes(
                                    'application/json'
                                )
                            ) {

                                console.error(
                                    await response.text()
                                );


                                throw new Error(
                                    'The server returned an invalid response.'
                                );
                            }


                            const data =
                                await response.json();


                            if (!response.ok) {

                                throw new Error(
                                    data.message ||
                                    'Unable to complete the operation.'
                                );
                            }


                            window.location.reload();

                        }
                        catch (error) {

                            console.error(
                                error
                            );


                            alert(
                                error.message
                            );


                            button.disabled =
                                false;


                            button.innerHTML =
                                originalHTML;
                        }

                    }


                    /*
                     * =====================================================
                     * Dashboard Check In / Out
                     * =====================================================
                     */
                    document.addEventListener(
                        'click',
                        function (event) {

                            const checkInButton =
                                event.target.closest(
                                    '.dashboard-check-in'
                                );


                            if (checkInButton) {

                                sendGpsAction(
                                    checkInButton
                                        .dataset
                                        .url,

                                    checkInButton
                                );

                                return;
                            }


                            const checkOutButton =
                                event.target.closest(
                                    '.dashboard-check-out'
                                );


                            if (checkOutButton) {

                                sendGpsAction(
                                    checkOutButton
                                        .dataset
                                        .url,

                                    checkOutButton
                                );

                            }

                        }
                    );

                }
            );

        </script>

    @endpush

@endsection
