@extends('layouts.app')

@section('title', 'Customer Coverage')

@section('content')

    <div class="p-4 md:p-6 pb-24 md:pb-6">

        <div class="mb-5">

            <a href="{{ route(
            'admin.reports.index'
        ) }}"
               class="text-sm text-[#62685F]">

                <i class="fa-solid fa-arrow-left mr-1"></i>

                Reports

            </a>

            <h1 class="text-2xl
                   md:text-3xl
                   font-bold
                   mt-3">

                Customer Coverage

            </h1>

        </div>


        <form method="GET"
              class="bg-white
                 border
                 border-[#DAD4C3]
                 rounded-xl
                 p-4
                 mb-5">

            <div class="grid
                    sm:grid-cols-2
                    lg:grid-cols-4
                    gap-3">

                <input type="date"
                       name="from_date"
                       value="{{ request(
                       'from_date',
                       $fromDate->format('Y-m-d')
                   ) }}"
                       class="rounded-lg
                          border-[#DAD4C3]">


                <input type="date"
                       name="to_date"
                       value="{{ request(
                       'to_date',
                       $toDate->format('Y-m-d')
                   ) }}"
                       class="rounded-lg
                          border-[#DAD4C3]">


                <select  id="sales_rep_id" name="sales_rep_id"
                        class="rounded-lg
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


                <select name="coverage"
                        class="rounded-lg
                           border-[#DAD4C3]">

                    <option value="">
                        All Customers
                    </option>

                    <option value="never_visited"
                        @selected(
                            request('coverage')
                            === 'never_visited'
                        )>

                        Never Visited

                    </option>

                    <option value="not_visited_period"
                        @selected(
                            request('coverage')
                            === 'not_visited_period'
                        )>

                        Not Visited During Period

                    </option>

                    <option value="no_samples"
                        @selected(
                            request('coverage')
                            === 'no_samples'
                        )>

                        No Samples

                    </option>

                    <option value="no_resources"
                        @selected(
                            request('coverage')
                            === 'no_resources'
                        )>

                        No Resources Shared

                    </option>

                    <option value="unassigned"
                        @selected(
                            request('coverage')
                            === 'unassigned'
                        )>

                        Unassigned

                    </option>

                </select>

            </div>


            <div class="flex
                    justify-end
                    gap-2
                    mt-4">

                <a href="{{ route(
                'admin.reports.customer-coverage'
            ) }}"
                   class="px-4 py-2.5
                      border
                      border-[#DAD4C3]
                      rounded-lg
                      text-sm">

                    Reset

                </a>


                <button class="px-4 py-2.5
                           bg-[#1E4B43]
                           text-white
                           rounded-lg
                           text-sm">

                    Apply

                </button>

            </div>

        </form>


        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl
                overflow-hidden">

            @forelse($customers as $customer)

                <a href="{{ route(
                'customers.show',
                $customer
            ) }}"
                   class="block
                      p-4
                      border-b
                      last:border-b-0
                      border-[#DAD4C3]
                      hover:bg-[#FAF9F5]">

                    <div class="flex
                            items-start
                            justify-between
                            gap-4">

                        <div>

                            <p class="font-semibold">

                                {{ $customer->name }}

                            </p>


                            <p class="text-xs
                                  text-[#62685F]
                                  mt-1">

                                {{ $customer
                                    ->currentAssignment
                                    ?->salesRep
                                    ?->name
                                    ?? 'Unassigned' }}

                            </p>

                        </div>


                        <div class="text-right">

                            @if($customer->last_visit)

                                <p class="text-xs
                                      font-medium">

                                    Last visit

                                </p>

                                <p class="text-xs
                                      text-[#62685F]
                                      mt-1">

                                    {{ $customer
                                        ->last_visit
                                        ->scheduled_at
                                        ?->format('d M Y h:i A') }}

                                </p>

                            @else

                                <span class="text-xs
                                         text-red-700">

                                Never visited

                            </span>

                            @endif

                        </div>

                    </div>


                    <div class="flex
                            flex-wrap
                            gap-4
                            mt-3
                            text-xs
                            text-[#62685F]">

                    <span>
                        {{ $customer->visits_count }}
                        visits
                    </span>

                        <span>
                        {{ $customer
                            ->resource_shares_count }}
                        resources shared
                    </span>

                    </div>

                </a>

            @empty

                <div class="py-14 text-center">

                    <p class="text-sm text-[#62685F]">
                        No customers found.
                    </p>

                </div>

            @endforelse

        </div>


        @if($customers->hasPages())

            <div class="mt-5">

                {{ $customers->links() }}

            </div>

        @endif

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

