@foreach($visits as $visit)

    @php
        $canManageVisit =
            auth()->user()->hasRole('sales_rep')
                ? $visit->sales_rep_id === auth()->id()
                : auth()->user()->can('visits.view');
    @endphp

    <div class="border-b
                last:border-b-0
                border-[#DAD4C3]">

        {{-- =====================================================
             VISIT DETAILS - CLICKABLE
        ====================================================== --}}
        <button type="button"
                class="open-visit-modal
                       w-full
                       text-left
                       px-5 pt-4
                       pb-3
                       hover:bg-[#FAF9F5]
                       transition"
                data-url="{{ route('visits.get', $visit) }}"
                data-update-url="{{ route('visits.update', $visit) }}"
                data-checkin-url="{{ route('visits.checkIn', $visit) }}"
                data-checkout-url="{{ route('visits.checkOut', $visit) }}"
            @disabled(!$canManageVisit)>

            <div class="flex
                        items-start
                        justify-between
                        gap-3">

                <div class="min-w-0 flex-1">

                    {{-- Status + Purpose --}}
                    <div class="flex
                                flex-wrap
                                items-center
                                gap-2">

                        <span class="text-[10px]
                                     uppercase
                                     font-semibold
                                     rounded-full
                                     px-2 py-1

                            @if($visit->status === 'completed')

                                bg-[#E3ECE7]
                                text-[#1E4B43]

                            @elseif($visit->status === 'checked_in')

                                bg-blue-100
                                text-blue-700

                            @elseif(
                                ($visit->status === 'scheduled' && $visit->scheduled_at->lt(today()) && !$visit->check_in_at)
                            )

                                bg-red-100
                                text-red-700

                            @else

                                bg-[#FBEAD9]
                                text-[#D6772F]

                            @endif
                        ">
                            @if(
                                ($visit->status === 'scheduled' && $visit->scheduled_at->lt(today()) && !$visit->check_in_at)
                            )
                          Missed
                            @else
                                {{ str_replace('_', ' ', $visit->status) }}
                            @endif
                        </span>


                        <span class="font-medium text-sm">

                            {{ $visit->visitPurpose?->name
                                ?? $visit->purpose_other
                                ?? 'Visit' }}

                        </span>

                    </div>


                    {{-- Sales Representative --}}
                    @if($visit->salesRep)

                        <p class="text-xs
                                  text-[#62685F]
                                  mt-2">

                            <i class="fa-regular fa-user mr-1"></i>

                            {{ $visit->salesRep->name }}

                        </p>

                    @endif


                    {{-- Notes --}}
                    @if($visit->visit_notes)

                        <p class="text-sm
                                  text-[#62685F]
                                  mt-2
                                  line-clamp-2">

                            {{ $visit->visit_notes }}

                        </p>

                    @endif


                    {{-- Client Requests --}}
                    @if($visit->client_requests)

                        <div class="mt-3
                                    rounded-lg
                                    bg-[#F1EFE7]
                                    px-3 py-2">

                            <p class="text-[10px]
                                      uppercase
                                      font-semibold
                                      text-[#62685F]">

                                Client Request

                            </p>

                            <p class="text-sm
                                      mt-1
                                      line-clamp-2">

                                {{ $visit->client_requests }}

                            </p>

                        </div>

                    @endif


                    {{-- Samples --}}
                    @if($visit->visitSamples->isNotEmpty())

                        <div class="flex
                                    flex-wrap
                                    gap-1.5
                                    mt-3">

                            @foreach($visit->visitSamples as $visitSample)

                                <span class="inline-flex
                                             items-center
                                             gap-1
                                             rounded-full
                                             bg-[#FBEAD9]
                                             text-[#D6772F]
                                             px-2 py-1
                                             text-[10px]">

                                    <i class="fa-solid fa-box"></i>

                                    {{ $visitSample->sample?->name }}

                                    × {{ $visitSample->quantity }}

                                </span>

                            @endforeach

                        </div>

                    @endif


                    {{-- Check In / Check Out Audit --}}
                    @if($visit->check_in_at)

                        <div class="flex
                                    flex-wrap
                                    gap-x-4
                                    gap-y-1
                                    mt-3
                                    text-xs
                                    text-[#62685F]">

                            <span>

                                <i class="fa-solid
                                          fa-location-dot
                                          text-[#1E4B43]
                                          mr-1">
                                </i>

                                Check in:

                                <strong>
                                    {{ $visit->check_in_at->format('H:i') }}
                                </strong>

                            </span>


                            @if($visit->check_out_at)

                                <span>

                                    <i class="fa-solid
                                              fa-circle-check
                                              text-[#1E4B43]
                                              mr-1">
                                    </i>

                                    Check out:

                                    <strong>
                                        {{ $visit->check_out_at->format('H:i') }}
                                    </strong>

                                </span>

                            @endif

                        </div>

                    @endif

                </div>


                {{-- Date --}}
                <div class="text-right shrink-0">

                    <p class="text-xs font-medium">

                        {{ $visit->scheduled_at?->format('d M Y h:i A') }}

                    </p>

                    <i class="fa-solid
                              fa-chevron-right
                              text-[10px]
                              text-[#9A9E98]
                              mt-3">
                    </i>

                </div>

            </div>

        </button>


        {{-- =====================================================
             QUICK CHECK IN / CHECK OUT
        ====================================================== --}}
        @if(
            Auth::user()->canAny(['visits.checkin', 'visits.checkout'])
            && !in_array($visit->status, ['cancelled', 'missed', 'completed'])
        )

            <div class="px-5
                        pb-4
                        flex
                        items-center
                        gap-2">


                {{-- ================================
                     NOT CHECKED IN YET
                ================================= --}}
                @if(!$visit->check_in_at)


                    <div class="flex
                flex-wrap
                items-center
                gap-2">

                        {{-- CHECK IN - ONLY TODAY --}}
                        @if($visit->scheduled_at?->isToday())

                            <button type="button"
                                    class="visit-list-check-in
                           inline-flex
                           items-center
                           justify-center
                           gap-2
                           px-4 py-2.5
                           rounded-lg
                           bg-[#1E4B43]
                           text-white
                           text-sm
                           font-medium
                           active:scale-[0.98]
                           transition"
                                    data-url="{{ route(
                        'visits.checkIn',
                        $visit
                    ) }}">

                                <i class="fa-solid fa-location-dot"></i>

                                Check In

                            </button>

                        @endif


                        {{-- CANCEL VISITf - ONLY SCHEDULED --}}

                        @if($visit->status === 'scheduled' && !$visit->scheduled_at->lt(today()) )

                            <button type="button"
                                    class="visit-list-cancel
                           inline-flex
                           items-center
                           justify-center
                           gap-2
                           px-4 py-2.5
                           rounded-lg
                           border
                           border-red-200
                           bg-red-50
                           text-red-700
                           text-sm
                           font-medium
                           hover:bg-red-100
                           active:scale-[0.98]
                           transition"
                                    data-url="{{ route(
                        'visits.cancel',
                        $visit
                    ) }}">

                                <i class="fa-solid fa-ban"></i>

                                Cancel Visit

                            </button>

                        @endif

                        @if($visit->status === 'scheduled')

                            <button type="button"
                                    class="visit-list-reschedule
                   inline-flex
                   items-center
                   justify-center
                   gap-2
                   px-4 py-2.5
                   rounded-lg
                   border
                   border-[#DAD4C3]
                   bg-white
                   text-[#1E4B43]
                   text-sm
                   font-medium
                   hover:bg-[#F1EFE7]
                   transition"
                                    data-url="{{ route('visits.reschedule', $visit) }}"
                                    data-date="{{ $visit->scheduled_at?->format('Y-m-d\TH:i') }}">

                                <i class="fa-regular fa-calendar-days"></i>

                                Reschedule

                            </button>
                        @endif

                            {{-- ================================
                                 CHECKED IN BUT NOT OUT
                            ================================= --}}
                @elseif(!$visit->check_out_at)

                    <div class="flex
                                flex-col
                                sm:flex-row
                                sm:items-center
                                gap-2
                                w-full">

                        <div class="inline-flex
                                    items-center
                                    gap-2
                                    text-xs
                                    text-[#1E4B43]
                                    bg-[#E3ECE7]
                                    rounded-lg
                                    px-3 py-2.5">

                            <i class="fa-solid fa-circle-check"></i>

                            Checked in at

                            <strong>
                                {{ $visit->check_in_at->format('H:i') }}
                            </strong>

                        </div>


{{--                        <button type="button"--}}
{{--                                class="visit-list-check-out--}}
{{--                                       inline-flex--}}
{{--                                       items-center--}}
{{--                                       justify-center--}}
{{--                                       gap-2--}}
{{--                                       px-4 py-2.5--}}
{{--                                       rounded-lg--}}
{{--                                       bg-[#D6772F]--}}
{{--                                       text-white--}}
{{--                                       text-sm--}}
{{--                                       font-medium--}}
{{--                                       active:scale-[0.98]--}}
{{--                                       transition"--}}
{{--                                data-url="{{ route(--}}
{{--                                    'visits.checkOut',--}}
{{--                                    $visit--}}
{{--                                ) }}">--}}

{{--                            <i class="fa-solid fa-location-arrow"></i>--}}

{{--                            Check Out--}}

{{--                        </button>--}}

                    </div>

                @endif

            </div>

        @endif

    </div>

@endforeach
