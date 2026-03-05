@props([
    'breadcrumbs' => []
])

<x-layouts::whquestacg.sidebar :breadcrumbs="$breadcrumbs ?? null">
    <flux:main class="">
        {{ $slot }}
    </flux:main>
</x-layouts::whquestacg.sidebar>
