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
        Schema::create('dynamic_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_data_table_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('tipo'); // text, number, date, select, boolean
            $table->boolean('es_requerido')->default(false);
            $table->boolean('es_fijo')->default(false); // true para Actor, Rol, Categoria
            $table->json('opciones')->nullable(); // para campos select
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_columns');
    }
};
