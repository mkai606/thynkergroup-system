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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('platform');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->string('access_level')->default('public');
            $table->integer('min_followers')->default(0);
            $table->integer('exp_reward')->default(0);
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->integer('slots_total')->default(10);
            $table->integer('slots_taken')->default(0);
            $table->date('deadline')->nullable();
            $table->boolean('instructions_locked')->default(false);
            $table->text('hidden_details')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
