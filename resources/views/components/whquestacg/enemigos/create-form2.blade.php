<div>
<form wire:submit="save" class="space-y-6">

    <div class="grid grid-cols-2 gap-4">

        


<flux:field>    
    <flux:label>Titulo</flux:label>    
    <flux:input wire:model="titulo" />    
    <flux:error name="titulo" />
</flux:field>



        <flux:input type="number" label="Copias"
            wire:model="copias" min="1" />

        <flux:input label="Familia" wire:model="familia" />
        <flux:input label="Tipo" wire:model="tipo" />

        <flux:input type="number" label="Nivel" wire:model="nivel" />
        <flux:input type="number" label="Resistencia" wire:model="resistencia" />
        <flux:input type="number" label="Vida" wire:model="vida" />
        <flux:input type="number" label="Ataque" wire:model="ataque" />

    </div>

    <div class="grid grid-cols-3 gap-4">

        <flux:textarea label="Efecto 1" wire:model="efecto1" />
        <flux:textarea label="Efecto 2" wire:model="efecto2" />
        <flux:textarea label="Efecto 3" wire:model="efecto3" />

    </div>

    <div class="grid grid-cols-3 gap-4">

        <flux:textarea label="Acción 1" wire:model="accion1" />
        <flux:textarea label="Acción 2" wire:model="accion2" />
        <flux:textarea label="Acción 3" wire:model="accion3" />

    </div>

    <flux:textarea label="Texto Nemesis" wire:model="nemesis" />

    <flux:textarea label="Flavor" wire:model="flavor" />

    <flux:input label="Imagen (ruta)" wire:model="imagen" />

    <div class="flex justify-end">
        <flux:button type="submit" variant="primary">
            Guardar
        </flux:button>
    </div>

</form>
</div>