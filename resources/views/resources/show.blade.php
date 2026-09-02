@extends('layouts.app')

@section('title', $resource->name)

@section('content')

    <div class="p-4
            md:p-6
            max-w-6xl
            mx-auto">


        {{-- =========================================================
             BACK
        ========================================================== --}}
        <div class="mb-4">

            <a href="{{ route(
            'resources.index'
        ) }}"
               class="inline-flex
                  items-center
                  gap-2
                  text-sm
                  text-[#62685F]">

                <i class="fa-solid fa-arrow-left"></i>

                Back to Resources

            </a>

        </div>



        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="flex
                flex-col
                md:flex-row
                md:items-start
                justify-between
                gap-4
                mb-5">

            <div>

                <div class="flex
                        flex-wrap
                        gap-2
                        mb-2">

                    @if(
                        $resource
                            ->resourceCategory
                    )

                        <span class="px-2.5 py-1
                                 rounded-full
                                 bg-[#F1EFE7]
                                 text-xs
                                 text-[#62685F]">

                        {{ $resource
                            ->resourceCategory
                            ->name }}

                    </span>

                    @endif


                    <span class="px-2.5 py-1
                             rounded-full
                             bg-[#E3ECE7]
                             text-[#1E4B43]
                             uppercase
                             text-xs">

                    {{ $resource->file_type }}

                </span>

                </div>


                <h1 class="text-2xl
                       md:text-3xl
                       font-bold">

                    {{ $resource->name }}

                </h1>


                <p class="text-xs
                      text-[#62685F]
                      mt-2">

                    @if($resource->creator)

                        Added by

                        {{ $resource
                            ->creator
                            ->name }}

                        ·

                    @endif


                    {{ $resource
                        ->created_at
                        ->format(
                            'd M Y H:i'
                        ) }}

                </p>

            </div>


            <div class="flex
                    flex-wrap
                    gap-2">


                {{-- Edit --}}
                @can('resources.update')

                    @if(
                        !auth()->user()
                            ->hasRole('sales_rep')
                        ||
                        $resource->created_by
                            === auth()->id()
                    )

                        <a href="{{ route(
                        'resources.edit',
                        $resource
                    ) }}"
                           class="inline-flex
                              items-center
                              gap-2
                              px-4 py-2.5
                              rounded-lg
                              border
                              border-[#DAD4C3]
                              text-sm">

                            <i class="fa-solid fa-pen"></i>

                            Edit

                        </a>

                    @endif

                @endcan


                {{-- Share --}}
                @can('resources.share')

                    <button type="button"
                            class="open-resource-share
                               inline-flex
                               items-center
                               gap-2
                               px-4 py-2.5
                               rounded-lg
                               bg-[#1E4B43]
                               text-white
                               text-sm
                               font-medium"
                            data-name="{{ $resource->name }}"
                            data-url="{{ route(
                            'resources.share',
                            $resource
                        ) }}">

                        <i class="fa-brands fa-whatsapp"></i>

                        Share

                    </button>

                @endcan

            </div>

        </div>



        <div class="grid
                lg:grid-cols-3
                gap-5">


            {{-- =====================================================
                 PREVIEW
            ====================================================== --}}
            <div class="lg:col-span-2">

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        overflow-hidden">


                    @if(
                        $resource->file_type
                        === 'image'
                    )

                        <img src="{{ Storage::url(
                        $resource->file_path
                    ) }}"
                             alt="{{ $resource->name }}"
                             class="w-full
                                max-h-[700px]
                                object-contain
                                bg-[#F1EFE7]">

                    @else

                        <div class="p-6
                                sm:p-10
                                text-center
                                bg-[#F1EFE7]">

                            <i class="fa-regular
                                  fa-file-pdf
                                  text-6xl
                                  text-red-600">
                            </i>


                            <p class="font-semibold mt-4">

                                PDF Document

                            </p>


                            <a href="{{ route(
                            'resources.public',
                            $resource
                        ) }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex
                                  items-center
                                  justify-center
                                  gap-2
                                  mt-4
                                  px-5 py-3
                                  rounded-lg
                                  bg-[#1E4B43]
                                  text-white
                                  text-sm">

                                <i class="fa-regular fa-file-pdf"></i>

                                Open PDF

                            </a>

                        </div>

                    @endif

                </div>

            </div>



            {{-- =====================================================
                 DETAILS
            ====================================================== --}}
            <div>

                <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        p-5">

                    <h2 class="font-semibold
                           mb-4">

                        Resource Information

                    </h2>


                    @if(
                        $resource->description
                    )

                        <div class="mb-5">

                            <p class="text-xs
                                  text-[#62685F]">

                                Description

                            </p>

                            <p class="text-sm
                                  mt-1
                                  whitespace-pre-line">

                                {{ $resource->description }}

                            </p>

                        </div>

                    @endif


                    <div class="space-y-3
                            text-sm">

                        <div class="flex
                                justify-between
                                gap-3">

                        <span class="text-[#62685F]">
                            Category
                        </span>

                            <span class="font-medium
                                     text-right">

                            {{ $resource
                                ->resourceCategory
                                ?->name
                                ?? '—' }}

                        </span>

                        </div>


                        <div class="flex
                                justify-between
                                gap-3">

                        <span class="text-[#62685F]">
                            Type
                        </span>

                            <span class="font-medium
                                     uppercase">

                            {{ $resource->file_type }}

                        </span>

                        </div>


                        <div class="flex
                                justify-between
                                gap-3">

                        <span class="text-[#62685F]">
                            Added by
                        </span>

                            <span class="font-medium
                                     text-right">

                            {{ $resource
                                ->creator
                                ?->name
                                ?? '—' }}

                        </span>

                        </div>


                        <div class="flex
                                justify-between
                                gap-3">

                        <span class="text-[#62685F]">
                            Added
                        </span>

                            <span class="font-medium
                                     text-right">

                            {{ $resource
                                ->created_at
                                ->format(
                                    'd M Y H:i'
                                ) }}

                        </span>

                        </div>

                    </div>


                    {{-- Admin removal --}}
                    @can('resources.delete')

                        @unless(
                            auth()->user()
                                ->hasRole('sales_rep')
                        )

                            <div class="mt-5
                                    pt-5
                                    border-t
                                    border-[#DAD4C3]">

                                <form method="POST"
                                      action="{{ route(
                                      'resources.destroy',
                                      $resource
                                  ) }}"
                                      onsubmit="return confirm('Remove this resource?');">

                                    @csrf
                                    @method('DELETE')


                                    <button type="submit"
                                            class="w-full
                                               px-4 py-2.5
                                               rounded-lg
                                               border
                                               border-red-200
                                               text-red-600
                                               text-sm">

                                        <i class="fa-solid
                                              fa-trash
                                              mr-1">
                                        </i>

                                        Remove Resource

                                    </button>

                                </form>

                            </div>

                        @endunless

                    @endcan

                </div>

            </div>

        </div>

    </div>


    @include(
        'resources.partials.share-modal'
    )

    @include(
        'resources.partials.share-script'
    )

@endsection
