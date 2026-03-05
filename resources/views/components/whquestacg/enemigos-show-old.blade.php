<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\whquestacg\Enemigo;

new class extends Component
{

    public Enemigo $enemigo;
    public float $zoom = 0.4;
    protected int $dpi = 300;
    protected float $cardWidthMm = 63;
    protected float $cardHeightMm = 88;
    public int $cardWidthPx;
    public int $cardHeightPx;
    protected float $bleedMm = 3;
    protected float $cutMarginMm = 3;


    public function mount(Enemigo $enemigo)
    {
        $this->enemigo = $enemigo;
        $this->cardWidthPx  = $this->mmToPx($this->cardWidthMm);
        $this->cardHeightPx = $this->mmToPx($this->cardHeightMm);
        $this->bleedPx = $this->mmToPx($this->bleedMm);
        $this->cutMarginPx = $this->mmToPx($this->cutMarginMm);
        $this->totalWidthPx  = $this->cardWidthPx + ($this->bleedPx * 2) + ($this->cutMarginPx * 2);
        $this->totalHeightPx = $this->cardHeightPx + ($this->bleedPx * 2) + ($this->cutMarginPx * 2);
    }

    protected function mmToPx(float $mm): int
    {
        return (int) round(($mm / 25.4) * $this->dpi);
    }

    public function zoomIn()
    {
        $this->zoom = min($this->zoom + 0.1, 2);
    }

    public function zoomOut()
    {
        $this->zoom = max($this->zoom - 0.1, 0.2);
    }

    #[Computed]
    public function scaledHeight()
    {
        return round($this->cardHeightPx * $this->zoom);
    }
   

};
?>

<div class="min-h-full flex flex-col items-center p-6 bg-slate-100 rounded-xl">

    {{-- Controles --}}
    <div class="flex items-center gap-4 mb-4">
        <flux:button size="sm" wire:click="zoomOut">-</flux:button>
        <span class="text-sm font-medium">
            {{ number_format($zoom, 1) }}x
        </span>
        <flux:button size="sm" wire:click="zoomIn">+</flux:button>
    </div>

    {{-- Wrapper --}}
    <div class="w-full rounded-xl overflow-auto">

        {{-- Spacer que define altura real --}}
        <div 
            class="relative flex justify-center"
            style="height: {{ $this->scaledHeight }}px;"
        >

            {{-- Capa que se escala --}}
            <div 
                class="absolute top-0 transition-transform duration-200 ease-out"
                style="
                    transform: scale({{ $zoom }});
                    transform-origin: top center;
                "
            >

                {{-- Carta tamaño real --}}
                <div 
                    class="relative bg-white border border-red-600"
                    style="
                        width: {{ $cardWidthPx }}px;
                        height: {{ $cardHeightPx }}px;
                    "
                >
                    <div class="p-10">
                        <div class="text-3xl font-bold mb-4">
                            {{ $enemigo->titulo }}
                        </div>

                        <div class="text-lg">
                            Vida: {{ $enemigo->vida }} |
                            Ataque: {{ $enemigo->ataque }}
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


</div>



