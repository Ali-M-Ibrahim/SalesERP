@extends('layouts.app')

@section('title', 'Performance Settings')

@section('content')

    <div class="p-4 sm:p-6 lg:p-8">

        <div class="mx-auto max-w-3xl">

            <div class="mb-6">

                <h1 class="text-2xl
                       font-bold
                       text-gray-900">

                    Sales Performance Settings

                </h1>

                <p class="mt-1
                      text-sm
                      text-gray-500">

                    Configure how each KPI contributes
                    to the Field Performance Score.

                </p>

            </div>


            @if(session('success'))

                <div class="mb-5
                        rounded-lg
                        border
                        border-green-200
                        bg-green-50
                        px-4
                        py-3
                        text-sm
                        text-green-700">

                    {{ session('success') }}

                </div>

            @endif


            @if($errors->any())

                <div class="mb-5
                        rounded-lg
                        border
                        border-red-200
                        bg-red-50
                        px-4
                        py-3
                        text-sm
                        text-red-700">

                    @foreach($errors->all() as $error)

                        <p>{{ $error }}</p>

                    @endforeach

                </div>

            @endif


            <form
                method="POST"
                action="{{
                route(
                    'admin.settings.sales-performance.update'
                )
            }}"
            >

                @csrf
                @method('PUT')


                <div class="overflow-hidden
                        rounded-xl
                        border
                        border-gray-200
                        bg-white">

                    <div class="border-b
                            border-gray-200
                            px-5
                            py-4">

                        <div class="grid
                                grid-cols-12
                                gap-4
                                text-xs
                                font-semibold
                                uppercase
                                text-gray-500">

                            <div class="col-span-6">
                                KPI
                            </div>

                            <div class="col-span-3
                                    text-center">
                                Active
                            </div>

                            <div class="col-span-3
                                    text-right">
                                Weight
                            </div>

                        </div>

                    </div>


                    @foreach($settings as $setting)

                        <div class="border-b
                                border-gray-100
                                px-5
                                py-4
                                last:border-0">

                            <div class="grid
                                    grid-cols-12
                                    items-center
                                    gap-4">

                                <div class="col-span-6">

                                    <p class="font-medium
                                          text-gray-900">

                                        {{ $setting->label }}

                                    </p>

                                </div>


                                <div class="col-span-3
                                        text-center">

                                    <input
                                        type="checkbox"
                                        name="active[{{ $setting->id }}]"
                                        value="1"
                                        @checked(
                                            old(
                                                'active.'
                                                . $setting->id,
                                                $setting->is_active
                                            )
                                        )
                                        class="rounded
                                           border-gray-300
                                           text-black
                                           focus:ring-black"
                                    >

                                </div>


                                <div class="col-span-3">

                                    <div class="relative">

                                        <input
                                            type="number"
                                            name="weights[{{ $setting->id }}]"
                                            value="{{
                                            old(
                                                'weights.'
                                                . $setting->id,
                                                $setting->weight
                                            )
                                        }}"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            required
                                            class="w-full
                                               rounded-lg
                                               border-gray-300
                                               pr-8
                                               text-right
                                               text-sm
                                               focus:border-black
                                               focus:ring-black"
                                        >

                                        <span class="absolute
                                                 inset-y-0
                                                 right-3
                                                 flex
                                                 items-center
                                                 text-sm
                                                 text-gray-400">

                                        %

                                    </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach


                    {{-- Total --}}
                    <div class="bg-gray-50
                            px-5
                            py-4">

                        <div class="flex
                                items-center
                                justify-between">

                        <span class="text-sm
                                     font-semibold
                                     text-gray-700">

                            Active Weight Total

                        </span>

                            <span id="weight-total"
                                  class="text-lg
                                     font-bold
                                     text-gray-900">

                            0%

                        </span>

                        </div>

                        <p id="weight-message"
                           class="mt-1
                              text-right
                              text-xs
                              text-gray-500">

                            Active KPI weights must total 100%.

                        </p>

                    </div>

                </div>


                <div class="mt-5
                        flex
                        justify-end">

                    <button
                        type="submit"
                        id="save-weights"
                        class="rounded-lg
                           bg-black
                           px-6
                           py-3
                           text-sm
                           font-semibold
                           text-white
                           hover:bg-gray-800">

                        Save Settings

                    </button>

                </div>

            </form>

        </div>

    </div>


    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    const weightInputs =
                        document.querySelectorAll(
                            'input[name^="weights"]'
                        );

                    const activeInputs =
                        document.querySelectorAll(
                            'input[name^="active"]'
                        );

                    const totalElement =
                        document.getElementById(
                            'weight-total'
                        );

                    const messageElement =
                        document.getElementById(
                            'weight-message'
                        );


                    function calculateTotal() {

                        let total = 0;


                        activeInputs.forEach(
                            function (checkbox) {

                                if (!checkbox.checked) {
                                    return;
                                }


                                const match =
                                    checkbox.name.match(
                                        /active\[(.+)\]/
                                    );


                                if (!match) {
                                    return;
                                }


                                const id =
                                    match[1];


                                const input =
                                    document.querySelector(
                                        `input[name="weights[${id}]"]`
                                    );


                                if (input) {

                                    total +=
                                        parseFloat(
                                            input.value
                                        ) || 0;

                                }

                            }
                        );


                        totalElement.textContent =
                            total.toFixed(2)
                                .replace('.00', '')
                            + '%';


                        if (
                            Math.abs(
                                total - 100
                            ) < 0.01
                        ) {

                            totalElement.classList.remove(
                                'text-red-600'
                            );

                            totalElement.classList.add(
                                'text-green-600'
                            );

                            messageElement.textContent =
                                'Weight configuration is valid.';

                        } else {

                            totalElement.classList.remove(
                                'text-green-600'
                            );

                            totalElement.classList.add(
                                'text-red-600'
                            );

                            messageElement.textContent =
                                'Active KPI weights must total 100%.';

                        }

                    }


                    weightInputs.forEach(
                        function (input) {

                            input.addEventListener(
                                'input',
                                calculateTotal
                            );

                        }
                    );


                    activeInputs.forEach(
                        function (input) {

                            input.addEventListener(
                                'change',
                                calculateTotal
                            );

                        }
                    );


                    calculateTotal();

                }
            );

        </script>

    @endpush

@endsection
