<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloodSugarLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('blood_sugar_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('level', 5, 2); // Blood sugar level
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blood_sugar_levels');
    }
}