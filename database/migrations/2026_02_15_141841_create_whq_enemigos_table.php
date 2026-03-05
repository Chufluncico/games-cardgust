<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whq_enemigos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            $table->string('titulo');
            $table->unsignedTinyInteger('copias')->default(1);
            $table->string('familia')->nullable();
            $table->string('tipo')->nullable();
            $table->unsignedTinyInteger('nivel')->nullable();
            $table->unsignedTinyInteger('resistencia')->nullable();
            $table->unsignedTinyInteger('vida')->nullable();
            $table->unsignedTinyInteger('ataque')->nullable();
            $table->text('efecto1')->nullable();
            $table->text('efecto2')->nullable();
            $table->text('efecto3')->nullable();
            $table->text('accion1')->nullable();
            $table->text('accion2')->nullable();
            $table->text('accion3')->nullable();
            $table->text('nemesis')->nullable();
            $table->text('flavor')->nullable();
            $table->string('imagen')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('whq_acciones_enemigo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whq_enemigos');
        Schema::dropIfExists('whq_acciones_enemigo');
    }
};
