@php
    $isAdmin = auth()->user()->hasRole('admin');

    $dashboardRoute = $isAdmin
        ? route('admin.dashboard')
        : route('dashboard');
@endphp

<nav class="md:hidden
            fixed
            bottom-0
            left-0
            right-0
            z-50
            bg-white
            border-t
            border-[#DAD4C3]
            safe-area-bottom">

    <div class="flex items-center">

        {{-- Dashboard --}}
        <a href="{{ $dashboardRoute }}"
           class="flex-1
                  flex flex-col
                  items-center
                  justify-center
                  gap-1
                  py-2.5
                  {{ request()->routeIs('dashboard')
                        || request()->routeIs('admin.dashboard')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">

            <i class="fa-solid fa-house text-lg"></i>

            <span class="text-[10px] font-medium">
                Dashboard
            </span>

        </a>


        {{-- =====================================================
             ADMIN MOBILE NAVIGATION
        ====================================================== --}}
        @role('admin')

        {{-- Sales Representatives --}}
        <a href="{{ route('admin.sales-reps.index') }}"
           class="flex-1
                      flex flex-col
                      items-center
                      justify-center
                      gap-1
                      py-2.5
                      {{ request()->routeIs('admin.sales-reps.*')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">

            <i class="fa-solid fa-user-tie text-lg"></i>

            <span class="text-[10px] font-medium">
                    Team
                </span>

        </a>


        {{-- Customers --}}
        <a href="{{ route('admin.customers.index') }}"
           class="flex-1
                      flex flex-col
                      items-center
                      justify-center
                      gap-1
                      py-2.5
                      {{ request()->routeIs('customers.*') || request()->routeIs('admin.customers.*')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">

            <i class="fa-solid fa-users text-lg"></i>

            <span class="text-[10px] font-medium">
                    Customers
                </span>

        </a>





        <a href="{{ route('admin.calendar.index') }}"
           class="flex-1
          flex flex-col
          items-center
          justify-center
          gap-1
          py-2.5
          {{ request()->routeIs('admin.calendar.*')
                ? 'text-[#1E4B43]'
                : 'text-[#62685F]' }}">

            <i class="fa-solid fa-calendar-days text-lg"></i>

            <span class="text-[10px] font-medium">
        Calendar
    </span>

        </a>


        {{-- More --}}
        <a href="{{ route('admin.visits.index') }}"
           class="flex-1
                      flex flex-col
                      items-center
                      justify-center
                      gap-1
                      py-2.5
                      {{ request()->routeIs('admin.visits.*')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">

            <i class="fa-solid
              fa-clipboard-check
              w-5
              text-center">
            </i>


            <span class="text-[10px] font-medium">
                    Visits
                </span>

        </a>



        {{-- Reports --}}
        <a href="{{ route('admin.reports.index') }}"
           class="flex-1
                      flex flex-col
                      items-center
                      justify-center
                      gap-1
                      py-2.5
                      {{ request()->routeIs('admin.reports.*')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">


            <i class="fa-solid
              fa-chart-column
              w-5
              text-center">
            </i>

            <span class="text-[10px] font-medium">
                    Reports
                </span>

        </a>



        @else

            {{-- =================================================
                 SALES REP MOBILE NAVIGATION
            ================================================== --}}

            {{-- Customers --}}
            <a href="{{ route('customers.index') }}"
               class="flex-1
                      flex flex-col
                      items-center
                      justify-center
                      gap-1
                      py-2.5
                      {{ request()->routeIs('customers.*')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">

                <i class="fa-solid fa-users text-lg"></i>

                <span class="text-[10px] font-medium">
                    Customers
                </span>

            </a>


            {{-- Calendar --}}
            <a href="{{ route('calendar.index') }}"
               class="flex-1
                      flex flex-col
                      items-center
                      justify-center
                      gap-1
                      py-2.5
                      {{ request()->routeIs('calendar.*')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">

                <i class="fa-solid fa-calendar-days text-lg"></i>

                <span class="text-[10px] font-medium">
                    Calendar
                </span>

            </a>


            {{-- Resources --}}
            <a href="{{ route('resources.index') }}"
               class="flex-1
                      flex flex-col
                      items-center
                      justify-center
                      gap-1
                      py-2.5
                      {{ request()->routeIs('resources.*')
                            ? 'text-[#1E4B43]'
                            : 'text-[#62685F]' }}">

                <i class="fa-solid fa-folder-open text-lg"></i>

                <span class="text-[10px] font-medium">
                    Resources
                </span>

            </a>

            @endrole

    </div>

</nav>
