@extends('layouts.app')

@section('title', $user->name)

@section('content')

    <div class="p-4 md:p-6">

        <div class="mb-4">

            <a href="{{ route(
            'admin.sales-reps.index'
        ) }}"
               class="text-sm text-[#62685F]">

                <i class="fa-solid fa-arrow-left mr-1"></i>

                Sales Representatives

            </a>

        </div>


        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                p-5
                mb-5">

            <div class="flex
                    items-center
                    gap-4">

                <div class="w-14 h-14
                        rounded-full
                        bg-[#1E4B43]
                        text-white
                        flex
                        items-center
                        justify-center
                        text-xl
                        font-bold">

                    {{ strtoupper(
                        substr(
                            $user->name,
                            0,
                            1
                        )
                    ) }}

                </div>


                <div>

                    <h1 class="text-xl
                           md:text-2xl
                           font-bold">

                        {{ $user->name }}

                    </h1>

                    <p class="text-sm
                          text-[#62685F]">

                        {{ $user->email }}

                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================
             MONTH FILTER
        ========================================================== --}}
        <form method="GET"
              class="mb-5">

            <div class="flex
                    items-center
                    gap-2">

                <label class="text-sm font-medium">
                    Month
                </label>

                <input type="month"
                       name="month"
                       value="{{ $month->format('Y-m') }}"
                       onchange="this.form.submit()"
                       class="rounded-lg
                          border-[#DAD4C3]">

            </div>

        </form>


        {{-- =========================================================
             KPI
        ========================================================== --}}
        <div class="grid
                grid-cols-2
                lg:grid-cols-5
                gap-3
                mb-5">

            @foreach([
                [
                    'label' => 'Customers',
                    'value' => $assignedCustomerCount
                ],
                [
                    'label' => 'Visits',
                    'value' => $monthlyVisitCount
                ],
                [
                    'label' => 'Completed',
                    'value' => $completedCount
                ],
                [
                    'label' => 'Missed',
                    'value' => $missedCount
                ],
                  [
                    'label' => 'Cancelled',
                    'value' => $canceledCount
                ],

                [
                    'label' => 'Samples',
                    'value' => $samplesGiven
                ],
            ] as $metric)

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-4">

                    <p class="text-xs text-[#62685F]">
                        {{ $metric['label'] }}
                    </p>

                    <p class="text-2xl
                          font-bold
                          mt-2">

                        {{ $metric['value'] }}

                    </p>

                </div>

            @endforeach

        </div>


        <div class="grid
                lg:grid-cols-3
                gap-5">

            {{-- =====================================================
                 RECENT VISITS
            ====================================================== --}}
            <div class="lg:col-span-2">

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl">

                    <div class="px-5 py-4
                            border-b
                            border-[#DAD4C3]">

                        <h2 class="font-semibold">
                            Recent Visits
                        </h2>

                    </div>


                    @forelse($recentVisits as $visit)

                        <a href="{{ route(
                                        'admin.visits.show',
                                        $visit
                                    ) }}"
                           class="block
                              px-5 py-4
                              border-b
                              last:border-b-0
                              border-[#DAD4C3]">

                            <div class="flex
                                    justify-between
                                    gap-3">

                                <div>

                                    <p class="font-medium text-sm">

                                        {{ $visit->customer?->name }}

                                    </p>


                                    <p class="text-xs
                                          text-[#62685F]
                                          mt-1">

                                        {{ $visit
                                            ->visitPurpose
                                            ?->name
                                            ?? $visit->purpose_other
                                            ?? 'Visit' }}

                                    </p>


                                    @if(
                                        $visit
                                            ->visitSamples
                                            ->isNotEmpty()
                                    )

                                        <p class="text-xs
                                              text-[#D6772F]
                                              mt-2">

                                            {{ $visit
                                                ->visitSamples
                                                ->sum('quantity') }}

                                            samples

                                        </p>

                                    @endif

                                </div>


                                <div class="text-right">

                                    <p class="text-xs
                                          font-medium">
                                        {{ $visit ->scheduled_at ?->format('d M Y h:i A') }}


                                    </p>


                                    <p class="text-[10px]
                                          uppercase
                                          text-[#62685F]
                                          mt-1">

                                        {{ str_replace(
                                            '_',
                                            ' ',
                                            $visit->status
                                        ) }}

                                    </p>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="p-5
                                text-center
                                text-sm
                                text-[#62685F]">

                            No visits.

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =====================================================
                 ASSIGNED CUSTOMERS
            ====================================================== --}}
            <div>

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl">

                    <div class="px-5 py-4
                            border-b
                            border-[#DAD4C3]">

                        <h2 class="font-semibold">
                            Assigned Customers
                        </h2>

                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            {{ $assignedCustomerCount }}
                            total

                        </p>

                    </div>


                    @forelse(
                        $assignedCustomers
                        as $customer
                    )

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
                              border-[#DAD4C3]">

                            <div class="min-w-0">

                                <p class="text-sm
                                      font-medium
                                      truncate">

                                    {{ $customer->name }}

                                </p>

                                <p class="text-xs
                                      text-[#62685F]">

                                    {{ $customer->phone ?: 'No phone' }}

                                </p>

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

                            No assigned customers.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

@endsection
