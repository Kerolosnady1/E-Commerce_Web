<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    const ACTION_TYPES = [
        'login_success' => 'دخول ناجح',
        'login_failed' => 'محاولة دخول فاشلة',
        'logout' => 'تسجيل خروج',
        'permission_change' => 'تغيير صلاحيات',
        'settings_change' => 'تغيير إعدادات',
        'role_change' => 'تغيير الدور',
        'user_created' => 'إنشاء مستخدم',
        'user_deleted' => 'حذف مستخدم',
        'data_export' => 'تصدير بيانات',
        'password_change' => 'تغيير كلمة المرور',
        '2fa_enabled' => 'تفعيل أمان',
        'suspicious_activity' => 'نشاط مشبوه',
        'multiple_failed' => 'فشل متعدد',
    ];

    const STATUS_SUCCESS = 'success';
    const STATUS_WARNING = 'warning';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'username',
        'action_type',
        'description',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'status',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionTypeLabel(): string
    {
        return self::ACTION_TYPES[$this->action_type] ?? $this->action_type;
    }
}
