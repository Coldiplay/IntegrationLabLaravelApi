<?php

use App\Enums\BodyType;
use App\Enums\Rights;
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
            $table->mediumInteger('needed_rights', Rights::getValues());
            $table->float('lifting_capacity');
            $table->enum('body_type', BodyType::getKeys());

            $table->double('vehicle_size_length');
            $table->double('vehicle_size_width');
            $table->double('vehicle_size_height');

            $table->double('body_size_length');
            $table->double('body_size_width');
            $table->double('body_size_height');

            $table->float('max_cargo_volume');
            $table->float('vehicle_weight');
            $table->tinyInteger('number_of_axes', unsigned: true);
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
