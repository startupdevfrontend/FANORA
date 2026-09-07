<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creator_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('display_name', 120)->nullable();
            $table->string('tagline', 300)->nullable();
            $table->unsignedBigInteger('subscription_price_cents')->nullable();
            $table->string('verification_status', 20)->default('pending')->index();
            $table->string('rejection_reason', 500)->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->integer('subscriber_count')->default(0)->index();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_profiles');
    }
};