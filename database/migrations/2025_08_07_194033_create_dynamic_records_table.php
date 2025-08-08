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
        Schema::create('dynamic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_data_table_id')->constrained()->onDelete('cascade');
            $table->json('datos'); // almacena los datos como JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_records');
    }
};
