@php
    $isAdmin = auth()->user()->hasRole('admin');

    $dashboardRoute = $isAdmin
        ? route('admin.dashboard')
        : route('dashboard');
@endphp

<aside class="hidden md:flex
              w-64
              shrink-0
              min-h-screen
              flex-col
              border-r
              border-[#DAD4C3]
              bg-[#F1EFE7]
              sticky
              top-0
              h-screen">

    {{-- =========================================================
         LOGO
    ========================================================== --}}
    <div class="h-20 flex items-center px-5">

        <div class="flex items-center gap-3">

            <div class="w-10 h-10
                        rounded-full
                        bg-[#1E4B43]
                        border-2
                        border-[#D6772F]
                        flex
                        items-center
                        justify-center
                        text-white
                        font-bold
                        text-xs">

                ERP

            </div>


            <div>

                <p class="font-bold text-sm">
                    Sales ERP
                </p>

                <p class="text-xs text-[#62685F]">

                    @role('admin')
                    Administration
                    @else
                        Field Sales System
                        @endrole

                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
         NAVIGATION
    ========================================================== --}}
    <nav class="flex-1
                px-3
                py-4
                space-y-1
                overflow-y-auto">


        {{-- Dashboard --}}
        <a href="{{ $dashboardRoute }}"
           class="flex
                  items-center
                  gap-3
                  px-3 py-3
                  rounded-lg
                  text-sm
                  font-medium
                  transition
                  {{ request()->routeIs('dashboard')
                        || request()->routeIs('admin.dashboard')
                            ? 'bg-[#E3ECE7] text-[#1E4B43]'
                            : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

            <i class="fa-solid
                      fa-house
                      w-5
                      text-center">
            </i>

            Dashboard

        </a>


        {{-- =====================================================
             ADMIN
        ====================================================== --}}
        @role('admin')

        <div class="pt-4 pb-2 px-3">

            <p class="text-[10px]
                          uppercase
                          tracking-wider
                          font-semibold
                          text-[#8A8F87]">

                Management

            </p>

        </div>


        {{-- Sales Representatives --}}
        <a href="{{ route('admin.sales-reps.index') }}"
           class="flex
                      items-center
                      gap-3
                      px-3 py-3
                      rounded-lg
                      text-sm
                      font-medium
                      transition
                      {{ request()->routeIs('admin.sales-reps.*')
                            ? 'bg-[#E3ECE7] text-[#1E4B43]'
                            : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

            <i class="fa-solid
                          fa-user-tie
                          w-5
                          text-center">
            </i>

            Sales Representatives

        </a>


        {{-- Customers --}}
        <a href="{{ route('admin.customers.index') }}"
           class="flex
                      items-center
                      gap-3
                      px-3 py-3
                      rounded-lg
                      text-sm
                      font-medium
                      transition
                      {{ request()->routeIs('admin.customers.*')
                            ? 'bg-[#E3ECE7] text-[#1E4B43]'
                            : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

            <i class="fa-solid
                          fa-users
                          w-5
                          text-center">
            </i>

            Customers

        </a>


        <a href="{{ route('admin.calendar.index') }}"
           class="flex
          items-center
          gap-3
          px-3 py-3
          rounded-lg
          text-sm
          font-medium
          transition
          {{ request()->routeIs('admin.calendar.*')
                ? 'bg-[#E3ECE7] text-[#1E4B43]'
                : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

            <i class="fa-solid
              fa-calendar-days
              w-5
              text-center">
            </i>

            Calendar

        </a>


        {{-- Resources --}}
        <a href="{{ route('resources.index') }}"
           class="flex
                      items-center
                      gap-3
                      px-3 py-3
                      rounded-lg
                      text-sm
                      font-medium
                      transition
                      {{ request()->routeIs('resources.*')
                            ? 'bg-[#E3ECE7] text-[#1E4B43]'
                            : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

            <i class="fa-solid
                          fa-folder-open
                          w-5
                          text-center">
            </i>

            Resources

        </a>


        {{-- =================================================
             ADMINISTRATION
        ================================================== --}}
        <div class="pt-4 pb-2 px-3">

            <p class="text-[10px]
                          uppercase
                          tracking-wider
                          font-semibold
                          text-[#8A8F87]">

                Administration

            </p>

        </div>


        {{-- Visits --}}
        <a href="{{ route('admin.visits.index') }}"
           class="flex
          items-center
          gap-3
          px-3 py-3
          rounded-lg
          text-sm
          font-medium
          transition

          {{ request()->routeIs('admin.visits.*')
                ? 'bg-[#E3ECE7] text-[#1E4B43]'
                : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

            <i class="fa-solid
              fa-clipboard-check
              w-5
              text-center">
            </i>

            Visits

        </a>



        {{-- Reports --}}
        <a href="{{ route('admin.reports.index') }}"
           class="flex
          items-center
          gap-3
          px-3 py-3
          rounded-lg
          text-sm
          font-medium
          transition

          {{ request()->routeIs('admin.reports.*')
                ? 'bg-[#E3ECE7] text-[#1E4B43]'
                : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

            <i class="fa-solid
              fa-chart-column
              w-5
              text-center">
            </i>

            Reports

        </a>



        @else

            {{-- =================================================
                 SALES REPRESENTATIVE
            ================================================== --}}

            {{-- Customers --}}
            <a href="{{ route('customers.index') }}"
               class="flex
                      items-center
                      gap-3
                      px-3 py-3
                      rounded-lg
                      text-sm
                      font-medium
                      transition
                      {{ request()->routeIs('customers.*')
                            ? 'bg-[#E3ECE7] text-[#1E4B43]'
                            : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

                <i class="fa-solid
                          fa-users
                          w-5
                          text-center">
                </i>

                Customers

            </a>


            {{-- Calendar --}}
            <a href="{{ route('calendar.index') }}"
               class="flex
                      items-center
                      gap-3
                      px-3 py-3
                      rounded-lg
                      text-sm
                      font-medium
                      transition
                      {{ request()->routeIs('calendar.*')
                            ? 'bg-[#E3ECE7] text-[#1E4B43]'
                            : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

                <i class="fa-solid
                          fa-calendar-days
                          w-5
                          text-center">
                </i>

                Calendar

            </a>


            {{-- Resources --}}
            <a href="{{ route('resources.index') }}"
               class="flex
                      items-center
                      gap-3
                      px-3 py-3
                      rounded-lg
                      text-sm
                      font-medium
                      transition
                      {{ request()->routeIs('resources.*')
                            ? 'bg-[#E3ECE7] text-[#1E4B43]'
                            : 'text-[#62685F] hover:bg-[#E9E4D6]' }}">

                <i class="fa-solid
                          fa-folder-open
                          w-5
                          text-center">
                </i>

                Resources

            </a>

            @endrole

    </nav>


    {{-- =========================================================
         LOGGED USER
    ========================================================== --}}
    <div class="border-t
                border-[#DAD4C3]
                p-4">

        <div class="flex
                    items-center
                    gap-3">

            <div class="w-9 h-9
                        rounded-full
                        bg-[#1E4B43]
                        text-white
                        flex
                        items-center
                        justify-center
                        font-semibold
                        text-sm">

                {{ strtoupper(
                    substr(
                        auth()->user()->name,
                        0,
                        1
                    )
                ) }}

            </div>


            <div class="min-w-0 flex-1">

                <p class="text-sm
                          font-medium
                          truncate">

                    {{ auth()->user()->name }}

                </p>


                <p class="text-xs
                          text-[#62685F]
                          truncate
                          capitalize">

                    {{ str_replace(
                        '_',
                        ' ',
                        auth()->user()
                            ->getRoleNames()
                            ->first()
                    ) }}

                </p>

            </div>

        </div>

    </div>

</aside>
