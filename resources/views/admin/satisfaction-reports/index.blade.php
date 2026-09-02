@extends('layouts.app')

@section('title', 'Customer Satisfaction Reports')

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

                    Customer Satisfaction

                </h1>

                <p class="text-sm
                          text-[#62685F]
                          mt-1">

                    Monitor customer feedback, response rates and sales representative performance.

                </p>

            </div>

        </div>


        {{-- =========================================================
             FILTERS
        ========================================================== --}}
        <form method="GET"
              action="{{ route('admin.satisfaction-reports.index') }}"
              class="bg-white
                     border
                     border-[#DAD4C3]
                     rounded-xl
                     p-4
                     mb-5">

            <div class="grid
                        grid-cols-1
                        md:grid-cols-3
                        gap-3">

                {{-- From Date --}}
                <div>

                    <label class="block
                                  text-xs
                                  font-medium
                                  text-[#62685F]
                                  mb-1.5">

                        From Date

                    </label>

                    <input type="date"
                           name="from_date"
                           value="{{ request(
                               'from_date',
                               $fromDate->format('Y-m-d')
                           ) }}"
                           class="w-full
                                  rounded-lg
                                  border-[#DAD4C3]">

                </div>


                {{-- To Date --}}
                <div>

                    <label class="block
                                  text-xs
                                  font-medium
                                  text-[#62685F]
                                  mb-1.5">

                        To Date

                    </label>

                    <input type="date"
                           name="to_date"
                           value="{{ request(
                               'to_date',
                               $toDate->format('Y-m-d')
                           ) }}"
                           class="w-full
                                  rounded-lg
                                  border-[#DAD4C3]">

                </div>


                {{-- Sales Representative --}}
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

                        @foreach($salesReps as $salesRep)

                            <option value="{{ $salesRep->id }}"
                                @selected(
                                    request('sales_rep_id')
                                    == $salesRep->id
                                )>

                                {{ $salesRep->name }}

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

                <a href="{{ route('admin.satisfaction-reports.index') }}"
                   class="inline-flex
                          items-center
                          justify-center
                          px-4
                          py-2.5
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
                               px-4
                               py-2.5
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
             MAIN KPI CARDS
        ========================================================== --}}
        <div class="grid
                    grid-cols-2
                    md:grid-cols-4
                    gap-3
                    mb-3">

            {{-- Sent --}}
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

                        <p class="text-xs text-[#62685F]">
                            Surveys Sent
                        </p>

                        <p class="text-2xl
                                  font-bold
                                  mt-1">

                            {{ number_format($sentInvitations) }}

                        </p>

                    </div>

                    <div class="w-9 h-9
                                rounded-lg
                                bg-[#E3ECE7]
                                text-[#1E4B43]
                                flex
                                items-center
                                justify-center">

                        <i class="fa-regular fa-envelope"></i>

                    </div>

                </div>

            </div>


            {{-- Responses --}}
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

                        <p class="text-xs text-[#62685F]">
                            Responses
                        </p>

                        <p class="text-2xl
                                  font-bold
                                  mt-1">

                            {{ number_format($responses) }}

                        </p>

                    </div>

                    <div class="w-9 h-9
                                rounded-lg
                                bg-[#E3ECE7]
                                text-[#1E4B43]
                                flex
                                items-center
                                justify-center">

                        <i class="fa-solid fa-comment-dots"></i>

                    </div>

                </div>

            </div>


            {{-- Response Rate --}}
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

                        <p class="text-xs text-[#62685F]">
                            Response Rate
                        </p>

                        <p class="text-2xl
                                  font-bold
                                  mt-1">

                            {{ number_format($responseRate, 1) }}%

                        </p>

                    </div>

                    <div class="w-9 h-9
                                rounded-lg
                                bg-[#E3ECE7]
                                text-[#1E4B43]
                                flex
                                items-center
                                justify-center">

                        <i class="fa-solid fa-percent"></i>

                    </div>

                </div>

            </div>


            {{-- Overall --}}
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

                        <p class="text-xs text-[#62685F]">
                            Overall Rating
                        </p>

                        <p class="text-2xl
                                  font-bold
                                  mt-1">

                            {{ number_format($overallRating, 1) }}

                            <span class="text-sm
                                         font-normal
                                         text-[#62685F]">
                                / 5
                            </span>

                        </p>

                    </div>

                    <div class="w-9 h-9
                                rounded-lg
                                bg-[#FBEAD9]
                                text-[#D6772F]
                                flex
                                items-center
                                justify-center">

                        <i class="fa-solid fa-star"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             CATEGORY KPI CARDS
        ========================================================== --}}
        <div class="grid
                    grid-cols-2
                    md:grid-cols-4
                    gap-3
                    mb-6">

            {{-- Sales Rep --}}
            <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-4">

                <p class="text-xs text-[#62685F]">
                    Sales Rep Rating
                </p>

                <div class="flex
                            items-end
                            gap-2
                            mt-2">

                    <p class="text-xl font-bold">

                        {{ number_format($salesmanRating, 1) }}

                    </p>

                    <p class="text-xs
                              text-[#62685F]
                              mb-1">

                        / 5

                    </p>

                </div>

                <div class="mt-2 text-[#D6772F] text-xs">

                    <i class="fa-solid fa-star"></i>

                </div>

            </div>


            {{-- Product --}}
            <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-4">

                <p class="text-xs text-[#62685F]">
                    Product Rating
                </p>

                <div class="flex
                            items-end
                            gap-2
                            mt-2">

                    <p class="text-xl font-bold">

                        {{ number_format($productRating, 1) }}

                    </p>

                    <p class="text-xs
                              text-[#62685F]
                              mb-1">

                        / 5

                    </p>

                </div>

                <div class="mt-2 text-[#D6772F] text-xs">

                    <i class="fa-solid fa-star"></i>

                </div>

            </div>


            {{-- Meeting --}}
            <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-4">

                <p class="text-xs text-[#62685F]">
                    Meeting Rating
                </p>

                <div class="flex
                            items-end
                            gap-2
                            mt-2">

                    <p class="text-xl font-bold">

                        {{ number_format($meetingRating, 1) }}

                    </p>

                    <p class="text-xs
                              text-[#62685F]
                              mb-1">

                        / 5

                    </p>

                </div>

                <div class="mt-2 text-[#D6772F] text-xs">

                    <i class="fa-solid fa-star"></i>

                </div>

            </div>


            {{-- Low Ratings --}}
            <div class="bg-white
                        border
                        border-red-200
                        rounded-xl
                        p-4">

                <p class="text-xs text-[#62685F]">
                    Low Rating Responses
                </p>

                <div class="flex
                            items-center
                            gap-2
                            mt-2">

                    <p class="text-xl
                              font-bold
                              text-red-600">

                        {{ number_format($lowRatingResponses) }}

                    </p>

                    <i class="fa-solid
                              fa-triangle-exclamation
                              text-red-500">
                    </i>

                </div>

                <p class="text-[10px]
                          text-[#62685F]
                          mt-2">

                    Responses containing a rating of 2 or below.

                </p>

            </div>

        </div>


        {{-- =========================================================
             REPORT CONTENT
        ========================================================== --}}
        <div class="grid
                    grid-cols-1
                    xl:grid-cols-3
                    gap-5
                    mb-6">


            {{-- =====================================================
                 SALES REP PERFORMANCE
            ====================================================== --}}
            <div class="xl:col-span-2
                        bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        overflow-hidden">

                <div class="px-5
                            py-4
                            border-b
                            border-[#DAD4C3]">

                    <h2 class="font-semibold">
                        Sales Representative Performance
                    </h2>

                    <p class="text-xs
                              text-[#62685F]
                              mt-1">

                        Customer satisfaction results grouped by sales representative.

                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-[#F1EFE7]">

                        <tr class="text-left
                                   text-xs
                                   text-[#62685F]">

                            <th class="px-5 py-3">
                                Sales Representative
                            </th>

                            <th class="px-5 py-3 text-center">
                                Sent
                            </th>

                            <th class="px-5 py-3 text-center">
                                Responses
                            </th>

                            <th class="px-5 py-3 text-center">
                                Response Rate
                            </th>

                            <th class="px-5 py-3 text-center">
                                Avg. Rating
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($salesRepPerformance as $row)

                            <tr class="border-t
                                       border-[#DAD4C3]">

                                <td class="px-5 py-4">

                                    <div class="flex
                                                items-center
                                                gap-3">

                                        <div class="w-9 h-9
                                                    shrink-0
                                                    rounded-full
                                                    bg-[#E3ECE7]
                                                    text-[#1E4B43]
                                                    flex
                                                    items-center
                                                    justify-center
                                                    text-xs
                                                    font-bold">

                                            {{ strtoupper(
                                                substr(
                                                    $row['sales_rep']->name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>

                                        <div>

                                            <p class="text-sm
                                                      font-medium">

                                                {{ $row['sales_rep']->name }}

                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-5
                                           py-4
                                           text-center
                                           text-sm">

                                    {{ $row['sent'] }}

                                </td>


                                <td class="px-5
                                           py-4
                                           text-center
                                           text-sm">

                                    {{ $row['responses'] }}

                                </td>


                                <td class="px-5
                                           py-4
                                           text-center">

                                    <span class="inline-flex
                                                 px-2
                                                 py-1
                                                 rounded-full
                                                 bg-[#E3ECE7]
                                                 text-[#1E4B43]
                                                 text-xs
                                                 font-semibold">

                                        {{ number_format(
                                            $row['response_rate'],
                                            1
                                        ) }}%

                                    </span>

                                </td>


                                <td class="px-5
                                           py-4
                                           text-center">

                                    @if($row['average_rating'] > 0)

                                        <div class="inline-flex
                                                    items-center
                                                    gap-1">

                                            <i class="fa-solid
                                                      fa-star
                                                      text-[#D6772F]
                                                      text-xs">
                                            </i>

                                            <span class="font-semibold">

                                                {{ number_format(
                                                    $row['average_rating'],
                                                    1
                                                ) }}

                                            </span>

                                            <span class="text-xs
                                                         text-[#62685F]">

                                                / 5

                                            </span>

                                        </div>

                                    @else

                                        <span class="text-[#62685F]">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="px-5
                                           py-12
                                           text-center
                                           text-sm
                                           text-[#62685F]">

                                    No sales representative data available.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =====================================================
                 RATING DISTRIBUTION
            ====================================================== --}}
            <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        overflow-hidden">

                <div class="px-5
                            py-4
                            border-b
                            border-[#DAD4C3]">

                    <h2 class="font-semibold">
                        Rating Distribution
                    </h2>

                    <p class="text-xs
                              text-[#62685F]
                              mt-1">

                        Distribution across all rating questions.

                    </p>

                </div>


                <div class="p-5">

                    @php

                        $distributionTotal =
                            collect($ratingDistribution)
                                ->sum();

                    @endphp


                    <div class="space-y-5">

                        @foreach([5, 4, 3, 2, 1] as $rating)

                            @php

                                $count =
                                    (int) (
                                        $ratingDistribution[$rating]
                                        ?? 0
                                    );

                                $percentage =
                                    $distributionTotal > 0
                                        ? round(
                                            (
                                                $count
                                                /
                                                $distributionTotal
                                            ) * 100,
                                            1
                                        )
                                        : 0;

                            @endphp


                            <div>

                                <div class="flex
                                            items-center
                                            justify-between
                                            gap-3
                                            mb-1.5">

                                    <div class="flex
                                                items-center
                                                gap-1">

                                        <span class="text-sm
                                                     font-medium">

                                            {{ $rating }}

                                        </span>

                                        <i class="fa-solid
                                                  fa-star
                                                  text-[#D6772F]
                                                  text-xs">
                                        </i>

                                    </div>


                                    <div class="text-xs
                                                text-[#62685F]">

                                        {{ $count }}

                                        <span class="ml-1">

                                            ({{ number_format(
                                                $percentage,
                                                1
                                            ) }}%)

                                        </span>

                                    </div>

                                </div>


                                <div class="w-full
                                            h-2
                                            bg-[#F1EFE7]
                                            rounded-full
                                            overflow-hidden">

                                    <div class="h-full
                                                bg-[#1E4B43]
                                                rounded-full"
                                         style="width: {{ $percentage }}%">
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    @if($distributionTotal === 0)

                        <p class="text-sm
                                  text-[#62685F]
                                  text-center
                                  mt-6">

                            No ratings available for the selected period.

                        </p>

                    @endif

                </div>

            </div>

        </div>


        {{-- =========================================================
             RECENT RESPONSES
        ========================================================== --}}
        <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    overflow-hidden">

            <div class="px-5
                        py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        items-center
                        justify-between
                        gap-3">

                <div>

                    <h2 class="font-semibold">
                        Recent Responses
                    </h2>

                    <p class="text-xs
                              text-[#62685F]
                              mt-1">

                        Latest completed customer satisfaction surveys.

                    </p>

                </div>

                <div class="text-xs
                            text-[#62685F]">

                    {{ $recentResponses->total() }}

                    {{ \Illuminate\Support\Str::plural(
                        'response',
                        $recentResponses->total()
                    ) }}

                </div>

            </div>


            {{-- =====================================================
                 DESKTOP RESPONSES
            ====================================================== --}}
            <div class="hidden lg:block overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-[#F1EFE7]">

                    <tr class="text-left
                               text-xs
                               text-[#62685F]">

                        <th class="px-5 py-3">
                            Customer
                        </th>

                        <th class="px-5 py-3">
                            Sales Rep
                        </th>

                        <th class="px-5 py-3">
                            Visit
                        </th>

                        <th class="px-5 py-3">
                            Responded
                        </th>

                        <th class="px-5 py-3 text-center">
                            Rating
                        </th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse($recentResponses as $invitation)

                        @php

                            $averageRating =
                                $invitation
                                    ->answers
                                    ->whereNotNull('rating')
                                    ->avg('rating');

                            $hasLowRating =
                                $invitation
                                    ->answers
                                    ->whereNotNull('rating')
                                    ->contains(
                                        fn ($answer) =>
                                            $answer->rating <= 2
                                    );

                        @endphp


                        <tr class="border-t
                                   border-[#DAD4C3]
                                   {{ $hasLowRating
                                        ? 'bg-red-50/40'
                                        : '' }}">

                            {{-- Customer --}}
                            <td class="px-5 py-4">

                                <p class="text-sm
                                          font-semibold">

                                    {{ $invitation
                                        ->customer
                                        ?->name
                                        ?? '—' }}

                                </p>

                                <p class="text-xs
                                          text-[#62685F]
                                          mt-1">

                                    {{ $invitation->email }}

                                </p>

                            </td>


                            {{-- Sales Rep --}}
                            <td class="px-5 py-4">

                                <p class="text-sm">

                                    {{ $invitation
                                        ->salesRep
                                        ?->name
                                        ?? '—' }}

                                </p>

                            </td>


                            {{-- Visit --}}
                            <td class="px-5 py-4">

                                <p class="text-sm">

                                    {{ $invitation
                                        ->visit
                                        ?->scheduled_at
                                        ?->format('d M Y')
                                        ?? '—' }}

                                </p>

                                <p class="text-[10px]
                                          text-[#62685F]
                                          mt-1">

                                    {{ $invitation
                                        ->visit
                                        ?->scheduled_at
                                        ?->format('H:i')
                                        ?? '' }}

                                </p>

                            </td>


                            {{-- Response Date --}}
                            <td class="px-5 py-4">

                                <p class="text-sm">

                                    {{ $invitation
                                        ->completed_at
                                        ?->format('d M Y')
                                        ?? '—' }}

                                </p>

                                <p class="text-[10px]
                                          text-[#62685F]
                                          mt-1">

                                    {{ $invitation
                                        ->completed_at
                                        ?->format('H:i')
                                        ?? '' }}

                                </p>

                            </td>


                            {{-- Average Rating --}}
                            <td class="px-5
                                       py-4
                                       text-center">

                                @if($averageRating)

                                    <span class="inline-flex
                                                 items-center
                                                 gap-1
                                                 px-2.5
                                                 py-1.5
                                                 rounded-full
                                                 text-xs
                                                 font-semibold

                                        {{ $averageRating <= 2
                                            ? 'bg-red-100 text-red-700'
                                            : (
                                                $averageRating < 4
                                                    ? 'bg-[#FBEAD9] text-[#D6772F]'
                                                    : 'bg-[#E3ECE7] text-[#1E4B43]'
                                            ) }}">

                                        <i class="fa-solid fa-star"></i>

                                        {{ number_format(
                                            $averageRating,
                                            1
                                        ) }}

                                    </span>

                                @else

                                    —

                                @endif


                                @if($hasLowRating)

                                    <div class="mt-1">

                                        <span class="text-[9px]
                                                     text-red-600
                                                     font-medium">

                                            <i class="fa-solid
                                                      fa-triangle-exclamation">
                                            </i>

                                            Low rating

                                        </span>

                                    </div>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="px-5
                                       py-14
                                       text-center
                                       text-sm
                                       text-[#62685F]">

                                No satisfaction responses found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
                 MOBILE RESPONSES
            ====================================================== --}}
            <div class="lg:hidden">

                @forelse($recentResponses as $invitation)

                    @php

                        $averageRating =
                            $invitation
                                ->answers
                                ->whereNotNull('rating')
                                ->avg('rating');

                        $hasLowRating =
                            $invitation
                                ->answers
                                ->whereNotNull('rating')
                                ->contains(
                                    fn ($answer) =>
                                        $answer->rating <= 2
                                );

                    @endphp


                    <div class="p-4
                                border-t
                                border-[#DAD4C3]

                                {{ $hasLowRating
                                    ? 'bg-red-50/40'
                                    : '' }}">

                        <div class="flex
                                    items-start
                                    justify-between
                                    gap-3">

                            <div>

                                <p class="font-semibold
                                          text-sm">

                                    {{ $invitation
                                        ->customer
                                        ?->name
                                        ?? '—' }}

                                </p>

                                <p class="text-xs
                                          text-[#62685F]
                                          mt-1">

                                    {{ $invitation
                                        ->salesRep
                                        ?->name
                                        ?? '—' }}

                                </p>

                            </div>


                            @if($averageRating)

                                <span class="inline-flex
                                             items-center
                                             gap-1
                                             px-2.5
                                             py-1.5
                                             rounded-full
                                             text-xs
                                             font-semibold

                                    {{ $averageRating <= 2
                                        ? 'bg-red-100 text-red-700'
                                        : (
                                            $averageRating < 4
                                                ? 'bg-[#FBEAD9] text-[#D6772F]'
                                                : 'bg-[#E3ECE7] text-[#1E4B43]'
                                        ) }}">

                                    <i class="fa-solid fa-star"></i>

                                    {{ number_format(
                                        $averageRating,
                                        1
                                    ) }}

                                </span>

                            @endif

                        </div>


                        <div class="grid
                                    grid-cols-2
                                    gap-3
                                    mt-4">

                            <div>

                                <p class="text-[10px]
                                          uppercase
                                          text-[#62685F]">

                                    Visit

                                </p>

                                <p class="text-xs
                                          font-medium
                                          mt-1">

                                    {{ $invitation
                                        ->visit
                                        ?->scheduled_at
                                        ?->format(
                                            'd M Y H:i'
                                        )
                                        ?? '—' }}

                                </p>

                            </div>


                            <div>

                                <p class="text-[10px]
                                          uppercase
                                          text-[#62685F]">

                                    Responded

                                </p>

                                <p class="text-xs
                                          font-medium
                                          mt-1">

                                    {{ $invitation
                                        ->completed_at
                                        ?->format(
                                            'd M Y H:i'
                                        )
                                        ?? '—' }}

                                </p>

                            </div>

                        </div>


                        @if($hasLowRating)

                            <div class="mt-3
                                        p-2.5
                                        rounded-lg
                                        bg-red-100
                                        text-red-700
                                        text-xs">

                                <i class="fa-solid
                                          fa-triangle-exclamation
                                          mr-1">
                                </i>

                                This response contains a low rating.

                            </div>

                        @endif

                    </div>

                @empty

                    <div class="py-14
                                text-center
                                text-sm
                                text-[#62685F]">

                        No satisfaction responses found.

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($recentResponses->hasPages())

            <div class="mt-5">

                {{ $recentResponses->links() }}

            </div>

        @endif

    </div>

@endsection
