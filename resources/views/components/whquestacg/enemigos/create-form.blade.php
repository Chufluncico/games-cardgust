<div>
<form wire:submit="save" class="space-y-8">

    {{-- ================== INFORMACIÓN BÁSICA ================== --}}
    <div class="grid grid-cols-4 gap-2 items-start mb-2">

        <flux:field class="col-span-3">
            <flux:label>Título</flux:label>
            <flux:input wire:model.blur.live="titulo" size="sm" type="text" />
            <flux:error name="titulo" class="mt-0!" />
        </flux:field>

        <flux:field>
            <flux:label>Copias</flux:label>
            <flux:input wire:model="copias" size="sm" type="number" min="1" />
            <flux:error name="copias" />
        </flux:field>

        <flux:field wire.key="familia" class="col-span-2">
            <flux:label>Familia</flux:label>
            <div 
                x-data="{ open: @entangle('mostrarSugerenciasFamilia') }"
                @click.outside="open = false"
                class="relative"
            >
                <flux:input size="sm"
                    wire:model.live.debounce.300ms="familia"
                    
                />
                <div 
                    x-show="open"
                    x-transition
                    class="absolute z-20 mt-2 w-full bg-white border rounded-lg shadow"
                >
                    @foreach($this->familiaSugerencias as $sugerencia)
                        <div
                            wire:mousedown.prevent="seleccionarFamilia('{{ $sugerencia }}')"
                            class="px-3 py-1 hover:bg-gray-100 cursor-pointer text-sm"
                        >
                            {{ $sugerencia }}
                        </div>
                    @endforeach
                </div>
            </div>
            <flux:error name="familia" />
        </flux:field>

        <flux:field class="col-span-2">
            <flux:label>Tipo</flux:label>
            <flux:select size="sm" wire:model="tipo">
                @foreach($this->tiposDisponibles as $valor => $label)
                    <option value="{{ $valor }}">
                        {{ $label }}
                    </option>
                @endforeach
            </flux:select>
            <flux:error name="tipo" />
        </flux:field>


        <flux:field>
            <flux:label>Nivel</flux:label>
            <flux:select size="sm" wire:model="nivel">
                <option value=""></option>
                <option value="1">Nivel 1</option>
                <option value="2">Nivel 2</option>
                <option value="3">Nivel 3</option>
            </flux:select>
            <flux:error name="nivel" />
        </flux:field>



        <flux:field>
            <flux:label>Vida</flux:label>
            <flux:input size="sm" wire:model="vida" type="number" min="0" max="50" />
            <flux:error name="vida" />
        </flux:field>

        <flux:field>
            <flux:label>Ataque</flux:label>
            <flux:input size="sm" wire:model="ataque" type="number" min="0" max="50" />
            <flux:error name="ataque" />
        </flux:field>

        <flux:field>
            <flux:label>Resistencia</flux:label>
            <flux:input size="sm" wire:model="resistencia" type="number" min="0" max="50" />
            <flux:error name="resistencia" />
        </flux:field>
    </div>

    {{-- ================== ACCIONES ================== --}}
    <div class="grid grid-cols-3 gap-2 mb-2">

        <flux:field>
            <flux:label>Acción 1</flux:label>
            <flux:select size="sm" wire:model="accion1">
                <option value="">-- Sin acción --</option>
                @foreach($this->accionesDisponibles as $accion)
                    <option value="{{ $accion->id }}">
                        {{ $accion->nombre }}
                    </option>
                @endforeach
            </flux:select>
            <flux:error name="accion1" />
        </flux:field>

        <flux:field>
            <flux:label>Acción 2</flux:label>
            <flux:select size="sm" wire:model="accion2">
                <option value="">-- Sin acción --</option>
                @foreach($this->accionesDisponibles as $accion)
                    <option value="{{ $accion->id }}">
                        {{ $accion->nombre }}
                    </option>
                @endforeach
            </flux:select>
            <flux:error name="accion2" />
        </flux:field>

        <flux:field>
            <flux:label>Acción 3</flux:label>
            <flux:select size="sm" wire:model="accion3">
                <option value="">-- Sin acción --</option>
                @foreach($this->accionesDisponibles as $accion)
                    <option value="{{ $accion->id }}">
                        {{ $accion->nombre }}
                    </option>
                @endforeach
            </flux:select>
            <flux:error name="accion3" />
        </flux:field>

    </div>

    {{-- ================== EFECTOS ================== --}}
    <div class="grid grid-cols-1 gap-2 mb-2">

        <flux:field>
            <flux:label>Efecto 1</flux:label>
            <flux:textarea rows="2" wire:model="efecto1" />
            <flux:error name="efecto1" />
        </flux:field>

        <flux:field>
            <flux:label>Efecto 2</flux:label>
            <flux:textarea rows="2" wire:model="efecto2" />
            <flux:error name="efecto2" />
        </flux:field>

        <flux:field>
            <flux:label>Efecto 3</flux:label>
            <flux:textarea rows="2" wire:model="efecto3" />
            <flux:error name="efecto3" />
        </flux:field>

    </div>




    {{-- ================== TEXTOS ESPECIALES ================== --}}
    <div class="grid grid-cols-1 gap-2">

        <flux:field>
            <flux:label>Flavor</flux:label>
            <flux:textarea rows="2" wire:model="flavor" />
            <flux:error name="flavor" />
        </flux:field>

        <flux:field>
            <flux:label>Texto Nemesis</flux:label>
            <flux:textarea rows="2" wire:model="nemesis" />
            <flux:error name="nemesis" />
        </flux:field>

        <flux:field>
            <flux:label>Imagen (ruta)</flux:label>
            <flux:input size="sm" wire:model="imagen" type="text" />
            <flux:error name="imagen" />
        </flux:field>

    </div>


    {{-- ================== BOTÓN ================== --}}
    <div class="flex justify-end">
        <flux:button size="sm" type="submit" variant="primary">
            Guardar enemigo
        </flux:button>
    </div>

</form>
</div>
