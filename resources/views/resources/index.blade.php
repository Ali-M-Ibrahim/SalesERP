@extends('layouts.app')

@section('title', 'Resources')

@section('content')

<div class="p-4 md:p-6">

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

                Resources

            </h1>


            <p class="text-sm
                      text-[#62685F]
                      mt-1">

                Catalogues, PDFs, images and sales materials.

            </p>

        </div>


        @can('resources.create')

            <a href="{{ route(
                'resources.create'
            ) }}"
               class="inline-flex
                      items-center
                      justify-center
                      gap-2
                      px-4 py-3
                      rounded-lg
                      bg-[#1E4B43]
                      text-white
                      text-sm
                      font-medium">

                <i class="fa-solid fa-plus"></i>

                Add Resource

            </a>

        @endcan

    </div>



    {{-- =========================================================
         FILTERS
    ========================================================== --}}
    <form method="GET"
          action="{{ route(
              'resources.index'
          ) }}"
          class="bg-white
                 border
                 border-[#DAD4C3]
                 rounded-xl
                 p-4
                 mb-5">

        <div class="grid
                    sm:grid-cols-2
                    lg:grid-cols-4
                    gap-3">

            {{-- Search --}}
            <input type="search"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search resources..."
                   class="w-full
                          rounded-lg
                          border-[#DAD4C3]">


            {{-- Category --}}
            <select name="category"
                    class="w-full
                           rounded-lg
                           border-[#DAD4C3]">

                <option value="">
                    All categories
                </option>


                @foreach(
                    $categories
                    as $category
                )

                    <option value="{{ $category->id }}"
                        @selected(
                            request('category')
                            == $category->id
                        )>

                        {{ $category->name }}

                    </option>

                @endforeach

            </select>


            {{-- Type --}}
            <select name="type"
                    class="w-full
                           rounded-lg
                           border-[#DAD4C3]">

                <option value="">
                    All types
                </option>

                <option value="pdf"
                    @selected(
                        request('type')
                        === 'pdf'
                    )>
                    PDF
                </option>

                <option value="image"
                    @selected(
                        request('type')
                        === 'image'
                    )>
                    Image
                </option>

            </select>


            <div class="flex gap-2">

                <button type="submit"
                        class="flex-1
                               rounded-lg
                               bg-[#1E4B43]
                               text-white
                               text-sm">

                    Filter

                </button>


                <a href="{{ route(
                    'resources.index'
                ) }}"
                   class="inline-flex
                          items-center
                          justify-center
                          rounded-lg
                          border
                          border-[#DAD4C3]
                          px-3">

                    <i class="fa-solid fa-rotate-left"></i>

                </a>

            </div>

        </div>

    </form>



    {{-- =========================================================
         RESOURCE CARDS
    ========================================================== --}}
    <div class="grid
                sm:grid-cols-2
                lg:grid-cols-3
                xl:grid-cols-4
                gap-4">

        @forelse(
            $resources
            as $resource
        )

            <div class="bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl
                        overflow-hidden
                        flex
                        flex-col">


                {{-- Preview --}}
                <a href="{{ route(
                    'resources.show',
                    $resource
                ) }}"
                   class="h-44
                          block
                          bg-[#F1EFE7]
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
                                    h-full
                                    object-cover">

                    @else

                        <div class="w-full
                                    h-full
                                    flex
                                    flex-col
                                    items-center
                                    justify-center">

                            <i class="fa-regular
                                      fa-file-pdf
                                      text-5xl
                                      text-red-600">
                            </i>

                            <p class="text-xs
                                      text-[#62685F]
                                      mt-3">

                                PDF Document

                            </p>

                        </div>

                    @endif

                </a>


                {{-- Details --}}
                <div class="p-4 flex-1">

                    <div class="flex
                                flex-wrap
                                gap-1.5
                                mb-2">

                        @if(
                            $resource
                                ->resourceCategory
                        )

                            <span class="px-2 py-1
                                         rounded-full
                                         bg-[#F1EFE7]
                                         text-[10px]
                                         text-[#62685F]">

                                {{ $resource
                                    ->resourceCategory
                                    ->name }}

                            </span>

                        @endif


                        <span class="px-2 py-1
                                     rounded-full
                                     bg-[#E3ECE7]
                                     text-[#1E4B43]
                                     text-[10px]
                                     uppercase">

                            {{ $resource->file_type }}

                        </span>

                    </div>


                    <a href="{{ route(
                        'resources.show',
                        $resource
                    ) }}"
                       class="font-semibold
                              hover:text-[#1E4B43]">

                        {{ $resource->name }}

                    </a>


                    @if(
                        $resource->description
                    )

                        <p class="text-sm
                                  text-[#62685F]
                                  mt-2
                                  line-clamp-2">

                            {{ $resource->description }}

                        </p>

                    @endif


                    <p class="text-[10px]
                              text-[#62685F]
                              mt-3">

                        @if(
                            $resource->creator
                        )

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


                {{-- Actions --}}
                <div class="grid
                            grid-cols-2
                            gap-2
                            p-3
                            border-t
                            border-[#DAD4C3]">

                    <a href="{{ route(
                        'resources.show',
                        $resource
                    ) }}"
                       class="inline-flex
                              items-center
                              justify-center
                              gap-2
                              rounded-lg
                              border
                              border-[#DAD4C3]
                              px-3 py-2.5
                              text-sm">

                        <i class="fa-regular fa-eye"></i>

                        View

                    </a>


                    @can('resources.share')

                        <button type="button"
                                class="open-resource-share
                                       inline-flex
                                       items-center
                                       justify-center
                                       gap-2
                                       rounded-lg
                                       bg-[#1E4B43]
                                       text-white
                                       px-3 py-2.5
                                       text-sm"
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

        @empty

            <div class="sm:col-span-2
                        lg:col-span-3
                        xl:col-span-4
                        py-14
                        text-center
                        bg-white
                        border
                        border-[#DAD4C3]
                        rounded-xl">

                <i class="fa-regular
                          fa-folder-open
                          text-3xl
                          text-[#62685F]">
                </i>


                <p class="font-medium mt-3">

                    No resources found

                </p>

            </div>

        @endforelse

    </div>



    {{-- PAGINATION --}}
    @if($resources->hasPages())

        <div class="mt-5">

            {{ $resources->links() }}

        </div>

    @endif

</div>


@include(
    'resources.partials.share-modal'
)


@include(
    'resources.partials.share-script'
)

@endsection
