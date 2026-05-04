<?php

use App\Enums\ShippingStatus;
use App\Models\User;
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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_point', 120);
            $table->dateTime('estimated_delivery_date');
            $table->dateTime('delivery_date')->nullable();
            $table->enum('shipping_status', ShippingStatus::getKeys())
                ->default(ShippingStatus::getKey(ShippingStatus::InProcessing));
            $table->date('shipping_date');
            $table->dateTime('shipped_date')->nullable();
            $table->foreignIdFor(Vehicle::class);
            $table->foreignIdFor(User::class, 'designated_driver_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
