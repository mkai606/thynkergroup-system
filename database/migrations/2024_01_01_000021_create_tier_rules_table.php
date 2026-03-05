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
        Schema::create('tier_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('tier_a_min_followers')->default(10000);
            $table->integer('tier_b_min_followers')->default(5000);
            $table->integer('tier_c_min_followers')->default(3000);
            $table->integer('tier_d_min_followers')->default(2000);
            $table->integer('tier_e_min_followers')->default(0);
            $table->boolean('auto_promotion')->default(true);
            $table->boolean('auto_downgrade')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tier_rules');
    }
};
