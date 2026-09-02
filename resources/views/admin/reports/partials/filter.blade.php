<form method="GET"
      action="{{ $action }}"
      class="bg-white
             border
             border-[#DAD4C3]
             rounded-xl
             p-4
             mb-5">

    <div class="grid
                grid-cols-1
                sm:grid-cols-2
                lg:grid-cols-4
                gap-3">

        {{-- From --}}
        <div>

            <label class="block
                          text-xs
                          font-medium
                          text-[#62685F]
                          mb-1.5">

                From

            </label>

            <input type="date"
                   name="from_date"
                   value="{{ request(
                       'from_date',
                       $fromDate->format('Y-m-d')
                   ) }}"
                   class="w-full
                          rounded-lg
                          border-[#DAD4C3]">

        </div>


        {{-- To --}}
        <div>

            <label class="block
                          text-xs
                          font-medium
                          text-[#62685F]
                          mb-1.5">

                To

            </label>

            <input type="date"
                   name="to_date"
                   value="{{ request(
                       'to_date',
                       $toDate->format('Y-m-d')
                   ) }}"
                   class="w-full
                          rounded-lg
                          border-[#DAD4C3]">

        </div>


        {{-- Sales Rep --}}
        <div>

            <label class="block
                          text-xs
                          font-medium
                          text-[#62685F]
                          mb-1.5">

                Sales Representative

            </label>

            <select id="sales_rep_id" name="sales_rep_id"
                    class="w-full
                           rounded-lg
                           border-[#DAD4C3]">

                <option value="">
                    All Sales Representatives
                </option>

                @foreach($salesReps as $rep)

                    <option value="{{ $rep->id }}"
                        @selected(
                            request('sales_rep_id')
                            == $rep->id
                        )>

                        {{ $rep->name }}

                    </option>

                @endforeach

            </select>

        </div>


        <div class="flex
                    items-end
                    gap-2">

            <button type="submit"
                    class="flex-1
                           bg-[#1E4B43]
                           text-white
                           rounded-lg
                           px-4 py-2.5
                           text-sm
                           font-medium">

                <i class="fa-solid
                          fa-filter
                          mr-1">
                </i>

                Apply

            </button>


            <a href="{{ $action }}"
               class="inline-flex
                      items-center
                      justify-center
                      rounded-lg
                      border
                      border-[#DAD4C3]
                      px-4 py-2.5">

                <i class="fa-solid fa-rotate-left"></i>

            </a>

        </div>

    </div>

</form>
