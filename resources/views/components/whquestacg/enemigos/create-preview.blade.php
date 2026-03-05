@props([
    'titulo' => null,
    'vida' => null,
    'ataque' => null,
    'efecto1' => null,
    'efecto2' => null,
    'efecto3' => null,
    'accion1' => null,
    'accion2' => null,
    'accion3' => null,
    'nemesis' => null,
    'flavor' => null,
])

{{-- Controles --}}
<div class="flex items-center gap-3 mb-4">
    {{-- Reset zoom --}}
    <flux:button 
        size="sm" 
        wire:click="zoomReset"
        class="p-1! px-2! font-normal"
    >
        100%
    </flux:button>

    <flux:input.group>    
        <flux:button icon="minus" size="sm" wire:click="zoomOut" class="p-1!"></flux:button>
        <flux:input disabled size="sm" value="{{ number_format($this->zoom * 100, 0) }}%" class="w-15! text-center!"/>    
        <flux:button icon="plus" size="sm" wire:click="zoomIn" class="p-1!"></flux:button>
    </flux:input.group>

    {{-- Toggle borde carta --}}
    <flux:button 
        size="sm"
        wire:click="toggleBorderCard"
        :variant="$this->showBorderCard ? null : 'filled'"
        class="p-1!"
    >
        <flux:icon.stop class="text-fuchsia-400 dark:text-fuchsia-300" />
    </flux:button>

    {{-- Toggle zona segura --}}
    <flux:button 
        size="sm"
        wire:click="toggleSafeArea"
        :variant="$this->showSafeArea ? null : 'filled'"
        class="p-1!"
    >
        <flux:icon.stop class="text-green-400 dark:text-green-300" />
    </flux:button>
</div>

{{-- Wrapper --}}
<div class="w-full p-1 overflow-auto">

    {{-- Spacer que define altura real --}}
    <div 
    class="relative m-auto"
    style="
        height: {{ $this->scaledHeight }}px;
        width: {{ $this->scaledWidth }}px;
    "
>

        {{-- Capa que se escala --}}
        <div 
            class="absolute top-0 transition-transform duration-200 ease-out"
            style="
    transform: scale({{ $this->zoom }});
    transform-origin: top left;
"
        >

            {{-- Total area --}}
            <div 
                class="relative bg-white"
                style="
                    width: {{ $this->totalWidthPx }}px;
                    height: {{ $this->totalHeightPx }}px;
                "
            >

                @php
                    $w = $this->totalWidthPx;
                    $h = $this->totalHeightPx;

                    $cut = $this->cutMarginPx + $this->bleedPx;

                    $safeTop = $this->cutMarginPx + $this->bleedPx + $this->safeAreaPx;
                    $safeLeft = $safeTop;

                    $safeWidth = $this->cardWidthPx - ($this->safeAreaPx * 2);
                    $safeHeight = $this->cardHeightPx - ($this->safeAreaPx * 2);
                @endphp

                <svg 
                    class="absolute top-0 left-0 pointer-events-none z-20"
                    width="{{ $w }}"
                    height="{{ $h }}"
                >

                    <!-- Marcas de corte -->
                    <line x1="{{ $cut }}" y1="0"
                          x2="{{ $cut }}" y2="{{ $this->cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="0" y1="{{ $cut }}"
                          x2="{{ $this->cutMarginPx }}" y2="{{ $cut }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="{{ $w - $cut }}" y1="0"
                          x2="{{ $w - $cut }}" y2="{{ $this->cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="{{ $w }}" y1="{{ $cut }}"
                          x2="{{ $w - $this->cutMarginPx }}" y2="{{ $cut }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="{{ $cut }}" y1="{{ $h }}"
                          x2="{{ $cut }}" y2="{{ $h - $this->cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="0" y1="{{ $h - $cut }}"
                          x2="{{ $this->cutMarginPx }}" y2="{{ $h - $cut }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="{{ $w - $cut }}" y1="{{ $h }}"
                          x2="{{ $w - $cut }}" y2="{{ $h - $this->cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="{{ $w }}" y1="{{ $h - $cut }}"
                          x2="{{ $w - $this->cutMarginPx }}" y2="{{ $h - $cut }}"
                          stroke="black" stroke-width="1"/>

                    @if($this->showBorderCard)
                        <rect
                            x="{{ $this->cutMarginPx + $this->bleedPx }}"
                            y="{{ $this->cutMarginPx + $this->bleedPx }}"
                            width="{{ $this->cardWidthPx }}"
                            height="{{ $this->cardHeightPx }}"
                            fill="none"
                            stroke="fuchsia"
                            stroke-width="1"
                        />
                    @endif

                    @if($this->showSafeArea)
                        <rect
                            x="{{ $safeLeft }}"
                            y="{{ $safeTop }}"
                            width="{{ $safeWidth }}"
                            height="{{ $safeHeight }}"
                            fill="none"
                            stroke="MediumSpringGreen"
                            stroke-width="1"
                            stroke-dasharray="12 8"
                        />
                    @endif
                </svg>

                {{-- Área con bleed --}}
                <div 
                    class="absolute bg-black"
                    style="
                        top: {{ $this->cutMarginPx }}px;
                        left: {{ $this->cutMarginPx }}px;
                        width: {{ $this->cardWidthPx + ($this->cutMarginPx + $this->bleedPx) }}px;
                        height: {{ $this->cardHeightPx + ($this->cutMarginPx + $this->bleedPx) }}px;
                        background-image: url('/images/ene-3.png');
                    "
                >

                    {{-- Carta real --}}
                    <div 
                        class="absolute"
                        style="
                            top: {{ $this->bleedPx }}px;
                            left: {{ $this->bleedPx }}px;
                            width: {{ $this->cardWidthPx }}px;
                            height: {{ $this->cardHeightPx }}px;
                        "
                    >
                        {{-- Titulo --}}
                        <div
                            class="fitTitulo font-[CelestiaAntiquaSB] mb-4 overflow-hidden"
                            style="
                                position: absolute;
                                width: 485px;
                                height: 66px;
                                left: 140px;
                                top: 27px;
                            "
                        >
                            {{ $titulo }}
                        </div>
                    </div>{{-- /Carta real --}}

                    

                    <table class="table-fixed border-collapse border border-black"
                        style="
                            position: absolute;
                            width: 610px;
                            height: 70px;
                            top: 560px;
                            left: 54px;
                        "
                    >
                        <tr class="h-full">
                            
                                <td class="text-[40px] text-center align-middle border border-black">
                                    
                                </td>
                            
                        </tr>
                    </table>

                </div>

            </div>

        </div>

    </div>

</div>
