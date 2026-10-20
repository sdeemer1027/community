<nav x-data="{ open: false, membersOpen: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
     @if(Auth::user()->is_super_admin)
      <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"> Admin</x-nav-link>
     @endif


<x-nav-link :href="route('media-library.index')" :active="request()->routeIs('media-library.*')">
    My Media Library
</x-nav-link>








<div class="relative flex items-center" style="position: relative;">
    <button
        type="button"
        @click="membersOpen = !membersOpen"
        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none"
    >
        Members
        <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
        </svg>
    </button>

    

<div
    x-show="membersOpen"
    @click.outside="membersOpen = false"
    x-transition
    style="display: none; position: absolute; top: 100%; left: 0; margin-top: 8px; width: 240px; z-index: 99999; white-space: nowrap;"
    class="w-64 whitespace-nowrap rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5"
>


        <a href="{{ route('members.index') }}" class="block whitespace-nowrap px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Find Members
        </a>
        <a href="{{ route('connections.index') }}" class="block whitespace-nowrap px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Connection Requests
        </a>
        <a href="{{ route('my-connections.index') }}" class="block whitespace-nowrap px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            My Connections
        </a>
    </div>



</div>








                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>



    
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>


<x-responsive-nav-link :href="route('media-library.index')" :active="request()->routeIs('media-library.*')">
    My Media Library
</x-responsive-nav-link>



<x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">
    Find Members
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('connections.index')" :active="request()->routeIs('connections.*')">
    Connection Requests
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('my-connections.index')" :active="request()->routeIs('my-connections.*')">
    My Connections
</x-responsive-nav-link>







        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>




</nav>
{{--
{{ Auth::user()->is_super_admin }}

"is_super_admin":true,"
--}}