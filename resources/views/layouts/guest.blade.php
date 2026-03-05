<x-layouts::guest.header :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::guest.header>
