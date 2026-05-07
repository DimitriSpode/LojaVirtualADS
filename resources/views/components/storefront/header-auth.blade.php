<div class="flex shrink-0 items-center gap-2 sm:gap-3">
    @guest
        @if (Route::has('login'))
            <a
                href="{{ route('login') }}"
                class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 bg-white shadow-sm ring-1 ring-gray-200/80 hover:bg-gray-50 hover:ring-gray-300 transition dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-700"
            >
                {{ __('Login') }}
            </a>
        @endif
        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="rounded-full px-4 py-2 text-sm font-semibold text-white bg-gray-900 shadow-sm hover:bg-gray-800 transition dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white"
            >
                {{ __('Register') }}
            </a>
        @endif
    @else
        <div class="hidden sm:flex sm:items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-gray-700 bg-white shadow-sm ring-1 ring-gray-200/80 hover:bg-gray-50 hover:ring-gray-300 transition dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-700"
                    >
                        <span class="max-w-[10rem] truncate">{{ Auth::user()->name }}</span>
                        <svg class="h-4 w-4 shrink-0 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('dashboard')">
                        {{ __('Dashboard') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <div class="flex flex-col items-end gap-1 sm:hidden text-sm">
            <span class="font-medium text-gray-700 dark:text-gray-300 max-w-[8rem] truncate">{{ Auth::user()->name }}</span>
            <div class="flex gap-2">
                <a href="{{ route('dashboard') }}" class="text-gray-600 underline dark:text-gray-400">{{ __('Dashboard') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 underline dark:text-gray-400">{{ __('Log Out') }}</button>
                </form>
            </div>
        </div>
    @endguest
</div>
