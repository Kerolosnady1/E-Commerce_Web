<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'basic',
                'name_ar' => 'الأساسية',
                'name_en' => 'Basic',
                'price_monthly' => 49.00,
                'max_users' => 2,
                'storage_limit_gb' => 10,
                'features' => ['إدارة المنتجات', 'الفواتير الأساسية', 'تقارير محدودة'],
                'is_popular' => false,
            ],
            [
                'slug' => 'pro',
                'name_ar' => 'الاحترافية',
                'name_en' => 'Pro',
                'price_monthly' => 199.00,
                'max_users' => 10,
                'storage_limit_gb' => 50,
                'features' => ['كل مميزات الأساسية', 'تقارير متقدمة', 'الدعم الفني', 'API'],
                'is_popular' => true,
            ],
            [
                'slug' => 'professional',
                'name_ar' => 'المهنية',
                'name_en' => 'Professional',
                'price_monthly' => 299.00,
                'max_users' => 25,
                'storage_limit_gb' => 100,
                'features' => ['كل مميزات الاحترافية', 'أمن متقدم', 'إدارة فروع'],
                'is_popular' => false,
            ],
            [
                'slug' => 'enterprise',
                'name_ar' => 'المؤسسية',
                'name_en' => 'Enterprise',
                'price_monthly' => 499.00,
                'max_users' => null, // Unlimited
                'storage_limit_gb' => 500,
                'features' => ['كل المميزات', 'دعم مخصص', 'تخصيص كامل', 'SLA'],
                'is_popular' => false,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
