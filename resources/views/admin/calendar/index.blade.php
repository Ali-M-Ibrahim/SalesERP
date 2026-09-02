@extends('layouts.app')

@section('title', 'Calendar')

@section('content')

    <div class="p-4 md:p-6 pb-24 md:pb-6">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="flex
                flex-col
                lg:flex-row
                lg:items-center
                lg:justify-between
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

                    Review visits across all sales representatives.

                </p>

            </div>


            {{-- Sales Rep Filter --}}
            <form method="GET"
                  action="{{ route('admin.calendar.index') }}"
                  class="flex
                     flex-col
                     sm:flex-row
                     sm:items-center
                     gap-2">

                <input type="hidden"
                       name="month"
                       value="{{ $month->format('Y-m') }}">


                <label class="text-xs
                          font-medium
                          text-[#62685F]">

                    Viewing:

                </label>


                <select id="sales_rep_id" name="sales_rep_id"
                        onchange="this.form.submit()"
                        class="w-full
                           sm:w-64
                           rounded-lg
                           border-[#DAD4C3]
                           text-sm">

                    <option value="">
                        All Sales Representatives
                    </option>


                    @foreach($salesReps as $rep)

                        <option value="{{ $rep->id }}"
                            @selected(
                                $salesRepId == $rep->id
                            )>

                            {{ $rep->name }}

                        </option>

                    @endforeach

                </select>


                @if($salesRepId)

                    <a href="{{ route(
                    'admin.calendar.index',
                    [
                        'month' =>
                            $month->format('Y-m')
                    ]
                ) }}"
                       class="inline-flex
                          items-center
                          gap-1
                          text-xs
                          text-[#62685F]
                          hover:text-[#1E4B43]">

                        <i class="fa-solid fa-xmark"></i>

                        Clear

                    </a>

                @endif

            </form>

        </div>


        {{-- =========================================================
             SUMMARY
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

                <p class="text-xs text-[#62685F]">
                    Month Visits
                </p>

                <p class="text-2xl font-bold mt-2">
                    {{ $totalVisits }}
                </p>

            </div>


            {{-- Scheduled --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Scheduled
                </p>

                <p class="text-2xl
                      font-bold
                      text-[#D6772F]
                      mt-2">

                    {{ $scheduledCount }}

                </p>

            </div>


            {{-- Checked In --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Checked In
                </p>

                <p class="text-2xl
                      font-bold
                      text-blue-700
                      mt-2">

                    {{ $checkedInCount }}

                </p>

            </div>


            {{-- Completed --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Completed
                </p>

                <p class="text-2xl
                      font-bold
                      text-[#1E4B43]
                      mt-2">

                    {{ $completedCount }}

                </p>

            </div>


            {{-- Missed --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Missed
                </p>

                <p class="text-2xl
                      font-bold
                      text-red-700
                      mt-2">

                    {{ $missedCount }}

                </p>

            </div>

            {{-- Cancelled --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Cancelled
                </p>

                <p class="text-2xl
                      font-bold
                      text-red-700
                      mt-2">

                    {{ $cancelledCount }}

                </p>

            </div>


        </div>


        {{-- =========================================================
             CALENDAR CARD
        ========================================================== --}}
        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                overflow-hidden">

            {{-- Month Navigation --}}
            <div class="px-4
                    md:px-5
                    py-4
                    border-b
                    border-[#DAD4C3]
                    flex
                    items-center
                    justify-between
                    gap-3">

                {{-- Previous --}}
                <a href="{{ route(
                'admin.calendar.index',
                array_filter([
                    'month' =>
                        $previousMonth,

                    'sales_rep_id' =>
                        $salesRepId,
                ])
            ) }}"
                   class="w-9 h-9
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


                {{-- Current Month --}}
                <div class="text-center">

                    <h2 class="font-semibold
                           text-base
                           md:text-lg">

                        {{ $month->format('F Y') }}

                    </h2>


                    <p class="text-[10px]
                          text-[#62685F]
                          mt-0.5">

                        @if($salesRepId)

                            {{ $salesReps
                                ->firstWhere(
                                    'id',
                                    $salesRepId
                                )
                                ?->name
                                ?? 'Sales Representative' }}

                        @else

                            All Sales Representatives

                        @endif

                    </p>

                </div>


                {{-- Next --}}
                <a href="{{ route(
                'admin.calendar.index',
                array_filter([
                    'month' =>
                        $nextMonth,

                    'sales_rep_id' =>
                        $salesRepId,
                ])
            ) }}"
                   class="w-9 h-9
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
                 WEEK DAY HEADERS
            ====================================================== --}}
            <div class="grid
                    grid-cols-7
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

                    <div class="px-1
                            py-2.5
                            text-center
                            text-[10px]
                            sm:text-xs
                            font-semibold
                            text-[#62685F]">

                        {{ $dayName }}

                    </div>

                @endforeach

            </div>


            {{-- =====================================================
                 CALENDAR GRID
            ====================================================== --}}
            @php

                /*
                 * Monday = 1
                 * Sunday = 7
                 *
                 * Number of blank cells before
                 * the first day of the month.
                 */
                $firstDayOffset =
                    $month
                        ->copy()
                        ->startOfMonth()
                        ->dayOfWeekIso
                        - 1;

            @endphp


            <div class="grid grid-cols-7">


                {{-- Blank cells before first day --}}
                @for(
                    $i = 0;
                    $i < $firstDayOffset;
                    $i++
                )

                    <div class="min-h-[86px]
                            sm:min-h-[130px]
                            border-r
                            border-b
                            border-[#DAD4C3]
                            bg-[#FAF9F5]">
                    </div>

                @endfor


                {{-- =================================================
                     MONTH DAYS
                ================================================== --}}
                @foreach($days as $day)

                    @php

                        $date =
                            $day['date'];

                        $dayVisits =
                            $day['visits'];

                        $isToday =
                            $date->isToday();

                    @endphp


                    <div class="min-h-[86px]
                            sm:min-h-[130px]
                            border-r
                            border-b
                            border-[#DAD4C3]
                            p-1.5
                            sm:p-2

                            {{ $isToday
                                ? 'bg-[#F7FBF9]'
                                : 'bg-white' }}">


                        {{-- Day header --}}
                        <div class="flex
                                items-center
                                justify-between
                                mb-1.5">

                        <span class="w-6 h-6
                                     sm:w-7
                                     sm:h-7
                                     rounded-full
                                     flex
                                     items-center
                                     justify-center
                                     text-[10px]
                                     sm:text-xs
                                     font-semibold

                            {{ $isToday
                                ? 'bg-[#1E4B43] text-white'
                                : 'text-[#62685F]' }}">

                            {{ $date->day }}

                        </span>


                            @if($dayVisits->isNotEmpty())

                                <span class="text-[9px]
                                         text-[#62685F]">

                                {{ $dayVisits->count() }}

                            </span>

                            @endif

                        </div>


                        {{-- =========================================
                             DESKTOP / TABLET VISITS
                        ========================================== --}}
                        <div class="hidden
                                sm:block
                                space-y-1">

                            @foreach(
                                $dayVisits->take(3)
                                as $visit
                            )

                                @php

                                    /*
                                     * Missed is calculated dynamically.
                                     *
                                     * Past date
                                     * + still scheduled
                                     * + no check-in.
                                     */
                                    $isMissed =
                                        $visit->status === 'scheduled'
                                        &&
                                        $visit
                                            ->scheduled_at
                                            ->lt(today())
                                        &&
                                        !$visit->check_in_at;

                                @endphp


                                <button type="button"
                                        class="open-admin-calendar-visit
                                           w-full
                                           text-left
                                           rounded-md
                                           px-2
                                           py-1.5
                                           text-[10px]
                                           leading-tight
                                           border

                                @if($isMissed)

                                    bg-red-50
                                    border-red-100
                                    text-red-700

                                @elseif(
                                    $visit->status === 'completed'
                                )

                                    bg-[#E3ECE7]
                                    border-[#CCE0D5]
                                    text-[#1E4B43]

                                @elseif(
                                    $visit->status === 'checked_in'
                                )

                                    bg-blue-50
                                    border-blue-100
                                    text-blue-700

                                @else

                                    bg-[#FBEAD9]
                                    border-[#F2D4B9]
                                    text-[#9A551E]

                                @endif
                            "
                                        data-url="{{ route(
                                        'admin.visits.show',
                                        $visit
                                    ) }}">


                                    <p class="font-semibold
                                          truncate">

                                        {{ $visit
                                            ->customer
                                            ?->name
                                            ?? 'Customer' }}

                                    </p>


                                    {{-- When showing all reps,
                                         display rep below customer --}}
                                    @if(!$salesRepId)

                                        <p class="truncate
                                              opacity-75
                                              mt-0.5">

                                            {{ $visit
                                                ->salesRep
                                                ?->name
                                                ?? '—' }}

                                        </p>

                                    @endif

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

            <span class="w-2.5 h-2.5
                         rounded-full
                         bg-[#D6772F]">
            </span>

            Scheduled

        </span>


            <span class="inline-flex
                     items-center
                     gap-1.5">

            <span class="w-2.5 h-2.5
                         rounded-full
                         bg-blue-500">
            </span>

            Checked In

        </span>


            <span class="inline-flex
                     items-center
                     gap-1.5">

            <span class="w-2.5 h-2.5
                         rounded-full
                         bg-[#1E4B43]">
            </span>

            Completed

        </span>


            <span class="inline-flex
                     items-center
                     gap-1.5">

            <span class="w-2.5 h-2.5
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

                            Visits scheduled for this day.

                        </p>

                    </div>


                    <button type="button"
                            id="close-day-visits-modal"
                            class="w-8
                               h-8
                               rounded-lg
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



    {{-- =============================================================
         VISIT DETAILS MODAL
    ============================================================= --}}
    <div id="admin-calendar-visit-modal"
         class="hidden
            fixed
            inset-0
            z-[130]
            bg-black/50
            p-4
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


                {{-- Header --}}
                <div class="px-5
                        py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-center
                        justify-between">

                    <div>

                        <h3 class="font-semibold">

                            Visit Details

                        </h3>

                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            Read-only audit view

                        </p>

                    </div>


                    <button type="button"
                            id="close-admin-calendar-visit-modal"
                            class="w-8
                               h-8
                               rounded-lg
                               hover:bg-[#F1EFE7]">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                {{-- Loading --}}
                <div id="admin-calendar-visit-loading"
                     class="hidden
                        py-14
                        text-center">

                    <i class="fa-solid
                          fa-spinner
                          fa-spin
                          text-[#1E4B43]">
                    </i>

                    <p class="text-sm
                          text-[#62685F]
                          mt-2">

                        Loading visit...

                    </p>

                </div>


                {{-- AJAX content --}}
                <div id="admin-calendar-visit-content">
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
                     *
                     * Already prepared safely in controller.
                     */
                    const calendarVisits =
                        @json($calendarVisits);


                    /*
                     * =====================================================
                     * DAY VISITS MODAL
                     * =====================================================
                     */
                    const dayModal =
                        document.getElementById(
                            'day-visits-modal'
                        );

                    const dayTitle =
                        document.getElementById(
                            'day-visits-title'
                        );

                    const dayContent =
                        document.getElementById(
                            'day-visits-content'
                        );


                    function openDayModal(
                        date
                    ) {

                        const visits =
                            calendarVisits[date]
                            || [];


                        dayTitle.textContent =
                            formatDate(date);


                        dayContent.innerHTML =
                            '';


                        if (
                            visits.length === 0
                        ) {

                            dayContent.innerHTML = `
                    <div class="
                        py-10
                        text-center
                        text-sm
                        text-[#62685F]
                    ">
                        No visits on this day.
                    </div>
                `;

                        } else {

                            visits.forEach(
                                function (visit) {

                                    const row =
                                        document.createElement(
                                            'button'
                                        );


                                    row.type =
                                        'button';


                                    row.className =
                                        `
                            open-admin-calendar-visit
                            w-full
                            text-left
                            px-5
                            py-4
                            hover:bg-[#FAF9F5]
                            `;


                                    row.dataset.url =
                                        visit.url;


                                    let statusClass =
                                        'bg-[#FBEAD9] text-[#D6772F]';


                                    if (
                                        visit.status
                                        === 'completed'
                                    ) {

                                        statusClass =
                                            'bg-[#E3ECE7] text-[#1E4B43]';

                                    } else if (
                                        visit.status
                                        === 'checked_in'
                                    ) {

                                        statusClass =
                                            'bg-blue-100 text-blue-700';

                                    } else if (
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
                                        || 'Customer'
                                    )}
                                    </p>


                                    <p class="
                                        text-xs
                                        text-[#62685F]
                                        mt-1
                                    ">
                                        ${escapeHtml(
                                        visit.purpose
                                        || 'Visit'
                                    )}
                                    </p>


                                    <p class="
                                        text-xs
                                        text-[#62685F]
                                        mt-1
                                    ">

                                        <i class="
                                            fa-regular
                                            fa-user
                                            mr-1
                                        "></i>

                                        ${escapeHtml(
                                        visit.sales_rep
                                        || '—'
                                    )}

                                    </p>


                                    ${
                                        visit.check_in_at

                                            ? `
                                                <p class="
                                                    text-[10px]
                                                    text-[#62685F]
                                                    mt-2
                                                ">

                                                    In:
                                                    ${escapeHtml(
                                                visit.check_in_at
                                            )}

                                                    ${
                                                visit.check_out_at

                                                    ? `
                                                                · Out:
                                                                ${escapeHtml(
                                                        visit.check_out_at
                                                    )}
                                                            `

                                                    : ''
                                            }

                                                </p>
                                            `

                                            : ''
                                    }

                                </div>


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

                            </div>
                        `;


                                    dayContent.appendChild(
                                        row
                                    );
                                }
                            );
                        }


                        dayModal
                            .classList
                            .remove('hidden');


                        document.body
                            .classList
                            .add('overflow-hidden');
                    }


                    function closeDayModal() {

                        dayModal
                            ?.classList
                            .add('hidden');


                        document.body
                            .classList
                            .remove('overflow-hidden');
                    }


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


                            openDayModal(
                                button.dataset.date
                            );
                        }
                    );


                    document
                        .getElementById(
                            'close-day-visits-modal'
                        )
                        ?.addEventListener(
                            'click',
                            closeDayModal
                        );


                    /*
                     * =====================================================
                     * VISIT DETAILS MODAL
                     * =====================================================
                     */
                    const visitModal =
                        document.getElementById(
                            'admin-calendar-visit-modal'
                        );

                    const visitContent =
                        document.getElementById(
                            'admin-calendar-visit-content'
                        );

                    const visitLoading =
                        document.getElementById(
                            'admin-calendar-visit-loading'
                        );


                    async function openVisitModal(
                        url
                    ) {

                        /*
                         * Hide day modal if opened.
                         */
                        dayModal
                            ?.classList
                            .add('hidden');


                        visitModal
                            .classList
                            .remove('hidden');


                        document.body
                            .classList
                            .add('overflow-hidden');


                        visitContent.innerHTML =
                            '';


                        visitLoading
                            .classList
                            .remove('hidden');


                        try {

                            const response =
                                await fetch(
                                    url,
                                    {
                                        headers: {

                                            'Accept':
                                                'application/json',

                                            'X-Requested-With':
                                                'XMLHttpRequest',
                                        }
                                    }
                                );


                            const contentType =
                                response.headers.get(
                                    'content-type'
                                );


                            if (
                                !contentType
                                ||
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
                                    data.message
                                    || 'Unable to load visit.'
                                );
                            }


                            visitContent.innerHTML =
                                data.html;

                        }
                        catch (error) {

                            console.error(
                                error
                            );


                            visitContent.innerHTML = `
                    <div class="
                        py-14
                        px-5
                        text-center
                    ">

                        <i class="
                            fa-solid
                            fa-triangle-exclamation
                            text-red-600
                        ">
                        </i>

                        <p class="
                            text-sm
                            text-red-700
                            mt-2
                        ">

                            ${escapeHtml(
                                error.message
                            )}

                        </p>

                    </div>
                `;
                        }
                        finally {

                            visitLoading
                                .classList
                                .add('hidden');
                        }
                    }


                    /*
                     * Works for:
                     * - visit buttons directly in calendar
                     * - visit buttons created inside day modal
                     */
                    document.addEventListener(
                        'click',
                        function (event) {

                            const button =
                                event.target.closest(
                                    '.open-admin-calendar-visit'
                                );


                            if (!button) {
                                return;
                            }


                            window.location.href =
                                button.dataset.url;

                        }
                    );

                    function closeVisitModal() {

                        visitModal
                            ?.classList
                            .add('hidden');


                        document.body
                            .classList
                            .remove('overflow-hidden');


                        visitContent.innerHTML =
                            '';
                    }


                    document
                        .getElementById(
                            'close-admin-calendar-visit-modal'
                        )
                        ?.addEventListener(
                            'click',
                            closeVisitModal
                        );


                    /*
                     * =====================================================
                     * BACKDROP CLOSE
                     * =====================================================
                     */
                    dayModal?.addEventListener(
                        'click',
                        function (event) {

                            if (
                                event.target
                                === dayModal
                            ) {

                                closeDayModal();
                            }
                        }
                    );


                    visitModal?.addEventListener(
                        'click',
                        function (event) {

                            if (
                                event.target
                                === visitModal
                            ) {

                                closeVisitModal();
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
                                    weekday: 'long',
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric',
                                }
                            );
                    }

                }
            );

        </script>

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

@endsection
