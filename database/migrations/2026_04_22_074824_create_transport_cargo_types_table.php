<?php

use App\Models\CargoType;
use App\Models\Vehicle;
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
        Schema::create('transport_cargo_types', function (Blueprint $table) {
            $table->foreignIdFor(Vehicle::class)->constrained('vehicles');
            $table->foreignIdFor(CargoType::class)->constrained('cargo_types');
            $table->primary(['vehicle_id', 'cargo_type_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_cargo_types');
    }
};
