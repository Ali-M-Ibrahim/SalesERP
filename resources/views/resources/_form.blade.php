<div class="space-y-5">


    {{-- CATEGORY --}}
    <div>

        <label class="block
                      text-sm
                      font-medium
                      mb-1.5">

            Category *

        </label>


        <select name="resource_category_id"
                required
                class="w-full
                       rounded-lg
                       border-[#DAD4C3]">

            <option value="">
                Select category
            </option>


            @foreach($categories as $category)

                <option value="{{ $category->id }}"
                    @selected(
                        old(
                            'resource_category_id',
                            $resource
                                ->resource_category_id
                                ?? null
                        )
                        == $category->id
                    )>

                    {{ $category->name }}

                </option>

            @endforeach

        </select>


        @error('resource_category_id')

        <p class="text-red-600
                      text-xs
                      mt-1">

            {{ $message }}

        </p>

        @enderror

    </div>


    {{-- NAME --}}
    <div>

        <label class="block
                      text-sm
                      font-medium
                      mb-1.5">

            Resource Name *

        </label>


        <input type="text"
               name="name"
               required
               maxlength="255"
               value="{{ old(
                    'name',
                    $resource->name ?? ''
               ) }}"
               placeholder="Example: 2026 Product Catalogue"
               class="w-full
                      rounded-lg
                      border-[#DAD4C3]">


        @error('name')

        <p class="text-red-600
                      text-xs
                      mt-1">

            {{ $message }}

        </p>

        @enderror

    </div>


    {{-- DESCRIPTION --}}
    <div>

        <label class="block
                      text-sm
                      font-medium
                      mb-1.5">

            Description

        </label>


        <textarea name="description"
                  rows="4"
                  maxlength="3000"
                  placeholder="Short description..."
                  class="w-full
                         rounded-lg
                         border-[#DAD4C3]">{{ old(
                            'description',
                            $resource->description ?? ''
                         ) }}</textarea>


        @error('description')

        <p class="text-red-600
                      text-xs
                      mt-1">

            {{ $message }}

        </p>

        @enderror

    </div>


    {{-- FILE --}}
    <div>

        <label class="block
                      text-sm
                      font-medium
                      mb-1.5">

            File
            @if(!isset($resource))
                *
            @endif

        </label>


        @if(
            isset($resource)
            && $resource->file_path
        )

            <div class="rounded-lg
                        border
                        border-[#DAD4C3]
                        bg-[#F1EFE7]
                        p-3
                        mb-3">

                <div class="flex
                            items-center
                            gap-3">

                    <div class="w-10 h-10
                                rounded-lg
                                bg-white
                                flex
                                items-center
                                justify-center">

                        @if(
                            $resource->file_type
                            === 'pdf'
                        )

                            <i class="fa-regular
                                      fa-file-pdf
                                      text-red-600">
                            </i>

                        @else

                            <i class="fa-regular
                                      fa-image
                                      text-[#1E4B43]">
                            </i>

                        @endif

                    </div>


                    <div>

                        <p class="text-sm
                                  font-medium">

                            Current file

                        </p>

                        <p class="text-xs
                                  text-[#62685F]">

                            {{ strtoupper(
                                $resource->file_type
                            ) }}

                        </p>

                    </div>

                </div>

            </div>

        @endif


        <input type="file"
               name="file"
               accept=".pdf,.jpg,.jpeg,.png,.webp"
               @required(!isset($resource))
               class="w-full
                      rounded-lg
                      border
                      border-[#DAD4C3]
                      p-2.5
                      bg-white">


        <p class="text-xs
                  text-[#62685F]
                  mt-1.5">

            PDF, JPG, PNG or WEBP.
            Maximum 10 MB.

            @if(isset($resource))
                Leave empty to keep the existing file.
            @endif

        </p>


        @error('file')

        <p class="text-red-600
                      text-xs
                      mt-1">

            {{ $message }}

        </p>

        @enderror

    </div>

</div>
