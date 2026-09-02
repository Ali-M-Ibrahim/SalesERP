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


<body class="font-sans antialiased">

@yield('content')

</body>

</html>
