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
        Schema::table('users', function (Blueprint $table) {
            $table->string('asaas_customer_id')->nullable()->after('password')->index();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('asaas_subscription_id')->nullable()->after('status')->index();
            $table->string('billing_type')->default('CREDIT_CARD')->after('asaas_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('asaas_customer_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['asaas_subscription_id', 'billing_type']);
        });
    }
};
