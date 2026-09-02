@extends('layouts.app')

@section('title', 'Customers')

@section('content')

    <div class="p-4 md:p-6 pb-24 md:pb-6">


        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="flex
                flex-col
                sm:flex-row
                sm:items-center
                justify-between
                gap-4
                mb-5">

            <div>

                <h1 class="text-2xl
                       md:text-3xl
                       font-bold">

                    Customers

                </h1>

                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Manage customers, leads and sales assignments.

                </p>

            </div>


            <div class="flex
            flex-col
            sm:flex-row
            sm:items-center
            gap-2">

                <div class="text-sm
                text-[#62685F]">

                    {{ $customers->total() }}

                    {{ \Illuminate\Support\Str::plural(
                        'record',
                        $customers->total()
                    ) }}

                </div>


                <a href="{{ route(
        'admin.customers.export',
        request()->query()
    ) }}"
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
              font-medium
              hover:bg-[#173a34]
              transition">

                    <i class="fa-solid
                  fa-file-excel">
                    </i>

                    Export Excel

                </a>

            </div>

        </div>


        {{-- =========================================================
             QUICK FILTERS
        ========================================================== --}}
        <div class="flex
                flex-wrap
                gap-2
                mb-4">

            <a href="{{ route(
            'admin.customers.index'
        ) }}"
               class="px-3 py-2
                  rounded-lg
                  text-xs
                  font-medium
                  border
                  border-[#DAD4C3]

                  {{ !request('status')
                     && !request('type')
                        ? 'bg-[#1E4B43] text-white border-[#1E4B43]'
                        : 'bg-white text-[#62685F]' }}">

                All

            </a>


            <a href="{{ route(
            'admin.customers.index',
            [
                'status' =>
                    'unassigned'
            ]
        ) }}"
               class="px-3 py-2
                  rounded-lg
                  text-xs
                  font-medium
                  border
                  border-[#DAD4C3]

                  {{ request('status') === 'unassigned'
                        ? 'bg-[#1E4B43] text-white border-[#1E4B43]'
                        : 'bg-white text-[#62685F]' }}">

                <i class="fa-solid
                      fa-user-slash
                      mr-1">
                </i>

                Unassigned

            </a>


            <a href="{{ route(
            'admin.customers.index',
            [
                'status' =>
                    'never_visited'
            ]
        ) }}"
               class="px-3 py-2
                  rounded-lg
                  text-xs
                  font-medium
                  border
                  border-[#DAD4C3]

                  {{ request('status') === 'never_visited'
                        ? 'bg-[#1E4B43] text-white border-[#1E4B43]'
                        : 'bg-white text-[#62685F]' }}">

                <i class="fa-solid
                      fa-user-clock
                      mr-1">
                </i>

                Never Visited

            </a>


            <a href="{{ route(
            'admin.customers.index',
            [
                'status' =>
                    'no_samples'
            ]
        ) }}"
               class="px-3 py-2
                  rounded-lg
                  text-xs
                  font-medium
                  border
                  border-[#DAD4C3]

                  {{ request('status') === 'no_samples'
                        ? 'bg-[#1E4B43] text-white border-[#1E4B43]'
                        : 'bg-white text-[#62685F]' }}">

                <i class="fa-solid
                      fa-box-open
                      mr-1">
                </i>

                No Samples

            </a>


            <a href="{{ route(
            'admin.customers.index',
            [
                'type' => 'lead'
            ]
        ) }}"
               class="px-3 py-2
                  rounded-lg
                  text-xs
                  font-medium
                  border
                  border-[#DAD4C3]

                  {{ request('type') === 'lead'
                        ? 'bg-[#D6772F] text-white border-[#D6772F]'
                        : 'bg-white text-[#62685F]' }}">

                Leads

            </a>

        </div>


        {{-- =========================================================
             FILTER FORM
        ========================================================== --}}
        <form method="GET"
              action="{{ route(
              'admin.customers.index'
          ) }}"
              class="bg-white
                 border
                 border-[#DAD4C3]
                 rounded-xl
                 p-4
                 mb-5">

            {{-- Preserve quick status --}}
            @if(request('status'))

                <input type="hidden"
                       name="status"
                       value="{{ request('status') }}">

            @endif


            <div class="grid
                    grid-cols-1
                    md:grid-cols-4
                    gap-3">


                {{-- Search --}}
                <div class="md:col-span-2">

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
                               placeholder="Name, phone, email or customer number..."
                               class="w-full
                                  rounded-lg
                                  border-[#DAD4C3]
                                  pl-9">

                    </div>

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


                    <select name="sales_rep_id"
                            class="w-full
                               rounded-lg
                               border-[#DAD4C3]">

                        <option value="">
                            All Sales Reps
                        </option>


                        @foreach(
                            $salesReps
                            as $rep
                        )

                            <option value="{{ $rep->id }}"
                                @selected(
                                    request(
                                        'sales_rep_id'
                                    )
                                    == $rep->id
                                )>

                                {{ $rep->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Type --}}
                <div>

                    <label class="block
                              text-xs
                              font-medium
                              text-[#62685F]
                              mb-1.5">

                        Type

                    </label>


                    <select name="type"
                            class="w-full
                               rounded-lg
                               border-[#DAD4C3]">

                        <option value="">
                            All Types
                        </option>

                        <option value="customer"
                            @selected(
                                request('type')
                                === 'customer'
                            )>

                            Customer

                        </option>

                        <option value="lead"
                            @selected(
                                request('type')
                                === 'lead'
                            )>

                            Lead

                        </option>

                    </select>

                </div>

            </div>


            <div class="flex
                    flex-col-reverse
                    sm:flex-row
                    sm:justify-end
                    gap-2
                    mt-4">

                <a href="{{ route(
                'admin.customers.index'
            ) }}"
                   class="inline-flex
                      justify-center
                      items-center
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
                            Customer
                        </th>

                        <th class="px-5 py-3">
                            Type
                        </th>

                        <th class="px-5 py-3">
                            Sales Rep
                        </th>

                        <th class="px-5 py-3">
                            Visits
                        </th>

                        <th class="px-5 py-3">
                            Shared
                        </th>

                        <th class="px-5 py-3">
                            Assignment
                        </th>

                        <th class="px-5 py-3"></th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $customers
                        as $customer
                    )

                        <tr class="border-t
                                   border-[#DAD4C3]">

                            {{-- Customer --}}
                            <td class="px-5 py-4">

                                <a href="{{ route(
                                    'customers.show',
                                    $customer
                                ) }}"
                                   class="font-semibold
                                          text-sm
                                          hover:text-[#1E4B43]">

                                    {{ $customer->name }}

                                </a>


                                <p class="text-xs
                                          text-[#62685F]
                                          mt-1">

                                    {{ $customer->customer_number }}

                                    @if($customer->phone)
                                        · {{ $customer->phone }}
                                    @endif

                                </p>

                            </td>


                            {{-- Type --}}
                            <td class="px-5 py-4">

                                <span class="inline-flex
                                             px-2 py-1
                                             rounded-full
                                             text-[10px]
                                             font-semibold
                                             uppercase

                                    {{ $customer->type === 'lead'
                                        ? 'bg-[#FBEAD9] text-[#D6772F]'
                                        : 'bg-[#E3ECE7] text-[#1E4B43]' }}">

                                    {{ $customer->type }}

                                </span>

                            </td>


                            {{-- Sales Rep --}}
                            <td class="px-5 py-4">

                                @if(
                                    $customer
                                        ->currentAssignment
                                        ?->salesRep
                                )

                                    <p class="text-sm font-medium">

                                        {{ $customer
                                            ->currentAssignment
                                            ->salesRep
                                            ->name }}

                                    </p>

                                @else

                                    <span class="text-xs
                                                 text-red-600">

                                        Unassigned

                                    </span>

                                @endif

                            </td>


                            {{-- Visits --}}
                            <td class="px-5 py-4">

                                <p class="text-sm font-medium">

                                    {{ $customer->visits_count }}

                                </p>

                                <p class="text-[10px]
                                          text-[#62685F]">

                                    {{ $customer
                                        ->completed_visits_count }}

                                    completed

                                </p>

                            </td>


                            {{-- Resources --}}
                            <td class="px-5 py-4">

                                <span class="text-sm">

                                    {{ $customer
                                        ->resource_shares_count }}

                                </span>

                            </td>


                            {{-- Assignment --}}
                            <td class="px-5 py-4">

                                <button type="button"
                                        class="open-assignment-modal
                                               inline-flex
                                               items-center
                                               gap-1.5
                                               px-3 py-2
                                               rounded-lg
                                               border
                                               border-[#DAD4C3]
                                               text-xs
                                               font-medium
                                               hover:bg-[#F1EFE7]"
                                        data-id="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        data-current-rep="{{ $customer->currentAssignment?->sales_rep_id }}"
                                        data-url="{{ route(
                                            'admin.customers.assign',
                                            $customer
                                        ) }}">

                                    <i class="fa-solid
                                              fa-user-pen">
                                    </i>

                                    {{ $customer->currentAssignment
                                        ? 'Reassign'
                                        : 'Assign' }}

                                </button>

                            </td>


                            {{-- View --}}
                            <td class="px-5 py-4 text-right">

                                <a href="{{ route(
                                    'customers.show',
                                    $customer
                                ) }}"
                                   class="w-8 h-8
                                          rounded-lg
                                          border
                                          border-[#DAD4C3]
                                          inline-flex
                                          items-center
                                          justify-center">

                                    <i class="fa-solid
                                              fa-chevron-right
                                              text-xs">
                                    </i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="py-14
                                       text-center
                                       text-sm
                                       text-[#62685F]">

                                No customers found.

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

            @forelse(
                $customers
                as $customer
            )

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-4">

                    <div class="flex
                            items-start
                            justify-between
                            gap-3">

                        <a href="{{ route(
                        'customers.show',
                        $customer
                    ) }}"
                           class="min-w-0 flex-1">

                            <div class="flex
                                    items-center
                                    gap-2">

                            <span class="inline-flex
                                         px-2 py-1
                                         rounded-full
                                         text-[9px]
                                         uppercase
                                         font-semibold

                                {{ $customer->type === 'lead'
                                    ? 'bg-[#FBEAD9] text-[#D6772F]'
                                    : 'bg-[#E3ECE7] text-[#1E4B43]' }}">

                                {{ $customer->type }}

                            </span>


                                <span class="text-[10px]
                                         font-mono
                                         text-[#62685F]">

                                {{ $customer->customer_number }}

                            </span>

                            </div>


                            <p class="font-semibold mt-2">

                                {{ $customer->name }}

                            </p>


                            @if($customer->phone)

                                <p class="text-xs
                                      text-[#62685F]
                                      mt-1">

                                    <i class="fa-solid
                                          fa-phone
                                          mr-1">
                                    </i>

                                    {{ $customer->phone }}

                                </p>

                            @endif

                        </a>


                        <a href="{{ route(
                        'customers.show',
                        $customer
                    ) }}"
                           class="w-8 h-8
                              shrink-0
                              rounded-lg
                              border
                              border-[#DAD4C3]
                              flex
                              items-center
                              justify-center">

                            <i class="fa-solid
                                  fa-chevron-right
                                  text-xs">
                            </i>

                        </a>

                    </div>


                    {{-- Rep --}}
                    <div class="mt-4
                            pt-3
                            border-t
                            border-[#DAD4C3]
                            flex
                            items-center
                            justify-between
                            gap-3">

                        <div>

                            <p class="text-[10px]
                                  uppercase
                                  text-[#62685F]">

                                Sales Representative

                            </p>


                            @if(
                                $customer
                                    ->currentAssignment
                                    ?->salesRep
                            )

                                <p class="text-sm
                                      font-medium
                                      mt-1">

                                    {{ $customer
                                        ->currentAssignment
                                        ->salesRep
                                        ->name }}

                                </p>

                            @else

                                <p class="text-sm
                                      text-red-600
                                      mt-1">

                                    Unassigned

                                </p>

                            @endif
                            <p class="mt-1 text-[10px]
                                  uppercase
                                  text-[#62685F]">

                                last visit {{ $customer->visits->max('scheduled_at')?->format('d M Y') }}

                            </p>


                        </div>


                        <button type="button"
                                class="open-assignment-modal
                                   inline-flex
                                   items-center
                                   gap-1
                                   px-3 py-2
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]
                                   text-xs"
                                data-id="{{ $customer->id }}"
                                data-name="{{ $customer->name }}"
                                data-current-rep="{{ $customer->currentAssignment?->sales_rep_id }}"
                                data-url="{{ route(
                                'admin.customers.assign',
                                $customer
                            ) }}">

                            <i class="fa-solid fa-user-pen"></i>

                            {{ $customer->currentAssignment
                                ? 'Change'
                                : 'Assign' }}

                        </button>

                    </div>


                    {{-- Stats --}}
                    <div class="grid
                            grid-cols-3
                            gap-2
                            mt-3">

                        <div class="bg-[#F1EFE7]
                                rounded-lg
                                px-3 py-2
                                text-center">

                            <p class="font-semibold">

                                {{ $customer
                                    ->visits_count }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]">

                                Visits

                            </p>

                        </div>


                        <div class="bg-[#F1EFE7]
                                rounded-lg
                                px-3 py-2
                                text-center">

                            <p class="font-semibold">

                                {{ $customer
                                    ->completed_visits_count }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]">

                                Completed

                            </p>

                        </div>


                        <div class="bg-[#F1EFE7]
                                rounded-lg
                                px-3 py-2
                                text-center">

                            <p class="font-semibold">

                                {{ $customer
                                    ->resource_shares_count }}

                            </p>

                            <p class="text-[9px]
                                  text-[#62685F]">

                                Shared

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

                        No customers found.

                    </p>

                </div>

            @endforelse

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($customers->hasPages())

            <div class="mt-5">

                {{ $customers->links() }}

            </div>

        @endif

    </div>



    {{-- =============================================================
         ASSIGNMENT MODAL
    ============================================================= --}}
    <div id="assignment-modal"
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
                    max-w-md
                    rounded-xl
                    shadow-xl">

                {{-- Header --}}
                <div class="px-5 py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-center
                        justify-between">

                    <div>

                        <h3 class="font-semibold">

                            Assign Customer

                        </h3>

                        <p id="assignment-customer-name"
                           class="text-xs
                              text-[#62685F]
                              mt-1">
                        </p>

                    </div>


                    <button type="button"
                            id="close-assignment-modal"
                            class="w-8 h-8">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                <form method="POST"
                      id="assignment-form">

                    @csrf


                    <div class="p-5">

                        <label class="block
                                  text-sm
                                  font-medium
                                  mb-1.5">

                            Sales Representative *

                        </label>


                        <select name="sales_rep_id"
                                id="assignment-sales-rep"
                                required
                                class="w-full
                                   rounded-lg
                                   border-[#DAD4C3]">

                            <option value="">
                                Select sales representative
                            </option>


                            @foreach(
                                $salesReps
                                as $rep
                            )

                                <option value="{{ $rep->id }}">

                                    {{ $rep->name }}

                                </option>

                            @endforeach

                        </select>


                        <div class="mt-4
                                p-3
                                rounded-lg
                                bg-[#F1EFE7]
                                text-xs
                                text-[#62685F]">

                            <i class="fa-solid
                                  fa-circle-info
                                  mr-1">
                            </i>

                            Reassigning preserves the previous assignment in history.

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
                                id="cancel-assignment-modal"
                                class="px-5 py-3
                                   rounded-lg
                                   border
                                   border-[#DAD4C3]
                                   text-sm">

                            Cancel

                        </button>


                        <button type="submit"
                                class="px-5 py-3
                                   rounded-lg
                                   bg-[#1E4B43]
                                   text-white
                                   text-sm
                                   font-medium">

                            Save Assignment

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    const modal =
                        document.getElementById(
                            'assignment-modal'
                        );

                    const form =
                        document.getElementById(
                            'assignment-form'
                        );

                    const name =
                        document.getElementById(
                            'assignment-customer-name'
                        );

                    const salesRep =
                        document.getElementById(
                            'assignment-sales-rep'
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

                        form.removeAttribute(
                            'action'
                        );

                        salesRep.value =
                            '';

                    }


                    /*
                     * Open assignment modal.
                     */
                    document.addEventListener(
                        'click',
                        function (event) {

                            const button =
                                event.target.closest(
                                    '.open-assignment-modal'
                                );


                            if (!button) {
                                return;
                            }


                            form.action =
                                button.dataset.url;


                            name.textContent =
                                button.dataset.name;


                            salesRep.value =
                                button.dataset.currentRep
                                || '';


                            openModal();

                        }
                    );


                    document
                        .getElementById(
                            'close-assignment-modal'
                        )
                        ?.addEventListener(
                            'click',
                            closeModal
                        );


                    document
                        .getElementById(
                            'cancel-assignment-modal'
                        )
                        ?.addEventListener(
                            'click',
                            closeModal
                        );


                    /*
                     * Close when backdrop clicked.
                     */
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

                }
            );

        </script>

    @endpush

@endsection
