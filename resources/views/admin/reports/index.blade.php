@extends('layouts.app')

@section('title', 'Reports')

@section('content')

    <div class="p-4 md:p-6 pb-24 md:pb-6">

        {{-- Header --}}
        <div class="mb-5">

            <h1 class="text-2xl
                   md:text-3xl
                   font-bold">

                Reports

            </h1>

            <p class="text-sm
                  text-[#62685F]
                  mt-1">

                Review sales performance, customer coverage and distribution activity.

            </p>

        </div>


        @include(
            'admin.reports.partials.filter',
            [
                'action' =>
                    route(
                        'admin.reports.index'
                    )
            ]
        )


        {{-- Summary --}}
        <div class="grid
                grid-cols-2
                lg:grid-cols-5
                gap-3
                mb-6">

            @foreach([
                [
                    'label' => 'Visits',
                    'value' => $totalVisits,
                    'icon' => 'fa-calendar-check',
                ],
                [
                    'label' => 'Completed',
                    'value' => $completedVisits,
                    'icon' => 'fa-circle-check',
                ],
                [
                    'label' => 'Missed',
                    'value' => $missedVisits,
                    'icon' => 'fa-triangle-exclamation',
                ],
                [
                    'label' => 'Samples',
                    'value' => $samplesGiven,
                    'icon' => 'fa-box-open',
                ],
                [
                    'label' => 'Resources Shared',
                    'value' => $resourceShares,
                    'icon' => 'fa-share-nodes',
                ],
            ] as $metric)

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

                                {{ $metric['label'] }}

                            </p>

                            <p class="text-2xl
                                  font-bold
                                  mt-2">

                                {{ $metric['value'] }}

                            </p>

                        </div>


                        <div class="w-9 h-9
                                rounded-lg
                                bg-[#E3ECE7]
                                text-[#1E4B43]
                                flex
                                items-center
                                justify-center">

                            <i class="fa-solid
                                  {{ $metric['icon'] }}">
                            </i>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- Reports --}}
        <div class="grid
                sm:grid-cols-2
                gap-4">

            {{-- Sales --}}
            <a href="{{ route(
            'admin.reports.sales-performance',
            request()->only([
                'from_date',
                'to_date',
                'sales_rep_id',
            ])
        ) }}"
               class="bg-white
                  border
                  border-[#DAD4C3]
                  rounded-xl
                  p-5
                  hover:border-[#1E4B43]
                  transition">

                <div class="w-11 h-11
                        rounded-lg
                        bg-[#E3ECE7]
                        text-[#1E4B43]
                        flex
                        items-center
                        justify-center">

                    <i class="fa-solid fa-chart-line"></i>

                </div>

                <h2 class="font-semibold mt-4">

                    Sales Performance

                </h2>

                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Visits, completion rate, customer coverage, samples and resources by sales representative.

                </p>

            </a>


            {{-- Audit --}}
            <a href="{{ route(
            'admin.reports.visits',
            request()->only([
                'from_date',
                'to_date',
                'sales_rep_id',
            ])
        ) }}"
               class="bg-white
                  border
                  border-[#DAD4C3]
                  rounded-xl
                  p-5
                  hover:border-[#1E4B43]
                  transition">

                <div class="w-11 h-11
                        rounded-lg
                        bg-[#FBEAD9]
                        text-[#D6772F]
                        flex
                        items-center
                        justify-center">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

                <h2 class="font-semibold mt-4">

                    Visit Audit

                </h2>

                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Check-in, check-out, GPS audit, visit purpose and activity.

                </p>

            </a>


            {{-- Coverage --}}
            <a href="{{ route(
            'admin.reports.customer-coverage',
            request()->only([
                'from_date',
                'to_date',
                'sales_rep_id',
            ])
        ) }}"
               class="bg-white
                  border
                  border-[#DAD4C3]
                  rounded-xl
                  p-5
                  hover:border-[#1E4B43]
                  transition">

                <div class="w-11 h-11
                        rounded-lg
                        bg-[#F1EFE7]
                        text-[#62685F]
                        flex
                        items-center
                        justify-center">

                    <i class="fa-solid fa-users-viewfinder"></i>

                </div>

                <h2 class="font-semibold mt-4">

                    Customer Coverage

                </h2>

                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Never visited, no samples, no resources and unassigned customers.

                </p>

            </a>


            {{-- Distribution --}}
            <a href="{{ route(
            'admin.reports.distribution',
            request()->only([
                'from_date',
                'to_date',
                'sales_rep_id',
            ])
        ) }}"
               class="bg-white
                  border
                  border-[#DAD4C3]
                  rounded-xl
                  p-5
                  hover:border-[#1E4B43]
                  transition">

                <div class="w-11 h-11
                        rounded-lg
                        bg-[#E3ECE7]
                        text-[#1E4B43]
                        flex
                        items-center
                        justify-center">

                    <i class="fa-solid fa-boxes-stacked"></i>

                </div>

                <h2 class="font-semibold mt-4">

                    Distribution

                </h2>

                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Sample quantities and resources shared with customers.

                </p>

            </a>

            <a href="{{ route(
    'admin.reports.location-verification',
    request()->only([
        'from_date',
        'to_date',
        'sales_rep_id',
    ])
) }}"
               class="bg-white
          border
          border-[#DAD4C3]
          rounded-xl
          p-5
          hover:border-[#1E4B43]
          transition">

                <div class="w-11 h-11
                rounded-lg
                bg-[#FBEAD9]
                text-[#D6772F]
                flex
                items-center
                justify-center">

                    <i class="fa-solid fa-location-crosshairs"></i>

                </div>

                <h2 class="font-semibold mt-4">

                    Location Verification

                </h2>

                <p class="text-sm
              text-[#62685F]
              mt-1">

                    Compare customer, check-in and check-out GPS locations and flag visits that require review.

                </p>

            </a>

            <a href="{{ route(
                'admin.satisfaction-reports.index',
                request()->only([
                    'from_date',
                    'to_date',
                    'sales_rep_id',
                ])
            ) }}"
               class="bg-white
          border
          border-[#DAD4C3]
          rounded-xl
          p-5
          hover:border-[#1E4B43]
          transition">

                <div class="w-11 h-11
                rounded-lg
                bg-[#E4F3EE]
                text-[#1E7A64]
                flex
                items-center
                justify-center">

                    <i class="fa-solid fa-face-smile"></i>

                </div>

                <h2 class="font-semibold mt-4">
                    Customer Satisfaction
                </h2>

                <p class="text-sm
              text-[#62685F]
              mt-1">
                    Review customer satisfaction ratings and feedback to evaluate service quality and identify areas for improvement.
                </p>

            </a>

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

