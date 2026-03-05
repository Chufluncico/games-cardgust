<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\whquestacg\AccionEnemigo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;


new class extends Component
{
    use WithPagination;
    use AuthorizesRequests;


    public bool $modalEditar = false;

    public string $nombre = '';
    public ?string $descripcion = null;

    public ?int $accionEditandoId = null;
    public ?int $accionAEliminar = null;
    public bool $modalEliminar = false;


    public function mount()
    {
        
    }

    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('whq_acciones_enemigo', 'nombre')
                    ->ignore($this->accionEditandoId),
            ],
            'descripcion' => 'nullable|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre.unique' => 'Ya existe una acción con ese nombre.',
        ];
    }

    #[Computed]
    public function accionesEnemigo()
    {
        return AccionEnemigo::orderBy('nombre')->get();
    }

    public function abrirModalNuevaAccion()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->modalEditar = true;
    }

    public function editar(int $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $accion = AccionEnemigo::findOrFail($id);

        $this->authorize('update', $accion);


        $this->accionEditandoId = $accion->id;
        $this->nombre = $accion->nombre;
        $this->descripcion = $accion->descripcion;

        $this->modalEditar = true;
    }

    public function eliminar(int $id)
    {
        //AccionEnemigo::findOrFail($id)->delete();
        
        $accion = AccionEnemigo::findOrFail($id);

        $this->authorize('delete', $accion);

        $accion->delete();

        // Cerrar modal
        $this->dispatch('close-modal');
    }

    public function confirmarEliminar(int $id)
    {
        $this->accionAEliminar = $id;
        $this->modalEliminar = true;
    }

    public function eliminarConfirmado()
    {
        if (!$this->accionAEliminar) {
            return;
        }

        AccionEnemigo::findOrFail($this->accionAEliminar)->delete();

        $this->reset(['accionAEliminar', 'modalEliminar']);
    }



    public function guardarAccion2()
    {
        $data = $this->validate();

        AccionEnemigo::create($data);

        // Resetear formulario
        $this->reset(['nombre', 'descripcion']);

        // Cerrar modal
        $this->modalEditar = false;
    }


    private function propagarCambioNombre(string $nombreAnterior, string $nuevoNombre): void
    {
        DB::table('whq_enemigos')
            ->where('accion1', $nombreAnterior)
            ->update(['accion1' => $nuevoNombre]);

        DB::table('whq_enemigos')
            ->where('accion2', $nombreAnterior)
            ->update(['accion2' => $nuevoNombre]);

        DB::table('whq_enemigos')
            ->where('accion3', $nombreAnterior)
            ->update(['accion3' => $nuevoNombre]);
    }


    public function guardarAccion3()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $data = $this->validate();

        $data['user_id'] = auth()->id();

        if ($this->accionEditandoId) {

            $accion = AccionEnemigo::findOrFail($this->accionEditandoId);
            $accion->update($data);

        } else {

            AccionEnemigo::create($data);

        }

        // Resetear estado
        $this->reset(['nombre', 'descripcion', 'accionEditandoId']);

        $this->modalEditar = false;
    }


    public function guardarAccion()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $data = $this->validate();
        $data['user_id'] = auth()->id();

        DB::transaction(function () use ($data) {

            if ($this->accionEditandoId) {

                $accion = AccionEnemigo::findOrFail($this->accionEditandoId);

                $this->authorize('update', $accion);

                $nombreAnterior = $accion->nombre;
                $nuevoNombre = $data['nombre'];

                if ($nombreAnterior !== $nuevoNombre) {
                    $this->propagarCambioNombre($nombreAnterior, $nuevoNombre);
                }

                $accion->update($data);

            } else {

                $this->authorize('create', $accion);

                AccionEnemigo::create($data);

            }

        });

        $this->reset(['nombre', 'descripcion', 'accionEditandoId']);
        $this->modalEditar = false;
    }




};
?>


<div>

    <div class="flex gap-4 mb-6">
        <flux:heading size="xl">Acciones de enemigo</flux:heading>
        <flux:button size="sm" variant="primary" wire:click="abrirModalNuevaAccion">
            Nueva acción
        </flux:button>
    </div>

    
    <div class="w-full">
        <div class="">
            {{-- Contenedor vertical con altura limitada --}}
            <div class="">
                <table class="min-w-max w-full table-auto text-sm">
                    <thead class="sticky top-0 z-10 ">
                        <tr class="">
                            <th class="p-1 bg-zinc-300 ">
                                Accion
                            </th>
                            <th class="p-1 bg-zinc-300 ">
                                Descripcion
                            </th>
                            <th class="p-1 bg-zinc-300"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->accionesEnemigo as $accionEnemigo)
                        <tr>
                            <td class="p-1 border border-zinc-300 max-w-[400px]">
                                {{ $accionEnemigo->nombre }}
                            </td>
                            <td class="p-1 border border-zinc-300 max-w-[400px]">
                                @if($accionEnemigo->descripcion)
                                    {!! nl2br(e($accionEnemigo->descripcion)) !!}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-1 border border-gray-300">
                                @can('update', $accionEnemigo)
                                <flux:button 
                                    size="xs"
                                    wire:click="editar({{ $accionEnemigo->id }})"
                                >
                                    Editar
                                </flux:button>
                                @endcan
                                @can('delete', $accionEnemigo)
                                <flux:button 
                                    size="xs"
                                    wire:click="confirmarEliminar({{ $accionEnemigo->id }})"
                                >
                                    Eliminar
                                </flux:button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <flux:modal wire:model="modalEditar" class="w-full">
        <flux:heading size="lg">
            {{ $accionEditandoId ? 'Editar acción' : 'Nueva acción' }}
        </flux:heading>
        <div class="space-y-6 mt-4">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model="nombre" />
                <flux:error name="nombre" />
            </flux:field>
            <flux:field>
                <flux:label>Descripción</flux:label>
                <flux:textarea wire:model="descripcion" />
                <flux:error name="descripcion" />
            </flux:field>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <flux:button 
                variant="ghost"
                wire:click="
                    $set('modalEditar', false);
                    $set('accionEditandoId', null);
                "
            >
                Cancelar
            </flux:button>
            <flux:button 
                variant="primary"
                wire:click="guardarAccion"
            >
                Guardar
            </flux:button>
        </div>
    </flux:modal>


    <flux:modal wire:model="modalEliminar">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">¿Eliminar acción?</flux:heading>
                <flux:text class="mt-2">
                    Esta acción se eliminará definitivamente.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:button 
                    variant="ghost"
                    wire:click="$set('modalEliminar', false)"
                >
                    Cancelar
                </flux:button>
                <flux:button 
                    variant="danger"
                    wire:click="eliminarConfirmado"
                >
                    Eliminar
                </flux:button>
            </div>
        </div>
    </flux:modal>


</div>

