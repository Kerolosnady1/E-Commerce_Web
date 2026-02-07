<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // Assets (1xxx)
            ['code' => '1000', 'name' => 'Cash in Hand (الصندوق)', 'type' => 'asset'],
            ['code' => '1010', 'name' => 'Main Bank Account (البنك - الحساب الرئيسي)', 'type' => 'asset'],
            ['code' => '1200', 'name' => 'Accounts Receivable (المدينون)', 'type' => 'asset'],
            ['code' => '1300', 'name' => 'Inventory (المخزون)', 'type' => 'asset'],

            // Liabilities (2xxx)
            ['code' => '2000', 'name' => 'Accounts Payable (الدائنون)', 'type' => 'liability'],
            ['code' => '2100', 'name' => 'VAT Payable (ضريبة القيمة المضافة)', 'type' => 'liability'],

            // Equity (3xxx)
            ['code' => '3000', 'name' => 'Owner\'s Equity (رأس المال)', 'type' => 'equity'],
            ['code' => '3100', 'name' => 'Retained Earnings (الأرباح المبقاة)', 'type' => 'equity'],

            // Revenue (4xxx)
            ['code' => '4000', 'name' => 'Sales Revenue (إيرادات المبيعات)', 'type' => 'revenue'],
            ['code' => '4100', 'name' => 'Other Income (إيرادات أخرى)', 'type' => 'revenue'],

            // Expenses (5xxx)
            ['code' => '5000', 'name' => 'Cost of Goods Sold (تكلفة البضاعة المبيعة)', 'type' => 'expense'],
            ['code' => '5100', 'name' => 'Rent Expense (مصروف الإيجار)', 'type' => 'expense'],
            ['code' => '5200', 'name' => 'Salaries Expense (مصروف الرواتب)', 'type' => 'expense'],
            ['code' => '5300', 'name' => 'Utilities Expense (مصروف المرافق)', 'type' => 'expense'],
        ];

        foreach ($accounts as $account) {
            \App\Models\Account::updateOrCreate(['code' => $account['code']], $account);
        }
    }
}
