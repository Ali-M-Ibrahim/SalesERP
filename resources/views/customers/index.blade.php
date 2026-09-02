@extends('layouts.app')

@section('title', 'Customers')

@section('content')

    <div class="p-4 md:p-6">

        <div class="flex flex-col
                sm:flex-row
                sm:items-center
                justify-between
                gap-3
                mb-5">

            <div>

                <h1 class="text-2xl font-bold">
                    Customers
                </h1>

                <p class="text-sm text-[#62685F]">
                    Leads and existing customers.
                </p>

            </div>


            @can('customers.create')

                <a href="{{ route('customers.create') }}"
                   class="inline-flex
                      items-center
                      justify-center
                      gap-2
                      bg-[#1E4B43]
                      text-white
                      rounded-lg
                      px-4 py-2.5
                      text-sm
                      font-medium">

                    <i class="fa-solid fa-plus"></i>

                    Add Customer

                </a>

            @endcan

        </div>


        {{-- Search --}}
        <form method="GET"
              action="{{ route('customers.index') }}"
              class="bg-white
                 border
                 border-[#DAD4C3]
                 rounded-xl
                 p-3
                 mb-5">

            <div class="flex flex-col md:flex-row gap-2">

                <div class="relative flex-1">

                    <i class="fa-solid fa-magnifying-glass
                          absolute
                          left-3
                          top-3
                          text-[#62685F]"></i>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search name, phone or customer number..."
                           class="w-full
                              rounded-lg
                              border-[#DAD4C3]
                              pl-10">

                </div>


                <select name="type"
                        class="rounded-lg border-[#DAD4C3]">

                    <option value="">
                        All Types
                    </option>

                    <option value="lead"
                        @selected(request('type') === 'lead')>

                        Leads

                    </option>

                    <option value="customer"
                        @selected(request('type') === 'customer')>

                        Customers

                    </option>

                </select>


                <button type="submit"
                        class="bg-[#1E4B43]
                           text-white
                           rounded-lg
                           px-5
                           text-sm">

                    Search

                </button>

            </div>

        </form>


        @if($customers->isEmpty())

            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    py-16
                    text-center">

                <i class="fa-regular fa-building
                      text-3xl
                      text-[#62685F]"></i>

                <p class="font-medium mt-3">
                    No customers found
                </p>

            </div>

        @else

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">

                @foreach($customers as $customer)

                    <a href="{{ route('customers.show', $customer) }}"
                       class="bg-white
                          border
                          border-[#DAD4C3]
                          rounded-xl
                          p-4
                          hover:shadow-sm
                          transition">

                        <div class="flex items-start
                                justify-between
                                gap-2">

                            <div>

                            <span class="inline-flex
                                         rounded-full
                                         px-2 py-1
                                         text-[10px]
                                         font-semibold
                                         uppercase
                                         {{ $customer->type === 'lead'
                                            ? 'bg-[#FBEAD9] text-[#D6772F]'
                                            : 'bg-[#E3ECE7] text-[#1E4B43]' }}">

                                {{ $customer->type }}

                            </span>

                                <p class="font-semibold text-lg mt-2">
                                    {{ $customer->name }}
                                </p>

                            </div>


                            <i class="fa-solid
                                  fa-chevron-right
                                  text-[#62685F]
                                  text-xs"></i>

                        </div>


                        <div class="space-y-2 mt-4 text-sm">

                            @if($customer->phone)

                                <p class="flex items-center gap-2
                                      text-[#62685F]">

                                    <i class="fa-solid fa-phone w-4"></i>

                                    {{ $customer->phone }}

                                </p>

                            @endif


                            @if($customer->address)

                                <p class="flex items-start gap-2
                                      text-[#62685F]">

                                    <i class="fa-solid
                                          fa-location-dot
                                          w-4 mt-1"></i>

                                    <span class="line-clamp-2">
                                    {{ $customer->address }}
                                </span>

                                </p>

                            @endif

                        </div>


                        <div class="border-t
                                border-[#DAD4C3]
                                mt-4 pt-3
                                flex items-center
                                justify-between
                                text-xs
                                text-[#62685F]">

                        <span>
                            {{ count($customer->visits) }} visits @if($customer->visits->isNotEmpty())
                                · last {{ $customer->visits->max('scheduled_at')?->format('d M Y') }}
                            @endif
                        </span>

                            <span>
                            {{ optional($customer->currentAssignment?->salesRep)->name ?? 'Unassigned' }}
                        </span>

                        </div>

                    </a>

                @endforeach

            </div>


            <div class="mt-6">
                {{ $customers->links() }}
            </div>

        @endif

    </div>

@endsection
