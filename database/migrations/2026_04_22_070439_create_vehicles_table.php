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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number_plate', 15)->unique();
            $table->string('brand', 20);
            $table->string('model', 40);
            //TODO: Посмотреть, есть ли возможность множественного значения (как в c# A|B)
            $table->enum('needed_rights', ['A', 'B']);
            $table->float('lifting_capacity');
            //TODO: Заполнить enum body_type
            $table->enum('body_type', []);
            //TODO: vehicleSize (dimensions)
            //TODO: bodySize (dimensions)
            $table->float('max_cargo_value');
            $table->float('vehicle_weight');
            $table->float('number_of_axes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
