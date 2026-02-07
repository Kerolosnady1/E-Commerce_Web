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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('username', 150)->nullable();
            $table->enum('action_type', [
                'login_success',
                'login_failed',
                'logout',
                'permission_change',
                'settings_change',
                'role_change',
                'user_created',
                'user_deleted',
                'data_export',
                'password_change',
                '2fa_enabled',
                'suspicious_activity',
                'multiple_failed'
            ]);
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('browser', 100)->nullable();
            $table->enum('status', ['success', 'warning', 'failed'])->default('success');
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();

            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
