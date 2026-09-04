<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1, viewport-fit=cover">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">


    {{-- =========================================================
         TITLE
    ========================================================== --}}
    <title>
        @yield('title', config('app.name', 'Sales ERP'))
    </title>


    {{-- =========================================================
         FAVICON
    ========================================================== --}}
    <link rel="icon"
          type="image/png"
          sizes="32x32"
          href="{{ asset('assets/images/icons/favicon-32x32.png') }}?v2">

    <link rel="icon"
          type="image/png"
          sizes="16x16"
          href="{{ asset('assets/images/icons/favicon-16x16.png') }}?v2">

    <link rel="shortcut icon"
          type="image/png"
          href="{{ asset('assets/images/icons/favicon-32x32.png') }}?v2">


    {{-- =========================================================
         APPLE / IOS ICON
    ========================================================== --}}
    <link rel="apple-touch-icon"
          sizes="180x180"
          href="{{ asset('assets/images/icons/apple-icon-180x180.png') }}">


    {{-- =========================================================
         PWA
    ========================================================== --}}
    <link rel="manifest"
          href="{{ asset('build/manifest.webmanifest') }}">

    <meta name="theme-color"
          content="#000000">

    <meta name="mobile-web-app-capable"
          content="yes">

    <meta name="apple-mobile-web-app-capable"
          content="yes">

    <meta name="apple-mobile-web-app-status-bar-style"
          content="black">

    <meta name="apple-mobile-web-app-title"
          content="{{ config('app.name', 'Sales ERP') }}">


    {{-- =========================================================
         FONT AWESOME
    ========================================================== --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


    {{-- =========================================================
         VITE
    ========================================================== --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- =========================================================
         PAGE SPECIFIC STYLES
    ========================================================== --}}
    @stack('styles')

</head>

<body class="bg-[#F1EFE7] text-[#1C201D] antialiased">

<div class="min-h-screen flex">

    {{-- Desktop Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Area --}}
    <div class="flex-1 min-w-0">

        {{-- Header --}}
        @include('partials.header')

        {{-- Page Content --}}
        <main class="pb-24 md:pb-6">

            @if(session('success'))
                <div class="mx-4 md:mx-6 mt-4
                            rounded-lg
                            border border-green-200
                            bg-green-50
                            px-4 py-3
                            text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 md:mx-6 mt-4
                            rounded-lg
                            border border-red-200
                            bg-red-50
                            px-4 py-3
                            text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

{{-- Mobile Bottom Navigation --}}
@include('partials.mobile-navigation')

@stack('scripts')

{{-- Global Loader --}}
{{-- =========================================================
     GLOBAL FULL SCREEN LOADER
========================================================== --}}
<div id="global-loader"
     class="fixed inset-0 z-[999999] hidden">

    {{-- Full screen background --}}
    <div class="absolute inset-0 bg-white"></div>

    {{-- Loader content --}}
    <div class="relative
                z-10
                flex
                h-screen
                w-screen
                items-center
                justify-center">

        <div class="text-center">

            {{-- Spinner --}}
            <div class="mx-auto
                        h-12
                        w-12
                        animate-spin
                        rounded-full
                        border-4
                        border-gray-200
                        border-t-black">
            </div>

            {{-- Loading text --}}
            <div class="mt-5 flex items-center justify-center">

                <span class="text-base
                             font-semibold
                             text-black">

                    Loading

                </span>

                <span class="loading-dots
                             ml-1
                             text-base
                             font-bold
                             text-black">
                </span>

            </div>

            <p class="mt-2 text-xs text-gray-500">
                Please wait
            </p>

        </div>

    </div>

</div>


<style>
    /*
     * Animated Loading...
     */
    .loading-dots::after {
        content: '';
        animation: loadingDots 1.4s infinite;
    }

    @keyframes loadingDots {
        0% {
            content: '';
        }

        25% {
            content: '.';
        }

        50% {
            content: '..';
        }

        75%,
        100% {
            content: '...';
        }
    }
</style>

<script>
    function showLoader() {
        const loader = document.getElementById('global-loader');

        if (!loader) {
            return;
        }

        loader.classList.remove('hidden');
        loader.classList.add('flex');
    }

    function hideLoader() {
        const loader = document.getElementById('global-loader');

        if (!loader) {
            return;
        }

        loader.classList.add('hidden');
        loader.classList.remove('flex');
    }


    document.addEventListener('DOMContentLoaded', function () {

        /*
         * Normal form submissions
         */
        document.addEventListener('submit', function (event) {

            const form = event.target;

            /*
             * Add data-no-loader to forms
             * where you don't want the loader.
             */
            if (form.hasAttribute('data-no-loader')) {
                return;
            }

            showLoader();
        });


        /*
         * Normal links
         */
        document.addEventListener('click', function (event) {

            const link = event.target.closest('a');

            if (!link) {
                return;
            }

            /*
             * Ignore links that should not trigger loader.
             */
            if (
                link.hasAttribute('data-no-loader') ||
                link.target === '_blank' ||
                link.hasAttribute('download') ||
                link.getAttribute('href') === '#' ||
                link.getAttribute('href')?.startsWith('javascript:') ||
                link.getAttribute('href')?.startsWith('mailto:') ||
                link.getAttribute('href')?.startsWith('tel:')
            ) {
                return;
            }

            showLoader();
        });


        /*
         * Hide loader when browser restores page
         * using back/forward cache.
         */
        window.addEventListener('pageshow', function () {
            hideLoader();
        });

    });
</script>

<script>
    window.showLoader = function () {
        const loader = document.getElementById('global-loader');

        if (!loader) {
            return;
        }

        loader.classList.remove('hidden');

        /*
         * Prevent scrolling while loading.
         */
        document.body.style.overflow = 'hidden';
    };


    window.hideLoader = function () {
        const loader = document.getElementById('global-loader');

        if (!loader) {
            return;
        }

        loader.classList.add('hidden');

        document.body.style.overflow = '';
    };


    document.addEventListener('DOMContentLoaded', function () {

        /*
         * Show loader when submitting a normal form.
         */
        document.addEventListener('submit', function (event) {

            const form = event.target;

            if (form.hasAttribute('data-no-loader')) {
                return;
            }

            showLoader();
        });


        /*
         * Show loader when navigating to another page.
         */
        document.addEventListener('click', function (event) {

            const link = event.target.closest('a');

            if (!link) {
                return;
            }

            const href = link.getAttribute('href');

            /*
             * Ignore links that don't navigate away.
             */
            if (
                link.hasAttribute('data-no-loader') ||
                link.target === '_blank' ||
                link.hasAttribute('download') ||
                !href ||
                href === '#' ||
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                href.startsWith('mailto:') ||
                href.startsWith('tel:')
            ) {
                return;
            }

            showLoader();
        });


        /*
         * Important for browser back/forward navigation.
         */
        window.addEventListener('pageshow', function () {
            hideLoader();
        });

    });
</script>
</body>
</html>
