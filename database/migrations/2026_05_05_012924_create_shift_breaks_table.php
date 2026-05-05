<?php

use App\Models\DriversShift;
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
        Schema::create('shift_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DriversShift::class, 'shift_id')
                ->constrained('shifts', 'id')
                ->cascadeOnDelete();
            $table->dateTime('start');
            $table->dateTime('end')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_breaks');
    }
};
