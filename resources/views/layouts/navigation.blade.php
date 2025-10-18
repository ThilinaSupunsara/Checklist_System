<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/HK.png') }}" alt="My App Logo" class="block h-9 w-auto">
                        <style>
                        .block {
                        animation: pulseGlow 3s ease-in-out infinite;
                        }

                        @keyframes pulseGlow {
                        0%, 100% {
                            transform: scale(1);
                        }
                        50% {
                            transform: scale(1.1);
                        }
                        }
                        </style>

                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        {{-- Admin Links --}}
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#6366F1" d="M3 3h8v8H3z"/><path fill="#A5B4FC" d="M13 3h8v8h-8zM3 13h8v8H3z"/><path fill="#C7D2FE" d="M13 13h8v8h-8z"/></svg>
                                    {{ __('Dashboard') }}
                                </span>
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                <span class="flex items-center">
                                     <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3" fill="#A5B4FC"/><path d="M12 11c-3.31 0-6 2.69-6 6v2h12v-2c0-3.31-2.69-6-6-6z" fill="#6366F1"/><circle cx="17" cy="8" r="2" fill="#C7D2FE"/><path d="M17 13c-1.5 0-2.8.8-3.5 2h5c-.7-1.2-2-2-3.5-2z" fill="#A5B4FC"/><circle cx="7" cy="8" r="2" fill="#C7D2FE"/><path d="M7 13c1.5 0 2.8.8-3.5 2h-5c.7-1.2 2-2 3.5-2z" fill="#A5B4FC"/></svg>
                                    {{ __('Users') }}
                                </span>
                            </x-nav-link>
                            <x-nav-link :href="route('admin.defaults.index')" :active="request()->routeIs('admin.defaults.*')">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#A5B4FC" d="M6 2h10l4 4v14a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2z"/><path fill="#6366F1" d="M15 2v5h5L15 2z"/><path fill="#FFFFFF" d="M8 12h8v2H8zm0 4h8v2H8z"/></svg>
                                    {{ __('Templates') }}
                                </span>
                            </x-nav-link>
                            <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#6366F1" d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/><path fill="#A5B4FC" d="M8 12h8v2H8zm0 4h8v2H8z"/></svg>
                            {{ __('Reports') }}
                        </span>
                    </x-nav-link>
                        @endif

                        {{-- Owner Links --}}
                        @if(Auth::user()->role === 'owner')
                             <x-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')"><span class="flex items-center"><svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#6366F1" d="M3 3h8v8H3z"/><path fill="#A5B4FC" d="M13 3h8v8h-8zM3 13h8v8H3z"/><path fill="#C7D2FE" d="M13 13h8v8h-8z"/></svg>{{ __('Dashboard') }}</span></x-nav-link>
                            <x-nav-link :href="route('owner.properties.index')" :active="request()->routeIs('owner.properties.*')"><span class="flex items-center"><svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#A5B4FC" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/><path fill="#6366F1" d="M4 11l8-7 8 7v9H4z"/><path fill="#FFFFFF" d="M15 14h-2v-2h2v2z"/></svg>{{ __('My Properties') }}</span></x-nav-link>
                            <x-nav-link :href="route('owner.my-housekeepers.index')" :active="request()->routeIs('owner.my-housekeepers.*')"><span class="flex items-center"><svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3" fill="#A5B4FC"/><path d="M12 11c-3.31 0-6 2.69-6 6v2h12v-2c0-3.31-2.69-6-6-6z" fill="#6366F1"/><circle cx="17" cy="8" r="2" fill="#C7D2FE"/><path d="M17 13c-1.5 0-2.8.8-3.5 2h5c-.7-1.2-2-2-3.5-2z" fill="#A5B4FC"/><circle cx="7" cy="8" r="2" fill="#C7D2FE"/><path d="M7 13c1.5 0 2.8.8-3.5 2h-5c.7-1.2 2-2 3.5-2z" fill="#A5B4FC"/></svg>{{ __('My Housekeepers') }}</span></x-nav-link>
                            <x-nav-link :href="route('owner.calendar.index')" :active="request()->routeIs('owner.calendar.*')"><span class="flex items-center"><svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#A5B4FC" d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2zm0 16H5V8h14v12z"/><path fill="#6366F1" d="M5 6h14v2H5z"/></svg>{{ __('Calendar') }}</span></x-nav-link>
                            <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#6366F1" d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/><path fill="#A5B4FC" d="M8 12h8v2H8zm0 4h8v2H8z"/></svg>
                            {{ __('Reports') }}
                        </span>
                    </x-nav-link>
                        @endif

                        {{-- Housekeeper Links --}}
                        @if(Auth::user()->role === 'housekeeper')
                             <x-nav-link :href="route('housekeeper.dashboard')" :active="request()->routeIs('housekeeper.dashboard')"><span class="flex items-center"><svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><path fill="#A5B4FC" d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2zm0 16H5V8h14v12z"/><path fill="#6366F1" d="M5 6h14v2H5z"/></svg>{{ __('My Schedule') }}</span></x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                     <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" fill="#6366F1"/><path d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="#A5B4FC"/></svg>
                            {{ __('Profile') }}
                        </span>
                    </x-nav-link>

                    <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                        @csrf
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 text-red-400" viewBox="0 0 24 24"><path fill="currentColor" d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 012 2v2h-2V4H5v16h9v-2h2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V4a2 2 0 012-2h9z"/></svg>
                            <span class="text-gray-600">{{ __('Log Out') }}</span>
                        </a>
                    </form>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition" aria-label="Open main menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
             @auth
                {{-- Main Role-Based Links --}}
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('Users') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.defaults.index')" :active="request()->routeIs('admin.defaults.*')">{{ __('Templates') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
                @endif
                @if(Auth::user()->role === 'owner')
                    <x-responsive-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('owner.properties.index')" :active="request()->routeIs('owner.properties.*')">{{ __('My Properties') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('owner.my-housekeepers.index')" :active="request()->routeIs('owner.my-housekeepers.*')">{{ __('My Housekeepers') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('owner.calendar.index')" :active="request()->routeIs('owner.calendar.*')">{{ __('Calendar') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
                @endif
                @if(Auth::user()->role === 'housekeeper')
                    <x-responsive-nav-link :href="route('housekeeper.dashboard')" :active="request()->routeIs('housekeeper.dashboard')">{{ __('My Schedule') }}</x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-3 border-t border-gray-200">
             <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
