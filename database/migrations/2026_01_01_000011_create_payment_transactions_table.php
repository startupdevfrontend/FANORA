<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Financial settlement record per paid charge.
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('gross_amount_cents');
            $table->unsignedInteger('commission_rate')->default(0);
            $table->unsignedBigInteger('commission_cents')->default(0);
            $table->unsignedBigInteger('gateway_fee_cents')->default(0);
            $table->unsignedBigInteger('creator_amount_cents')->default(0);
            $table->string('status', 20)->default('pending')->index();
            $table->string('provider', 50)->nullable();
            $table->string('provider_transaction_id', 191)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['creator_id', 'status']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};