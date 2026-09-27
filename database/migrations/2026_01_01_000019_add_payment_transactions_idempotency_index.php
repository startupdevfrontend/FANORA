<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Speed up webhook idempotency lookups. Not unique on purpose:
            // refund reversals reuse the original provider_transaction_id.
            $table->index(['subscription_id', 'provider_transaction_id'], 'payment_transactions_sub_txn_idx');
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropIndex('payment_transactions_sub_txn_idx');
        });
    }
};