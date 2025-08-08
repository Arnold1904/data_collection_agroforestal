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
        Schema::create('recoleccion_de_datos', function (Blueprint $table) {
            $table->id();
            $table->string('actor');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('rol_sector_agropecuario_id')->constrained('rol_sector_agropecuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recoleccion_de_datos');
    }
};
