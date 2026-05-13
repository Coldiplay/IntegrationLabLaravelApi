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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_phone', 32)->index();
            $table->string('channel', 32)->index();
            $table->string('template_key')->nullable();
            $table->integer('template_version')->nullable();
            $table->string('locale', 8)->default('ru');
            $table->json('payload')->nullable();
            $table->text('rendered_body')->nullable();
            $table->string('status', 32)->index();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->unsignedInteger('attempts_count')->default(0);
            $table->string('external_message_id')->nullable()->index();
            $table->uuid('correlation_id')->nullable()->index();
            $table->string('idempotency_key', 128)->nullable();
            $table->string('error_code', 64)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->unique(['idempotency_key'], 'notifications_idem_key_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
