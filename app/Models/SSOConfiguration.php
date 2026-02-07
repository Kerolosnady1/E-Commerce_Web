<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SSOConfiguration extends Model
{
    const PROVIDER_GOOGLE = 'google';
    const PROVIDER_AZURE = 'azure';
    const PROVIDER_SAML2 = 'saml2';
    const PROVIDER_LDAP = 'ldap';

    protected $table = 'sso_configurations';

    protected $fillable = [
        'provider',
        'is_enabled',
        'google_client_id',
        'google_client_secret',
        'azure_tenant_id',
        'azure_client_id',
        'azure_client_secret',
        'saml_entity_id',
        'saml_sso_url',
        'saml_certificate',
        'ldap_server_uri',
        'ldap_bind_dn',
        'ldap_bind_password',
        'ldap_user_search_base',
        'ldap_group_search_base',
        'role_mapping',
        'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'role_mapping' => 'array',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
