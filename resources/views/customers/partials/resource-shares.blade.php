@foreach($resourceShares as $share)

    <div class="px-5 py-4
                border-b
                last:border-b-0
                border-[#DAD4C3]">

        <div class="flex items-start gap-3">

            {{-- Icon --}}
            <div class="w-10 h-10
                        rounded-lg
                        bg-[#E9E4D6]
                        flex
                        items-center
                        justify-center
                        shrink-0">

                @if($share->resource?->file_type === 'pdf')

                    <i class="fa-regular
                              fa-file-pdf
                              text-red-600">
                    </i>

                @elseif($share->resource?->file_type === 'image')

                    <i class="fa-regular
                              fa-image
                              text-[#1E4B43]">
                    </i>

                @else

                    <i class="fa-regular fa-file"></i>

                @endif

            </div>


            {{-- Information --}}
            <div class="flex-1 min-w-0">

                <p class="font-medium text-sm">

                    {{ $share->resource?->name ?? 'Resource' }}

                </p>


                <div class="flex
                            flex-wrap
                            items-center
                            gap-1
                            text-xs
                            text-[#62685F]
                            mt-1">

                    @if($share->resource?->resourceCategory)

                        <span>

                            {{ $share->resource
                                ->resourceCategory
                                ->name }}

                        </span>

                    @endif


                    @if($share->method)

                        <span>·</span>

                        <span>

                            {{ ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $share->method
                                )
                            ) }}

                        </span>

                    @endif

                </div>


                <p class="text-xs
                          text-[#62685F]
                          mt-2">

                    @if($share->sharedBy)

                        Shared by {{ $share->sharedBy->name }}

                    @endif


                    @if($share->shared_at)

                        ·

                        {{ $share->shared_at->format('d M Y H:i') }}

                    @endif

                </p>

            </div>

        </div>

    </div>

@endforeach
