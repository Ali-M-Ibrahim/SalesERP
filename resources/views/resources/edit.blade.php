@extends('layouts.app')

@section('title', 'Edit Resource')

@section('content')

    <div class="p-4
            md:p-6
            max-w-3xl
            mx-auto">


        <div class="mb-5">

            <a href="{{ route(
            'resources.show',
            $resource
        ) }}"
               class="inline-flex
                  items-center
                  gap-2
                  text-sm
                  text-[#62685F]">

                <i class="fa-solid fa-arrow-left"></i>

                Back to Resource

            </a>


            <h1 class="text-2xl
                   md:text-3xl
                   font-bold
                   mt-3">

                Edit Resource

            </h1>


            <p class="text-sm
                  text-[#62685F]
                  mt-1">

                {{ $resource->name }}

            </p>

        </div>


        <form method="POST"
              action="{{ route(
              'resources.update',
              $resource
          ) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4
                    md:p-6">


                @include(
                    'resources._form'
                )


                <div class="flex
                        flex-col-reverse
                        sm:flex-row
                        sm:justify-end
                        gap-2
                        mt-6
                        pt-5
                        border-t
                        border-[#DAD4C3]">

                    <a href="{{ route(
                    'resources.show',
                    $resource
                ) }}"
                       class="w-full
                          sm:w-auto
                          inline-flex
                          justify-center
                          px-5 py-3
                          rounded-lg
                          border
                          border-[#DAD4C3]
                          text-sm">

                        Cancel

                    </a>


                    <button type="submit"
                            class="w-full
                               sm:w-auto
                               px-5 py-3
                               rounded-lg
                               bg-[#1E4B43]
                               text-white
                               text-sm
                               font-medium">

                        Update Resource

                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection
