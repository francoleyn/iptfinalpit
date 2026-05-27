<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swap_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('offered_skill_id')->constrained('skills')->cascadeOnDelete();
            $table->foreignId('requested_skill_id')->constrained('skills')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swap_requests');
    }
};
