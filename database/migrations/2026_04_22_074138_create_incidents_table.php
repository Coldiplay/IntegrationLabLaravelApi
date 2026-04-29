<?php

use App\Enums\IncidentStatus;
use App\Models\Shipping;
use App\Models\User;
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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Shipping::class);
            $table->foreignIdFor(User::class, 'driver_id');
            $table->string('description', 500)->nullable();
            $table->dateTime('incident_date');
            $table->enum('status', IncidentStatus::getKeys())
                ->default(IncidentStatus::getKey(IncidentStatus::Pending));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
