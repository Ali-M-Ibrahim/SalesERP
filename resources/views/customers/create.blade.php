@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')

    <div class="p-4 md:p-6 max-w-5xl mx-auto">

        <div class="mb-5">

            <a href="{{ route('customers.index') }}"
               class="inline-flex
                  items-center
                  gap-2
                  text-sm
                  text-[#62685F]
                  hover:text-[#1E4B43]">

                <i class="fa-solid fa-arrow-left"></i>

                Back to Customers

            </a>


            <div class="mt-3">

                <h1 class="text-2xl font-bold">
                    Add Customer
                </h1>

                <p class="text-sm
                      text-[#62685F]
                      mt-1">

                    Create a new lead or customer.

                </p>

            </div>

        </div>


        <form method="POST"
              action="{{ route('customers.store') }}">

            @csrf


            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4 md:p-6">

                @include('customers._form')


                <div class="flex
                        flex-col-reverse
                        sm:flex-row
                        sm:justify-end
                        gap-2
                        mt-6
                        pt-5
                        border-t
                        border-[#DAD4C3]">


                    <a href="{{ route('customers.index') }}"
                       class="w-full sm:w-auto
                          inline-flex
                          items-center
                          justify-center
                          px-5 py-3
                          rounded-lg
                          border
                          border-[#DAD4C3]
                          text-sm
                          font-medium">

                        Cancel

                    </a>


                    <button type="submit"
                            class="w-full sm:w-auto
                               inline-flex
                               items-center
                               justify-center
                               gap-2
                               px-5 py-3
                               rounded-lg
                               bg-[#1E4B43]
                               text-white
                               text-sm
                               font-medium">

                        <i class="fa-solid fa-check"></i>

                        Save Customer

                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection
