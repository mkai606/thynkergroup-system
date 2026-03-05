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
        Schema::create('task_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('task_submissions')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->boolean('auto_verified')->default(false);
            $table->string('detected_handle')->nullable();
            $table->string('fraud_risk')->nullable();
            $table->integer('exp_awarded')->default(0);
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->foreign('reviewer_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_approvals');
    }
};
