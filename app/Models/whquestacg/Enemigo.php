<?php

namespace App\Models\whquestacg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Enemigo extends Model
{
    use SoftDeletes;

    protected $table = 'whq_enemigos';

    protected $fillable = [
        'user_id',
        'titulo',
        'copias',
        'familia',
        'tipo',
        'nivel',
        'resistencia',
        'vida',
        'ataque',
        'efecto1',
        'efecto2',
        'efecto3',
        'accion1',
        'accion2',
        'accion3',
        'nemesis',
        'flavor',
        'imagen',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNumeroAccionesAttribute(): int
    {
        return collect([
            $this->accion1,
            $this->accion2,
            $this->accion3,
        ])->filter()->count();
    }

    public function getFondoCartaAttribute(): string
    {
        $acciones = $this->numero_acciones;
        $tipo = $this->tipo;

        // Ejemplo de reglas
        if ($tipo === 'Élite') {
            return match($acciones) {
                1 => 'images/eli-1.png',
                2 => 'images/eli-2.png',
                3 => 'images/eli-3.png',
                default => 'images/nemesis_1.png',
            };
        }elseif ($tipo === 'PNJ'){
            return match($acciones) {
                1 => 'images/ene-1.png',
                2 => 'images/ene-2.png',
                3 => 'images/ene-3.png',
                default => 'images/ene-1.png',
            };
        }elseif ($tipo === 'Antagonista'){
            return match($acciones) {
                1 => 'images/ant-1.png',
                2 => 'images/ant-2.png',
                3 => 'images/ant-3.png',
                default => 'images/ant-1.png',
            };
        }else{
            return match($acciones) {
                1 => 'images/ene-1.png',
                2 => 'images/ene-2.png',
                3 => 'images/ene-3.png',
                default => 'images/ene-1.png',
            };
        }
    }


}
