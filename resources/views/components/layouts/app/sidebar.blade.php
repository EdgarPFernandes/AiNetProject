<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

{{--            <a href="{{ route('home') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>--}}
            <a href="{{ route('home') }}" class="mb-0" wire:navigate>
                <x-app-logo />
            </a>

            @if(session('cart'))
            <flux:navlist variant="outline">
                <div class="relative inline-flex items-center mr-4">
                    <div class="-top-0.5 absolute left-6 z-10">
                        <p class="flex p-3 h-3 w-3 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                            {{ session('cart')->count() }}                            
                        </p>
                    </div>
                    <flux:navlist.item icon="shopping-cart" icon:variant="solid"  :href="route('cart.show')" :current="request()->routeIs('cart.show')" wire:navigate><span class="pl-2">Shopping Cart</span></flux:navlist.item>
                </div>                
            </flux:navlist>
            @endif

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="'Management'" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item icon="building-library" :href="route('departments.index')" :current="request()->routeIs('departments.index')" wire:navigate>{{ __('Departments') }}</flux:navlist.item>
                    <flux:navlist.item icon="folder-open" :href="route('courses.index')" :current="request()->routeIs('courses.index')" wire:navigate>{{ __('Courses') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>


            <flux:navlist variant="outline">
                <flux:navlist.group :heading="'Academics'" class="grid">
                    <flux:navlist.item icon="academic-cap" :href="route('courses.showcase')" :current="request()->routeIs('courses.showcase')" wire:navigate>Courses</flux:navlist.item>
                    <flux:navlist.item icon="document" :href="route('disciplines.index')" :current="request()->routeIs('disciplines.index')" wire:navigate>Disciplines</flux:navlist.item>
                    <flux:navlist.item icon="document" :href="route('products.index')" :current="request()->routeIs('products.index')" wire:navigate>Products</flux:navlist.item>
                    <flux:navlist.group heading="Curricula" expandable :expanded="request()->routeIs('courses.curriculum')">
                        
                        
                    </flux:navlist.group>
                </flux:navlist.group>
            </flux:navlist>

            {{-- <flux:spacer /> --}}

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="'People'" class="grid">
                    <flux:navlist.item icon="user" :href="route('teachers.index')" :current="request()->routeIs('teachers.index')" wire:navigate>Teachers</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('students.index')" :current="request()->routeIs('students.index')" wire:navigate>Students</flux:navlist.item>
                    <flux:navlist.item icon="user-circle" :href="route('administratives.index')" :current="request()->routeIs('administratives.index')" wire:navigate>Administratives</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer/>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="'My Content'" class="grid">
                    <flux:navlist.item icon="document" icon:variant="solid"  :href="'#'" :current="false" wire:navigate>My Disciplines</flux:navlist.item>
                    <flux:navlist.item icon="user"  icon:variant="solid"  :href="'#'" :current="false" wire:navigate>My Teachers</flux:navlist.item>
                    <flux:navlist.item icon="users" icon:variant="solid" :href="'#'" :current="false" wire:navigate>My Students</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>


            <!-- Desktop User Menu -->
            @auth
                <flux:dropdown position="bottom" align="start">
                    <flux:profile
                        :name="auth()->user()?->firstLastName()"
                        :initials="auth()->user()?->firstLastInitial()"
                        icon-trailing="chevrons-up-down"
                    />

                    <flux:menu class="w-[220px]">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()?->firstLastInitial()}}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()?->name}}</span>
                                        <span class="truncate text-xs">{{ auth()->user()?->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>


                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:navlist variant="outline">
                    <flux:navlist.group :heading="'Authentication'" class="grid">
                        <flux:navlist.item icon="key" :href="route('login')" :current="request()->routeIs('login')" wire:navigate>Login</flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
            @endauth
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @if(session('cart'))
            <flux:navlist variant="outline">
                <div class="relative inline-flex items-center mr-4">
                    <div class="-top-0.5 absolute left-6 z-10">
                        <p class="flex p-3 h-3 w-3 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                            {{ session('cart')->count() }}
                        </p>
                    </div>
                    <flux:navlist.item icon="shopping-cart" icon:variant="solid"  :href="route('cart.show')" :current="request()->routeIs('cart.show')" wire:navigate><span class="pl-2"></span></flux:navlist.item>
                </div>                
            </flux:navlist>
            @endif
            @auth
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()?->firstLastInitial()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()?->firstLastInitial()}}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()?->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()?->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            @else
                <flux:navbar>
                    <flux:navbar.item  icon="key" :href="route('login')" :current="request()->routeIs('login')" wire:navigate>Login</flux:navbar.item>
                </flux:navbar>
            @endauth
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
