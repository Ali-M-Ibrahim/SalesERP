@extends('layouts.app')

@section('title', 'Sales Representatives')

@section('content')

    <div class="p-4 md:p-6">

        <div class="mb-5">

            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-[#62685F]">

                <i class="fa-solid fa-arrow-left mr-1"></i>

                Admin Dashboard

            </a>


            <h1 class="text-2xl
                   md:text-3xl
                   font-bold
                   mt-3">

                Sales Representatives

            </h1>

        </div>


        {{-- Search --}}
        <form method="GET"
              class="bg-white
                 border
                 border-[#DAD4C3]
                 rounded-xl
                 p-4
                 mb-5">

            <div class="flex gap-2">

                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search name or email..."
                       class="flex-1
                          rounded-lg
                          border-[#DAD4C3]">


                <button type="submit"
                        class="px-4
                           rounded-lg
                           bg-[#1E4B43]
                           text-white">

                    <i class="fa-solid fa-search"></i>

                </button>

            </div>

        </form>


        <div class="bg-white
                border
                border-[#DAD4C3]
                rounded-xl">

            @forelse($salesReps as $rep)

                <a href="{{ route(
                'admin.sales-reps.show',
                $rep
            ) }}"
                   class="flex
                      items-center
                      justify-between
                      gap-4
                      px-5 py-4
                      border-b
                      last:border-b-0
                      border-[#DAD4C3]
                      hover:bg-[#FAF9F5]">

                    <div>

                        <p class="font-semibold">
                            {{ $rep->name }}
                        </p>


                        <p class="text-xs
                              text-[#62685F]
                              mt-1">

                            {{ $rep->email }}

                        </p>


                        <p class="text-xs
                              text-[#62685F]
                              mt-2">

                            {{ $rep->assigned_customers_count }}
                            customers

                            ·

                            {{ $rep->sales_visits_count }}
                            visits

                        </p>

                    </div>


                    <i class="fa-solid
                          fa-chevron-right
                          text-xs
                          text-[#62685F]">
                    </i>

                </a>

            @empty

                <div class="py-14 text-center">

                    <p class="text-sm text-[#62685F]">
                        No sales representatives found.
                    </p>

                </div>

            @endforelse

        </div>


        @if($salesReps->hasPages())

            <div class="mt-5">
                {{ $salesReps->links() }}
            </div>

        @endif

    </div>

@endsection
