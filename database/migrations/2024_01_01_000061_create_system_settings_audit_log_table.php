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
        Schema::create('system_settings_audit_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settings_id')->constrained('system_settings')->cascadeOnDelete();
            $table->unsignedBigInteger('changed_by_user_id')->nullable();
            $table->foreign('changed_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->json('diff')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings_audit_log');
    }
};
