<?php

use App\Models\CargoType;
use App\Models\Shipping;
use App\Models\ShippingOrder;
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
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('description', 200)->nullable();
            $table->double('weight');
            //TODO: Сделать сложное свойство Dimensions (Высота, длина, ширина)
            //$table->
            $table->enum('danger_level', ['Low', 'Medium', 'High', 'Extreme'])->nullable()->default(0);
            $table->foreignIdFor(ShippingOrder::class);
            $table->foreignIdFor(Shipping::class);
            $table->foreignIdFor(CargoType::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
