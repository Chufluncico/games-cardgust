<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\whquestacg\Enemigo;
use App\Models\whquestacg\AccionEnemigo;


new class extends Component
{
    public string $titulo = '';
    public int $copias = 1;
    public ?string $familia = null;
    public array $familiaSugerencias = [];
    public bool $mostrarSugerenciasFamilia = false;
    public ?string $tipo = null;
    public array $tiposDisponibles = [
        '' => '',
        'elite' => 'Élite',
        'antagonista' => 'Antagonista',
        'pnj' => 'PNJ',
    ];
    public ?int $nivel = null;
    public ?int $vida = 0;
    public ?int $ataque = 0;
    public ?int $resistencia = 0;

    public ?string $efecto1 = null;
    public ?string $efecto2 = null;
    public ?string $efecto3 = null;

    public ?int $accion1 = null;
    public ?int $accion2 = null;
    public ?int $accion3 = null;

    public ?string $nemesis = null;
    public ?string $flavor = null;

    public ?string $imagen = null;

    public float $zoom = 0.5;
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


    public function mount()
    {
        $this->cardWidthPx  = $this->mmToPx($this->cardWidthMm);
        $this->cardHeightPx = $this->mmToPx($this->cardHeightMm);
        $this->bleedPx = $this->mmToPx($this->bleedMm);
        $this->cutMarginPx = $this->mmToPx($this->cutMarginMm);
        $this->totalWidthPx  = $this->cardWidthPx + ($this->bleedPx * 2) + ($this->cutMarginPx * 2);
        $this->totalHeightPx = $this->cardHeightPx + ($this->bleedPx * 2) + ($this->cutMarginPx * 2);
        $this->cropMarkLengthPx = $this->mmToPx($this->cropMarkLengthMm);
        $this->safeAreaPx = $this->mmToPx($this->safeAreaMm);
    }

    protected function rules()
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'copias' => ['required', 'integer', 'min:1'],

            'familia' => ['required', 'string', 'max:255'],
            'tipo' => ['nullable', 'string', 'max:255'],

            'nivel' => ['nullable', 'integer', 'min:0', 'max:10'],
            'resistencia' => ['nullable', 'integer', 'min:0', 'max:999'],
            'vida' => ['nullable', 'integer', 'min:0', 'max:999'],
            'ataque' => ['nullable', 'integer', 'min:0', 'max:999'],

            'efecto1' => ['nullable', 'string'],
            'efecto2' => ['nullable', 'string'],
            'efecto3' => ['nullable', 'string'],

            'accion1' => ['nullable', 'string'],
            'accion2' => ['nullable', 'string'],
            'accion3' => ['nullable', 'string'],

            'nemesis' => ['nullable', 'string'],
            'flavor' => ['nullable', 'string'],

            'imagen' => ['nullable', 'string'],
        ];
    }

    protected function messages()
    {
        return [
            'titulo.required' => 'El enemigo debe tener un título.',
            'titulo.max' => 'El título es demasiado largo.',
        ];
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
    public function zoomOffsetX(): float
    {
        return (($this->totalWidthPx * $this->zoom) - $this->totalWidthPx) / 2;
    }

    #[Computed]
    public function scaledWidth(): float
    {
        return $this->totalWidthPx * $this->zoom;
    }


    #[Computed]
    public function familiasDisponiblesOld(): array
    {
        return Enemigo::query()
            ->whereNotNull('familia')
            ->distinct()
            ->orderBy('familia')
            ->pluck('familia')
            ->toArray();
    }

    public function updatedFamiliaOld()
    {
        if (!$this->familia) {
            $this->familiaSugerencias = [];
            $this->mostrarSugerenciasFamilia = false;
            return;
        }

        $this->familiaSugerencias = Enemigo::query()
            ->where('familia', 'like', '%'.$this->familia.'%')
            ->distinct()
            ->limit(5)
            ->pluck('familia')
            ->toArray() ?? [];

        $this->mostrarSugerencias = !empty($this->familiaSugerencias);
    }

    public function updatedFamilia()
    {
        $query = Enemigo::query()
            ->whereNotNull('familia');

        if (!empty($this->familia)) {
            $query->where('familia', 'like', '%'.$this->familia.'%');
        }

        $this->familiaSugerencias = $query
            ->distinct()
            ->orderBy('familia')
            ->limit(5)
            ->pluck('familia')
            ->toArray();

        $this->mostrarSugerenciasFamilia = !empty($this->familiaSugerencias);
    }

    public function seleccionarFamilia(string $valor)
    {
        $this->familia = $valor;
        $this->mostrarSugerenciasFamilia = false;
    }


    #[Computed]
    public function accionesDisponibles()
    {
        return AccionEnemigo::orderBy('nombre')->get();
    }



    public function save()
    {
        // 1️⃣ Recoger datos del componente
        $data = $this->all();

        // 2️⃣ Convertir strings vacíos en null
        $data = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $data);

        // 3️⃣ Reinyectar datos ya limpios en el componente
        $this->fill($data);

        $data = $this->validate();

        $data['user_id'] = auth()->id();

        Enemigo::create($data);

        return redirect()->route('whquestacg.enemigos.index');
    }




};
?>




<div class="">

    <div class="flex gap-4 mb-6">

        <flux:heading size="xl">Nuevo Enemigo</flux:heading>

        <flux:button 
            size="sm"
            wire:click="save"
            variant="primary"
        >
            Guardar enemigo
        </flux:button>

    </div>

    <div class="grid grid-cols-2 gap-8">

        {{-- FORMULARIO --}}
        <div>
            <x-whquestacg.enemigos.create-form />
        </div>

        {{-- PREVIEW --}}
        <div class="min-h-full flex flex-col items-center p-6 bg-zinc-50 rounded-xl">
            
            <x-whquestacg.enemigos.create-preview
                :titulo="$titulo"
                :vida="$vida"
                :ataque="$ataque"
                :efecto1="$efecto1"
                :efecto2="$efecto2"
                :efecto3="$efecto3"
                :accion1="$accion1"
                :accion2="$accion2"
                :accion3="$accion3"
                :nemesis="$nemesis"
                :flavor="$flavor"
            />
        </div>

    </div>
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
    document.querySelectorAll('.fitEfectos').forEach(el => {
        if (!el.dataset.fitted && el.clientWidth > 0 && el.clientHeight > 0) {
            textFit(el, {
                multiLine: true,
                alignHoriz: false,
                alignVert: false,
                reProcess: true,
                minFontSize: 8,
                maxFontSize: 500
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
