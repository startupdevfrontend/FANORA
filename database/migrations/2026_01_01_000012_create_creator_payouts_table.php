<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creator_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->date('period_started_on');
            $table->date('period_ended_on');
            $table->unsignedBigInteger('gross_amount_cents')->default(0);
            $table->unsignedBigInteger('fees_cents')->default(0);
            $table->unsignedBigInteger('net_amount_cents')->default(0);
            $table->string('status', 20)->default('pending')->index();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['creator_id', 'status']);
            $table->unique(['creator_id', 'period_started_on', 'period_ended_on'], 'creator_payouts_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_payouts');
    }
};