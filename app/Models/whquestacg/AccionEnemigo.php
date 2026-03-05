<?php

namespace App\Models\whquestacg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;


class AccionEnemigo extends Model
{
    use SoftDeletes;

    protected $table = 'whq_acciones_enemigo';

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
