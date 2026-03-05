<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\whquestacg\Enemigo;

new class extends Component
{
    use WithPagination;

    public string $viewMode = 'table'; // table | grid
    public array $columns = [
        'titulo'  => ['label' => 'Título', 'visible' => true,  'sortable' => true, 'selectable' => false],
        'familia' => ['label' => 'Familia', 'visible' => true,  'sortable' => true, 'selectable' => true],
        'nivel'   => ['label' => 'Nivel', 'visible' => true,  'sortable' => true, 'selectable' => true],
        'tipo'    => ['label' => 'Tipo', 'visible' => false, 'sortable' => true, 'selectable' => true],
        'vida'    => ['label' => 'Vida', 'visible' => true,  'sortable' => true, 'selectable' => true],
        'ataque'  => ['label' => 'Ataque', 'visible' => true,  'sortable' => true, 'selectable' => true],
        'resistencia'  => ['label' => 'Resistencia', 'visible' => true,  'sortable' => true, 'selectable' => true],
        'copias'  => ['label' => 'Nº Copias', 'visible' => false,  'sortable' => false, 'selectable' => true],
        'nemesis' => ['label' => 'Acción Nemesis', 'visible' => true,  'sortable' => false, 'selectable' => true],
        'accion1' => ['label' => 'Acción 1', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'accion2' => ['label' => 'Acción 2', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'accion3' => ['label' => 'Acción 3', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'efecto1' => ['label' => 'Efecto 1', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'efecto2' => ['label' => 'Efecto 2', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'efecto3' => ['label' => 'Efecto 3', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'flavor'  => ['label' => 'Flavor', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'image'   => ['label' => 'Imagen Principal', 'visible' => false, 'sortable' => false, 'selectable' => true],
        'owner'   => ['label' => 'Creador', 'visible' => false, 'sortable' => true, 'selectable' => false],
        'created_at'   => ['label' => 'Fecha', 'visible' => false, 'sortable' => true, 'selectable' => false],
        'updated_at'   => ['label' => 'Fecha', 'visible' => false, 'sortable' => true, 'selectable' => false],
    ];
    public bool $showColumnSelector = false;
    public array $selectedColumns = [];
    protected string $sessionKey = 'whq_enemies_columns';

    public string $search = '';
    public string $sortField = 'titulo';
    public string $sortDirection = 'asc';


    public function mount()
    {
        $saved = session($this->sessionKey);

        if (is_array($saved)) {
            $this->selectedColumns = $saved;
        } else {
            $this->selectedColumns = collect($this->columns)
                ->filter(fn ($col) => $col['visible'])
                ->keys()
                ->toArray();
        }

        $this->syncColumns();
    }


    public function setView(string $mode): void
    {
        $this->viewMode = $mode;
    }


    public function selectAllColumns()
    {
        $this->selectedColumns = collect($this->columns)
            ->filter(fn ($col) => ($col['selectable'] ?? true) || $col['visible'])
            ->keys()
            ->toArray();

        $this->syncColumns();
    }


    public function toggleColumn(string $key)
    {
        if (($index = array_search($key, $this->selectedColumns)) !== false) {
            unset($this->selectedColumns[$index]);
            $this->selectedColumns = array_values($this->selectedColumns);
        } else {
            $this->selectedColumns[] = $key;
        }

        $this->syncColumns();
    }


    protected function syncColumns(): void
    {
        foreach ($this->columns as $key => &$column) {
            $column['visible'] = in_array($key, $this->selectedColumns);
        }

        session()->put($this->sessionKey, $this->selectedColumns);

        $this->resetPage();
    }


    #[Computed]
    public function visibleColumns(): array
    {
        return collect($this->columns)
            ->filter(fn ($col) => $col['visible'])
            ->keys()
            ->toArray();
    }


    public function updatedSearch()
    {
        $this->resetPage();
    }


    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }


    #[Computed]
    public function enemigosOld()
    {
        return Enemigo::orderBy('familia')
            ->orderBy('nivel')
            ->orderBy('titulo')
            ->paginate(20);
    }


    #[Computed]
    public function enemigos()
    {
        return Enemigo::query()
            ->when(strlen($this->search) >= 2, function ($query) {

                $query->where(function ($q) {
                    $q->where('titulo', 'like', '%' . $this->search . '%')
                      ->orWhere('familia', 'like', '%' . $this->search . '%')
                      ->orWhere('tipo', 'like', '%' . $this->search . '%');
                });

            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(25);
    }


};
?>


<div>

    <div class="flex gap-4 mb-6">
        <flux:heading size="xl">Enemigos</flux:heading>
        <flux:button href="{{ route('whquestacg.enemigos.create') }}" size="sm" variant="primary">Nuevo Enemigo</flux:button>
    </div>


    <div class="flex justify-between items-center mb-6 gap-2">
        <div class="flex">
            <flux:input icon="magnifying-glass" placeholder="Titulo, Familia, Tipo..." size="sm"
                wire:model.live.debounce.300ms="search"
            />
        </div>

        <flux:dropdown>
            <flux:button size="sm" icon="view-columns" />
            <flux:menu keep-open>
                {{-- Seleccionar todo --}}
                <flux:menu.item wire:click="selectAllColumns">
                    Seleccionar todo
                </flux:menu.item>
                <flux:menu.separator />
                @foreach($columns as $key => $column)
                    @if(($column['selectable'] ?? true))
                        <flux:menu.item
                            wire:key="column-{{ $key }}"
                            wire:click="toggleColumn('{{ $key }}')">
                            <span class="mr-2">
                                @if(in_array($key, $selectedColumns))
                                    <flux:icon name="square-check-big" class="w-4 h-4 text-green-500" />
                                @else
                                    <flux:icon name="square" class="w-4 h-4 text-zinc-500" />
                                @endif
                            </span>
                            {{ $column['label'] }}
                        </flux:menu.item>
                    @endif
                    @if(!($column['selectable'] ?? true) && ($column['visible'] ?? true))
                        <flux:menu.item disabled>
                            <span class="mr-2">
                                <flux:icon name="square-check-big" class="w-4 h-4" />
                            </span>
                            {{ $column['label'] }}
                        </flux:menu.item>
                    @endif

                @endforeach
            </flux:menu>
        </flux:dropdown>

        <flux:spacer />

        <div class="flex gap-2">
            <flux:button 
                size="sm"
                :variant="$viewMode === 'table' ? 'primary' : null"
                wire:click="setView('table')">
                Tabla
            </flux:button>

            <flux:button 
                size="sm"
                :variant="$viewMode === 'grid' ? 'primary' : null"
                wire:click="setView('grid')">
                Cartas
            </flux:button>
        </div>
    </div>

    @if($viewMode === 'table')

        <div class="w-full min-w-0">
            <div class="overflow-x-auto">
                {{-- Contenedor vertical con altura limitada --}}
                <div class="max-h-[56vh] overflow-y-auto">
                    <table class="min-w-max w-full  text-sm">
                        <thead class="sticky top-0 z-10 ">
                            <tr class="">
                                @foreach($this->visibleColumns as $column)
                                    <th class="p-1 bg-zinc-300 ">
                                        @if($columns[$column]['sortable'])
                                    <button 
                                        wire:click="sortBy('{{ $column }}')"
                                        class="flex items-center gap-1 hover:text-primary"
                                    >
                                        {{ $columns[$column]['label'] }}

                                        {{-- Icono --}}
                                        @if($sortField === $column)
                                            @if($sortDirection === 'asc')
                                                <flux:icon name="chevron-up" variant="mini" />
                                            @else
                                                <flux:icon name="chevron-down" variant="mini" />
                                            @endif
                                        @else
                                            <flux:icon name="chevrons-up-down" variant="micro" class="opacity-30" />
                                        @endif

                                    </button>
                                @else
                                    {{ $columns[$column]['label'] }}
                                @endif
                                    </th>
                                @endforeach
                                <th class="p-1 bg-zinc-300"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->enemigos as $enemy)
                                <tr>
                                    @foreach($this->visibleColumns as $column)
                                        <td class="p-1 border border-zinc-300 max-w-[400px]">
                                            {{ $enemy->$column }}
                                        </td>
                                    @endforeach

                                    <td class="p-1 border border-gray-300">
                                        <a href="{{ route('whquestacg.enemigos.show', $enemy) }}">Ver</a> /
                                        <a href="">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    @else

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($this->enemigos as $enemy)
                <div class="border rounded-xl p-4 shadow hover:shadow-lg transition">
                    <h2 class="font-bold text-lg mb-2">
                        {{ $enemy->titulo }}
                    </h2>

                    <div class="text-sm text-neutral-500 mb-2">
                        {{ $enemy->familia }} · Nivel {{ $enemy->nivel }}
                    </div>

                    <div class="flex justify-between text-sm">
                        <span>Vida: {{ $enemy->vida }}</span>
                        <span>Ataque: {{ $enemy->ataque }}</span>
                    </div>

                    @if($enemy->nemesis)
                        <div class="mt-2 text-red-600 font-semibold">
                            Nemesis
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    @endif

    <div class="mt-4">
        {{ $this->enemigos->links() }}
    </div>

</div>

