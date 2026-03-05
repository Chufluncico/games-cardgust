<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\whquestacg\EnemigoController;
use App\Http\Controllers\whquestacg\AccionEnemigoController;


/*
|--------------------------------------------------------------------------
| Rutas protegidas (acciones)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('whquestacg')
    ->name('whquestacg.')
    ->group(function () {

        Route::get('/enemies/create', [EnemigoController::class, 'create'])
            ->name('enemigos.create');

        Route::post('/enemies/store', [EnemigoController::class, 'store'])
            ->name('enemigos.store');

    });
    
/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::prefix('whquestacg')
    ->name('whquestacg.')
    ->group(function () {

        // Home del juego (pública)
        Route::get('/', function () {
            return view('whquestacg.home');
        })->name('home');

        // Listados públicos
        Route::get('/heroes', function () {
            return view('whquestacg.heroes.index');
        })->name('heroes.index');

        Route::get('/locations', function () {
            return view('whquestacg.locations.index');
        })->name('locations.index');

        Route::get('/enemies', [EnemigoController::class, 'index'])
            ->name('enemigos.index');


        Route::get('/enemies/{enemigo}', [EnemigoController::class, 'show'])
            ->name('enemigos.show');

        Route::get('/enemy-actions', [AccionEnemigoController::class, 'index'])
            ->name('acciones-enemigo');

    });


