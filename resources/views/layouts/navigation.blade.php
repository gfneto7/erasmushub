<nav x-data="{ open: false, langOpen: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 text-xl font-bold text-gray-900 hover:text-indigo-600 transition">
                        <span class="text-2xl">🎓</span>
                        <span class="bg-gradient-to-r from-indigo-600 to-sky-600 bg-clip-text text-transparent">ErasmusHub</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 md:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('destinations.index')" :active="request()->routeIs('destinations.*')">
                        {{ __('Destinations & Weather') }}
                    </x-nav-link>
                    <x-nav-link :href="route('guides.index')" :active="request()->routeIs('guides.*') && !request()->routeIs('admin.*')">
                        {{ __('Guides') }}
                    </x-nav-link>
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                        {{ __('About') }}
                    </x-nav-link>

                    @auth
                        @if (Auth::user()->canManageGuides())
                            <x-nav-link :href="route('admin.guides.index')" :active="request()->routeIs('admin.guides.*')">
                                {{ __('CMS') }}
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                                {{ __('Categories Management') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('User Management') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side (Language Switcher + Auth) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:space-x-4">
                <!-- Language Selector Dropdown -->
                <div class="relative" x-data="{ langOpen: false }">
                    <button @click="langOpen = ! langOpen" type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 transition focus:outline-none">
                        @if (app()->getLocale() === 'en')
                            <span>🇬🇧 English</span>
                        @elseif (app()->getLocale() === 'pt')
                            <span>🇵🇹 Português</span>
                        @elseif (app()->getLocale() === 'hr')
                            <span>🇭🇷 Hrvatski</span>
                        @endif
                        <svg class="ms-1.5 h-3.5 w-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="langOpen" @click.away="langOpen = false" x-transition class="absolute right-0 mt-2 w-36 bg-white rounded-md shadow-lg py-1 border border-gray-200 z-50">
                        <a href="{{ route('locale.switch', 'en') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ app()->getLocale() === 'en' ? 'font-bold bg-gray-50' : '' }}">
                            <span class="me-2">🇬🇧</span> English
                        </a>
                        <a href="{{ route('locale.switch', 'pt') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ app()->getLocale() === 'pt' ? 'font-bold bg-gray-50' : '' }}">
                            <span class="me-2">🇵🇹</span> Português
                        </a>
                        <a href="{{ route('locale.switch', 'hr') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ app()->getLocale() === 'hr' ? 'font-bold bg-gray-50' : '' }}">
                            <span class="me-2">🇭🇷</span> Hrvatski
                        </a>
                    </div>
                </div>

                @auth
                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs uppercase">
                                        {{ substr(__(Auth::user()->name), 0, 2) }}
                                    </span>
                                    <span>{{ __(Auth::user()->name) }}</span>
                                    <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">
                                        {{ __(Auth::user()->role?->name ?? 'registered') }}
                                    </span>
                                </div>

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

                            @if (Auth::user()->canManageGuides())
                                <x-dropdown-link :href="route('admin.guides.index')">
                                    {{ __('Manage Guides') }}
                                </x-dropdown-link>
                            @endif

                            @if (Auth::user()->isAdmin())
                                <x-dropdown-link :href="route('admin.categories.index')">
                                    {{ __('Manage Categories') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.users.index')">
                                    {{ __('Manage Users') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                            {{ __('Log in') }}
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-3.5 py-1.5 border border-transparent text-xs font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button for Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('destinations.index')" :active="request()->routeIs('destinations.*')">
                {{ __('Destinations & Weather') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('guides.index')" :active="request()->routeIs('guides.*') && !request()->routeIs('admin.*')">
                {{ __('Guides') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
                {{ __('About') }}
            </x-responsive-nav-link>

            @auth
                @if (Auth::user()->canManageGuides())
                    <x-responsive-nav-link :href="route('admin.guides.index')" :active="request()->routeIs('admin.guides.*')">
                        {{ __('Manage Guides') }}
                    </x-responsive-nav-link>
                @endif
                @if (Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Manage Categories') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Manage Users') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Language switcher in mobile -->
        <div class="pt-3 pb-3 px-4 border-t border-gray-200">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('Language') }}</div>
            <div class="flex space-x-2">
                <a href="{{ route('locale.switch', 'en') }}" class="px-2.5 py-1 text-xs rounded border {{ app()->getLocale() === 'en' ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-gray-50 text-gray-700' }}">🇬🇧 EN</a>
                <a href="{{ route('locale.switch', 'pt') }}" class="px-2.5 py-1 text-xs rounded border {{ app()->getLocale() === 'pt' ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-gray-50 text-gray-700' }}">🇵🇹 PT</a>
                <a href="{{ route('locale.switch', 'hr') }}" class="px-2.5 py-1 text-xs rounded border {{ app()->getLocale() === 'hr' ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-gray-50 text-gray-700' }}">🇭🇷 HR</a>
            </div>
        </div>

        <!-- Responsive Auth Settings -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ __(Auth::user()->name) }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }} ({{ __(Auth::user()->role?->name ?? 'registered') }})</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
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
            @else
                <div class="px-4 space-y-2">
                    <a href="{{ route('login') }}" class="block text-center w-full py-2 px-4 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-md">
                        {{ __('Log in') }}
                    </a>
                    <a href="{{ route('register') }}" class="block text-center w-full py-2 px-4 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        {{ __('Register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
