<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>Customer Feedback</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-[#F5F4EF]
             text-[#1F2925]">

<div class="min-h-screen
            px-4
            py-8
            md:py-12">

    <div class="max-w-2xl
                mx-auto">


        {{-- Header --}}
        <div class="text-center mb-8">

            <div class="w-14 h-14
                        rounded-full
                        bg-[#1E4B43]
                        text-white
                        flex
                        items-center
                        justify-center
                        mx-auto
                        mb-4">

                <i class="fa-solid
                          fa-comment-dots
                          text-xl">
                </i>

            </div>


            <h1 class="text-2xl
                       md:text-3xl
                       font-bold">

                How was your experience?

            </h1>


            <p class="text-sm
                      text-[#62685F]
                      mt-2">

                Thank you for meeting with

                <strong>
                    {{ $invitation
                        ->visit
                        ?->salesRep
                        ?->name
                        ?? 'our team' }}
                </strong>.

            </p>

        </div>


        {{-- Visit Info --}}
        <div class="bg-white
                    border
                    border-[#DAD4C3]
                    rounded-xl
                    p-4
                    mb-5">

            <div class="grid
                        sm:grid-cols-2
                        gap-4">

                <div>

                    <p class="text-xs
                              text-[#62685F]">

                        Customer

                    </p>

                    <p class="font-medium mt-1">

                        {{ $invitation
                            ->customer
                            ?->name
                            ?? '—' }}

                    </p>

                </div>


                <div>

                    <p class="text-xs
                              text-[#62685F]">

                        Visit Date

                    </p>

                    <p class="font-medium mt-1">

                        {{ $invitation
                            ->visit
                            ?->scheduled_at
                            ?->format(
                                'd M Y H:i'
                            )
                            ?? '—' }}

                    </p>

                </div>

            </div>

        </div>


        {{-- Errors --}}
        @if($errors->any())

            <div class="bg-red-50
                        border
                        border-red-200
                        rounded-lg
                        p-4
                        mb-5
                        text-sm
                        text-red-700">

                Please complete all required questions.

            </div>

        @endif


        <form method="POST"
              action="{{ route(
                  'satisfaction.store',
                  $invitation->token
              ) }}">

            @csrf


            <div class="space-y-4">

                @foreach(
                    $questions
                    as $question
                )

                    <div class="bg-white
                                border
                                border-[#DAD4C3]
                                rounded-xl
                                p-5">

                        <label class="block
                                      font-semibold
                                      text-sm
                                      mb-4">

                            {{ $question->question }}

                            @if($question->is_required)

                                <span class="text-red-600">
                                    *
                                </span>

                            @endif

                        </label>


                        {{-- Rating --}}
                        @if(
                            $question->type
                            === 'rating'
                        )

                            <div class="grid
                                        grid-cols-5
                                        gap-2">

                                @foreach(
                                    [
                                        1 => 'Very Poor',
                                        2 => 'Poor',
                                        3 => 'Average',
                                        4 => 'Good',
                                        5 => 'Excellent',
                                    ]
                                    as $rating => $label
                                )

                                    <label class="cursor-pointer">

                                        <input type="radio"
                                               name="answers[{{ $question->id }}]"
                                               value="{{ $rating }}"
                                               class="peer sr-only"

                                            @checked(
                                                old(
                                                    'answers.'
                                                    . $question->id
                                                )
                                                == $rating
                                            )

                                            @required(
                                                $question
                                                    ->is_required
                                            )>


                                        <div class="border
                                                    border-[#DAD4C3]
                                                    rounded-lg
                                                    py-3
                                                    px-1
                                                    text-center
                                                    peer-checked:bg-[#1E4B43]
                                                    peer-checked:text-white
                                                    peer-checked:border-[#1E4B43]
                                                    transition">

                                            <p class="font-bold">

                                                {{ $rating }}

                                            </p>

                                            <p class="text-[9px]
                                                      mt-1">

                                                {{ $label }}

                                            </p>

                                        </div>

                                    </label>

                                @endforeach

                            </div>


                            {{-- Textarea --}}
                        @elseif(
                            $question->type
                            === 'textarea'
                        )

                            <textarea
                                name="answers[{{ $question->id }}]"
                                rows="4"
                                @required(
                                    $question->is_required
                                )
                                class="w-full
                                       rounded-lg
                                       border-[#DAD4C3]"
                                placeholder="Share your feedback...">{{ old(
                                    'answers.'
                                    . $question->id
                                ) }}</textarea>


                            {{-- Text --}}
                        @else

                            <input type="text"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ old(
                                       'answers.'
                                       . $question->id
                                   ) }}"
                                   @required(
                                       $question->is_required
                                   )
                                   class="w-full
                                          rounded-lg
                                          border-[#DAD4C3]">

                        @endif


                        @error(
                            'answers.'
                            . $question->id
                        )

                        <p class="text-xs
                                      text-red-600
                                      mt-2">

                            {{ $message }}

                        </p>

                        @enderror

                    </div>

                @endforeach

            </div>


            <button type="submit"
                    class="w-full
                           mt-5
                           bg-[#1E4B43]
                           text-white
                           rounded-xl
                           py-3.5
                           font-semibold">

                Submit Feedback

            </button>

        </form>

    </div>

</div>

</body>

</html>
