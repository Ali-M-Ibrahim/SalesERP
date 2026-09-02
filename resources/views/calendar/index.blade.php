@extends('layouts.app')

@section('title', 'Calendar')

@section('content')

    <style>

        /*
         * Force exactly seven columns.
         * This avoids Tailwind purge/compiler issues
         * with grid-cols-7.
         */
        .calendar-grid {
            display: grid;
            grid-template-columns:
            repeat(7, minmax(0, 1fr));
            width: 100%;
        }

        .calendar-cell {
            min-width: 0;
            min-height: 90px;
        }

        @media (min-width: 640px) {

            .calendar-cell {
                min-height: 145px;
            }

        }

    </style>


    <div class="p-4 md:p-6 pb-24 md:pb-6">


        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="flex
                items-start
                justify-between
                gap-4
                mb-5">

            <div>

                <h1 class="text-2xl
                       md:text-3xl
                       font-bold">

                    Calendar

                </h1>


                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    View and manage your scheduled customer visits.

                </p>

            </div>


            {{-- Today --}}
            <a href="{{ route('calendar.index') }}"
               class="inline-flex
                  items-center
                  justify-center
                  gap-2
                  px-3
                  py-2.5
                  rounded-lg
                  border
                  border-[#DAD4C3]
                  bg-white
                  text-sm
                  font-medium">

                <i class="fa-regular fa-calendar"></i>

                <span class="hidden sm:inline">

                Today

            </span>

            </a>

        </div>



        {{-- =========================================================
             SUMMARY CARDS
        ========================================================== --}}
        <div class="grid
                grid-cols-2
                lg:grid-cols-5
                gap-3
                mb-5">


            {{-- Total --}}
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

                            Month Visits

                        </p>


                        <p class="text-2xl
                              font-bold
                              mt-2">

                            {{ $totalVisits }}

                        </p>

                    </div>


                    <div class="w-9
                            h-9
                            rounded-lg
                            bg-[#F1EFE7]
                            flex
                            items-center
                            justify-center
                            text-[#62685F]">

                        <i class="fa-regular fa-calendar"></i>

                    </div>

                </div>

            </div>



            {{-- Scheduled --}}
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

                            Scheduled

                        </p>


                        <p class="text-2xl
                              font-bold
                              text-[#D6772F]
                              mt-2">

                            {{ $scheduledCount }}

                        </p>

                    </div>

                    <div class="w-9
                            h-9
                            rounded-lg
                            bg-[#FBEAD9]
                            flex
                            items-center
                            justify-center
                            text-[#D6772F]">

                        <i class="fa-regular fa-clock"></i>

                    </div>

                </div>

            </div>



            {{-- Checked In --}}
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

                            Checked In

                        </p>


                        <p class="text-2xl
                              font-bold
                              text-blue-700
                              mt-2">

                            {{ $checkedInCount }}

                        </p>

                    </div>


                    <div class="w-9
                            h-9
                            rounded-lg
                            bg-blue-50
                            flex
                            items-center
                            justify-center
                            text-blue-700">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                </div>

            </div>



            {{-- Completed --}}
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
                              text-[#1E4B43]
                              mt-2">

                            {{ $completedCount }}

                        </p>

                    </div>


                    <div class="w-9
                            h-9
                            rounded-lg
                            bg-[#E3ECE7]
                            flex
                            items-center
                            justify-center
                            text-[#1E4B43]">

                        <i class="fa-solid fa-check"></i>

                    </div>

                </div>

            </div>



            {{-- Missed --}}
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

                            Missed

                        </p>


                        <p class="text-2xl
                              font-bold
                              text-red-700
                              mt-2">

                            {{ $missedCount }}

                        </p>

                    </div>


                    <div class="w-9
                            h-9
                            rounded-lg
                            bg-red-50
                            flex
                            items-center
                            justify-center
                            text-red-700">

                        <i class="fa-solid
                              fa-triangle-exclamation">
                        </i>

                    </div>

                </div>

            </div>

            {{-- Cancelled --}}
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

                            Cancelled

                        </p>


                        <p class="text-2xl
                              font-bold
                              text-red-700
                              mt-2">

                            {{ $canceledInCount }}

                        </p>

                    </div>


                    <div class="w-9
                            h-9
                            rounded-lg
                            bg-red-50
                            flex
                            items-center
                            justify-center
                            text-red-700">

                        <i class="fa-solid
                              fa-triangle-exclamation">
                        </i>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
             CALENDAR
        ========================================================== --}}
        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                overflow-hidden">


            {{-- =====================================================
                 MONTH NAVIGATION
            ====================================================== --}}
            <div class="px-3
                    sm:px-5
                    py-4
                    border-b
                    border-[#DAD4C3]
                    flex
                    items-center
                    justify-between
                    gap-3">


                {{-- Previous --}}
                <a href="{{ route(
                'calendar.index',
                [
                    'month' => $previousMonth
                ]
            ) }}"
                   class="w-10
                      h-10
                      shrink-0
                      rounded-lg
                      border
                      border-[#DAD4C3]
                      flex
                      items-center
                      justify-center
                      hover:bg-[#F1EFE7]">

                    <i class="fa-solid
                          fa-chevron-left">
                    </i>

                </a>



                {{-- Month --}}
                <div class="text-center min-w-0">

                    <h2 class="font-semibold
                           text-base
                           sm:text-lg">

                        {{ $month->format('F Y') }}

                    </h2>


                    <p class="text-[10px]
                          sm:text-xs
                          text-[#62685F]
                          mt-0.5">

                        {{ $totalVisits }}

                        {{ \Illuminate\Support\Str::plural(
                            'visit',
                            $totalVisits
                        ) }}

                    </p>

                </div>



                {{-- Next --}}
                <a href="{{ route(
                'calendar.index',
                [
                    'month' => $nextMonth
                ]
            ) }}"
                   class="w-10
                      h-10
                      shrink-0
                      rounded-lg
                      border
                      border-[#DAD4C3]
                      flex
                      items-center
                      justify-center
                      hover:bg-[#F1EFE7]">

                    <i class="fa-solid
                          fa-chevron-right">
                    </i>

                </a>

            </div>



            {{-- =====================================================
                 WEEK DAYS
            ====================================================== --}}
            <div class="calendar-grid
                    bg-[#F1EFE7]
                    border-b
                    border-[#DAD4C3]">

                @foreach([
                    'Mon',
                    'Tue',
                    'Wed',
                    'Thu',
                    'Fri',
                    'Sat',
                    'Sun'
                ] as $dayName)

                    <div class="py-2.5
                            text-center
                            text-[9px]
                            sm:text-xs
                            font-semibold
                            text-[#62685F]">


                        {{-- Phone --}}
                        <span class="sm:hidden">

                        {{ substr(
                            $dayName,
                            0,
                            1
                        ) }}

                    </span>


                        {{-- Desktop --}}
                        <span class="hidden sm:inline">

                        {{ $dayName }}

                    </span>

                    </div>

                @endforeach

            </div>



            {{-- =====================================================
                 DAYS
            ====================================================== --}}
            @php

                $firstDayOffset =
                    $month
                        ->copy()
                        ->startOfMonth()
                        ->dayOfWeekIso
                        - 1;

            @endphp


            <div class="calendar-grid">


                {{-- Empty cells --}}
                @for(
                    $i = 0;
                    $i < $firstDayOffset;
                    $i++
                )

                    <div class="calendar-cell
                            bg-[#FAF9F5]
                            border-r
                            border-b
                            border-[#E5E0D3]">
                    </div>

                @endfor



                {{-- Actual Month Days --}}
                @foreach(
                    $days as $day
                )

                    @php

                        $date =
                            $day['date'];

                        $dayVisits =
                            $day['visits'];

                        $isToday =
                            $date->isToday();

                    @endphp


                    <div class="calendar-cell
                            relative
                            p-1.5
                            sm:p-2
                            border-r
                            border-b
                            border-[#E5E0D3]

                            {{ $isToday
                                ? 'bg-[#F7FBF9]'
                                : 'bg-white' }}">


                        {{-- =========================================
                             DAY HEADER
                        ========================================== --}}
                        <div class="flex
                                items-start
                                justify-between
                                gap-1
                                mb-1.5">


                            {{-- Number --}}
                            <span class="w-7
                                     h-7
                                     rounded-full
                                     inline-flex
                                     items-center
                                     justify-center
                                     text-xs
                                     shrink-0

                            {{ $isToday
                                ? 'bg-[#1E4B43] text-white font-semibold'
                                : 'text-[#1C201D]' }}">

                            {{ $date->day }}

                        </span>



                            {{-- Count --}}
                            @if(
                                $dayVisits->isNotEmpty()
                            )

                                <span class="min-w-[20px]
                                         h-5
                                         px-1
                                         rounded-full
                                         bg-[#F1EFE7]
                                         text-[#62685F]
                                         flex
                                         items-center
                                         justify-center
                                         text-[9px]
                                         font-bold">

                                {{ $dayVisits->count() }}

                            </span>

                            @endif

                        </div>



                        {{-- =========================================
                             DESKTOP VISITS
                        ========================================== --}}
                        <div class="hidden
                                sm:block
                                space-y-1">


                            @foreach(
                                $dayVisits->take(3)
                                as $visit
                            )

                                @php

                                    $isMissed =
                                        $visit->status === 'scheduled'
                                        &&
                                        $visit->scheduled_at->lt(
                                            today()
                                        )
                                        &&
                                        !$visit->check_in_at;

                                @endphp


                                <button type="button"
                                        data-date="{{ $date->format(
                                        'Y-m-d'
                                    ) }}"
                                        class="open-day-visits
                                           w-full
                                           text-left
                                           rounded-md
                                           border
                                           px-2
                                           py-1.5
                                           text-[10px]
                                           leading-tight
                                           transition

                                @if($isMissed)

                                    bg-red-50
                                    border-red-100
                                    text-red-700

                                @elseif(
                                    $visit->status
                                    === 'completed'
                                )

                                    bg-[#E3ECE7]
                                    border-[#CCE0D5]
                                    text-[#1E4B43]

                                @elseif(
                                    $visit->status
                                    === 'checked_in'
                                )

                                    bg-blue-50
                                    border-blue-100
                                    text-blue-700

                                @else

                                    bg-[#FBEAD9]
                                    border-[#F2D4B9]
                                    text-[#9A551E]

                                @endif
                            ">


                                    <p class="font-semibold
                                          truncate">

                                        {{ $visit
                                            ->customer
                                            ?->name
                                            ?? 'Customer' }}

                                    </p>


                                    <p class="truncate
                                          opacity-75
                                          mt-0.5">

                                        {{ $visit
                                            ->visitPurpose
                                            ?->name
                                            ??
                                            $visit->purpose_other
                                            ??
                                            'Visit' }}

                                    </p>

                                </button>

                            @endforeach



                            {{-- More --}}
                            @if(
                                $dayVisits->count()
                                > 3
                            )

                                <button type="button"
                                        class="open-day-visits
                                           text-[9px]
                                           font-semibold
                                           text-[#1E4B43]
                                           hover:underline"
                                        data-date="{{ $date->format(
                                        'Y-m-d'
                                    ) }}">

                                    +{{ $dayVisits->count() - 3 }}
                                    more

                                </button>

                            @endif

                        </div>



                        {{-- =========================================
                             MOBILE DOTS
                        ========================================== --}}
                        <div class="sm:hidden">

                            @if(
                                $dayVisits->isNotEmpty()
                            )

                                <button type="button"
                                        class="open-day-visits
                                           w-full
                                           mt-1
                                           text-left"
                                        data-date="{{ $date->format(
                                        'Y-m-d'
                                    ) }}">


                                    <div class="flex
                                            flex-wrap
                                            gap-1">

                                        @foreach(
                                            $dayVisits->take(5)
                                            as $visit
                                        )

                                            @php

                                                $isMissed =
                                                    $visit->status
                                                    === 'scheduled'
                                                    &&
                                                    $visit
                                                        ->scheduled_at
                                                        ->lt(today())
                                                    &&
                                                    !$visit
                                                        ->check_in_at;

                                            @endphp


                                            <span class="w-2
                                                     h-2
                                                     rounded-full

                                            @if($isMissed)

                                                bg-red-500

                                            @elseif(
                                                $visit->status
                                                === 'completed'
                                            )

                                                bg-[#1E4B43]

                                            @elseif(
                                                $visit->status
                                                === 'checked_in'
                                            )

                                                bg-blue-500

                                            @else

                                                bg-[#D6772F]

                                            @endif
                                        ">
                                        </span>

                                        @endforeach

                                    </div>

                                </button>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>



        {{-- =========================================================
             LEGEND
        ========================================================== --}}
        <div class="flex
                flex-wrap
                items-center
                gap-x-4
                gap-y-2
                mt-4
                text-xs
                text-[#62685F]">


        <span class="inline-flex
                     items-center
                     gap-1.5">

            <span class="w-2.5
                         h-2.5
                         rounded-full
                         bg-[#D6772F]">
            </span>

            Scheduled

        </span>


            <span class="inline-flex
                     items-center
                     gap-1.5">

            <span class="w-2.5
                         h-2.5
                         rounded-full
                         bg-blue-500">
            </span>

            Checked In

        </span>


            <span class="inline-flex
                     items-center
                     gap-1.5">

            <span class="w-2.5
                         h-2.5
                         rounded-full
                         bg-[#1E4B43]">
            </span>

            Completed

        </span>


            <span class="inline-flex
                     items-center
                     gap-1.5">

            <span class="w-2.5
                         h-2.5
                         rounded-full
                         bg-red-500">
            </span>

            Missed

        </span>

        </div>

    </div>



    {{-- =============================================================
         DAY VISITS MODAL
    ============================================================= --}}
    <div id="day-visits-modal"
         class="hidden
            fixed
            inset-0
            z-[120]
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
                    shadow-xl
                    overflow-hidden">


                {{-- Header --}}
                <div class="px-5
                        py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-center
                        justify-between
                        gap-3">

                    <div>

                        <h3 id="day-visits-title"
                            class="font-semibold">

                            Visits

                        </h3>


                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            Customer visits scheduled for this day.

                        </p>

                    </div>


                    <button type="button"
                            id="close-day-visits-modal"
                            class="w-9
                               h-9
                               rounded-full
                               flex
                               items-center
                               justify-center
                               hover:bg-[#F1EFE7]">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>



                {{-- Content --}}
                <div id="day-visits-content"
                     class="divide-y
                        divide-[#DAD4C3]">
                </div>

            </div>

        </div>

    </div>



    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    /*
                     * =====================================================
                     * CALENDAR DATA
                     * =====================================================
                     */
                    const calendarVisits =
                        @json($calendarVisits);


                    const modal =
                        document.getElementById(
                            'day-visits-modal'
                        );


                    const title =
                        document.getElementById(
                            'day-visits-title'
                        );


                    const content =
                        document.getElementById(
                            'day-visits-content'
                        );



                    /*
                     * =====================================================
                     * OPEN DAY
                     * =====================================================
                     */
                    function openDay(
                        date
                    ) {

                        const visits =
                            calendarVisits[date]
                            || [];


                        title.textContent =
                            formatDate(date);


                        content.innerHTML =
                            '';


                        /*
                         * No Visits
                         */
                        if (
                            visits.length === 0
                        ) {

                            content.innerHTML = `
                    <div class="
                        py-12
                        px-5
                        text-center
                    ">

                        <i class="
                            fa-regular
                            fa-calendar
                            text-[#62685F]
                        ">
                        </i>

                        <p class="
                            text-sm
                            text-[#62685F]
                            mt-2
                        ">

                            No visits scheduled.

                        </p>

                    </div>
                `;

                        } else {

                            visits.forEach(
                                function (visit) {

                                    const row =
                                        document.createElement(
                                            'a'
                                        );


                                    row.href =
                                        visit.url;


                                    row.className = `
                            block
                            px-5
                            py-4
                            hover:bg-[#FAF9F5]
                        `;


                                    /*
                                     * Status Style
                                     */
                                    let statusClass =
                                        'bg-[#FBEAD9] text-[#D6772F]';


                                    if (
                                        visit.status
                                        === 'completed'
                                    ) {

                                        statusClass =
                                            'bg-[#E3ECE7] text-[#1E4B43]';

                                    }
                                    else if (
                                        visit.status
                                        === 'checked_in'
                                    ) {

                                        statusClass =
                                            'bg-blue-100 text-blue-700';

                                    }
                                    else if (
                                        visit.status
                                        === 'missed'
                                    ) {

                                        statusClass =
                                            'bg-red-50 text-red-700';

                                    }


                                    row.innerHTML = `

                            <div class="
                                flex
                                items-start
                                justify-between
                                gap-4
                            ">

                                <div class="
                                    min-w-0
                                    flex-1
                                ">

                                    <p class="
                                        font-semibold
                                        text-sm
                                    ">

                                        ${escapeHtml(
                                        visit.customer
                                    )}

                                    </p>


                                    <p class="
                                        text-xs
                                        text-[#62685F]
                                        mt-1
                                    ">

                                        ${escapeHtml(
                                        visit.purpose
                                    )}

                                    </p>


                                    ${
                                        visit.check_in_at

                                            ? `
                                            <div class="
                                                flex
                                                flex-wrap
                                                gap-x-3
                                                mt-2
                                                text-[10px]
                                                text-[#62685F]
                                            ">

                                                <span>

                                                    <i class="
                                                        fa-solid
                                                        fa-location-dot
                                                        mr-1
                                                    "></i>

                                                    In:
                                                    ${escapeHtml(
                                                visit.check_in_at
                                            )}

                                                </span>


                                                ${
                                                visit.check_out_at

                                                    ? `
                                                        <span>

                                                            <i class="
                                                                fa-solid
                                                                fa-location-arrow
                                                                mr-1
                                                            "></i>

                                                            Out:
                                                            ${escapeHtml(
                                                        visit.check_out_at
                                                    )}

                                                        </span>
                                                    `

                                                    : ''
                                            }

                                            </div>
                                        `

                                            : ''
                                    }

                                </div>


                                <div class="
                                    flex
                                    items-center
                                    gap-2
                                    shrink-0
                                ">

                                    <span class="
                                        inline-flex
                                        rounded-full
                                        px-2
                                        py-1
                                        text-[9px]
                                        uppercase
                                        font-semibold
                                        ${statusClass}
                                    ">

                                        ${escapeHtml(
                                        (
                                            visit.status
                                            || ''
                                        ).replaceAll(
                                            '_',
                                            ' '
                                        )
                                    )}

                                    </span>


                                    <i class="
                                        fa-solid
                                        fa-chevron-right
                                        text-[10px]
                                        text-[#62685F]
                                    ">
                                    </i>

                                </div>

                            </div>
                        `;


                                    content.appendChild(
                                        row
                                    );

                                }
                            );
                        }


                        modal.classList.remove(
                            'hidden'
                        );


                        document.body.classList.add(
                            'overflow-hidden'
                        );

                    }



                    /*
                     * =====================================================
                     * CLICK CALENDAR VISIT
                     * =====================================================
                     */
                    document.addEventListener(
                        'click',
                        function (event) {

                            const button =
                                event.target.closest(
                                    '.open-day-visits'
                                );


                            if (!button) {

                                return;
                            }


                            openDay(
                                button.dataset.date
                            );

                        }
                    );



                    /*
                     * =====================================================
                     * CLOSE MODAL
                     * =====================================================
                     */
                    function closeModal() {

                        modal.classList.add(
                            'hidden'
                        );


                        document.body.classList.remove(
                            'overflow-hidden'
                        );

                    }


                    document
                        .getElementById(
                            'close-day-visits-modal'
                        )
                        ?.addEventListener(
                            'click',
                            closeModal
                        );


                    modal?.addEventListener(
                        'click',
                        function (event) {

                            if (
                                event.target
                                === modal
                            ) {

                                closeModal();

                            }

                        }
                    );



                    /*
                     * =====================================================
                     * ESC
                     * =====================================================
                     */
                    document.addEventListener(
                        'keydown',
                        function (event) {

                            if (
                                event.key
                                === 'Escape'
                                &&
                                !modal.classList.contains(
                                    'hidden'
                                )
                            ) {

                                closeModal();

                            }

                        }
                    );



                    /*
                     * =====================================================
                     * HELPERS
                     * =====================================================
                     */
                    function escapeHtml(
                        value
                    ) {

                        const div =
                            document.createElement(
                                'div'
                            );


                        div.textContent =
                            value ?? '';


                        return div.innerHTML;

                    }


                    function formatDate(
                        date
                    ) {

                        const value =
                            new Date(
                                date + 'T00:00:00'
                            );


                        return value
                            .toLocaleDateString(
                                undefined,
                                {
                                    weekday:
                                        'long',

                                    day:
                                        '2-digit',

                                    month:
                                        'short',

                                    year:
                                        'numeric',
                                }
                            );

                    }

                }
            );

        </script>

    @endpush

@endsection
