<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_creator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_profile_id')->constrained('creator_profiles')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['creator_profile_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_creator');
    }
};