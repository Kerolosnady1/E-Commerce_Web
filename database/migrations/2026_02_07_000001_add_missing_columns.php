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
        // Add missing columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('employee')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
        });

        // Add missing columns to company_settings table
        Schema::table('company_settings', function (Blueprint $table) {
            $table->json('password_policy')->nullable()->after('notification_preferences');
        });

        // Fix user_profiles timezone to accept any string
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('timezone', 100)->default('Asia/Riyadh')->change();
            $table->string('date_format', 20)->default('Y-m-d')->change();
            $table->string('time_format', 10)->default('24h')->after('date_format');
        });

        // Make subscriptions.start_date nullable with default
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->date('start_date')->nullable()->default(now())->change();
        });

        // Add is_default to print_templates  
        if (!Schema::hasColumn('print_templates', 'is_default')) {
            Schema::table('print_templates', function (Blueprint $table) {
                $table->boolean('is_default')->default(false)->after('content');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn('password_policy');
        });

        Schema::table('print_templates', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
