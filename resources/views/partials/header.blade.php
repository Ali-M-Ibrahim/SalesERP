<header class="sticky
               top-0
               z-40
               h-20
               bg-[#F1EFE7]/95
               backdrop-blur
               border-b
               border-[#DAD4C3]
               flex
               items-center
               justify-between
               px-4
               md:px-6">

    {{-- User --}}
    <div>

        <p class="text-xs text-[#62685F]">
            Welcome back
        </p>

        <h2 class="font-semibold text-base md:text-lg">
            {{ auth()->user()->name }}
        </h2>

    </div>


    <div class="flex items-center gap-2">

        {{-- Owner Badge --}}
        @role('owner')

        <span class="hidden sm:inline-flex
                         bg-[#FBEAD9]
                         text-[#D6772F]
                         rounded-full
                         px-3 py-1
                         text-[10px]
                         font-semibold
                         uppercase">

                View Only

            </span>

        @endrole


        {{-- Notification --}}
        <button type="button"
                class="relative
                       w-10 h-10
                       rounded-full
                       border
                       border-[#DAD4C3]
                       bg-white
                       flex
                       items-center
                       justify-center
                       hover:bg-[#E9E4D6]">

            <i class="fa-regular fa-bell"></i>

            {{-- Temporary notification indicator --}}
            <span class="absolute
                         top-1.5
                         right-1.5
                         w-2 h-2
                         rounded-full
                         bg-[#D6772F]">
            </span>

        </button>


        {{-- User Dropdown --}}
        <div class="relative"
             x-data="{ open: false }">

            <button type="button"
                    @click="open = !open"
                    class="w-10 h-10
                           rounded-full
                           bg-[#1E4B43]
                           text-white
                           flex
                           items-center
                           justify-center
                           font-semibold">

                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

            </button>


            <div x-show="open"
                 @click.outside="open = false"
                 x-transition
                 style="display:none;"
                 class="absolute
                        right-0
                        mt-2
                        w-52
                        bg-white
                        rounded-lg
                        shadow-lg
                        border
                        border-[#DAD4C3]
                        overflow-hidden">

                <div class="px-4 py-3 border-b border-[#DAD4C3]">

                    <p class="text-sm font-medium">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-[#62685F]">
                        {{ auth()->user()->email }}
                    </p>

                </div>



                <form method="POST"
                      action="{{ route('logout') }}">

                    @csrf

                    <button type="submit"
                            class="w-full
                                   flex items-center gap-2
                                   px-4 py-3
                                   text-sm
                                   text-red-600
                                   hover:bg-red-50">

                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </div>

</header>
