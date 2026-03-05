<x-layouts::whquestacg 
    :breadcrumbs="[
        ['label' => 'Enemigos', 'url' => route('whquestacg.enemigos.index')],
        ['label' => $enemigo->titulo]
    ]"
>

    <livewire:whquestacg.enemigos-show :enemigo="$enemigo" />

</x-layouts::whquestacg>
