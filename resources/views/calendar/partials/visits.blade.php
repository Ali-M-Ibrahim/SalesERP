@forelse($visits as $visit)

    <div class="px-4
                sm:px-5
                py-4
                border-b
                last:border-b-0
                border-[#DAD4C3]">

        <div class="flex
                    items-start
                    justify-between
                    gap-3">

            <div class="min-w-0 flex-1">

                {{-- Status --}}
                <div class="flex
                            flex-wrap
                            items-center
                            gap-2">

                    <span class="text-[10px]
                                 uppercase
                                 font-semibold
                                 px-2 py-1
                                 rounded-full

                        @if($visit->status === 'completed')

                            bg-[#E3ECE7]
                            text-[#1E4B43]

                        @elseif($visit->status === 'checked_in')

                            bg-blue-100
                            text-blue-700

                        @elseif(
                            $visit->status === 'cancelled'
                            || $visit->status === 'missed'
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


                    <a href="{{ route(
                        'customers.show',
                        $visit->customer_id
                    ) }}"
                       class="font-semibold
                              text-sm
                              hover:text-[#1E4B43]">

                        {{ $visit->customer?->name }}

                    </a>

                </div>


                {{-- Purpose --}}
                <p class="text-xs
                          text-[#62685F]
                          mt-2">

                    <i class="fa-solid
                              fa-bullseye
                              mr-1">
                    </i>

                    {{ $visit->visitPurpose?->name
                        ?? $visit->purpose_other
                        ?? 'Visit' }}

                </p>


                {{-- Sales Rep for admin/owner --}}
                @unless(auth()->user()->hasRole('sales_rep'))

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

                @endunless


                {{-- Customer Address --}}
                @if($visit->customer?->address)

                    <p class="text-xs
                              text-[#62685F]
                              mt-1">

                        <i class="fa-solid
                                  fa-location-dot
                                  mr-1">
                        </i>

                        {{ $visit->customer->address }}

                    </p>

                @endif


                {{-- Samples --}}
                @if($visit->visitSamples->isNotEmpty())

                    <div class="flex
                                flex-wrap
                                gap-1.5
                                mt-3">

                        @foreach(
                            $visit->visitSamples
                            as $visitSample
                        )

                            <span class="inline-flex
                                         items-center
                                         gap-1
                                         px-2 py-1
                                         rounded-full
                                         bg-[#FBEAD9]
                                         text-[#D6772F]
                                         text-[10px]">

                                <i class="fa-solid fa-box"></i>

                                {{ $visitSample->sample?->name }}

                                × {{ $visitSample->quantity }}

                            </span>

                        @endforeach

                    </div>

                @endif


                {{-- Check In / Out --}}
                @if($visit->check_in_at)

                    <div class="flex
                                flex-wrap
                                gap-x-4
                                gap-y-1
                                mt-3
                                text-xs
                                text-[#62685F]">

                        <span>

                            Check in:

                            <strong>

                                {{ $visit
                                    ->check_in_at
                                    ->format('H:i') }}

                            </strong>

                        </span>


                        @if($visit->check_out_at)

                            <span>

                                Check out:

                                <strong>

                                    {{ $visit
                                        ->check_out_at
                                        ->format('H:i') }}

                                </strong>

                            </span>

                        @endif

                    </div>

                @endif

            </div>


            {{-- Open Customer --}}
            <a href="{{ route(
                'customers.show',
                $visit->customer_id
            ) }}"
               class="w-9 h-9
                      shrink-0
                      rounded-lg
                      border
                      border-[#DAD4C3]
                      flex
                      items-center
                      justify-center
                      text-[#62685F]
                      hover:text-[#1E4B43]
                      hover:bg-[#F1EFE7]">

                <i class="fa-solid
                          fa-chevron-right
                          text-xs">
                </i>

            </a>

        </div>

    </div>

@empty

    <div class="py-12
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


        <p class="font-medium
                  mt-3">

            No visits

        </p>


        <p class="text-sm
                  text-[#62685F]
                  mt-1">

            There are no visits scheduled for this date.

        </p>

    </div>

@endforelse
