@extends('layouts.guest')

@section('title', 'Login')

@section('content')

    <div class="min-h-screen
                flex
                items-center
                justify-center
                bg-[#F5F5F5]
                px-4
                py-8">

        <div class="w-full max-w-md">

            {{-- =====================================================
                 LOGIN CARD
            ====================================================== --}}
            <div class="bg-white
                        border
                        border-gray-200
                        rounded-2xl
                        shadow-sm
                        overflow-hidden">


                {{-- =================================================
                     HEADER
                ================================================== --}}
                <div class="bg-black
                            px-6
                            py-8
                            text-center">

                    {{-- Logo --}}
                    <img
                        src="{{ asset('/logo.png') }}"
                        alt="{{ config('app.name') }}"
                        class="mx-auto
                               max-w-[190px]
                               max-h-[80px]
                               w-auto
                               h-auto"
                    >


                    <h1 class="mt-5
                               text-xl
                               font-bold
                               text-white">

                        Welcome Back

                    </h1>


                    <p class="mt-1
                              text-sm
                              text-gray-300">

                        Sign in to continue to your account.

                    </p>

                </div>


                {{-- =================================================
                     BODY
                ================================================== --}}
                <div class="p-6 sm:p-8">


                    {{-- =============================================
                         SESSION STATUS
                    ============================================== --}}
                    @if (session('status'))

                        <div class="mb-5
                                    rounded-lg
                                    border
                                    border-green-200
                                    bg-green-50
                                    px-4
                                    py-3
                                    text-sm
                                    text-green-700">

                            <div class="flex items-start gap-2">

                                <i class="fa-solid
                                          fa-circle-check
                                          mt-0.5">
                                </i>

                                <span>
                                    {{ session('status') }}
                                </span>

                            </div>

                        </div>

                    @endif


                    {{-- =============================================
                         VALIDATION ERRORS
                    ============================================== --}}
                    @if ($errors->any())

                        <div class="mb-5
                                    rounded-lg
                                    border
                                    border-red-200
                                    bg-red-50
                                    px-4
                                    py-3">

                            <div class="flex
                                        items-start
                                        gap-2">

                                <i class="fa-solid
                                          fa-circle-exclamation
                                          mt-0.5
                                          text-red-500">
                                </i>


                                <div class="text-sm
                                            text-red-700">

                                    @foreach ($errors->all() as $error)

                                        <p>
                                            {{ $error }}
                                        </p>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- =============================================
                         LOGIN FORM
                    ============================================== --}}
                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- =========================================
                             EMAIL
                        ========================================== --}}
                        <div>

                            <label
                                for="email"
                                class="block
                                       text-sm
                                       font-medium
                                       text-black
                                       mb-1.5"
                            >

                                Email Address

                            </label>


                            <div class="relative">

                                {{-- Icon --}}
                                <div class="absolute
                                            inset-y-0
                                            left-0
                                            pl-3
                                            flex
                                            items-center
                                            pointer-events-none
                                            text-black">

                                    <i class="fa-regular fa-envelope"></i>

                                </div>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Enter your email"
                                    class="w-full
                                           rounded-lg
                                           border
                                           border-gray-300
                                           bg-white
                                           py-3
                                           pl-10
                                           pr-4
                                           text-sm
                                           text-black
                                           placeholder:text-gray-400
                                           transition
                                           focus:border-black
                                           focus:ring-black"
                                >

                            </div>

                        </div>


                        {{-- =========================================
                             PASSWORD
                        ========================================== --}}
                        <div>

                            <label
                                for="password"
                                class="block
                                       text-sm
                                       font-medium
                                       text-black
                                       mb-1.5"
                            >

                                Password

                            </label>


                            <div class="relative">

                                {{-- Lock Icon --}}
                                <div class="absolute
                                            inset-y-0
                                            left-0
                                            pl-3
                                            flex
                                            items-center
                                            pointer-events-none
                                            text-black">

                                    <i class="fa-solid fa-lock"></i>

                                </div>


                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="w-full
                                           rounded-lg
                                           border
                                           border-gray-300
                                           bg-white
                                           py-3
                                           pl-10
                                           pr-12
                                           text-sm
                                           text-black
                                           placeholder:text-gray-400
                                           transition
                                           focus:border-black
                                           focus:ring-black"
                                >


                                {{-- Show / Hide Password --}}
                                <button
                                    type="button"
                                    id="toggle-password"
                                    aria-label="Show password"
                                    class="absolute
                                           inset-y-0
                                           right-0
                                           pr-3
                                           flex
                                           items-center
                                           text-black
                                           hover:text-gray-600
                                           transition"
                                >

                                    <i
                                        id="password-icon"
                                        class="fa-regular fa-eye"
                                    ></i>

                                </button>

                            </div>

                        </div>


                        {{-- =========================================
                             REMEMBER / FORGOT PASSWORD
                        ========================================== --}}
                        <div class="flex
                                    items-center
                                    justify-between
                                    gap-4">

                            {{-- Remember Me --}}
                            <label class="inline-flex
                                          items-center
                                          gap-2
                                          cursor-pointer">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="rounded
                                           border-gray-300
                                           text-black
                                           focus:ring-black"
                                >

                                <span class="text-sm
                                             text-gray-600">

                                    Remember me

                                </span>

                            </label>



                        </div>


                        {{-- =========================================
                             SUBMIT
                        ========================================== --}}
                        <button
                            type="submit"
                            class="w-full
                                   flex
                                   items-center
                                   justify-center
                                   gap-2
                                   rounded-lg
                                   bg-black
                                   px-4
                                   py-3
                                   text-sm
                                   font-semibold
                                   text-white
                                   transition
                                   hover:bg-gray-800
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-black
                                   focus:ring-offset-2"
                        >

                            <i class="fa-solid
                                      fa-right-to-bracket">
                            </i>

                            Sign In

                        </button>

                    </form>

                </div>

            </div>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}
            <p class="mt-5
                      text-center
                      text-xs
                      text-gray-500">

                © {{ date('Y') }}
                {{ config('app.name') }}.
                All rights reserved.

            </p>

        </div>

    </div>


    {{-- =============================================================
         PASSWORD VISIBILITY
    ============================================================== --}}
    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const button =
                    document.getElementById(
                        'toggle-password'
                    );

                const password =
                    document.getElementById(
                        'password'
                    );

                const icon =
                    document.getElementById(
                        'password-icon'
                    );


                if (!button || !password || !icon) {
                    return;
                }


                button.addEventListener(
                    'click',
                    function () {

                        const isHidden =
                            password.type === 'password';


                        /*
                         * Change password input type.
                         */
                        password.type =
                            isHidden
                                ? 'text'
                                : 'password';


                        /*
                         * Change icon.
                         */
                        if (isHidden) {

                            icon.classList.remove(
                                'fa-eye'
                            );

                            icon.classList.add(
                                'fa-eye-slash'
                            );

                            button.setAttribute(
                                'aria-label',
                                'Hide password'
                            );

                        } else {

                            icon.classList.remove(
                                'fa-eye-slash'
                            );

                            icon.classList.add(
                                'fa-eye'
                            );

                            button.setAttribute(
                                'aria-label',
                                'Show password'
                            );

                        }

                    }
                );

            }
        );

    </script>

@endsection
