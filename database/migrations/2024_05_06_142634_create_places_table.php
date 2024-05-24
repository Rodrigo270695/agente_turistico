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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 10, 8);
            $table->enum('dias_abierto_desde', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']);
            $table->enum('dias_cerrado_hasta', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']);
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->string('direccion',200);
            $table->string('tipo_acceso',15);
            $table->boolean('estado')->default(true);
            $table->foreignId('district_id')->constrained('districts');
            $table->foreignId('sub_category_id')->constrained('sub_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
