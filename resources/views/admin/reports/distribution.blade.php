@extends('layouts.app')

@section('title', 'Distribution')

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

                Distribution Report

            </h1>

            <p class="text-sm
                  text-[#62685F]
                  mt-1">

                Samples distributed and resources shared.

            </p>

        </div>


        @include(
            'admin.reports.partials.filter',
            [
                'action' =>
                    route(
                        'admin.reports.distribution'
                    )
            ]
        )


        <div class="grid
                lg:grid-cols-2
                gap-5">

            {{-- Samples --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    overflow-hidden">

                <div class="px-5 py-4
                        border-b
                        border-[#DAD4C3]">

                    <h2 class="font-semibold">
                        Sample Distribution
                    </h2>

                </div>


                @forelse(
                    $sampleDistribution
                    as $row
                )

                    <div class="px-5 py-3
                            border-b
                            last:border-b-0
                            border-[#DAD4C3]
                            flex
                            items-center
                            justify-between
                            gap-3">

                        <div>

                            <p class="text-sm
                                  font-medium">

                                {{ $row
                                    ->sample
                                    ?->name
                                    ?? 'Unknown Sample' }}

                            </p>

                            <p class="text-xs
                                  text-[#62685F]
                                  mt-1">

                                {{ $row->visit_count }}
                                visits

                            </p>

                        </div>


                        <span class="rounded-full
                                 bg-[#FBEAD9]
                                 text-[#D6772F]
                                 px-3 py-1
                                 text-xs
                                 font-semibold">

                        {{ $row->total_quantity }}

                    </span>

                    </div>

                @empty

                    <div class="p-10 text-center
                            text-sm
                            text-[#62685F]">

                        No samples distributed.

                    </div>

                @endforelse

            </div>


            {{-- Resources --}}
            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    overflow-hidden">

                <div class="px-5 py-4
                        border-b
                        border-[#DAD4C3]">

                    <h2 class="font-semibold">
                        Resources Shared
                    </h2>

                </div>


                @forelse(
                    $resourceDistribution
                    as $row
                )

                    <div class="px-5 py-3
                            border-b
                            last:border-b-0
                            border-[#DAD4C3]
                            flex
                            items-center
                            justify-between
                            gap-3">

                        <div>

                            <p class="text-sm
                                  font-medium">

                                {{ $row
                                    ->resource
                                    ?->name
                                    ?? 'Unknown Resource' }}

                            </p>

                            <p class="text-xs
                                  text-[#62685F]
                                  mt-1">

                                {{ $row
                                    ->customer_count }}
                                customers

                            </p>

                        </div>


                        <span class="rounded-full
                                 bg-[#E3ECE7]
                                 text-[#1E4B43]
                                 px-3 py-1
                                 text-xs
                                 font-semibold">

                        {{ $row->total_shares }}
                        shares

                    </span>

                    </div>

                @empty

                    <div class="p-10 text-center
                            text-sm
                            text-[#62685F]">

                        No resources shared.

                    </div>

                @endforelse

            </div>

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

