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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->json('tier_config')->nullable();
            $table->json('vip_config')->nullable();
            $table->json('campaign_config')->nullable();
            $table->json('reward_config')->nullable();
            $table->json('fraud_config')->nullable();
            $table->json('payment_config')->nullable();
            $table->json('ai_engine_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
