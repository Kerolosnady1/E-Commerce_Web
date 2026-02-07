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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->enum('plan', ['free', 'basic', 'professional', 'enterprise'])->default('free');
            $table->string('status', 20)->default('active');
            $table->date('start_date');
            $table->date('renewal_date')->nullable();
            $table->decimal('monthly_cost', 10, 2)->default(0);
            $table->decimal('storage_used', 10, 2)->default(0);
            $table->decimal('storage_total', 10, 2)->default(5);
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
