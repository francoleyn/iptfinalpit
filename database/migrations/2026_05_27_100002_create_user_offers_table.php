<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('intermediate');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_offers');
    }
};
