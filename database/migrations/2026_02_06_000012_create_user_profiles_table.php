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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('avatar')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('bio')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret', 255)->nullable();

            // Language and Localization Settings
            $table->enum('system_language', ['ar', 'en', 'fr'])->default('ar');
            $table->enum('report_language', ['ar', 'en', 'dual'])->default('ar');
            $table->enum('timezone', ['riyadh', 'dubai', 'cairo'])->default('riyadh');
            $table->boolean('use_24hour_format')->default(true);
            $table->enum('date_format', ['dmy', 'mdy', 'ymd'])->default('dmy');
            $table->enum('calendar_type', ['gregorian', 'hijri', 'both'])->default('gregorian');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
