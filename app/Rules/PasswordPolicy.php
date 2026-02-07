<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\CompanySettings;

class PasswordPolicy implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $settings = CompanySettings::first();
        $policy = $settings->password_policy ?? [];

        // Skip validation if no policy is set (fallback to basic length)
        if (empty($policy)) {
            if (strlen($value) < 8) {
                $fail('يجب ألا تقل كلمة المرور عن 8 أحرف.');
            }
            return;
        }

        // Min Length
        $minLength = $policy['min_length'] ?? 8;
        if (strlen($value) < $minLength) {
            $fail("يجب ألا تقل كلمة المرور عن {$minLength} أحرف.");
        }

        // Require Uppercase
        if (!empty($policy['require_uppercase']) && !preg_match('/[A-Z]/', $value)) {
            $fail('يجب أن تحتوي كلمة المرور على حرف كبير واحد على الأقل.');
        }

        // Require Lowercase
        if (!empty($policy['require_lowercase']) && !preg_match('/[a-z]/', $value)) {
            $fail('يجب أن تحتوي كلمة المرور على حرف صغير واحد على الأقل.');
        }

        // Require Numbers
        if (!empty($policy['require_numbers']) && !preg_match('/[0-9]/', $value)) {
            $fail('يجب أن تحتوي كلمة المرور على رقم واحد على الأقل.');
        }

        // Require Symbols
        if (!empty($policy['require_symbols']) && !preg_match('/[\W_]/', $value)) {
            $fail('يجب أن تحتوي كلمة المرور على رمز خاص واحد على الأقل.');
        }
    }
}
