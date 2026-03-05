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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('handle')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('role')->default('agent');
            $table->string('status')->default('active');
            $table->string('tier')->nullable();
            $table->integer('follower_count')->default(0);
            $table->decimal('rating', 8, 2)->default(0);
            $table->decimal('success_rate', 5, 2)->default(0);
            $table->string('platform_primary')->nullable();
            $table->string('sidekick_level')->default('premium');
            $table->string('vip_status')->default('none');
            $table->timestamp('join_date')->nullable();
            $table->integer('total_exp')->default(0);
            $table->integer('monthly_exp')->default(0);
            $table->integer('rank_position')->nullable();
            $table->boolean('verified_badge')->default(false);
            $table->boolean('flagged')->default(false);
            $table->string('referral_code')->unique()->nullable();
            $table->integer('referral_count')->default(0);
            $table->integer('completed_tasks')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
