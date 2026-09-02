<div class="grid md:grid-cols-2 gap-4">

    {{-- Type --}}
    <div>
        <label class="block text-sm font-medium mb-1.5">
            Type
        </label>

        <select name="type"
                class="w-full rounded-lg border-[#DAD4C3]">

            <option value="lead"
                @selected(old('type', $customer->type ?? 'lead') === 'lead')>
                Lead
            </option>

            <option value="customer"
                @selected(old('type', $customer->type ?? '') === 'customer')>
                Customer
            </option>

        </select>

        @error('type')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror
    </div>


    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium mb-1.5">
            Customer Name *
        </label>

        <input type="text"
               name="name"
               value="{{ old('name', $customer->name ?? '') }}"
               class="w-full rounded-lg border-[#DAD4C3]"
               required>

        @error('name')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror
    </div>


    {{-- Phone --}}
    <div>
        <label class="block text-sm font-medium mb-1.5">
            Phone
        </label>

        <input type="tel"
               name="phone"
               inputmode="tel"
               value="{{ old('phone', $customer->phone ?? '') }}"
               class="w-full rounded-lg border-[#DAD4C3]">

        @error('phone')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror
    </div>


    {{-- Email --}}
    <div>
        <label class="block text-sm font-medium mb-1.5">
            Email
        </label>

        <input type="email"
               name="email"
               inputmode="email"
               value="{{ old('email', $customer->email ?? '') }}"
               class="w-full rounded-lg border-[#DAD4C3]">

        @error('email')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror
    </div>


    {{-- Address --}}
    <div class="md:col-span-2">

        <label class="block text-sm font-medium mb-1.5">
            Written Address
        </label>

        <textarea name="address"
                  rows="3"
                  class="w-full rounded-lg border-[#DAD4C3]"
                  placeholder="Building, street, area, city...">{{ old('address', $customer->address ?? '') }}</textarea>

        @error('address')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror

    </div>


    {{-- =====================================================
         LOCATION
    ====================================================== --}}
    <div class="md:col-span-2">

        <div class="bg-[#F1EFE7]
                    border border-[#DAD4C3]
                    rounded-xl
                    p-4">

            <div class="flex flex-col
                        sm:flex-row
                        sm:items-center
                        justify-between
                        gap-3">

                <div>

                    <h3 class="font-medium text-sm">
                        Customer Location
                    </h3>

                    <p class="text-xs text-[#62685F] mt-1">
                        Pin the customer's current location using this device.
                    </p>

                </div>


                <button type="button"
                        id="get-current-location"
                        class="w-full sm:w-auto
                               inline-flex
                               items-center
                               justify-center
                               gap-2
                               px-4 py-3
                               rounded-lg
                               bg-[#1E4B43]
                               text-white
                               text-sm
                               font-medium">

                    <i class="fa-solid fa-location-crosshairs"></i>

                    <span id="location-button-text">

                        @if(
                            old('latitude', $customer->latitude ?? null) &&
                            old('longitude', $customer->longitude ?? null)
                        )
                            Update Location
                        @else
                            Pin Current Location
                        @endif

                    </span>

                </button>

            </div>


            {{-- Location Status --}}
            <div id="location-success"
                 class="{{ old('latitude', $customer->latitude ?? null) && old('longitude', $customer->longitude ?? null) ? '' : 'hidden' }}
                        mt-4
                        rounded-lg
                        bg-[#E3ECE7]
                        text-[#1E4B43]
                        px-4 py-3">

                <div class="flex items-start gap-2">

                    <i class="fa-solid
                              fa-circle-check
                              mt-0.5">
                    </i>

                    <div>

                        <p class="text-sm font-medium">
                            Location pinned
                        </p>

                        <p class="text-xs mt-1">
                            The coordinates will be saved with this customer.
                        </p>

                        @if(
                            isset($customer) &&
                            $customer->location_updated_at
                        )

                            <p class="text-xs mt-1 opacity-80">
                                Last updated:
                                {{ $customer->location_updated_at->format('d M Y H:i') }}
                            </p>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Error --}}
            <div id="location-error"
                 class="hidden
                        mt-4
                        rounded-lg
                        bg-red-50
                        border
                        border-red-200
                        text-red-700
                        px-4 py-3
                        text-sm">
            </div>


            {{-- Accuracy --}}
            <div id="location-accuracy-container"
                 class="hidden
                        mt-3
                        text-xs
                        text-[#62685F]">

                GPS accuracy:

                <span id="location-accuracy"
                      class="font-medium">
                </span>

                meters

            </div>


            {{-- Hidden coordinates --}}
            <input type="hidden"
                   name="latitude"
                   id="latitude"
                   value="{{ old('latitude', $customer->latitude ?? '') }}">

            <input type="hidden"
                   name="longitude"
                   id="longitude"
                   value="{{ old('longitude', $customer->longitude ?? '') }}">

        </div>


        @error('latitude')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror

        @error('longitude')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror

    </div>


    {{-- Stand --}}
    <div class="md:col-span-2">

        <label class="flex items-center gap-3
                      cursor-pointer">

            <input type="checkbox"
                   name="has_stand"
                   value="1"
                   class="rounded border-[#DAD4C3]"
                @checked(
                    old(
                        'has_stand',
                        $customer->has_stand ?? false
                    )
                )>

            <span>

                <span class="block text-sm font-medium">
                    Customer has our stand
                </span>

                <span class="block text-xs text-[#62685F]">
                    Enable if one of our stands is installed at this customer.
                </span>

            </span>

        </label>

    </div>



    {{-- Sales Representative --}}
    @role('admin')

    <div class="md:col-span-2">

        <label class="block text-sm font-medium mb-1.5">
            Sales Representative
        </label>

        <select name="sales_rep_id"
                class="w-full rounded-lg border-[#DAD4C3]">

            <option value="">
                Unassigned
            </option>

            @foreach($salesReps as $rep)

                <option value="{{ $rep->id }}"
                    @selected(
                        old(
                            'sales_rep_id',
                            isset($customer)
                                ? optional($customer->currentAssignment)->sales_rep_id
                                : null
                        ) == $rep->id
                    )>

                    {{ $rep->name }}

                </option>

            @endforeach

        </select>

        @error('sales_rep_id')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
        @enderror

    </div>

    @endrole

</div>


{{-- =========================================================
     GEOLOCATION
========================================================== --}}
@once

    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    const button =
                        document.getElementById(
                            'get-current-location'
                        );

                    const buttonText =
                        document.getElementById(
                            'location-button-text'
                        );

                    const latitudeInput =
                        document.getElementById(
                            'latitude'
                        );

                    const longitudeInput =
                        document.getElementById(
                            'longitude'
                        );

                    const successBox =
                        document.getElementById(
                            'location-success'
                        );

                    const errorBox =
                        document.getElementById(
                            'location-error'
                        );

                    const accuracyContainer =
                        document.getElementById(
                            'location-accuracy-container'
                        );

                    const accuracyText =
                        document.getElementById(
                            'location-accuracy'
                        );


                    if (!button) {
                        return;
                    }


                    button.addEventListener(
                        'click',
                        function () {

                            errorBox.classList.add(
                                'hidden'
                            );


                            if (!navigator.geolocation) {

                                errorBox.textContent =
                                    'Geolocation is not supported by this device or browser.';

                                errorBox.classList.remove(
                                    'hidden'
                                );

                                return;
                            }


                            const oldHTML =
                                button.innerHTML;


                            button.disabled = true;


                            button.innerHTML = `
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                Getting Location...
                            `;


                            navigator.geolocation
                                .getCurrentPosition(

                                    /*
                                     * Success
                                     */
                                    function (position) {

                                        latitudeInput.value =
                                            position.coords.latitude
                                                .toFixed(7);

                                        longitudeInput.value =
                                            position.coords.longitude
                                                .toFixed(7);


                                        successBox
                                            .classList
                                            .remove('hidden');


                                        accuracyText.textContent =
                                            Math.round(
                                                position.coords.accuracy
                                            );


                                        accuracyContainer
                                            .classList
                                            .remove('hidden');


                                        button.disabled = false;


                                        button.innerHTML = `
                                            <i class="fa-solid fa-location-crosshairs"></i>
                                            Update Location
                                        `;

                                    },


                                    /*
                                     * Error
                                     */
                                    function (error) {

                                        let message =
                                            'Unable to determine your current location.';


                                        switch (error.code) {

                                            case error.PERMISSION_DENIED:

                                                message =
                                                    'Location permission was denied. Please enable location access for this website.';

                                                break;


                                            case error.POSITION_UNAVAILABLE:

                                                message =
                                                    'Your current location is unavailable. Please check GPS/location services.';

                                                break;


                                            case error.TIMEOUT:

                                                message =
                                                    'The location request timed out. Please try again.';

                                                break;

                                        }


                                        errorBox.textContent =
                                            message;


                                        errorBox
                                            .classList
                                            .remove('hidden');


                                        button.disabled = false;

                                        button.innerHTML =
                                            oldHTML;

                                    },


                                    /*
                                     * GPS Options
                                     */
                                    {
                                        enableHighAccuracy:
                                            true,

                                        timeout:
                                            20000,

                                        maximumAge:
                                            0
                                    }

                                );

                        }
                    );

                }
            );

        </script>

    @endpush

@endonce
