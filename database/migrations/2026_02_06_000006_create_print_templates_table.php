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
        Schema::create('print_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->enum('template_type', ['sales_invoice', 'purchase_order', 'inventory_report', 'customer_statement']);
            $table->enum('style', ['standard', 'thermal', 'minimal'])->default('standard');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('show_qr_code')->default(true);
            $table->boolean('show_signature')->default(true);
            $table->boolean('show_vat')->default(true);
            $table->string('header_title', 255)->default('فاتورة ضريبية');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_templates');
    }
};
