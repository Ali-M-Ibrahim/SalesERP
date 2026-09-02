@extends('layouts.app')

@section('title', $customer->name)

@section('content')

    @php
        $isAdmin = auth()->user()->hasRole('admin');
        $customersIndexRoute = $isAdmin
            ? route('admin.customers.index')
            : route('customers.index');
    @endphp

    <div class="p-4 md:p-6">

        {{-- =========================================================
             BACK
        ========================================================== --}}
        <div class="mb-4">

            <a href="{{ $customersIndexRoute  }}"
               class="inline-flex
                  items-center
                  gap-2
                  text-sm
                  text-[#62685F]
                  hover:text-[#1E4B43]">

                <i class="fa-solid fa-arrow-left"></i>

                Back to Customers

            </a>

        </div>


        {{-- =========================================================
             CUSTOMER HEADER
        ========================================================== --}}
        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                p-4 md:p-6
                mb-5">

            <div class="flex
                    flex-col
                    md:flex-row
                    md:items-start
                    justify-between
                    gap-4">

                <div>

                    <div class="flex
                            flex-wrap
                            items-center
                            gap-2
                            mb-2">

                    <span class="inline-flex
                                 rounded-full
                                 px-2.5 py-1
                                 text-[10px]
                                 font-semibold
                                 uppercase
                                 {{ $customer->type === 'lead'
                                    ? 'bg-[#FBEAD9] text-[#D6772F]'
                                    : 'bg-[#E3ECE7] text-[#1E4B43]' }}">

                        {{ $customer->type }}

                    </span>


                        <span class="text-xs
                                 font-mono
                                 text-[#62685F]">

                        {{ $customer->customer_number }}

                    </span>

                    </div>


                    <h1 class="text-2xl md:text-3xl font-bold">

                        {{ $customer->name }}

                    </h1>


                    <p class="text-sm
                          text-[#62685F]
                          mt-1">

                        @if($customer->currentAssignment?->salesRep)

                            Managed by

                            <strong>

                                {{ $customer->currentAssignment->salesRep->name }}

                            </strong>

                        @else

                            Unassigned

                        @endif

                    </p>

                </div>


                {{-- Actions --}}
                <div class="grid
                        grid-cols-2
                        sm:flex
                        gap-2">

                    @can('visits.create')

                        <button type="button"
                                id="open-new-visit-modal"
                                class="inline-flex
                                   items-center
                                   justify-center
                                   gap-2
                                   px-4 py-3
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white
                                   text-sm
                                   font-medium">

                            <i class="fa-solid fa-clipboard-check"></i>

                            Log Visit

                        </button>


                        <button type="button"
                                id="open-schedule-visit-modal"
                                class="inline-flex
                                   items-center
                                   justify-center
                                   gap-2
                                   px-4 py-3
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]
                                   bg-white
                                   text-sm
                                   font-medium">

                            <i class="fa-regular fa-calendar-plus"></i>

                            Schedule

                        </button>

                    @endcan


                    @can('customers.update')

                        <a href="{{ route('customers.edit', $customer) }}"
                           class="inline-flex
                              items-center
                              justify-center
                              gap-2
                              px-4 py-3
                              rounded-lg
                              border
                              border-[#DAD4C3]
                              bg-white
                              text-sm
                              font-medium">

                            <i class="fa-solid fa-pen"></i>

                            Edit

                        </a>

                    @endcan

                </div>

            </div>

        </div>


        {{-- =========================================================
             MAIN CONTENT
        ========================================================== --}}
        <div class="grid xl:grid-cols-3 gap-5">


            {{-- =====================================================
                 LEFT COLUMN
            ====================================================== --}}
            <div class="xl:col-span-2 space-y-5">


                {{-- =================================================
                     CUSTOMER INFORMATION
                ================================================== --}}
                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl">

                    <div class="px-5 py-4
                            border-b
                            border-[#DAD4C3]">

                        <h2 class="font-semibold">

                            Customer Information

                        </h2>

                    </div>


                    <div class="p-5
                            grid
                            sm:grid-cols-2
                            gap-5">

                        {{-- Phone --}}
                        <div>

                            <p class="text-xs
                                  text-[#62685F]
                                  mb-1">

                                Phone

                            </p>


                            @if($customer->phone)

                                <a href="tel:{{ $customer->phone }}"
                                   class="inline-flex
                                      items-center
                                      gap-2
                                      font-medium
                                      text-[#1E4B43]">

                                    <i class="fa-solid fa-phone"></i>

                                    {{ $customer->phone }}

                                </a>

                            @else

                                <p>—</p>

                            @endif

                        </div>


                        {{-- Email --}}
                        <div>

                            <p class="text-xs
                                  text-[#62685F]
                                  mb-1">

                                Email

                            </p>


                            @if($customer->email)

                                <a href="mailto:{{ $customer->email }}"
                                   class="inline-flex
                                      items-center
                                      gap-2
                                      font-medium
                                      text-[#1E4B43]">

                                    <i class="fa-regular fa-envelope"></i>

                                    {{ $customer->email }}

                                </a>

                            @else

                                <p>—</p>

                            @endif

                        </div>


                        {{-- Address --}}
                        <div class="sm:col-span-2">

                            <p class="text-xs
                                  text-[#62685F]
                                  mb-1">

                                Address

                            </p>

                            <p class="flex items-start gap-2">

                                <i class="fa-solid
                                      fa-location-dot
                                      mt-1
                                      text-[#1E4B43]">
                                </i>

                                {{ $customer->address ?: 'No address provided' }}

                            </p>

                        </div>

                    </div>

                </div>


                <div class="bg-white
            border
            border-[#DAD4C3]
            rounded-xl
            overflow-hidden">

                    {{-- Header --}}
                    <div class="px-4 py-3
                border-b
                border-[#DAD4C3]
                flex
                items-center
                justify-between
                gap-3">

                        <div>

                            <h3 class="font-semibold text-sm">

                                Admin Notes

                            </h3>

                            <p class="text-xs
                      text-[#62685F]
                      mt-0.5">

                                Notes and instructions regarding this customer.

                            </p>

                        </div>


                        @role('admin')

                        <button type="button"
                                id="open-customer-note-modal"
                                class="inline-flex
                           items-center
                           gap-1.5
                           rounded-lg
                           bg-[#1E4B43]
                           text-white
                           px-3 py-2
                           text-xs
                           font-medium">

                            <i class="fa-solid fa-plus"></i>

                            Add

                        </button>

                        @endrole

                    </div>


                    {{-- Notes --}}
                    <div class="divide-y divide-[#DAD4C3]">

                        @forelse($adminNotes as $adminNote)

                            <div class="p-4

                {{ $adminNote->is_important
                    ? 'bg-red-50'
                    : '' }}">

                                <div class="flex
                            items-start
                            justify-between
                            gap-3">

                                    <div class="min-w-0 flex-1">

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


                                            @if($adminNote->is_important)

                                                <span class="inline-flex
                                             items-center
                                             gap-1
                                             rounded-full
                                             bg-red-100
                                             text-red-700
                                             px-2 py-0.5
                                             text-[9px]
                                             font-semibold
                                             uppercase">

                                    <i class="fa-solid
                                              fa-triangle-exclamation">
                                    </i>

                                    Important

                                </span>

                                            @endif

                                        </div>


                                        <p class="text-sm
                                  mt-2
                                  whitespace-pre-line">

                                            {{ $adminNote->note }}

                                        </p>


                                        <div class="flex
                                    flex-wrap
                                    gap-x-3
                                    gap-y-1
                                    mt-3
                                    text-[10px]
                                    text-[#62685F]">

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


                                            @if($adminNote->salesRep)

                                                <span>

                                    For:

                                    <strong>

                                        {{ $adminNote
                                            ->salesRep
                                            ->name }}

                                    </strong>

                                </span>

                                            @endif



                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="p-8 text-center">

                                <i class="fa-regular
                          fa-note-sticky
                          text-[#62685F]">
                                </i>

                                <p class="text-sm
                          text-[#62685F]
                          mt-2">

                                    No admin notes yet.

                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

                {{-- =================================================
                     LOCATION
                ================================================== --}}
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

                        <h2 class="font-semibold">

                            Customer Location

                        </h2>


                        @can('customers.update')

                            <a href="{{ route('customers.edit', $customer) }}"
                               class="text-sm
                                  text-[#1E4B43]
                                  font-medium">

                                <i class="fa-solid
                                      fa-location-crosshairs
                                      mr-1">
                                </i>

                                Update

                            </a>

                        @endcan

                    </div>


                    @if($customer->latitude && $customer->longitude)

                        <div class="p-5">

                            <div class="flex
                                    items-start
                                    gap-3">

                                <div class="w-11 h-11
                                        rounded-full
                                        bg-[#E3ECE7]
                                        text-[#1E4B43]
                                        flex
                                        items-center
                                        justify-center
                                        shrink-0">

                                    <i class="fa-solid fa-location-dot"></i>

                                </div>


                                <div class="flex-1">

                                    <p class="font-medium">

                                        Location pinned

                                    </p>


                                    @if($customer->address)

                                        <p class="text-sm
                                              text-[#62685F]
                                              mt-1">

                                            {{ $customer->address }}

                                        </p>

                                    @endif


                                    @if($customer->location_updated_at)

                                        <p class="text-xs
                                              text-[#62685F]
                                              mt-2">

                                            Last updated:

                                            {{ $customer
                                                ->location_updated_at
                                                ->format('d M Y H:i') }}

                                        </p>

                                    @endif

                                </div>

                            </div>


                            <div class="grid
                                    grid-cols-1
                                    sm:grid-cols-2
                                    gap-2
                                    mt-5">

                                <a href="https://www.google.com/maps/search/?api=1&query={{ $customer->latitude }},{{ $customer->longitude }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="inline-flex
                                      items-center
                                      justify-center
                                      gap-2
                                      rounded-lg
                                      border
                                      border-[#DAD4C3]
                                      px-4 py-3
                                      text-sm
                                      font-medium">

                                    <i class="fa-solid
                                          fa-map-location-dot
                                          text-[#1E4B43]">
                                    </i>

                                    View Location

                                </a>


                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $customer->latitude }},{{ $customer->longitude }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="inline-flex
                                      items-center
                                      justify-center
                                      gap-2
                                      rounded-lg
                                      bg-[#1E4B43]
                                      text-white
                                      px-4 py-3
                                      text-sm
                                      font-medium">

                                    <i class="fa-solid fa-route"></i>

                                    Get Directions

                                </a>

                            </div>

                        </div>

                    @else

                        <div class="py-12 px-5 text-center">

                            <div class="w-12 h-12
                                    mx-auto
                                    rounded-full
                                    bg-[#E9E4D6]
                                    flex
                                    items-center
                                    justify-center
                                    mb-3">

                                <i class="fa-solid
                                      fa-location-dot
                                      text-[#62685F]">
                                </i>

                            </div>

                            <p class="font-medium">

                                Location not pinned

                            </p>

                            <p class="text-sm
                                  text-[#62685F]
                                  mt-1">

                                The customer's GPS location has not been saved.

                            </p>


                            @can('customers.update')

                                <a href="{{ route('customers.edit', $customer) }}"
                                   class="inline-flex
                                      items-center
                                      justify-center
                                      gap-2
                                      bg-[#1E4B43]
                                      text-white
                                      rounded-lg
                                      px-4 py-3
                                      mt-4
                                      text-sm
                                      font-medium">

                                    <i class="fa-solid fa-location-crosshairs"></i>

                                    Pin Location

                                </a>

                            @endcan

                        </div>

                    @endif

                </div>


                {{-- =================================================
                     VISITS
                ================================================== --}}
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

                                Visit History

                            </h2>

                            <p class="text-xs
                                  text-[#62685F]
                                  mt-0.5">

                                Tap a visit to view or continue it.

                            </p>

                        </div>


                        <span class="text-xs
                                 text-[#62685F]">

                        {{ $visitCount }}

                            {{ \Illuminate\Support\Str::plural(
                                'visit',
                                $visitCount
                            ) }}

                    </span>

                    </div>


                    <div id="visits-container">

                        @include(
                            'customers.partials.visits',
                            ['visits' => $visits]
                        )

                    </div>


                    @if($visits->isEmpty())

                        <div class="py-14 px-5 text-center">

                            <i class="fa-regular
                                  fa-calendar
                                  text-2xl
                                  text-[#62685F]">
                            </i>

                            <p class="font-medium mt-3">

                                No visits yet

                            </p>

                        </div>

                    @endif


                    @if($visitCount > 3)

                        <div id="visits-load-more-wrapper"
                             class="px-5 py-4
                                border-t
                                border-[#DAD4C3]">

                            <button type="button"
                                    id="load-more-visits"
                                    data-url="{{ route(
                                    'customers.visits.loadMore',
                                    $customer
                                ) }}"
                                    data-offset="3"
                                    class="w-full
                                       rounded-lg
                                       border
                                       border-[#DAD4C3]
                                       py-3
                                       text-sm
                                       font-medium
                                       text-[#1E4B43]">

                                Load More Visits

                            </button>

                        </div>

                    @endif

                </div>

        </div>



                {{-- =================================================
                     SHARED RESOURCES
                ================================================== --}}
                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl mt-2 ">

                    <div class="px-5 py-4
                            border-b
                            border-[#DAD4C3]
                            flex
                            items-center
                            justify-between">

                        <h2 class="font-semibold">

                            Shared Resources

                        </h2>

                        <span class="text-xs text-[#62685F]">

                        {{ $resourceShareCount }} shared

                    </span>

                    </div>


                    <div id="resources-container">

                        @include(
                            'customers.partials.resource-shares',
                            ['resourceShares' => $resourceShares]
                        )

                    </div>


                    @if($resourceShares->isEmpty())

                        <div class="py-12 text-center">

                            <p class="text-sm text-[#62685F]">

                                No resources shared.

                            </p>

                        </div>

                    @endif


                    @if($resourceShareCount > 3)

                        <div id="resources-load-more-wrapper"
                             class="px-5 py-4
                                border-t
                                border-[#DAD4C3]">

                            <button type="button"
                                    id="load-more-resources"
                                    data-url="{{ route(
                                    'customers.resources.loadMore',
                                    $customer
                                ) }}"
                                    data-offset="3"
                                    class="w-full
                                       rounded-lg
                                       border
                                       border-[#DAD4C3]
                                       py-3
                                       text-sm
                                       font-medium
                                       text-[#1E4B43]">

                                Load More Resources

                            </button>

                        </div>

                    @endif

                </div>




            {{-- =====================================================
                 RIGHT COLUMN
            ====================================================== --}}
            <div class="space-y-5">


                {{-- STAND --}}
                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl mt-2
                        p-5">

                    <div class="flex
                            items-center
                            justify-between
                            gap-3
                            mb-4">

                        <h2 class="font-semibold">

                            Stand

                        </h2>


                        @if($customer->has_stand)

                            @can('customers.update')

                                <form method="POST"
                                      action="{{ route(
                                      'customers.stand.updateDate',
                                      $customer
                                  ) }}">

                                    @csrf

                                    <button type="submit"
                                            onclick="return confirm('Update the stand date to now?')"
                                            class="inline-flex
                                               items-center
                                               gap-2
                                               rounded-lg
                                               border
                                               border-[#DAD4C3]
                                               px-3 py-2
                                               text-xs
                                               text-[#1E4B43]">

                                        <i class="fa-solid fa-rotate"></i>

                                        Update Stand

                                    </button>

                                </form>

                            @endcan

                        @endif

                    </div>


                    <div class="flex
                            items-center
                            justify-between">

                    <span class="text-sm text-[#62685F]">

                        Customer has stand

                    </span>


                        @if($customer->has_stand)

                            <span class="rounded-full
                                     bg-[#E3ECE7]
                                     text-[#1E4B43]
                                     px-2.5 py-1
                                     text-xs
                                     font-medium">

                            <i class="fa-solid fa-check mr-1"></i>

                            Yes

                        </span>

                        @else

                            <span class="rounded-full
                                     bg-gray-100
                                     px-2.5 py-1
                                     text-xs">

                            No

                        </span>

                        @endif

                    </div>


                    @if($customer->stand_last_updated_at)

                        <p class="text-xs
                              text-[#62685F]
                              mt-4 pt-4
                              border-t
                              border-[#DAD4C3]">

                            Last updated:

                            <strong>

                                {{ $customer
                                    ->stand_last_updated_at
                                    ->format('d M Y H:i') }}

                            </strong>

                        </p>

                    @endif

                </div>


                {{-- QUICK ACTIONS --}}
                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-5">

                    <h2 class="font-semibold mb-4">

                        Quick Actions

                    </h2>


                    <div class="grid grid-cols-2 gap-2">

                        @if($customer->phone)

                            <a href="tel:{{ $customer->phone }}"
                               class="min-h-[80px]
                                  rounded-lg
                                  border
                                  border-[#DAD4C3]
                                  flex
                                  flex-col
                                  items-center
                                  justify-center
                                  gap-2
                                  text-sm">

                                <i class="fa-solid
                                      fa-phone
                                      text-[#1E4B43]">
                                </i>

                                Call

                            </a>

                        @endif


                        @if($customer->email)

                            <a href="mailto:{{ $customer->email }}"
                               class="min-h-[80px]
                                  rounded-lg
                                  border
                                  border-[#DAD4C3]
                                  flex
                                  flex-col
                                  items-center
                                  justify-center
                                  gap-2
                                  text-sm">

                                <i class="fa-regular
                                      fa-envelope
                                      text-[#1E4B43]">
                                </i>

                                Email

                            </a>

                        @endif

                    </div>

                </div>




        </div>

    </div>
    </div>



    {{-- =============================================================
         SCHEDULE VISIT MODAL
    ============================================================= --}}
    @can('visits.create')

        <div id="schedule-visit-modal"
             class="hidden
            fixed
            inset-0
            z-[100]
            bg-black/50
            px-4
            py-6
            overflow-y-auto">

            <div class="min-h-full
                flex
                items-center
                justify-center">

                <div class="bg-white
                    w-full
                    max-w-lg
                    rounded-xl
                    shadow-xl">

                    <div class="px-5 py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        justify-between">

                        <div>

                            <h3 class="font-semibold text-lg">

                                Schedule Visit

                            </h3>

                            <p class="text-xs text-[#62685F] mt-1">

                                {{ $customer->name }}

                            </p>

                        </div>


                        <button type="button"
                                id="close-schedule-visit-modal">

                            <i class="fa-solid fa-xmark"></i>

                        </button>

                    </div>


                    <form method="POST"
                          action="{{ route(
                      'customers.visits.schedule',
                      $customer
                  ) }}">

                        @csrf


                        <div class="p-5 space-y-4">

                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Visit Date *

                                </label>

                                <input type="datetime-local"
                                       name="scheduled_at"
                                       min="{{ now()->toDateString() }}"
                                       value="{{ old('scheduled_at') }}"
                                       required
                                       class="w-full
                                      rounded-lg
                                      border-[#DAD4C3]">

                            </div>


                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Purpose *

                                </label>

                                <select name="visit_purpose_id"
                                        id="schedule-visit-purpose"
                                        required
                                        class="w-full
                                       rounded-lg
                                       border-[#DAD4C3]">

                                    <option value="">

                                        Select purpose

                                    </option>


                                    @foreach($visitPurposes as $purpose)

                                        <option value="{{ $purpose->id }}">

                                            {{ $purpose->name }}

                                        </option>

                                    @endforeach


                                    <option value="other">

                                        Other

                                    </option>

                                </select>

                            </div>


                            <div id="schedule-other-purpose-wrapper"
                                 class="hidden">

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Other Purpose

                                </label>

                                <input type="text"
                                       name="purpose_other"
                                       id="schedule-purpose-other"
                                       class="w-full
                                      rounded-lg
                                      border-[#DAD4C3]">

                            </div>


                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Notes

                                </label>

                                <textarea name="notes"
                                          rows="4"
                                          class="w-full
                                         rounded-lg
                                         border-[#DAD4C3]"></textarea>

                            </div>

                        </div>


                        <div class="px-5 py-4
                            border-t
                            border-[#DAD4C3]
                            flex
                            flex-col-reverse
                            sm:flex-row
                            sm:justify-end
                            gap-2">

                            <button type="button"
                                    id="cancel-schedule-visit"
                                    class="px-5 py-3
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]">

                                Cancel

                            </button>


                            <button type="submit"
                                    class="px-5 py-3
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white">

                                Schedule Visit

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endcan


    {{-- =============================================================
         UNIFIED VISIT MODAL
    ============================================================= --}}
    @can('visits.view')

        <div id="visit-modal"
             class="hidden
            fixed
            inset-0
            z-[110]
            bg-black/50
            px-3
            sm:px-4
            py-4
            overflow-y-auto">

            <div class="min-h-full
                flex
                items-center
                justify-center">

                <div class="bg-white
                    w-full
                    max-w-2xl
                    rounded-xl
                    shadow-xl
                    overflow-hidden">


                    {{-- HEADER --}}
                    <div class="px-5 py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-start
                        justify-between
                        gap-3">

                        <div>

                            <h3 id="visit-modal-title"
                                class="font-semibold text-lg">

                                Log Visit

                            </h3>

                            <p class="text-xs
                              text-[#62685F]
                              mt-1">

                                {{ $customer->name }}

                                <span id="visit-modal-date"></span>

                            </p>

                        </div>


                        <button type="button"
                                id="close-visit-modal"
                                class="w-9 h-9
                               rounded-full
                               flex
                               items-center
                               justify-center">

                            <i class="fa-solid fa-xmark"></i>

                        </button>

                    </div>


                    <form id="visit-form">

                        @csrf

                        <input type="hidden"
                               id="visit-id">

                        <input type="hidden"
                               id="visit-mode"
                               value="create">


                        <div class="p-5 space-y-5">


                            {{-- STATUS --}}
                            <div id="visit-status-container"
                                 class="hidden
                                rounded-lg
                                bg-[#F1EFE7]
                                px-4 py-3">

                                <div class="flex
                                    items-center
                                    justify-between">

                            <span class="text-xs text-[#62685F]">

                                Status

                            </span>

                                    <span id="visit-status"
                                          class="text-xs
                                         uppercase
                                         font-semibold">
                            </span>

                                </div>

                            </div>


                            {{-- PURPOSE --}}
                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Visit Purpose *

                                </label>


                                <select name="visit_purpose_id"
                                        id="visit-purpose"
                                        required
                                        class="w-full
                                       rounded-lg
                                       border-[#DAD4C3]">

                                    <option value="">

                                        Select purpose

                                    </option>


                                    @foreach($visitPurposes as $purpose)

                                        <option value="{{ $purpose->id }}">

                                            {{ $purpose->name }}

                                        </option>

                                    @endforeach


                                    <option value="other">

                                        Other

                                    </option>

                                </select>

                            </div>


                            {{-- OTHER PURPOSE --}}
                            <div id="visit-other-wrapper"
                                 class="hidden">

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Other Purpose *

                                </label>

                                <input type="text"
                                       name="purpose_other"
                                       id="visit-purpose-other"
                                       maxlength="255"
                                       class="w-full
                                      rounded-lg
                                      border-[#DAD4C3]">

                            </div>


                            {{-- VISIT NOTES --}}
                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Visit Notes*

                                </label>

                                <textarea name="visit_notes"
                                          id="visit-notes"
                                          rows="3"
                                          required
                                          maxlength="3000"
                                          placeholder="What happened during the visit?"
                                          class="w-full
                                         rounded-lg
                                         border-[#DAD4C3]"></textarea>

                            </div>


                            {{-- CLIENT REQUESTS --}}
                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Client Requests

                                </label>

                                <textarea name="client_requests"
                                          id="visit-client-requests"
                                          rows="3"

                                          maxlength="3000"
                                          placeholder="What did the client request? If none, enter None."
                                          class="w-full
                                         rounded-lg
                                         border-[#DAD4C3]"></textarea>

                            </div>


                            {{-- CONTACT POINT --}}
                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Contact Point*

                                </label>

                                <input name="contact_point"
                                          id="contact_point"
                                          placeholder="Contact point"
                                        required
                                          class="w-full
                                         rounded-lg
                                         border-[#DAD4C3]"></input>

                            </div>

                            {{-- CONTACT POSITION --}}
                            <div>

                                <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                    Contact Position*

                                </label>

                                <input name="contact_position"
                                       id="contact_position"
                                       required
                                       placeholder="Contact Position"
                                       class="w-full
                                         rounded-lg
                                         border-[#DAD4C3]"></input>

                            </div>



                            {{-- =================================================
                                 SAMPLES
                            ================================================== --}}
                            <div>

                                <h4 class="text-sm font-medium">

                                    Samples Given

                                </h4>

                                <p class="text-xs
                                  text-[#62685F]
                                  mt-1 mb-3">

                                    Select samples and enter the quantity provided.

                                </p>


                                <div class="space-y-2
                                    max-h-72
                                    overflow-y-auto">

                                    @foreach($samples as $sample)

                                        <div class="border
                                            border-[#DAD4C3]
                                            rounded-lg
                                            p-3">

                                            <div class="flex
                                                items-center
                                                gap-3">

                                                <input type="checkbox"
                                                       class="visit-sample-checkbox
                                                      rounded
                                                      border-[#DAD4C3]"
                                                       data-id="{{ $sample->id }}"
                                                       name="samples[{{ $sample->id }}][selected]"
                                                       value="1">


                                                <div class="flex-1 min-w-0">

                                                    <p class="text-sm font-medium">

                                                        {{ $sample->name }}

                                                    </p>


                                                    @if($sample->sampleCategory)

                                                        <p class="text-xs
                                                          text-[#62685F]">

                                                            {{ $sample
                                                                ->sampleCategory
                                                                ->name }}

                                                        </p>

                                                    @endif

                                                </div>


                                                <div class="w-24">

                                                    <label class="text-[10px]
                                                          text-[#62685F]">

                                                        Quantity

                                                    </label>

                                                    <input type="number"
                                                           min="1"
                                                           max="999"
                                                           value="1"
                                                           disabled
                                                           name="samples[{{ $sample->id }}][quantity]"
                                                           id="visit-sample-quantity-{{ $sample->id }}"
                                                           class="visit-sample-quantity
                                                          w-full
                                                          rounded-lg
                                                          border-[#DAD4C3]
                                                          text-sm">

                                                </div>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>


                            {{-- =================================================
                                 AUDIT
                            ================================================== --}}
                            <div id="visit-audit"
                                 class="hidden
                                border
                                border-[#DAD4C3]
                                rounded-lg
                                p-4">

                                <h4 class="text-sm font-medium mb-3">

                                    Visit Audit

                                </h4>


                                <div id="visit-checkin-info"
                                     class="hidden
                                    rounded-lg
                                    bg-[#E3ECE7]
                                    px-3 py-2
                                    text-xs
                                    text-[#1E4B43]
                                    mb-2">
                                </div>


                                <div id="visit-checkout-info"
                                     class="hidden
                                    rounded-lg
                                    bg-[#E3ECE7]
                                    px-3 py-2
                                    text-xs
                                    text-[#1E4B43]">
                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             FOOTER
                        ================================================== --}}

                        @if( Auth::user()->canAny(['visits.create']))
                        <div class="px-5 py-4
                            border-t
                            border-[#DAD4C3]
                            bg-white
                            flex
                            flex-col
                            sm:flex-row
                            gap-2">


                            {{-- Existing visit check-in --}}
                            <button type="button"
                                    id="visit-checkin-button"
                                    class="hidden
                                   w-full sm:w-auto
                                   px-5 py-3
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white
                                   text-sm
                                   font-medium">

                                <i class="fa-solid fa-location-dot mr-1"></i>

                                Check In

                            </button>


                            {{-- Existing visit checkout --}}
                            <button type="button"
                                    id="visit-checkout-button"
                                    class="hidden
                                   w-full sm:w-auto
                                   px-5 py-3
                                   rounded-lg
                                   bg-[#D6772F]
                                   text-white
                                   text-sm
                                   font-medium">

                                <i class="fa-solid fa-location-arrow mr-1"></i>

                                Check Out

                            </button>


                            <div class="flex-1"></div>


                            <button type="button"
                                    id="cancel-visit-modal"
                                    class="w-full sm:w-auto
                                   px-5 py-3
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]
                                   text-sm">

                                Cancel

                            </button>


                            {{-- New visit only --}}
                            <button type="button"
                                    id="save-visit-only-button"
                                    class="w-full sm:w-auto
                                   px-5 py-3
                                   rounded-lg
                                   border
                                   border-[#1E4B43]
                                   text-[#1E4B43]
                                   text-sm
                                   font-medium">

                                Save Visit

                            </button>


                            {{-- New visit only --}}
                            <button type="button"
                                    id="save-and-checkin-button"
                                    class="w-full sm:w-auto
                                   px-5 py-3
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white
                                   text-sm
                                   font-medium">

                                <i class="fa-solid fa-location-dot mr-1"></i>

                                Save & Check In

                            </button>


                            {{-- Existing visit --}}
                            <button type="button"
                                    id="update-visit-button"
                                    class="hidden
                                   w-full sm:w-auto
                                   px-5 py-3
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white
                                   text-sm
                                   font-medium">

                                Save Changes

                            </button>

                        </div>

                        @endif
                    </form>

                </div>

            </div>

        </div>

    @endcan

    @role('admin')

    <div id="customer-note-modal"
         class="hidden
            fixed
            inset-0
            z-[150]
            bg-black/50
            p-4
            overflow-y-auto">

        <div class="min-h-full
                flex
                items-center
                justify-center">

            <div class="bg-white
                    w-full
                    max-w-lg
                    rounded-xl
                    shadow-xl">

                <div class="px-5 py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-center
                        justify-between">

                    <div>

                        <h3 class="font-semibold">

                            Add Customer Note

                        </h3>

                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            {{ $customer->name }}

                        </p>

                    </div>


                    <button type="button"
                            id="close-customer-note-modal"
                            class="w-8 h-8">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                <form method="POST"
                      action="{{ route(
                      'admin.customers.notes.store',
                      $customer
                  ) }}">

                    @csrf


                    <div class="p-5 space-y-4">

                        <div>

                            <label class="block
                                      text-sm
                                      font-medium
                                      mb-1.5">

                                Note *

                            </label>

                            <textarea name="note"
                                      rows="5"
                                      required
                                      placeholder="Write an instruction or note for the sales representative..."
                                      class="w-full
                                         rounded-lg
                                         border-[#DAD4C3]">{{ old('note') }}</textarea>

                        </div>


                        <label class="flex
                                  items-center
                                  gap-3
                                  cursor-pointer">

                            <input type="checkbox"
                                   name="is_important"
                                   value="1"
                                   class="rounded
                                      border-[#DAD4C3]
                                      text-[#1E4B43]">

                            <div>

                                <p class="text-sm font-medium">

                                    Mark as important

                                </p>

                                <p class="text-xs
                                      text-[#62685F]">

                                    Highlight this note for the sales representative.

                                </p>

                            </div>

                        </label>

                    </div>


                    <div class="px-5 py-4
                            border-t
                            border-[#DAD4C3]
                            flex
                            justify-end
                            gap-2">

                        <button type="button"
                                id="cancel-customer-note-modal"
                                class="px-4 py-2.5
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]
                                   text-sm">

                            Cancel

                        </button>


                        <button type="submit"
                                class="px-4 py-2.5
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white
                                   text-sm
                                   font-medium">

                            <i class="fa-solid
                                  fa-paper-plane
                                  mr-1">
                            </i>

                            Add Note

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    @endrole


    {{-- =============================================================
 RESCHEDULE VISIT MODAL
============================================================= --}}
    <div id="reschedule-visit-modal"
         class="hidden
            fixed
            inset-0
            z-[150]
            bg-black/50
            p-4">

        <div class="min-h-full
                flex
                items-center
                justify-center">

            <div class="bg-white
                    w-full
                    max-w-md
                    rounded-xl
                    shadow-xl
                    overflow-hidden">

                {{-- Header --}}
                <div class="px-5
                        py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-center
                        justify-between">

                    <div>

                        <h3 class="font-semibold text-lg">
                            Reschedule Visit
                        </h3>

                        <p class="text-xs
                              text-[#62685F]
                              mt-1">
                            Select the new visit date and time.
                        </p>

                    </div>


                    <button type="button"
                            id="close-reschedule-modal"
                            class="w-9
                               h-9
                               rounded-full
                               hover:bg-[#F1EFE7]">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                {{-- Body --}}
                <form id="reschedule-visit-form">

                    <div class="p-5">

                        <label for="reschedule-scheduled-at"
                               class="block
                                  text-sm
                                  font-medium
                                  mb-2">

                            New Date & Time
                            <span class="text-red-600">*</span>

                        </label>


                        <input type="datetime-local"
                               id="reschedule-scheduled-at"
                               name="scheduled_at"
                               required
                               class="w-full
                                  rounded-lg
                                  border
                                  border-[#DAD4C3]
                                  px-3
                                  py-2.5
                                  text-base
                                  focus:ring-[#1E4B43]
                                  focus:border-[#1E4B43]">


                        <div id="reschedule-error"
                             class="hidden
                                mt-3
                                rounded-lg
                                bg-red-50
                                px-3
                                py-2
                                text-sm
                                text-red-700">
                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="px-5
                            py-4
                            border-t
                            border-[#DAD4C3]
                            flex
                            justify-end
                            gap-2">

                        <button type="button"
                                id="cancel-reschedule-modal"
                                class="px-4
                                   py-2.5
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]
                                   text-sm
                                   font-medium">

                            Cancel

                        </button>


                        <button type="submit"
                                id="save-reschedule-button"
                                class="inline-flex
                                   items-center
                                   justify-center
                                   gap-2
                                   px-4
                                   py-2.5
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white
                                   text-sm
                                   font-medium">

                            <i class="fa-regular fa-calendar-check"></i>

                            Reschedule Visit

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
         JAVASCRIPT
    ============================================================= --}}

    @push('scripts')

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                /*
                 * =========================================================
                 * GLOBAL VARIABLES
                 * =========================================================
                 */
                const csrfMeta = document.querySelector(
                    'meta[name="csrf-token"]'
                );

                const csrfToken = csrfMeta
                    ? csrfMeta.content
                    : '';


                /*
                 * =========================================================
                 * JSON RESPONSE HELPER
                 * =========================================================
                 */
                async function getJson(response) {

                    const contentType =
                        response.headers.get('content-type');


                    if (
                        !contentType ||
                        !contentType.includes('application/json')
                    ) {

                        const text =
                            await response.text();


                        console.error(
                            'Non JSON response:',
                            text
                        );


                        throw new Error(
                            'The server returned an invalid response.'
                        );
                    }


                    const data =
                        await response.json();


                    if (!response.ok) {

                        let message =
                            data.message ||
                            'Unable to complete the operation.';


                        /*
                         * Laravel validation errors.
                         */
                        if (data.errors) {

                            message =
                                Object.values(data.errors)
                                    .flat()
                                    .join('\n');

                        }


                        throw new Error(message);
                    }


                    return data;
                }



                /*
                 * =========================================================
                 * GPS HELPER
                 * =========================================================
                 */
                function getCurrentPosition() {

                    return new Promise(
                        function (resolve, reject) {

                            if (!navigator.geolocation) {

                                reject(
                                    new Error(
                                        'Location is not supported by this device.'
                                    )
                                );

                                return;
                            }


                            navigator.geolocation.getCurrentPosition(

                                /*
                                 * Success
                                 */
                                resolve,


                                /*
                                 * Error
                                 */
                                function (error) {

                                    let message =
                                        'Unable to get your current location.';


                                    if (
                                        error.code ===
                                        error.PERMISSION_DENIED
                                    ) {

                                        message =
                                            'Location permission was denied. Please allow location access for this website.';

                                    }
                                    else if (
                                        error.code ===
                                        error.POSITION_UNAVAILABLE
                                    ) {

                                        message =
                                            'Your current location is unavailable. Please make sure GPS/location services are enabled.';

                                    }
                                    else if (
                                        error.code ===
                                        error.TIMEOUT
                                    ) {

                                        message =
                                            'Getting your location timed out. Please try again.';

                                    }


                                    reject(
                                        new Error(message)
                                    );
                                },


                                /*
                                 * GPS configuration
                                 */
                                {
                                    enableHighAccuracy: true,
                                    timeout: 20000,
                                    maximumAge: 0
                                }

                            );

                        }
                    );

                }



                /*
                 * =========================================================
                 * SEND CHECK IN / CHECK OUT WITH GPS
                 * =========================================================
                 */
                async function sendGpsAction(
                    url,
                    button
                ) {

                    if (!url) {

                        alert(
                            'Visit action URL is missing.'
                        );

                        return;
                    }


                    const originalHTML =
                        button.innerHTML;


                    button.disabled = true;


                    button.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin mr-1"></i>
            Getting Location...
        `;


                    try {

                        /*
                         * Get device GPS.
                         */
                        const position =
                            await getCurrentPosition();


                        button.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                Saving...
            `;


                        /*
                         * Send GPS to Laravel.
                         */
                        const response =
                            await fetch(
                                url,
                                {
                                    method: 'POST',

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

                                    body: JSON.stringify({

                                        latitude:
                                        position.coords.latitude,

                                        longitude:
                                        position.coords.longitude,

                                        accuracy:
                                        position.coords.accuracy

                                    })
                                }
                            );


                        const data =
                            await getJson(response);


                        /*
                         * Reload page so status changes:
                         *
                         * scheduled
                         *      ↓
                         * checked_in
                         *      ↓
                         * completed
                         */
                        window.location.reload();

                    }
                    catch (error) {

                        console.error(error);

                        alert(error.message);


                        button.disabled = false;

                        button.innerHTML =
                            originalHTML;

                    }

                }



                /*
                 * =========================================================
                 * LOAD MORE VISITS
                 * =========================================================
                 */
                const loadVisitsButton =
                    document.getElementById(
                        'load-more-visits'
                    );


                loadVisitsButton?.addEventListener(
                    'click',
                    async function () {

                        const button = this;

                        const originalHTML =
                            button.innerHTML;


                        button.disabled = true;

                        button.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                Loading...
            `;


                        try {

                            const response =
                                await fetch(
                                    `${button.dataset.url}?offset=${button.dataset.offset}`,
                                    {
                                        headers: {

                                            'Accept':
                                                'application/json',

                                            'X-Requested-With':
                                                'XMLHttpRequest'
                                        }
                                    }
                                );


                            const data =
                                await getJson(response);



                            document
                                .getElementById(
                                    'visits-container'
                                )
                                .insertAdjacentHTML(
                                    'beforeend',
                                    data.html
                                );


                            button.dataset.offset =
                                data.new_offset;


                            if (!data.has_more) {

                                document
                                    .getElementById(
                                        'visits-load-more-wrapper'
                                    )
                                    ?.remove();

                                return;
                            }


                            button.disabled = false;

                            button.innerHTML =
                                originalHTML;

                        }
                        catch (error) {

                            console.error(error);

                            alert(error.message);


                            button.disabled = false;

                            button.innerHTML =
                                originalHTML;
                        }

                    }
                );



                /*
                 * =========================================================
                 * LOAD MORE RESOURCES
                 * =========================================================
                 */
                const loadResourcesButton =
                    document.getElementById(
                        'load-more-resources'
                    );


                loadResourcesButton?.addEventListener(
                    'click',
                    async function () {

                        const button = this;

                        const originalHTML =
                            button.innerHTML;


                        button.disabled = true;

                        button.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                Loading...
            `;


                        try {

                            const response =
                                await fetch(
                                    `${button.dataset.url}?offset=${button.dataset.offset}`,
                                    {
                                        headers: {

                                            'Accept':
                                                'application/json',

                                            'X-Requested-With':
                                                'XMLHttpRequest'
                                        }
                                    }
                                );


                            const data =
                                await getJson(response);


                            document
                                .getElementById(
                                    'resources-container'
                                )
                                .insertAdjacentHTML(
                                    'beforeend',
                                    data.html
                                );


                            button.dataset.offset =
                                data.new_offset;


                            if (!data.has_more) {

                                document
                                    .getElementById(
                                        'resources-load-more-wrapper'
                                    )
                                    ?.remove();

                                return;
                            }


                            button.disabled = false;

                            button.innerHTML =
                                originalHTML;

                        }
                        catch (error) {

                            console.error(error);

                            alert(error.message);


                            button.disabled = false;

                            button.innerHTML =
                                originalHTML;
                        }

                    }
                );



                /*
                 * =========================================================
                 * SCHEDULE VISIT MODAL
                 * =========================================================
                 */
                const scheduleModal =
                    document.getElementById(
                        'schedule-visit-modal'
                    );

                const scheduleOpenButton =
                    document.getElementById(
                        'open-schedule-visit-modal'
                    );

                const scheduleCloseButton =
                    document.getElementById(
                        'close-schedule-visit-modal'
                    );

                const scheduleCancelButton =
                    document.getElementById(
                        'cancel-schedule-visit'
                    );

                const schedulePurpose =
                    document.getElementById(
                        'schedule-visit-purpose'
                    );

                const scheduleOtherWrapper =
                    document.getElementById(
                        'schedule-other-purpose-wrapper'
                    );

                const scheduleOtherInput =
                    document.getElementById(
                        'schedule-purpose-other'
                    );


                function openScheduleModal() {

                    if (!scheduleModal) {
                        return;
                    }


                    scheduleModal.classList.remove(
                        'hidden'
                    );


                    document.body.classList.add(
                        'overflow-hidden'
                    );
                }


                function closeScheduleModal() {

                    if (!scheduleModal) {
                        return;
                    }


                    scheduleModal.classList.add(
                        'hidden'
                    );


                    document.body.classList.remove(
                        'overflow-hidden'
                    );
                }


                scheduleOpenButton?.addEventListener(
                    'click',
                    openScheduleModal
                );


                scheduleCloseButton?.addEventListener(
                    'click',
                    closeScheduleModal
                );


                scheduleCancelButton?.addEventListener(
                    'click',
                    closeScheduleModal
                );


                schedulePurpose?.addEventListener(
                    'change',
                    function () {

                        if (
                            this.value === 'other'
                        ) {

                            scheduleOtherWrapper
                                ?.classList
                                .remove('hidden');


                            if (scheduleOtherInput) {

                                scheduleOtherInput.required =
                                    true;

                            }

                        }
                        else {

                            scheduleOtherWrapper
                                ?.classList
                                .add('hidden');


                            if (scheduleOtherInput) {

                                scheduleOtherInput.required =
                                    false;

                                scheduleOtherInput.value =
                                    '';

                            }

                        }

                    }
                );



                /*
                 * =========================================================
                 * VISIT MODAL VARIABLES
                 * =========================================================
                 */
                const visitModal =
                    document.getElementById(
                        'visit-modal'
                    );

                const visitForm =
                    document.getElementById(
                        'visit-form'
                    );

                const visitMode =
                    document.getElementById(
                        'visit-mode'
                    );

                const visitId =
                    document.getElementById(
                        'visit-id'
                    );

                const visitPurpose =
                    document.getElementById(
                        'visit-purpose'
                    );

                const visitOtherWrapper =
                    document.getElementById(
                        'visit-other-wrapper'
                    );

                const visitOtherInput =
                    document.getElementById(
                        'visit-purpose-other'
                    );

                const visitNotes =
                    document.getElementById(
                        'visit-notes'
                    );

                const visitRequests =
                    document.getElementById(
                        'visit-client-requests'
                    );

                const visitContactPoint =
                    document.getElementById(
                        'contact_point'
                    );

                const visitContactPosition =
                    document.getElementById(
                        'contact_position'
                    );

                /*
                 * Modal-specific Check In / Out buttons.
                 */
                const modalCheckInButton =
                    document.getElementById(
                        'visit-checkin-button'
                    );

                const modalCheckOutButton =
                    document.getElementById(
                        'visit-checkout-button'
                    );

                const saveVisitButton =
                    document.getElementById(
                        'save-visit-only-button'
                    );

                const saveAndCheckInButton =
                    document.getElementById(
                        'save-and-checkin-button'
                    );

                const updateVisitButton =
                    document.getElementById(
                        'update-visit-button'
                    );



                /*
                 * =========================================================
                 * SHOW / HIDE VISIT MODAL
                 * =========================================================
                 */
                function showVisitModal() {

                    if (!visitModal) {
                        return;
                    }


                    visitModal.classList.remove(
                        'hidden'
                    );


                    document.body.classList.add(
                        'overflow-hidden'
                    );
                }


                function hideVisitModal() {

                    if (!visitModal) {
                        return;
                    }


                    visitModal.classList.add(
                        'hidden'
                    );


                    document.body.classList.remove(
                        'overflow-hidden'
                    );
                }



                /*
                 * =========================================================
                 * ENABLE / DISABLE VISIT FORM
                 * =========================================================
                 */
                function setVisitFieldsDisabled(
                    disabled
                ) {

                    if (visitPurpose) {
                        visitPurpose.disabled = disabled;
                    }

                    if (visitOtherInput) {
                        visitOtherInput.disabled = disabled;
                    }

                    if (visitNotes) {
                        visitNotes.disabled = disabled;
                    }

                    if (visitRequests) {
                        visitRequests.disabled = disabled;
                    }

                    if (visitContactPoint) {
                        visitContactPoint.disabled = disabled;
                    }

                    if (visitContactPosition) {
                        visitContactPosition.disabled = disabled;
                    }


                    document
                        .querySelectorAll(
                            '.visit-sample-checkbox'
                        )
                        .forEach(
                            function (element) {

                                element.disabled =
                                    disabled;

                            }
                        );


                    document
                        .querySelectorAll(
                            '.visit-sample-quantity'
                        )
                        .forEach(
                            function (element) {

                                /*
                                 * For editable visit:
                                 * quantities are enabled only
                                 * when checkbox is selected.
                                 */
                                if (disabled) {

                                    element.disabled =
                                        true;

                                }
                                else {

                                    const sampleId =
                                        element.id.replace(
                                            'visit-sample-quantity-',
                                            ''
                                        );

                                    const checkbox =
                                        document.querySelector(
                                            `.visit-sample-checkbox[data-id="${sampleId}"]`
                                        );


                                    element.disabled =
                                        !checkbox?.checked;
                                }

                            }
                        );

                }



                /*
                 * =========================================================
                 * RESET VISIT MODAL
                 * =========================================================
                 */
                function resetVisitModal() {

                    if (!visitForm) {
                        return;
                    }


                    visitForm.reset();


                    visitMode.value =
                        'create';


                    visitId.value =
                        '';


                    document.getElementById(
                        'visit-modal-title'
                    ).textContent =
                        'Log Visit';


                    document.getElementById(
                        'visit-modal-date'
                    ).textContent =
                        '· {{ now()->format('d M Y') }}';


                    /*
                     * Hide status.
                     */
                    document
                        .getElementById(
                            'visit-status-container'
                        )
                        ?.classList
                        .add('hidden');


                    /*
                     * Hide audit.
                     */
                    document
                        .getElementById(
                            'visit-audit'
                        )
                        ?.classList
                        .add('hidden');


                    document
                        .getElementById(
                            'visit-checkin-info'
                        )
                        ?.classList
                        .add('hidden');


                    document
                        .getElementById(
                            'visit-checkout-info'
                        )
                        ?.classList
                        .add('hidden');


                    /*
                     * Other purpose.
                     */
                    visitOtherWrapper
                        ?.classList
                        .add('hidden');


                    if (visitOtherInput) {

                        visitOtherInput.required =
                            false;

                        visitOtherInput.value =
                            '';
                    }


                    /*
                     * Hide existing visit actions.
                     */
                    modalCheckInButton
                        ?.classList
                        .add('hidden');

                    modalCheckOutButton
                        ?.classList
                        .add('hidden');

                    updateVisitButton
                        ?.classList
                        .add('hidden');


                    /*
                     * Show new visit actions.
                     */
                    saveVisitButton
                        ?.classList
                        .remove('hidden');

                    saveAndCheckInButton
                        ?.classList
                        .remove('hidden');


                    /*
                     * Reset samples.
                     */
                    document
                        .querySelectorAll(
                            '.visit-sample-checkbox'
                        )
                        .forEach(
                            function (checkbox) {

                                checkbox.checked =
                                    false;

                                checkbox.disabled =
                                    false;
                            }
                        );


                    document
                        .querySelectorAll(
                            '.visit-sample-quantity'
                        )
                        .forEach(
                            function (input) {

                                input.value = 1;

                                input.disabled =
                                    true;
                            }
                        );


                    setVisitFieldsDisabled(
                        false
                    );

                }



                /*
                 * =========================================================
                 * OPEN NEW LOG VISIT
                 * =========================================================
                 */
                document
                    .getElementById(
                        'open-new-visit-modal'
                    )
                    ?.addEventListener(
                        'click',
                        function () {

                            resetVisitModal();

                            showVisitModal();

                        }
                    );



                /*
                 * =========================================================
                 * CLOSE VISIT MODAL
                 * =========================================================
                 */
                document
                    .getElementById(
                        'close-visit-modal'
                    )
                    ?.addEventListener(
                        'click',
                        hideVisitModal
                    );


                document
                    .getElementById(
                        'cancel-visit-modal'
                    )
                    ?.addEventListener(
                        'click',
                        hideVisitModal
                    );



                /*
                 * =========================================================
                 * VISIT PURPOSE OTHER
                 * =========================================================
                 */
                visitPurpose?.addEventListener(
                    'change',
                    function () {

                        if (
                            this.value === 'other'
                        ) {

                            visitOtherWrapper
                                ?.classList
                                .remove('hidden');


                            visitOtherInput.required =
                                true;

                        }
                        else {

                            visitOtherWrapper
                                ?.classList
                                .add('hidden');


                            visitOtherInput.required =
                                false;

                            visitOtherInput.value =
                                '';

                        }

                    }
                );



                /*
                 * =========================================================
                 * SAMPLE QUANTITY
                 *
                 * Uses delegated event handling because
                 * visits can be loaded dynamically.
                 * =========================================================
                 */
                document.addEventListener(
                    'change',
                    function (event) {

                        const checkbox =
                            event.target.closest(
                                '.visit-sample-checkbox'
                            );


                        if (!checkbox) {
                            return;
                        }


                        const quantity =
                            document.getElementById(
                                'visit-sample-quantity-' +
                                checkbox.dataset.id
                            );


                        if (!quantity) {
                            return;
                        }


                        quantity.disabled =
                            !checkbox.checked;

                        quantity.required =
                            checkbox.checked;


                        if (checkbox.checked) {

                            quantity.value =
                                quantity.value || 1;

                        }
                        else {

                            quantity.required =
                                false;

                        }

                    }
                );



                /*
                 * =========================================================
                 * OPEN EXISTING VISIT
                 * =========================================================
                 */
                document.addEventListener(
                    'click',
                    async function (event) {

                        const button =
                            event.target.closest(
                                '.open-visit-modal'
                            );


                        if (!button) {
                            return;
                        }


                        /*
                         * Do not open modal when clicking
                         * quick Check In/Out buttons.
                         */
                        if (
                            event.target.closest(
                                '.visit-list-check-in'
                            )
                            ||
                            event.target.closest(
                                '.visit-list-check-out'
                            )
                        ) {
                            return;
                        }


                        try {

                            const response =
                                await fetch(
                                    button.dataset.url,
                                    {
                                        headers: {

                                            'Accept':
                                                'application/json',

                                            'X-Requested-With':
                                                'XMLHttpRequest'
                                        }
                                    }
                                );


                            const data =
                                await getJson(response);


                            const visit =
                                data.visit;

                            console.log(visit);


                            /*
                             * Start with clean modal.
                             */
                            resetVisitModal();


                            visitMode.value =
                                'edit';


                            visitId.value =
                                visit.id;


                            document.getElementById(
                                'visit-modal-title'
                            ).textContent =
                                'Visit Details';


                            document.getElementById(
                                'visit-modal-date'
                            ).textContent =
                                visit.scheduled_at
                                    ? '· ' + visit.scheduled_at
                                    : '';


                            /*
                             * Purpose.
                             */
                            if (
                                visit.visit_purpose_id
                            ) {

                                visitPurpose.value =
                                    visit.visit_purpose_id;

                            }
                            else if (
                                visit.purpose_other
                            ) {

                                visitPurpose.value =
                                    'other';


                                visitOtherWrapper
                                    ?.classList
                                    .remove('hidden');


                                visitOtherInput.value =
                                    visit.purpose_other;


                                visitOtherInput.required =
                                    true;
                            }


                            /*
                             * Notes / Requests.
                             */
                            visitNotes.value =
                                visit.visit_notes || '';


                            visitRequests.value =
                                visit.client_requests || '';


                            if (visitContactPoint) {
                                visitContactPoint.value =
                                    visit.contact_point || '';
                            }

                            if (visitContactPosition) {
                                visitContactPosition.value =
                                    visit.contact_point_position || '';
                            }



                            /*
                             * Samples.
                             */
                            Object.entries(
                                visit.samples || {}
                            ).forEach(
                                function (
                                    [sampleId, sample]
                                ) {

                                    const checkbox =
                                        document.querySelector(
                                            `.visit-sample-checkbox[data-id="${sampleId}"]`
                                        );


                                    const quantity =
                                        document.getElementById(
                                            `visit-sample-quantity-${sampleId}`
                                        );


                                    if (checkbox) {

                                        checkbox.checked =
                                            true;

                                    }


                                    if (quantity) {

                                        quantity.disabled =
                                            false;

                                        quantity.required =
                                            true;

                                        quantity.value =
                                            sample.quantity || 1;

                                    }

                                }
                            );


                            /*
                             * Status.
                             */
                            const statusContainer =
                                document.getElementById(
                                    'visit-status-container'
                                );


                            statusContainer
                                ?.classList
                                .remove('hidden');


                            document.getElementById(
                                'visit-status'
                            ).textContent =
                                (visit.status || '')
                                    .replaceAll('_', ' ');


                            /*
                             * New-visit buttons are hidden
                             * for existing visits.
                             */
                            saveVisitButton
                                ?.classList
                                .add('hidden');


                            saveAndCheckInButton
                                ?.classList
                                .add('hidden');


                            /*
                             * URLs for actions.
                             */
                            if (modalCheckInButton) {

                                modalCheckInButton.dataset.url =
                                    button.dataset.checkinUrl;

                            }


                            if (modalCheckOutButton) {

                                modalCheckOutButton.dataset.url =
                                    button.dataset.checkoutUrl;

                            }


                            if (updateVisitButton) {

                                updateVisitButton.dataset.url =
                                    button.dataset.updateUrl;

                            }


                            /*
                             * Audit section.
                             */
                            if (
                                visit.check_in_at ||
                                visit.check_out_at
                            ) {

                                document
                                    .getElementById(
                                        'visit-audit'
                                    )
                                    ?.classList
                                    .remove('hidden');
                            }


                            /*
                             * Check In Audit.
                             */
                            if (visit.check_in_at) {

                                const element =
                                    document.getElementById(
                                        'visit-checkin-info'
                                    );


                                element
                                    ?.classList
                                    .remove('hidden');


                                if (element) {

                                    element.innerHTML = `
                            <i class="fa-solid fa-location-dot mr-1"></i>
                            Checked in at
                            <strong>${visit.check_in_at}</strong>
                        `;

                                }

                            }


                            /*
                             * Check Out Audit.
                             */
                            if (visit.check_out_at) {

                                const element =
                                    document.getElementById(
                                        'visit-checkout-info'
                                    );


                                element
                                    ?.classList
                                    .remove('hidden');


                                if (element) {

                                    element.innerHTML = `
                            <i class="fa-solid fa-circle-check mr-1"></i>
                            Checked out at
                            <strong>${visit.check_out_at}</strong>
                        `;

                                }

                            }


                            /*
                             * =====================================================
                             * BUTTON STATE
                             * =====================================================
                             */

                            /*
                             * Not checked in.
                             */
                            if (!visit.check_in_at) {

                                modalCheckInButton
                                    ?.classList
                                    .remove('hidden');


                                modalCheckOutButton
                                    ?.classList
                                    .add('hidden');


                                updateVisitButton
                                    ?.classList
                                    .remove('hidden');


                                setVisitFieldsDisabled(
                                    false
                                );

                            }

                            /*
                             * Checked in, not checked out.
                             */
                            else if (
                                !visit.check_out_at
                            ) {

                                modalCheckInButton
                                    ?.classList
                                    .add('hidden');


                                modalCheckOutButton
                                    ?.classList
                                    .remove('hidden');


                                updateVisitButton
                                    ?.classList
                                    .remove('hidden');


                                /*
                                 * Still allow notes,
                                 * requests and samples to
                                 * be updated during visit.
                                 */
                                setVisitFieldsDisabled(
                                    false
                                );

                            }

                            /*
                             * Completed.
                             */
                            else {

                                modalCheckInButton
                                    ?.classList
                                    .add('hidden');


                                modalCheckOutButton
                                    ?.classList
                                    .add('hidden');


                                updateVisitButton
                                    ?.classList
                                    .add('hidden');


                                /*
                                 * Completed visits become
                                 * read-only for audit.
                                 */
                                setVisitFieldsDisabled(
                                    true
                                );

                            }


                            showVisitModal();

                        }
                        catch (error) {

                            console.error(error);

                            alert(error.message);

                        }

                    }
                );



                /*
                 * =========================================================
                 * VALIDATE VISIT FORM
                 * =========================================================
                 * All HTML required fields must be completed before
                 * Save, Save & Check In, Save Changes, or Check Out.
                 * Existing scheduled visits may still Check In first.
                 */
                function validateVisitForm() {

                    if (!visitForm) {
                        return false;
                    }

                    if (!visitForm.checkValidity()) {

                        visitForm.reportValidity();

                        return false;
                    }

                    return true;
                }


                /*
                 * =========================================================
                 * BUILD VISIT FORM DATA
                 * =========================================================
                 */
                function buildVisitFormData() {

                    return new FormData(
                        visitForm
                    );

                }



                /*
                 * =========================================================
                 * CREATE VISIT
                 * =========================================================
                 */
                async function createVisit() {

                    const response =
                        await fetch(
                            @json(
                                route(
                                    'customers.visits.log',
                                    $customer
                                )
                            ),
                            {
                                method: 'POST',

                                headers: {

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'X-CSRF-TOKEN':
                                    csrfToken

                                },

                                body:
                                    buildVisitFormData()
                            }
                        );


                    return await getJson(
                        response
                    );

                }



                /*
                 * =========================================================
                 * SAVE NEW VISIT ONLY
                 * =========================================================
                 */
                saveVisitButton?.addEventListener(
                    'click',
                    async function () {

                        if (!validateVisitForm()) {
                            return;
                        }

                        const button = this;

                        const originalHTML =
                            button.innerHTML;


                        button.disabled = true;

                        button.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                Saving...
            `;


                        try {

                            await createVisit();


                            window.location.reload();

                        }
                        catch (error) {

                            console.error(error);

                            alert(error.message);


                            button.disabled = false;

                            button.innerHTML =
                                originalHTML;
                        }

                    }
                );



                /*
                 * =========================================================
                 * SAVE NEW VISIT + CHECK IN
                 * =========================================================
                 */
                saveAndCheckInButton?.addEventListener(
                    'click',
                    async function () {

                        if (!validateVisitForm()) {
                            return;
                        }

                        const button = this;

                        const originalHTML =
                            button.innerHTML;


                        button.disabled = true;


                        try {

                            /*
                             * First get GPS.
                             *
                             * This prevents creating the visit
                             * if user refuses GPS permission.
                             */
                            button.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                    Getting Location...
                `;


                            const position =
                                await getCurrentPosition();


                            /*
                             * Create visit.
                             */
                            button.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                    Creating Visit...
                `;


                            const created =
                                await createVisit();


                            if (
                                !created.visit ||
                                !created.visit.id
                            ) {

                                throw new Error(
                                    'Visit was created but the visit ID was not returned.'
                                );
                            }


                            /*
                             * Build check-in URL.
                             */
                            const checkInUrlTemplate =
                                @json(
                                    route(
                                        'visits.checkIn',
                                        ['visit' => '__VISIT_ID__']
                                    )
                                );


                            const checkInUrl =
                                checkInUrlTemplate.replace(
                                    '__VISIT_ID__',
                                    created.visit.id
                                );


                            /*
                             * Send check-in GPS.
                             */
                            button.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                    Checking In...
                `;


                            const response =
                                await fetch(
                                    checkInUrl,
                                    {
                                        method: 'POST',

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

                                        body: JSON.stringify({

                                            latitude:
                                            position.coords.latitude,

                                            longitude:
                                            position.coords.longitude,

                                            accuracy:
                                            position.coords.accuracy

                                        })
                                    }
                                );


                            await getJson(response);


                            window.location.reload();

                        }
                        catch (error) {

                            console.error(error);

                            alert(error.message);


                            button.disabled = false;

                            button.innerHTML =
                                originalHTML;
                        }

                    }
                );



                /*
                 * =========================================================
                 * MODAL CHECK IN
                 * =========================================================
                 */
                modalCheckInButton?.addEventListener(
                    'click',
                    function () {

                        sendGpsAction(
                            this.dataset.url,
                            this
                        );

                    }
                );



                /*
                 * =========================================================
                 * MODAL CHECK OUT
                 * =========================================================
                 */
                modalCheckOutButton?.addEventListener(
                    'click',
                    async function () {

                        /*
                         * A visit cannot be completed until every
                         * mandatory visit field has been filled.
                         */
                        if (!validateVisitForm()) {
                            return;
                        }

                        const button = this;
                        const originalHTML = button.innerHTML;

                        try {

                            button.disabled = true;
                            button.innerHTML = `
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                                Saving Visit...
                            `;

                            /*
                             * Save the latest notes, requests, contact
                             * information, purpose and samples BEFORE
                             * performing the check-out.
                             */
                            const formData = buildVisitFormData();
                            formData.append('_method', 'PUT');

                            const response = await fetch(
                                updateVisitButton?.dataset.url,
                                {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: formData
                                }
                            );

                            await getJson(response);

                            button.disabled = false;
                            button.innerHTML = originalHTML;

                            /*
                             * Only after the visit information is saved
                             * do we obtain GPS and complete check-out.
                             */
                            await sendGpsAction(
                                button.dataset.url,
                                button
                            );

                        }
                        catch (error) {

                            console.error(error);
                            alert(error.message);

                            button.disabled = false;
                            button.innerHTML = originalHTML;
                        }

                    }
                );



                /*
                 * =========================================================
                 * UPDATE EXISTING VISIT
                 * =========================================================
                 */
                updateVisitButton?.addEventListener(
                    'click',
                    async function () {

                        if (!validateVisitForm()) {
                            return;
                        }

                        const button = this;

                        const originalHTML =
                            button.innerHTML;


                        button.disabled = true;

                        button.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                Saving...
            `;


                        try {

                            const formData =
                                buildVisitFormData();


                            /*
                             * Laravel method spoofing.
                             */
                            formData.append(
                                '_method',
                                'PUT'
                            );


                            const response =
                                await fetch(
                                    button.dataset.url,
                                    {
                                        method: 'POST',

                                        headers: {

                                            'Accept':
                                                'application/json',

                                            'X-Requested-With':
                                                'XMLHttpRequest',

                                            'X-CSRF-TOKEN':
                                            csrfToken

                                        },

                                        body:
                                        formData
                                    }
                                );


                            await getJson(
                                response
                            );


                            window.location.reload();

                        }
                        catch (error) {

                            console.error(error);

                            alert(error.message);


                            button.disabled = false;

                            button.innerHTML =
                                originalHTML;
                        }

                    }
                );



                /*
                 * =========================================================
                 * QUICK CHECK IN / CHECK OUT FROM VISIT LIST
                 *
                 * Delegated listener means it also works
                 * for visits added using "Load More".
                 * =========================================================
                 */
                document.addEventListener(
                    'click',
                    function (event) {

                        /*
                         * QUICK CHECK IN
                         */
                        const listCheckInButton =
                            event.target.closest(
                                '.visit-list-check-in'
                            );


                        if (listCheckInButton) {

                            event.preventDefault();

                            event.stopPropagation();


                            sendGpsAction(
                                listCheckInButton.dataset.url,
                                listCheckInButton
                            );


                            return;
                        }


                        /*
                         * QUICK CHECK OUT
                         */
                        const listCheckOutButton =
                            event.target.closest(
                                '.visit-list-check-out'
                            );


                        if (listCheckOutButton) {

                            event.preventDefault();

                            event.stopPropagation();

                            /*
                             * Check-out must go through the visit modal so
                             * mandatory visit information can be validated
                             * before the visit is completed.
                             */
                            const visitRow =
                                listCheckOutButton.closest(
                                    '.open-visit-modal'
                                );

                            if (visitRow) {
                                visitRow.click();
                            }

                        }

                    }
                );



                /*
                 * =========================================================
                 * CLICK OUTSIDE SCHEDULE MODAL
                 * =========================================================
                 */
                scheduleModal?.addEventListener(
                    'click',
                    function (event) {

                        if (
                            event.target ===
                            scheduleModal
                        ) {

                            closeScheduleModal();

                        }

                    }
                );



                /*
                 * =========================================================
                 * CLICK OUTSIDE VISIT MODAL
                 * =========================================================
                 */
                visitModal?.addEventListener(
                    'click',
                    function (event) {

                        if (
                            event.target ===
                            visitModal
                        ) {

                            hideVisitModal();

                        }

                    }
                );



                /*
                 * =========================================================
                 * ESC KEY
                 * =========================================================
                 */
                document.addEventListener(
                    'keydown',
                    function (event) {

                        if (
                            event.key !== 'Escape'
                        ) {
                            return;
                        }


                        if (
                            visitModal &&
                            !visitModal.classList.contains(
                                'hidden'
                            )
                        ) {

                            hideVisitModal();

                            return;
                        }


                        if (
                            scheduleModal &&
                            !scheduleModal.classList.contains(
                                'hidden'
                            )
                        ) {

                            closeScheduleModal();

                        }

                    }
                );

            });

        </script>

        <script>
            const customerNoteModal =
                document.getElementById(
                    'customer-note-modal'
                );

            const openCustomerNote =
                document.getElementById(
                    'open-customer-note-modal'
                );

            const closeCustomerNote =
                document.getElementById(
                    'close-customer-note-modal'
                );

            const cancelCustomerNote =
                document.getElementById(
                    'cancel-customer-note-modal'
                );


            function openCustomerNoteModal() {

                customerNoteModal
                    ?.classList
                    .remove('hidden');

                document.body
                    .classList
                    .add('overflow-hidden');
            }


            function closeCustomerNoteModal() {

                customerNoteModal
                    ?.classList
                    .add('hidden');

                document.body
                    .classList
                    .remove('overflow-hidden');
            }


            openCustomerNote
                ?.addEventListener(
                    'click',
                    openCustomerNoteModal
                );


            closeCustomerNote
                ?.addEventListener(
                    'click',
                    closeCustomerNoteModal
                );


            cancelCustomerNote
                ?.addEventListener(
                    'click',
                    closeCustomerNoteModal
                );


            customerNoteModal
                ?.addEventListener(
                    'click',
                    function (event) {

                        if (
                            event.target
                            === customerNoteModal
                        ) {

                            closeCustomerNoteModal();
                        }

                    }
                );
        </script>
        <script>
            document.addEventListener(
                'click',
                async function (event) {

                    const button =
                        event.target.closest(
                            '.visit-list-cancel'
                        );


                    if (!button) {
                        return;
                    }


                    event.preventDefault();
                    event.stopPropagation();


                    /*
                     * Confirm cancellation.
                     */
                    const confirmed =
                        confirm(
                            'Are you sure you want to cancel this visit?'
                        );


                    if (!confirmed) {
                        return;
                    }


                    const originalHTML =
                        button.innerHTML;


                    button.disabled = true;

                    button.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Cancelling...
        `;


                    try {

                        const response =
                            await fetch(
                                button.dataset.url,
                                {
                                    method: 'POST',

                                    headers: {

                                        'Accept':
                                            'application/json',

                                        'X-Requested-With':
                                            'XMLHttpRequest',

                                        'X-CSRF-TOKEN':
                                        document.querySelector(
                                            'meta[name="csrf-token"]'
                                        ).content
                                    }
                                }
                            );


                        const data =
                            await response.json();


                        if (!response.ok) {

                            throw new Error(
                                data.message
                                || 'Unable to cancel visit.'
                            );
                        }


                        /*
                         * Reload so status changes
                         * immediately to Cancelled.
                         */
                        window.location.reload();

                    }
                    catch (error) {

                        console.error(error);

                        alert(
                            error.message
                        );


                        button.disabled = false;

                        button.innerHTML =
                            originalHTML;
                    }

                }
            );
        </script>
        <script>
            const rescheduleModal =
                document.getElementById(
                    'reschedule-visit-modal'
                );

            const rescheduleForm =
                document.getElementById(
                    'reschedule-visit-form'
                );

            const rescheduleDate =
                document.getElementById(
                    'reschedule-scheduled-at'
                );

            const rescheduleButton =
                document.getElementById(
                    'save-reschedule-button'
                );

            const rescheduleError =
                document.getElementById(
                    'reschedule-error'
                );


            let rescheduleUrl = null;


            /*
             * =====================================================
             * OPEN RESCHEDULE MODAL
             * =====================================================
             */
            document.addEventListener(
                'click',
                function (event) {

                    const button =
                        event.target.closest(
                            '.visit-list-reschedule'
                        );


                    if (!button) {
                        return;
                    }


                    event.preventDefault();
                    event.stopPropagation();


                    rescheduleUrl =
                        button.dataset.url;


                    rescheduleDate.value =
                        button.dataset.date || '';


                    rescheduleError.classList.add(
                        'hidden'
                    );

                    rescheduleError.textContent =
                        '';


                    rescheduleModal.classList.remove(
                        'hidden'
                    );


                    document.body.classList.add(
                        'overflow-hidden'
                    );

                }
            );


            /*
             * =====================================================
             * CLOSE MODAL
             * =====================================================
             */
            function closeRescheduleModal() {

                rescheduleModal.classList.add(
                    'hidden'
                );


                document.body.classList.remove(
                    'overflow-hidden'
                );


                rescheduleUrl =
                    null;
            }


            document
                .getElementById(
                    'close-reschedule-modal'
                )
                ?.addEventListener(
                    'click',
                    closeRescheduleModal
                );


            document
                .getElementById(
                    'cancel-reschedule-modal'
                )
                ?.addEventListener(
                    'click',
                    closeRescheduleModal
                );


            /*
             * Click outside
             */
            rescheduleModal?.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target ===
                        rescheduleModal
                    ) {

                        closeRescheduleModal();

                    }

                }
            );


            /*
             * =====================================================
             * SUBMIT RESCHEDULE
             * =====================================================
             */
            rescheduleForm?.addEventListener(
                'submit',
                async function (event) {

                    event.preventDefault();


                    if (
                        !rescheduleForm.checkValidity()
                    ) {

                        rescheduleForm.reportValidity();

                        return;
                    }


                    if (!rescheduleUrl) {
                        return;
                    }


                    const originalHtml =
                        rescheduleButton.innerHTML;


                    rescheduleButton.disabled =
                        true;


                    rescheduleButton.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Saving...
        `;


                    rescheduleError.classList.add(
                        'hidden'
                    );


                    try {

                        const response =
                            await fetch(
                                rescheduleUrl,
                                {
                                    method:
                                        'POST',

                                    headers: {

                                        'Accept':
                                            'application/json',

                                        'X-Requested-With':
                                            'XMLHttpRequest',

                                        'X-CSRF-TOKEN':
                                        document.querySelector(
                                            'meta[name="csrf-token"]'
                                        ).content

                                    },

                                    body:
                                        new FormData(
                                            rescheduleForm
                                        )
                                }
                            );


                        const data =
                            await response.json();


                        if (!response.ok) {

                            let message =
                                data.message
                                ||
                                'Unable to reschedule visit.';


                            if (data.errors) {

                                message =
                                    Object.values(
                                        data.errors
                                    )
                                        .flat()
                                        .join('\n');

                            }


                            throw new Error(
                                message
                            );

                        }


                        /*
                         * Simplest option because date,
                         * status, calendar counts etc.
                         * may all need refreshing.
                         */
                        window.location.reload();

                    }
                    catch (error) {

                        console.error(
                            error
                        );


                        rescheduleError.textContent =
                            error.message;


                        rescheduleError.classList.remove(
                            'hidden'
                        );

                    }
                    finally {

                        rescheduleButton.disabled =
                            false;


                        rescheduleButton.innerHTML =
                            originalHtml;

                    }

                }
            );
        </script>

    @endpush
@endsection
