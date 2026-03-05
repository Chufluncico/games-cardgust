<?php

namespace App\Http\Controllers\whquestacg;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\whquestacg\Enemigo;

class EnemigoController extends Controller
{

    public function index()
    {
        /*
        $enemigos = Enemigo::orderBy('familia')
                            ->orderBy('titulo')
                            ->get();

        return view('whquestacg.enemigos', compact('enemigos'));
        */
        return view('whquestacg.enemigos');
    }


    public function create()
    {
        return view('whquestacg.enemigos.create');
    }


    public function store(Request $request, Enemigo $enemigo)
    {
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'vida'   => 'nullable|integer|min:0',
            'ataque' => 'nullable|integer|min:0',
        ]);

        //$request['user_id'] = Auth::id();

        Enemigo::create([
            'user_id' => auth()->id(),
            'titulo' => $request->titulo,
            'vida' => $request->vida,
            'ataque' => $request->ataque,
        ]);

        return redirect()
            ->route('whquestacg.enemigos.index')
            ->with('status', 'Enemigo creado correctamente.');
    }


    public function show(Enemigo $enemigo)
    {
        return view('whquestacg.enemigos.show', compact('enemigo'));
    }







}
