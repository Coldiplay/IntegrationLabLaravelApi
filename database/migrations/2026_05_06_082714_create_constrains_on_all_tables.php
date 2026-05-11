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
        Schema::table('cargos', function (Blueprint $table) {
            $table->foreign('shipping_id')
                ->references('id')
                ->on('shippings')
                ->cascadeOnDelete();

            $table->foreign('cargo_type_id')
                ->references('id')
                ->on('cargo_types');

            $table->foreign('shipping_order_id')
                ->references('id')
                ->on('shipping_orders')
                ->cascadeOnDelete();
        });

        Schema::table('shippings', function (Blueprint $table) {
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnDelete();
            $table->foreign('designated_driver_id')
                ->references('user_id')
                ->on('drivers')
                ->cascadeOnDelete();
        });

        Schema::table('shift_breaks', function (Blueprint $table) {
            $table->foreign('shift_id')
                ->references('id')
                ->on('drivers_shifts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropForeign('cargos_shipping_id_foreign');
            $table->dropForeign('cargos_cargo_type_id_foreign');
            $table->dropForeign('cargos_shipping_order_id_foreign');
        });
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropForeign('shippings_vehicle_id_foreign');
        });
    }
};
