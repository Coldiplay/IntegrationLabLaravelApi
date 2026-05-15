<?php

use App\Enums\OrderStatus;
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
        Schema::create('shipping_orders', function (Blueprint $table) {
            $table->id();
            $table->datetime('order_date');
            $table->string('receiver_fio', 120);
            $table->string('receiver_phone', 20);
            $table->string('address', 255);
            $table->enum('status', OrderStatus::getKeys())->default(OrderStatus::getKey(OrderStatus::InProcessing));
            $table->dateTime('shipping_date'); //Каво? надо же какой-нибудь wished_receive_date и (опционально) wished_receive_time (nullable)
            $table->dateTime('sent_date')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_orders');
    }
};
