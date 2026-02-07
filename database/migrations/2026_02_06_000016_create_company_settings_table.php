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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name_ar', 255)->nullable();
            $table->string('company_name_en', 255)->nullable();
            $table->string('currency', 20)->default('SAR');
            $table->string('timezone', 50)->default('Asia/Riyadh');
            $table->string('logo')->nullable();
            $table->string('seal')->nullable();

            // Tax Settings
            $table->boolean('tax_enabled')->default(true);
            $table->string('vat_number', 50)->nullable();
            $table->decimal('default_tax_rate', 5, 2)->default(15);
            $table->boolean('prices_include_tax')->default(true);
            $table->boolean('show_vat_on_invoice')->default(true);

            // Print Template
            $table->enum('default_print_template', ['classic', 'modern', 'minimal'])->default('classic');

            // Notification preferences as JSON
            $table->json('notification_preferences')->nullable();

            // Storage
            $table->float('storage_used_mb')->default(0);
            $table->float('storage_quota_mb')->default(10240);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
