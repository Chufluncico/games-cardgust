<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="layout-grid" :href="route('whquestacg.home')" :current="request()->routeIs('whquestacg.home')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>   
            </flux:sidebar.nav>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Mazos')" class="grid">
                    <flux:sidebar.item icon="gallery-horizontal-end" :href="route('whquestacg.enemigos.index')" :current="request()->routeIs('whquestacg.enemigos.index')" wire:navigate>
                        {{ __('Enemigos') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="home" href="#">Heroes</flux:sidebar.item>
                    <flux:sidebar.item icon="inbox" href="#">Acciones de heroe</flux:sidebar.item>
                    <flux:sidebar.item icon="document-text" href="#">Lugares</flux:sidebar.item>
                    <flux:sidebar.item icon="calendar" href="#">Objetos</flux:sidebar.item>
                </flux:sidebar.group>
                
                <flux:sidebar.group icon="star" heading="Tablas" class="grid">
                    <flux:sidebar.item icon="table-2" :href="route('whquestacg.acciones-enemigo')" :current="request()->routeIs('whquestacg.acciones-enemigo')" wire:navigate>Acciones de enemigo</flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group icon="star" heading="Galerias de imagenes" class="grid">
                    <flux:sidebar.item href="#">Enemigos</flux:sidebar.item>
                    <flux:sidebar.item href="#">Acciones de heroe</flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="information-circle" href="#">BGG Link</flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>


        <flux:header class="block! bg-white lg:bg-zinc-50 pl-8 lg:pl-8 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700"> 
            <div class="flex h-full items-center"> 
                <flux:sidebar.toggle class="lg:hidden mr-4" icon="bars-2" inset="left" />
                <flux:breadcrumbs class="py-2"> 
                    <flux:breadcrumbs.item href="{{ route('whquestacg.home') }}">Warhammer Quest TACG</flux:breadcrumbs.item>
                @foreach($breadcrumbs as $crumb)
                    @if(isset($crumb['url']))
                        <flux:breadcrumbs.item href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</flux:breadcrumbs.item>
                    @else
                        <flux:breadcrumbs.item>{{ $crumb['label'] }}</flux:breadcrumbs.item>
                    @endif
                @endforeach
                </flux:breadcrumbs>

                <flux:spacer />

                @auth
                    <x-desktop-user-menu />
                @else
                    <flux:button href="{{ route('login') }}"> 
                        Log in
                    </flux:button>
                @endauth    
            </div>    
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
