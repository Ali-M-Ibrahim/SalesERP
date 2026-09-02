@extends('layouts.app')

@section('title', 'Visits')

@section('content')

    <div class="p-4 md:p-6 pb-24 md:pb-6">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="flex
                flex-col
                sm:flex-row
                sm:items-center
                sm:justify-between
                gap-3
                mb-5">

            <div>

                <h1 class="text-2xl
                       md:text-3xl
                       font-bold">

                    Visits

                </h1>

                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Review sales visits, activity and GPS audit data.

                </p>

            </div>


            <span class="text-sm text-[#62685F]">

            {{ $visits->total() }}

                {{ \Illuminate\Support\Str::plural(
                    'visit',
                    $visits->total()
                ) }}

        </span>

        </div>


        {{-- =========================================================
             FILTERS
        ========================================================== --}}
        <form method="GET"
              action="{{ route('admin.visits.index') }}"
              class="bg-white
                 border
                 border-[#DAD4C3]
                 rounded-xl
                 p-4
                 mb-5">

            <div class="grid
                    grid-cols-1
                    sm:grid-cols-2
                    lg:grid-cols-3
                    xl:grid-cols-6
                    gap-3">


                {{-- Search --}}
                <div class="sm:col-span-2">

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Search

                    </label>

                    <div class="relative">

                        <i class="fa-solid
                              fa-magnifying-glass
                              absolute
                              left-3
                              top-1/2
                              -translate-y-1/2
                              text-xs
                              text-[#62685F]">
                        </i>

                        <input type="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Customer, rep, notes..."
                               class="w-full
                                  rounded-lg
                                  border-[#DAD4C3]
                                  pl-9">

                    </div>

                </div>


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
                           value="{{ request('from_date') }}"
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
                           value="{{ request('to_date') }}"
                           class="w-full
                              rounded-lg
                              border-[#DAD4C3]">

                </div>


                {{-- Status --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Status

                    </label>

                    <select name="status"
                            class="w-full
                               rounded-lg
                               border-[#DAD4C3]">

                        <option value="">
                            All Statuses
                        </option>

                        <option value="scheduled"
                            @selected(
                                request('status')
                                === 'scheduled'
                            )>
                            Scheduled
                        </option>

                        <option value="checked_in"
                            @selected(
                                request('status')
                                === 'checked_in'
                            )>
                            Checked In
                        </option>

                        <option value="completed"
                            @selected(
                                request('status')
                                === 'completed'
                            )>
                            Completed
                        </option>

                        <option value="missed"
                            @selected(
                                request('status')
                                === 'missed'
                            )>
                            Missed
                        </option>

                        <option value="cancelled"
                            @selected(
                                request('status')
                                === 'cancelled'
                            )>
                            Cancelled
                        </option>

                    </select>

                </div>


                {{-- Sales Rep --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Sales Rep

                    </label>

                    <select id="sales_rep_id" name="sales_rep_id"
                            class="w-full
                               rounded-lg
                               border-[#DAD4C3]">

                        <option value="">
                            All Sales Reps
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
                <div class="sm:col-span-2 xl:col-span-2">

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Customer

                    </label>

                    <select name="customer_id"
                            id="admin-visit-customer"
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

            </div>


            <div class="flex
                    flex-col-reverse
                    sm:flex-row
                    sm:justify-end
                    gap-2
                    mt-4">

                <a href="{{ route('admin.visits.index') }}"
                   class="inline-flex
                      items-center
                      justify-center
                      px-4 py-2.5
                      rounded-lg
                      border
                      border-[#DAD4C3]
                      text-sm">

                    Reset

                </a>


                <button type="submit"
                        class="inline-flex
                           items-center
                           justify-center
                           gap-2
                           px-4 py-2.5
                           rounded-lg
                           bg-[#1E4B43]
                           text-white
                           text-sm
                           font-medium">

                    <i class="fa-solid fa-filter"></i>

                    Apply Filters

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

            {{-- Total --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4">

                <p class="text-xs text-[#62685F]">
                    Total
                </p>

                <p class="text-2xl font-bold mt-2">
                    {{ $totalCount }}
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
                      mt-2
                      text-[#D6772F]">

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
                      mt-2
                      text-blue-700">

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
                      mt-2
                      text-[#1E4B43]">

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
                      mt-2
                      text-red-700">

                    {{ $missedCount }}

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

                <table class="w-full">

                    <thead class="bg-[#F1EFE7]">

                    <tr class="text-left
                               text-xs
                               text-[#62685F]">

                        <th class="px-5 py-3">
                            Date
                        </th>

                        <th class="px-5 py-3">
                            Customer
                        </th>

                        <th class="px-5 py-3">
                            Sales Rep
                        </th>

                        <th class="px-5 py-3">
                            Purpose
                        </th>

                        <th class="px-5 py-3">
                            Status
                        </th>

                        <th class="px-5 py-3">
                            Audit
                        </th>

                        <th class="px-5 py-3">
                            Samples
                        </th>

                        <th class="px-5 py-3"></th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse($visits as $visit)

                        <tr class="border-t
                                   border-[#DAD4C3]
                                   hover:bg-[#FAF9F5]">

                            {{-- Date --}}
                            <td class="px-5 py-4">

                                <p class="text-sm font-medium">

                                    {{ $visit
                                        ->scheduled_at
                                       ?->format('d M Y h:i A') }}

                                </p>

                            </td>


                            {{-- Customer --}}
                            <td class="px-5 py-4">

                                <a href="{{ route(
                                    'customers.show',
                                    $visit->customer_id
                                ) }}"
                                   class="font-semibold
                                          text-sm
                                          hover:text-[#1E4B43]">

                                    {{ $visit
                                        ->customer
                                        ?->name
                                        ?? '—' }}

                                </a>

                                @if($visit->customer?->phone)

                                    <p class="text-xs
                                              text-[#62685F]
                                              mt-1">

                                        {{ $visit->customer->phone }}

                                    </p>

                                @endif

                            </td>


                            {{-- Sales Rep --}}
                            <td class="px-5 py-4">

                                <p class="text-sm">

                                    {{ $visit
                                        ->salesRep
                                        ?->name
                                        ?? '—' }}

                                </p>

                            </td>


                            {{-- Purpose --}}
                            <td class="px-5 py-4">

                                <p class="text-sm">

                                    {{ $visit
                                        ->visitPurpose
                                        ?->name
                                        ?? $visit->purpose_other
                                        ?? 'Visit' }}

                                </p>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                <span class="inline-flex
                                             px-2.5 py-1
                                             rounded-full
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

                            </td>


                            {{-- Audit --}}
                            <td class="px-5 py-4">

                                @if($visit->check_in_at)

                                    <p class="text-xs
                                              text-[#62685F]">

                                        In:

                                        <strong>

                                            {{ $visit
                                                ->check_in_at
                                                ->format('H:i') }}

                                        </strong>

                                    </p>

                                @else

                                    <span class="text-xs
                                                 text-[#62685F]">

                                        —

                                    </span>

                                @endif


                                @if($visit->check_out_at)

                                    <p class="text-xs
                                              text-[#62685F]
                                              mt-1">

                                        Out:

                                        <strong>

                                            {{ $visit
                                                ->check_out_at
                                                ->format('H:i') }}

                                        </strong>

                                    </p>

                                @endif

                            </td>


                            {{-- Samples --}}
                            <td class="px-5 py-4">

                                @if($visit->visitSamples->isNotEmpty())

                                    <span class="text-sm font-medium">

                                        {{ $visit
                                            ->visitSamples
                                            ->sum('quantity') }}

                                    </span>

                                @else

                                    <span class="text-xs
                                                 text-[#62685F]">

                                        —

                                    </span>

                                @endif

                            </td>


                            {{-- View --}}
                            <td class="px-5 py-4 text-right">

                                <button type="button"
                                        class="open-admin-visit
                                               w-9 h-9
                                               rounded-lg
                                               border
                                               border-[#DAD4C3]
                                               inline-flex
                                               items-center
                                               justify-center
                                               hover:bg-[#F1EFE7]"
                                        data-url="{{ route(
                                            'admin.visits.show',
                                            $visit
                                        ) }}">

                                    <i class="fa-regular
                                              fa-eye
                                              text-xs">
                                    </i>

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="py-14
                                       text-center
                                       text-sm
                                       text-[#62685F]">

                                No visits found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
             MOBILE / TABLET CARDS
        ========================================================== --}}
        <div class="lg:hidden
                space-y-3">

            @forelse($visits as $visit)

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-4">


                    <div class="flex
                            items-start
                            justify-between
                            gap-3">

                        <div class="min-w-0 flex-1">

                            <div class="flex
                                    flex-wrap
                                    items-center
                                    gap-2">

                            <span class="inline-flex
                                         px-2 py-1
                                         rounded-full
                                         text-[9px]
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


                                <span class="text-xs
                                         text-[#62685F]">

                                {{ $visit
                                    ->scheduled_at
                                   ?->format('d M Y h:i A')}}

                            </span>

                            </div>


                            <a href="{{ route(
                            'customers.show',
                            $visit->customer_id
                        ) }}"
                               class="block
                                  font-semibold
                                  mt-2">

                                {{ $visit->customer?->name }}

                            </a>


                            <p class="text-xs
                                  text-[#62685F]
                                  mt-1">

                                {{ $visit
                                    ->visitPurpose
                                    ?->name
                                    ?? $visit->purpose_other
                                    ?? 'Visit' }}

                            </p>


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

                        </div>


                        <button type="button"
                                class="open-admin-visit
                                   w-9 h-9
                                   shrink-0
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]
                                   inline-flex
                                   items-center
                                   justify-center"
                                data-url="{{ route(
                                'admin.visits.show',
                                $visit
                            ) }}">

                            <i class="fa-regular fa-eye"></i>

                        </button>

                    </div>


                    {{-- Audit --}}
                    @if(
                        $visit->check_in_at
                        || $visit->visitSamples->isNotEmpty()
                    )

                        <div class="flex
                                flex-wrap
                                gap-x-4
                                gap-y-2
                                mt-4
                                pt-3
                                border-t
                                border-[#DAD4C3]
                                text-xs
                                text-[#62685F]">

                            @if($visit->check_in_at)

                                <span>

                                Check In:

                                <strong>

                                    {{ $visit
                                        ->check_in_at
                                        ->format('H:i') }}

                                </strong>

                            </span>

                            @endif


                            @if($visit->check_out_at)

                                <span>

                                Check Out:

                                <strong>

                                    {{ $visit
                                        ->check_out_at
                                        ->format('H:i') }}

                                </strong>

                            </span>

                            @endif


                            @if($visit->visitSamples->isNotEmpty())

                                <span>

                                <i class="fa-solid fa-box-open mr-1"></i>

                                {{ $visit
                                    ->visitSamples
                                    ->sum('quantity') }}

                                samples

                            </span>

                            @endif

                        </div>

                    @endif

                </div>

            @empty

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        py-14
                        text-center">

                    <p class="text-sm text-[#62685F]">
                        No visits found.
                    </p>

                </div>

            @endforelse

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($visits->hasPages())

            <div class="mt-5">
                {{ $visits->links() }}
            </div>

        @endif

    </div>


    {{-- =============================================================
         READ-ONLY VISIT MODAL
    ============================================================= --}}
    <div id="admin-visit-modal"
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

                <div class="px-5 py-4
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
                            id="close-admin-visit-modal"
                            class="w-9 h-9
                               rounded-lg
                               flex
                               items-center
                               justify-center">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                {{-- Loading --}}
                <div id="admin-visit-loading"
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


                <div id="admin-visit-content">
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

                                placeholder:
                                    'Search customer...',

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


                    /*
                     * Modal.
                     */
                    const modal =
                        document.getElementById(
                            'admin-visit-modal'
                        );

                    const content =
                        document.getElementById(
                            'admin-visit-content'
                        );

                    const loading =
                        document.getElementById(
                            'admin-visit-loading'
                        );


                    function openModal() {

                        modal.classList.remove(
                            'hidden'
                        );

                        document.body.classList.add(
                            'overflow-hidden'
                        );
                    }


                    function closeModal() {

                        modal.classList.add(
                            'hidden'
                        );

                        document.body.classList.remove(
                            'overflow-hidden'
                        );

                        content.innerHTML =
                            '';
                    }


                    /*
                     * Open visit details.
                     */
                    document.addEventListener(
                        'click',
                        async function (event) {

                            const button =
                                event.target.closest(
                                    '.open-admin-visit'
                                );


                            if (!button) {
                                return;
                            }

                            window.location.href =
                                button.dataset.url;


                        }
                    );


                    document
                        .getElementById(
                            'close-admin-visit-modal'
                        )
                        ?.addEventListener(
                            'click',
                            closeModal
                        );


                    modal?.addEventListener(
                        'click',
                        function (event) {

                            if (
                                event.target === modal
                            ) {

                                closeModal();

                            }

                        }
                    );


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

                }
            );

        </script>

    @endpush

@endsection
