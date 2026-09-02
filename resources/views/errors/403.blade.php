<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Access Denied</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-gray-100">

<div class="min-h-screen
            flex
            items-center
            justify-center
            px-4">

    <div class="w-full
                max-w-md
                bg-white
                border
                border-gray-200
                rounded-2xl
                shadow-sm
                p-8
                text-center">

        {{-- Icon --}}
        <div class="w-16
                    h-16
                    mx-auto
                    rounded-full
                    bg-red-50
                    flex
                    items-center
                    justify-center
                    mb-5">

            <i class="fa-solid
                      fa-lock
                      text-2xl
                      text-red-600"></i>

        </div>

        {{-- Error --}}
        <p class="text-sm
                  font-semibold
                  text-red-600
                  mb-2">

            Error 403

        </p>

        <h1 class="text-2xl
                   font-bold
                   text-gray-900">

            Access Denied

        </h1>

        <p class="mt-3
                  text-sm
                  text-gray-500">

            You do not have permission to access this page.

        </p>


        {{-- Logout --}}
        @auth

            <form method="POST"
                  action="{{ route('logout') }}"
                  class="mt-7">

                @csrf

                <button type="submit"
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
                               hover:bg-gray-800
                               transition">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Logout

                </button>

            </form>

        @endauth

    </div>

</div>

</body>
</html>
