@can('resources.share')

    <div id="resource-share-modal"
         class="hidden
            fixed
            inset-0
            z-[120]
            bg-black/50
            p-4
            overflow-y-auto">

        <div class="min-h-full
                flex
                items-center
                justify-center">

            <div class="bg-white
                    w-full
                    max-w-lg
                    rounded-xl
                    shadow-xl">


                <div class="px-5 py-4
                        border-b
                        border-[#DAD4C3]
                        flex
                        justify-between
                        gap-3">

                    <div>

                        <h3 class="font-semibold">

                            Share Resource

                        </h3>

                        <p id="share-resource-name"
                           class="text-xs
                              text-[#62685F]
                              mt-1">
                        </p>

                    </div>


                    <button type="button"
                            id="close-resource-share">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                <div class="p-5">

                    <label class="block
                              text-sm
                              font-medium
                              mb-1.5">

                        Customer *

                    </label>


                    <select id="share-customer"
                            class="w-full">

                        <option value="">
                            Select customer
                        </option>

                        @foreach($customers as $customer)

                            <option value="{{ $customer->id }}"
                                    data-phone="{{ $customer->phone }}">

                                {{ $customer->name }}

                                @if($customer->phone)
                                    - {{ $customer->phone }}
                                @endif

                            </option>

                        @endforeach

                    </select>


                    <div class="mt-4
                            rounded-lg
                            bg-[#F1EFE7]
                            px-3 py-3
                            text-xs
                            text-[#62685F]">

                        <i class="fa-brands
                              fa-whatsapp
                              text-[#1E4B43]
                              mr-1">
                        </i>

                        The system will record the share and then open WhatsApp.

                    </div>

                </div>


                <div class="px-5 py-4
                        border-t
                        border-[#DAD4C3]
                        flex
                        flex-col-reverse
                        sm:flex-row
                        sm:justify-end
                        gap-2">

                    <button type="button"
                            id="cancel-resource-share"
                            class="px-5 py-3
                               rounded-lg
                               border
                               border-[#DAD4C3]
                               text-sm">

                        Cancel

                    </button>


                    <button type="button"
                            id="confirm-resource-share"
                            class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               px-5 py-3
                               rounded-lg
                               bg-[#1E4B43]
                               text-white
                               text-sm
                               font-medium">

                        <i class="fa-brands fa-whatsapp"></i>

                        Share via WhatsApp

                    </button>

                </div>

            </div>

        </div>

    </div>

@endcan
