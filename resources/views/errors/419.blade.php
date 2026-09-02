<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Session Expired</title>

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
                    bg-orange-50
                    flex
                    items-center
                    justify-center
                    mb-5">

            <i class="fa-regular
                      fa-clock
                      text-2xl
                      text-orange-600">
            </i>

        </div>


        <p class="text-sm
                  font-semibold
                  text-orange-600
                  mb-2">

            Error 419

        </p>


        <h1 class="text-2xl
                   font-bold
                   text-gray-900">

            Session Expired

        </h1>


        <p class="mt-3
                  text-sm
                  text-gray-500">

            Your session has expired.
            Please log in again to continue.

        </p>


        <div class="mt-7 space-y-3">

            @auth

                {{-- Logout and return to login --}}
                <form method="POST"
                      action="{{ route('logout') }}">

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

            @else

                {{-- Session already gone --}}
                <a href="{{ route('login') }}"
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

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Go to Login

                </a>

            @endauth

        </div>

    </div>

</div>

</body>
</html>
