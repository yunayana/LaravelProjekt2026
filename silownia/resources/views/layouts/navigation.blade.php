<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" aria-label="Strona główna">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Strona główna') }}
                    </x-nav-link>

                    @auth
                        @php $user = auth()->user(); @endphp

                        @if ($user->hasRole('client'))
                            <x-nav-link :href="route('client.membership.index')" :active="request()->routeIs('client.membership.index')">
                                {{ __('Mój karnet') }}
                            </x-nav-link>
                            <x-nav-link :href="route('client.classes.index')" :active="request()->routeIs('client.classes.index')">
                                {{ __('Zajęcia') }}
                            </x-nav-link>
                        @elseif ($user->hasRole('trainer'))
                            <x-nav-link :href="route('trainer.classes.index')" :active="request()->routeIs('trainer.classes.index')">
                                {{ __('Moje zajęcia') }}
                            </x-nav-link>
                        @elseif ($user->hasRole('admin'))
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Panel administratora') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    <!-- ✅ Panel dostępności (DESKTOP) -->
                    <div class="flex items-center gap-2 ms-4">
                        <button
                            type="button"
                            @click="highContrast = !highContrast"
                            class="text-xs px-2 py-1 border border-gray-300 rounded hover:bg-gray-100"
                        >
                            Kontrast
                        </button>

                        <button
                            type="button"
                            @click="largeFont = !largeFont"
                            class="text-xs px-2 py-1 border border-gray-300 rounded hover:bg-gray-100"
                        >
                            A+
                        </button>
                    </div>
                </div>
            </div>

            <!-- Settings / Auth Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Log in') }}
                        </a>
                        <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endguest
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Strona główna') }}
            </x-responsive-nav-link>
        </div>

        <!-- ✅ Panel dostępności (MOBILE) -->
        <div class="px-4 py-3 border-t border-gray-200 flex gap-2">
            <button
                @click="highContrast = !highContrast"
                class="text-xs px-2 py-1 border rounded w-full"
            >
                Kontrast
            </button>

            <button
                @click="largeFont = !largeFont"
                class="text-xs px-2 py-1 border rounded w-full"
            >
                A+
            </button>
        </div>
    </div>
</nav>
