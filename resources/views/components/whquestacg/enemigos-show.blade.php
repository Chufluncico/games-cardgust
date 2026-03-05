<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\whquestacg\Enemigo;
use App\Models\whquestacg\AccionEnemigo;

new class extends Component
{

    public Enemigo $enemigo;
    public float $zoom = 1;
    protected int $dpi = 300;
    protected float $cardWidthMm = 56;
    protected float $cardHeightMm = 89;
    public int $cardWidthPx;
    public int $cardHeightPx;
    protected float $bleedMm = 3;
    protected float $cutMarginMm = 3;

    public int $bleedPx;
    public int $cutMarginPx;
    public int $totalWidthPx;
    public int $totalHeightPx;

    protected float $cropMarkLengthMm = 3;
    public int $cropMarkLengthPx;

    protected float $safeAreaMm = 3;
    public int $safeAreaPx;

    public bool $showSafeArea = true;
    public bool $showBorderCard = true;


    public function mount(Enemigo $enemigo)
    {
        $this->enemigo = $enemigo;
        $this->cardWidthPx  = $this->mmToPx($this->cardWidthMm);
        $this->cardHeightPx = $this->mmToPx($this->cardHeightMm);
        $this->bleedPx = $this->mmToPx($this->bleedMm);
        $this->cutMarginPx = $this->mmToPx($this->cutMarginMm);
        $this->totalWidthPx  = $this->cardWidthPx + ($this->bleedPx * 2) + ($this->cutMarginPx * 2);
        $this->totalHeightPx = $this->cardHeightPx + ($this->bleedPx * 2) + ($this->cutMarginPx * 2);
        $this->cropMarkLengthPx = $this->mmToPx($this->cropMarkLengthMm);
        $this->safeAreaPx = $this->mmToPx($this->safeAreaMm);

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

    public function zoomReset(): void
    {
        $this->zoom = 1.0;
    }


    #[Computed]
    public function scaledHeight()
    {
        //le sumo el bleed y lo multiplico por dos para hacerlo mas grande y que no aparezca el scroll
        return round(($this->totalHeightPx + $this->bleedPx * 2) * $this->zoom);
    }
    
    public function toggleBorderCard()
    {
        $this->showBorderCard = ! $this->showBorderCard;
    }

    public function toggleSafeArea()
    {
        $this->showSafeArea = ! $this->showSafeArea;
    }

    #[Computed]
    public function accionesCompletas2()
    {
        return AccionEnemigo::whereIn('nombre', array_filter([
            $this->enemigo->accion1,
            $this->enemigo->accion2,
            $this->enemigo->accion3,
        ]))->get();
    }

    #[Computed]
    public function accionesCompletas()
    {
        $nombres = collect([
            $this->enemigo->accion1,
            $this->enemigo->accion2,
            $this->enemigo->accion3,
        ])
        ->filter()
        ->unique()     // 🔥 elimina nombres repetidos
        ->values();

        if ($nombres->isEmpty()) {
            return collect();
        }

        return AccionEnemigo::whereIn('nombre', $nombres)
            ->get()
            ->filter(fn ($accion) => filled($accion->descripcion)) // 🔥 solo con descripcion
            ->sortBy(fn ($accion) => $nombres->search($accion->nombre))
            ->values();
    }



};
?>

<div class="min-h-full flex flex-col items-center p-6 bg-zinc-50 rounded-xl">

    <x-whquestacg.enemigos.show-preview
        :enemigo="$enemigo"
    /> 

</div>



<script>
function runTextFit() {
    if (!window.textFit) return;

    // TITULO
    document.querySelectorAll('.fitTitulo').forEach(el => {
        if (!el.dataset.fitted && el.clientWidth > 0 && el.clientHeight > 0) {
            textFit(el, {
                multiLine: false,
                alignHoriz: true,
                alignVert: true,
                reProcess: true,
                minFontSize: 8,
                maxFontSize: 500
            });
            el.dataset.fitted = "true";
        }
    });

    // EFECTOS
    document.querySelectorAll('.fitAcciones').forEach(el => {
        if (!el.dataset.fitted && el.clientWidth > 0 && el.clientHeight > 0) {
            textFit(el, {
                multiLine: true,
                alignHoriz: false,
                alignVert: false,
                reProcess: true,
                minFontSize: 20,
                maxFontSize: 36
            });
            el.dataset.fitted = "true";
        }
    });

    // TEXTO
    document.querySelectorAll('.fitTexto').forEach(el => {
        if (!el.dataset.fitted && el.clientWidth > 0 && el.clientHeight > 0) {
            textFit(el, {
                multiLine: true,
                alignHoriz: false,
                alignVert: false,
                reProcess: true,
                minFontSize: 8,
                maxFontSize: 28
            });
            el.dataset.fitted = "true";
        }
    });
}

document.addEventListener("DOMContentLoaded", runTextFit);

const observer = new MutationObserver(() => {
    runTextFit();
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});
</script>



