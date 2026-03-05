{{-- Controles --}}
<div class="flex items-center gap-3 mb-4">
    <flux:input.group class="">    
        <flux:button icon="minus" size="sm" wire:click="zoomOut" class="p-1!"></flux:button>
        <flux:input disabled size="sm" value="{{ number_format($zoom, 1) }}x" class="w-15! text-center!"/>    
        <flux:button icon="plus" size="sm" wire:click="zoomIn" class="p-1!"></flux:button>
    </flux:input.group>

    {{-- Toggle borde carta --}}
    <flux:button 
        size="sm"
        wire:click="toggleBorderCard"
        :variant="$showBorderCard ? null : 'filled'"
        class="p-1!"
    >
        <flux:icon.stop class="text-fuchsia-400 dark:text-fuchsia-300" />
    </flux:button>

    {{-- Toggle zona segura --}}
    <flux:button 
        size="sm"
        wire:click="toggleSafeArea"
        :variant="$showSafeArea ? null : 'filled'"
        class="p-1!"
    >
        <flux:icon.stop class="text-green-400 dark:text-green-300" />
    </flux:button>
</div>

{{-- Wrapper --}}
<div class="w-full p-1 overflow-auto">

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

            {{-- Total area (incluye margen de marcas) --}}
            <div 
                class="relative bg-white"
                style="
                    width: {{ $totalWidthPx }}px;
                    height: {{ $totalHeightPx }}px;
                "
            >

                @php
                    $w = $totalWidthPx;
                    $h = $totalHeightPx;
                    $cut = $cutMarginPx + $bleedPx;
                    $safeTop = $cutMarginPx + $bleedPx + $safeAreaPx;
                    $safeLeft = $safeTop;
                    $safeWidth = $cardWidthPx - ($safeAreaPx * 2);
                    $safeHeight = $cardHeightPx - ($safeAreaPx * 2);
                @endphp

                <svg 
                    class="absolute top-0 left-0 pointer-events-none z-20"
                    width="{{ $w }}"
                    height="{{ $h }}"
                >
                    <!-- Superior izquierda -->
                    <line x1="{{ $cut }}" y1="0"
                          x2="{{ $cut }}" y2="{{ $cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="0" y1="{{ $cut }}"
                          x2="{{ $cutMarginPx }}" y2="{{ $cut }}"
                          stroke="black" stroke-width="1"/>

                    <!-- Superior derecha -->
                    <line x1="{{ $w - $cut }}" y1="0"
                          x2="{{ $w - $cut }}" y2="{{ $cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="{{ $w }}" y1="{{ $cut }}"
                          x2="{{ $w - $cutMarginPx }}" y2="{{ $cut }}"
                          stroke="black" stroke-width="1"/>

                    <!-- Inferior izquierda -->
                    <line x1="{{ $cut }}" y1="{{ $h }}"
                          x2="{{ $cut }}" y2="{{ $h - $cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="0" y1="{{ $h - $cut }}"
                          x2="{{ $cutMarginPx }}" y2="{{ $h - $cut }}"
                          stroke="black" stroke-width="1"/>

                    <!-- Inferior derecha -->
                    <line x1="{{ $w - $cut }}" y1="{{ $h }}"
                          x2="{{ $w - $cut }}" y2="{{ $h - $cutMarginPx }}"
                          stroke="black" stroke-width="1"/>

                    <line x1="{{ $w }}" y1="{{ $h - $cut }}"
                          x2="{{ $w - $cutMarginPx }}" y2="{{ $h - $cut }}"
                          stroke="black" stroke-width="1"/>
                    @if($showBorderCard)
                    <!-- Zona carta -->
                    <rect
                        x="{{ $cutMarginPx + $bleedPx }}"
                        y="{{ $cutMarginPx + $bleedPx }}"
                        width="{{ $cardWidthPx }}"
                        height="{{ $cardHeightPx }}"
                        fill="none"
                        stroke="fuchsia"
                        stroke-width="1"
                    />
                    @endif
                    @if($showSafeArea)
                    <!-- Zona segura -->
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
                        top: {{ $cutMarginPx }}px;
                        left: {{ $cutMarginPx }}px;
                        width: {{ $cardWidthPx + ($cutMarginPx + $bleedPx) }}px;
                        height: {{ $cardHeightPx + ($cutMarginPx + $bleedPx) }}px;
                        background-image: url('/images/ene-3.png');
                    "   
                >

                    {{-- Carta real (zona de corte) --}}
                    <div 
                        class="absolute"
                        style="
                            top: {{ $bleedPx }}px;
                            left: {{ $bleedPx }}px;
                            width: {{ $cardWidthPx }}px;
                            height: {{ $cardHeightPx }}px;
                        "
                    >
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
                            {{ $enemigo->titulo }}
                        </div>
                    </div>

                    <div class="text-lg">
                        Vida: {{ $enemigo->vida }} |
                        Ataque: {{ $enemigo->ataque }}
                    </div>
                    
                    <div class=""
                        style="
                            position: absolute;
                            width: 500px;
                            height: 500px;
                            top: 700px;
                        "
                    >
                    <pre class="w-full bg-white">
                        

                    </pre>
                    </div>

                    @php
                        $acciones = array_filter([
                            $enemigo->accion1,
                            $enemigo->accion2,
                            $enemigo->accion3,
                        ]);
                    @endphp

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
                            @foreach($acciones as $accion)
                            <td class="text-[40px] text-center align-middle border border-black">
                                <div>{{ $accion }}</div>
                            </td>
                            @endforeach
                        </tr>
                    </table>



                    

                </div>

            </div>


        </div>

    </div>

</div>