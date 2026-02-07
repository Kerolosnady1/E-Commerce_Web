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
        Schema::create('sso_configurations', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', ['google', 'azure', 'saml2', 'ldap'])->unique();
            $table->boolean('is_enabled')->default(false);

            // Google OAuth2 Configuration
            $table->string('google_client_id', 255)->nullable();
            $table->string('google_client_secret', 255)->nullable();

            // Microsoft Azure AD Configuration
            $table->string('azure_tenant_id', 255)->nullable();
            $table->string('azure_client_id', 255)->nullable();
            $table->string('azure_client_secret', 255)->nullable();

            // SAML 2.0 Configuration
            $table->string('saml_entity_id', 500)->nullable();
            $table->string('saml_sso_url', 500)->nullable();
            $table->text('saml_certificate')->nullable();

            // LDAP Configuration
            $table->string('ldap_server_uri', 500)->nullable();
            $table->string('ldap_bind_dn', 500)->nullable();
            $table->string('ldap_bind_password', 255)->nullable();
            $table->string('ldap_user_search_base', 500)->nullable();
            $table->string('ldap_group_search_base', 500)->nullable();

            // Role Mapping as JSON
            $table->json('role_mapping')->nullable();

            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sso_configurations');
    }
};
