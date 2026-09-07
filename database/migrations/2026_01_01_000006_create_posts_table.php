<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->string('visibility', 30)->default('public')->index();
            $table->boolean('is_premium_paid')->default(false);
            $table->string('status', 20)->default('published')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'visibility', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};