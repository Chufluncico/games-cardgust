<x-layouts::whquestacg>

    <div class="flex h-full w-full px-8 flex-1 flex-col gap-4 rounded-xl">

        <flux:heading size="xl">Mazos</flux:heading>

        <div class="grid auto-rows-min gap-4 md:grid-cols-4">

            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <a href="{{ route('whquestacg.enemigos.index') }}">
                    Enemies
                </a>
            </div>

            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>

            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>

        <flux:heading size="xl">Tablas</flux:heading>

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <a href="{{ route('whquestacg.acciones-enemigo') }}">
                    Acciones de enemigo
                </a>
            </div>
        </div>
    </div>

</x-layouts::whquestacg>
