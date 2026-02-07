<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // basic, pro, etc.
            $table->string('name_ar');
            $table->string('name_en');
            $table->decimal('price_monthly', 8, 2);
            $table->string('currency')->default('SAR');
            $table->integer('max_users')->nullable(); // null = unlimited
            $table->integer('storage_limit_gb');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
