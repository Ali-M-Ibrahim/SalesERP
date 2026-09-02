@extends('layouts.app')

@section('content')

    <div class="p-4 md:p-6 pb-24 md:pb-6">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}
        <div class="flex flex-col
                sm:flex-row
                sm:items-center
                sm:justify-between
                gap-3
                mb-5">

            <div>
                <h1 class="text-2xl font-bold text-[#1C201D]">
                    Admin Dashboard
                </h1>

                <p class="text-sm text-[#62685F] mt-1">
                    Overview of your sales team's activity
                </p>
            </div>

            <div class="text-sm text-[#62685F]">
                <i class="fa-regular fa-calendar mr-1"></i>
                {{ now()->format('d M Y') }}
            </div>

        </div>


        {{-- =========================================================
             SALES REPRESENTATIVE FILTER
        ========================================================== --}}
        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                p-4
                mb-5">

            <form method="GET"
                  action="{{ route('admin.dashboard') }}"
                  class="flex
                     flex-col
                     sm:flex-row
                     sm:items-center
                     gap-3">

                <div class="flex items-center gap-2">

                    <div class="w-9 h-9
                            rounded-lg
                            bg-[#E3ECE7]
                            text-[#1E4B43]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid fa-user-tie"></i>

                    </div>

                    <label for="sales_rep_id"
                           class="text-sm font-medium text-[#62685F]">

                        Viewing:

                    </label>

                </div>


                <select name="sales_rep_id"
                        id="sales_rep_id"
                        onchange="this.form.submit()"
                        class="w-full
                           sm:w-72
                           rounded-lg
                           border
                           border-[#DAD4C3]
                           bg-white
                           px-3
                           py-2
                           text-sm
                           focus:border-[#1E4B43]
                           focus:ring-[#1E4B43]">

                    <option value="">
                        All Sales Representatives
                    </option>

                    @foreach($salesReps as $rep)

                        <option value="{{ $rep->id }}"
                            @selected($selectedRepId == $rep->id)>

                            {{ $rep->name }}

                        </option>

                    @endforeach

                </select>


                @if($selectedRepId)

                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex
                          items-center
                          gap-1
                          text-xs
                          text-[#62685F]
                          hover:text-[#1E4B43]">

                        <i class="fa-solid fa-xmark"></i>

                        Clear filter

                    </a>

                @endif

            </form>

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

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium text-[#62685F]">
                            Today's Visits
                        </p>

                        <p class="text-2xl font-bold mt-2 text-[#1C201D]">
                            {{ $todayVisitCount }}
                        </p>

                    </div>

                    <div class="w-9 h-9
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


            {{-- Customers --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium text-[#62685F]">
                            Customers
                        </p>

                        <p class="text-2xl font-bold mt-2 text-[#1C201D]">
                            {{ $customerCount }}
                        </p>

                        @if($leadCount > 0)

                            <p class="text-xs text-[#D6772F] mt-1">
                                {{ $leadCount }} leads
                            </p>

                        @endif

                    </div>

                    <div class="w-9 h-9
                            rounded-lg
                            bg-[#E3ECE7]
                            text-[#1E4B43]
                            flex
                            items-center
                            justify-center">

                        <i class="fa-solid fa-building"></i>

                    </div>

                </div>

            </div>


            {{-- Completed --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium text-[#62685F]">
                            Completed Today
                        </p>

                        <p class="text-2xl font-bold mt-2 text-[#1C201D]">
                            {{ $completedTodayCount }}
                        </p>

                        @if($checkedInTodayCount > 0)

                            <p class="text-xs text-blue-700 mt-1">

                                <i class="fa-solid fa-location-dot mr-1"></i>

                                {{ $checkedInTodayCount }} checked in

                            </p>

                        @endif

                    </div>

                    <div class="w-9 h-9
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

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium text-[#62685F]">
                            Samples Given
                        </p>

                        <p class="text-2xl font-bold mt-2 text-[#1C201D]">
                            {{ $samplesGivenToday }}
                        </p>

                        <p class="text-xs text-[#62685F] mt-1">
                            Today
                        </p>

                    </div>

                    <div class="w-9 h-9
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
             MAIN DASHBOARD GRID
        ========================================================== --}}
        <div class="grid
                grid-cols-1
                xl:grid-cols-3
                gap-5
                mb-6">


            {{-- =====================================================
                 TODAY'S VISITS
            ====================================================== --}}
            <div class="xl:col-span-2
                    bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    overflow-hidden">


                {{-- Header --}}
                <div class="px-4
                        md:px-5
                        py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-center
                        justify-between
                        gap-3">

                    <div>

                        <h2 class="font-semibold text-[#1C201D]
                               flex items-center gap-2">

                            <i class="fa-regular
                                  fa-clock
                                  text-[#1E4B43]">
                            </i>

                            Today's Visits

                        </h2>


                        <p class="text-xs text-[#62685F] mt-1">

                            {{ now()->format('d M Y') }}

                            @if($selectedRepId)

                                @php
                                    $selectedRep = $salesReps
                                        ->firstWhere('id', $selectedRepId);
                                @endphp

                                @if($selectedRep)
                                    · {{ $selectedRep->name }}
                                @endif

                            @else

                                · All Sales Representatives

                            @endif

                        </p>

                    </div>


                    <span class="rounded-full
                             bg-[#F1EFE7]
                             px-2.5
                             py-1
                             text-xs
                             font-medium
                             text-[#62685F]">

                    {{ $todayVisits->count() }}

                </span>

                </div>


                {{-- Visits --}}
                @forelse($todayVisits as $visit)

                    <div class="px-4
                            md:px-5
                            py-4
                            border-b
                            last:border-b-0
                            border-[#DAD4C3]
                            hover:bg-[#FAF9F5]
                            transition">


                        <div class="flex
                                flex-col
                                sm:flex-row
                                sm:items-center
                                justify-between
                                gap-4">


                            {{-- Visit Main Information --}}
                            <a href="{{ route(
                            'customers.show',
                            $visit->customer_id
                        ) }}"
                               class="flex
                                  items-start
                                  gap-3
                                  flex-1
                                  min-w-0">


                                {{-- Status Icon --}}
                                <div class="w-10 h-10
                                        rounded-lg
                                        shrink-0
                                        flex
                                        items-center
                                        justify-center

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

                                    @if($visit->status === 'completed')

                                        <i class="fa-solid fa-circle-check"></i>

                                    @elseif($visit->status === 'checked_in')

                                        <i class="fa-solid fa-location-dot"></i>

                                    @elseif(
                                        $visit->status === 'missed'
                                        || $visit->status === 'cancelled'
                                    )

                                        <i class="fa-solid fa-triangle-exclamation"></i>

                                    @else

                                        <i class="fa-regular fa-clock"></i>

                                    @endif

                                </div>


                                {{-- Visit Details --}}
                                <div class="min-w-0">

                                    <p class="font-semibold
                                          text-sm
                                          text-[#1C201D]
                                          truncate">

                                        {{ $visit->customer?->name ?? 'Unknown Customer' }}

                                    </p>


                                    <p class="text-xs
                                          text-[#62685F]
                                          mt-1">

                                        {{ $visit->visitPurpose?->name
                                            ?? $visit->purpose_other
                                            ?? 'Visit' }}

                                    </p>


                                    {{-- Sales Rep --}}
                                    @if($visit->salesRep)

                                        <p class="text-xs
                                              text-[#62685F]
                                              mt-1">

                                            <i class="fa-regular
                                                  fa-user
                                                  mr-1">
                                            </i>

                                            {{ $visit->salesRep->name }}

                                        </p>

                                    @endif


                                    {{-- Samples --}}
                                    @if(
                                        isset($visit->visitSamples)
                                        && $visit->visitSamples->isNotEmpty()
                                    )

                                        <p class="text-xs
                                              text-[#D6772F]
                                              mt-2">

                                            <i class="fa-solid
                                                  fa-box-open
                                                  mr-1">
                                            </i>

                                            {{ $visit->visitSamples->sum('quantity') }}

                                            sample(s) given

                                        </p>

                                    @endif

                                </div>

                            </a>


                            {{-- Status / Audit --}}
                            <div class="flex
                                    items-center
                                    justify-between
                                    sm:justify-end
                                    gap-3">


                                <div class="sm:text-right">

                                    {{-- Status --}}
                                    <span class="inline-flex
                                             rounded-full
                                             px-2.5
                                             py-1
                                             text-[10px]
                                             uppercase
                                             tracking-wide
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


                                    {{-- Check In / Out --}}
                                    @if($visit->check_in_at)

                                        <p class="text-[10px]
                                              text-[#62685F]
                                              mt-2">

                                            <i class="fa-solid
                                                  fa-right-to-bracket
                                                  mr-0.5">
                                            </i>

                                            {{ $visit->check_in_at->format('H:i') }}


                                            @if($visit->check_out_at)

                                                <span class="mx-1">
                                                ·
                                            </span>

                                                <i class="fa-solid
                                                      fa-right-from-bracket
                                                      mr-0.5">
                                                </i>

                                                {{ $visit->check_out_at->format('H:i') }}

                                            @endif

                                        </p>

                                    @endif

                                </div>


                                {{-- Open Customer --}}
                                <a href="{{ route(
                                        'admin.visits.show',
                                        $visit
                                    ) }}"
                                   class="w-9 h-9
                                      rounded-lg
                                      border
                                      border-[#DAD4C3]
                                      flex
                                      items-center
                                      justify-center
                                      text-[#62685F]
                                      hover:bg-[#F1EFE7]
                                      hover:text-[#1E4B43]
                                      transition">

                                    <i class="fa-solid
                                          fa-chevron-right
                                          text-xs">
                                    </i>

                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="py-14
                            px-5
                            text-center">

                        <div class="w-12 h-12
                                rounded-full
                                bg-[#F1EFE7]
                                mx-auto
                                flex
                                items-center
                                justify-center">

                            <i class="fa-regular
                                  fa-calendar
                                  text-[#62685F]">
                            </i>

                        </div>

                        <p class="font-medium mt-3 text-[#1C201D]">
                            No visits today
                        </p>

                        <p class="text-sm
                              text-[#62685F]
                              mt-1">

                            @if($selectedRepId)

                                This sales representative has no visits today.

                            @else

                                There are no visits scheduled or logged today.

                            @endif

                        </p>

                    </div>

                @endforelse

            </div>


            {{-- =====================================================
                 NEEDS ATTENTION
            ====================================================== --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    overflow-hidden">

                <div class="px-5
                        py-4
                        border-b
                        border-[#DAD4C3]">

                    <h2 class="font-semibold
                           text-[#1C201D]
                           flex
                           items-center
                           gap-2">

                        <i class="fa-solid
                              fa-triangle-exclamation
                              text-[#D6772F]">
                        </i>

                        Needs Attention

                    </h2>

                    <p class="text-xs text-[#62685F] mt-1">
                        Items that may require follow-up
                    </p>

                </div>


                <div class="p-4 space-y-3">


                    {{-- Never Visited --}}
                    <div class="border
                            border-[#DAD4C3]
                            rounded-lg
                            p-3">

                        <div class="flex
                                items-center
                                justify-between
                                gap-3">

                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9
                                        rounded-lg
                                        bg-[#FBEAD9]
                                        text-[#D6772F]
                                        flex
                                        items-center
                                        justify-center
                                        shrink-0">

                                    <i class="fa-solid fa-user-clock"></i>

                                </div>

                                <div>

                                    <p class="text-sm font-medium">
                                        Never Visited
                                    </p>

                                    <p class="text-xs text-[#62685F]">
                                        Customers without a visit
                                    </p>

                                </div>

                            </div>


                            <span class="font-bold text-lg">
                            {{ $customersNeverVisitedCount }}
                        </span>

                        </div>

                    </div>


                    {{-- Without Samples --}}
                    <div class="border
                            border-[#DAD4C3]
                            rounded-lg
                            p-3">

                        <div class="flex
                                items-center
                                justify-between
                                gap-3">

                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9
                                        rounded-lg
                                        bg-[#F1EFE7]
                                        text-[#62685F]
                                        flex
                                        items-center
                                        justify-center
                                        shrink-0">

                                    <i class="fa-solid fa-box-open"></i>

                                </div>

                                <div>

                                    <p class="text-sm font-medium">
                                        No Samples Given
                                    </p>

                                    <p class="text-xs text-[#62685F]">
                                        Customers without samples
                                    </p>

                                </div>

                            </div>


                            <span class="font-bold text-lg">
                            {{ $customersWithoutSamplesCount }}
                        </span>

                        </div>

                    </div>


                    {{-- Unassigned --}}
                    @if(!$selectedRepId)

                        <div class="border
                                border-[#DAD4C3]
                                rounded-lg
                                p-3">

                            <div class="flex
                                    items-center
                                    justify-between
                                    gap-3">

                                <div class="flex items-center gap-3">

                                    <div class="w-9 h-9
                                            rounded-lg
                                            bg-red-50
                                            text-red-600
                                            flex
                                            items-center
                                            justify-center
                                            shrink-0">

                                        <i class="fa-solid fa-user-slash"></i>

                                    </div>

                                    <div>

                                        <p class="text-sm font-medium">
                                            Unassigned Customers
                                        </p>

                                        <p class="text-xs text-[#62685F]">
                                            No sales representative assigned
                                        </p>

                                    </div>

                                </div>


                                <span class="font-bold text-lg">
                                {{ $unassignedCustomerCount }}
                            </span>

                            </div>

                        </div>

                    @endif


                    {{-- All good --}}
                    @if(
                        $customersNeverVisitedCount == 0
                        && $customersWithoutSamplesCount == 0
                        && (
                            $selectedRepId
                            || $unassignedCustomerCount == 0
                        )
                    )

                        <div class="py-8 text-center">

                            <div class="w-11 h-11
                                    rounded-full
                                    bg-[#E3ECE7]
                                    text-[#1E4B43]
                                    flex
                                    items-center
                                    justify-center
                                    mx-auto">

                                <i class="fa-solid fa-circle-check"></i>

                            </div>

                            <p class="text-sm
                                  font-medium
                                  mt-3">

                                Everything looks good

                            </p>

                            <p class="text-xs
                                  text-[#62685F]
                                  mt-1">

                                No items currently need attention.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =========================================================
             RECENT RESOURCES
        ========================================================== --}}
        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                overflow-hidden">

            <div class="px-5
                    py-4
                    border-b
                    border-[#DAD4C3]
                    flex
                    items-center
                    justify-between
                    gap-3">

                <div>

                    <h2 class="font-semibold
                           text-[#1C201D]
                           flex
                           items-center
                           gap-2">

                        <i class="fa-regular
                              fa-folder-open
                              text-[#1E4B43]">
                        </i>

                        Recent Resources

                    </h2>

                    <p class="text-xs
                          text-[#62685F]
                          mt-1">

                        Latest files added to the resource library

                    </p>

                </div>


                <a href="{{ route('resources.index') }}"
                   class="text-xs
                      font-medium
                      text-[#1E4B43]
                      hover:underline">

                    View all

                    <i class="fa-solid
                          fa-arrow-right
                          ml-1">
                    </i>

                </a>

            </div>


            @forelse($recentResources as $resource)

                <div class="px-5
                        py-3
                        border-b
                        last:border-b-0
                        border-[#DAD4C3]
                        flex
                        items-center
                        gap-3">

                    {{-- Icon --}}
                    <div class="w-10 h-10
                            rounded-lg
                            bg-[#F1EFE7]
                            text-[#62685F]
                            flex
                            items-center
                            justify-center
                            shrink-0">

                        @if(
                            str_contains(
                                strtolower($resource->file_type ?? ''),
                                'pdf'
                            )
                        )

                            <i class="fa-solid
                                  fa-file-pdf
                                  text-red-600">
                            </i>

                        @elseif(
                            str_contains(
                                strtolower($resource->file_type ?? ''),
                                'image'
                            )
                        )

                            <i class="fa-solid fa-image"></i>

                        @else

                            <i class="fa-solid fa-file"></i>

                        @endif

                    </div>


                    {{-- Information --}}
                    <div class="flex-1 min-w-0">

                        <p class="text-sm
                              font-medium
                              truncate">

                            {{ $resource->name }}

                        </p>


                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            @if($resource->resourceCategory)

                                {{ $resource->resourceCategory->name }}

                                ·

                            @endif


                            @if($resource->creator)

                                Added by
                                {{ $resource->creator->name }}

                                ·

                            @endif


                            {{ $resource->created_at->format('d M Y') }}

                        </p>

                    </div>


                    <a href="{{ route(
                    'resources.index'
                ) }}"
                       class="w-8 h-8
                          rounded-lg
                          border
                          border-[#DAD4C3]
                          flex
                          items-center
                          justify-center
                          text-[#62685F]
                          hover:bg-[#F1EFE7]
                          transition">

                        <i class="fa-solid
                              fa-chevron-right
                              text-xs">
                        </i>

                    </a>

                </div>

            @empty

                <div class="py-12 text-center">

                    <div class="w-12 h-12
                            rounded-full
                            bg-[#F1EFE7]
                            flex
                            items-center
                            justify-center
                            mx-auto">

                        <i class="fa-regular
                              fa-folder-open
                              text-[#62685F]">
                        </i>

                    </div>

                    <p class="font-medium mt-3">
                        No resources yet
                    </p>

                    <p class="text-sm
                          text-[#62685F]
                          mt-1">

                        Recently added resources will appear here.

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
