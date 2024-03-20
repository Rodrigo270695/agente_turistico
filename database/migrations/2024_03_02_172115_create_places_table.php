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
            $table->string('name', 100)->unique();
            $table->string('department', 50);
            $table->string('province', 50);
            $table->string('district', 100);
            $table->string('address', 255);
            $table->text('description');
            $table->string('type_place',50);
            $table->string('care_day',50);
            $table->string('opening_hours',50);
            $table->double('price');
            $table->string('access', 50);
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
