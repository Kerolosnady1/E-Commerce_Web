<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\InventoryItem;
use App\Models\SaleInvoice;
use App\Models\SaleItem;
use App\Models\CompanySettings;
use App\Models\TaxRate;
use App\Models\Module;
use App\Models\Role;
use App\Models\PrintTemplate;
use App\Models\Notification;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@erp.com',
            'password' => Hash::make('password123'),
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'system_language' => 'ar',
        ]);

        // Company Settings
        CompanySettings::create([
            'company_name_ar' => 'شركة ERP المتكاملة',
            'company_name_en' => 'ERP Integrated Company',
            'currency' => 'SAR',
            'timezone' => 'Asia/Riyadh',
            'tax_enabled' => true,
            'default_tax_rate' => 15.00,
        ]);

        // Tax Rate
        TaxRate::create(['name' => 'ضريبة القيمة المضافة', 'rate' => 15.00, 'is_default' => true]);

        // Categories
        $electronics = Category::create(['name' => 'إلكترونيات']);
        Category::create(['name' => 'هواتف', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'أجهزة كمبيوتر', 'parent_id' => $electronics->id]);
        $clothing = Category::create(['name' => 'ملابس']);
        Category::create(['name' => 'ملابس رجالية', 'parent_id' => $clothing->id]);
        Category::create(['name' => 'ملابس نسائية', 'parent_id' => $clothing->id]);

        // Customers
        $customers = [];
        $customerData = [
            ['name' => 'أحمد محمد', 'email' => 'ahmed@example.com', 'phone' => '0501234567', 'customer_type' => 'individual'],
            ['name' => 'شركة التقنية المتقدمة', 'email' => 'info@techco.com', 'phone' => '0112345678', 'customer_type' => 'company'],
            ['name' => 'فاطمة علي', 'email' => 'fatima@example.com', 'phone' => '0551234567', 'customer_type' => 'individual'],
            ['name' => 'مؤسسة النور', 'email' => 'contact@noor.sa', 'phone' => '0122345678', 'customer_type' => 'company'],
        ];
        foreach ($customerData as $data) {
            $customers[] = Customer::create($data);
        }

        // Suppliers
        Supplier::create(['name' => 'مورد الإلكترونيات', 'email' => 'supplier1@example.com', 'phone' => '0531234567']);
        Supplier::create(['name' => 'مصنع الملابس', 'email' => 'supplier2@example.com', 'phone' => '0541234567']);

        // Products
        $products = [];
        $productData = [
            ['name' => 'آيفون 15 برو', 'sku' => 'IP15P-001', 'price' => 4999.00, 'category_id' => 2],
            ['name' => 'سامسونج S24', 'sku' => 'SS24-001', 'price' => 3999.00, 'category_id' => 2],
            ['name' => 'لابتوب ديل', 'sku' => 'DELL-001', 'price' => 5500.00, 'category_id' => 3],
            ['name' => 'قميص رجالي', 'sku' => 'SHIRT-001', 'price' => 199.00, 'category_id' => 5],
            ['name' => 'فستان نسائي', 'sku' => 'DRESS-001', 'price' => 350.00, 'category_id' => 6],
        ];
        foreach ($productData as $data) {
            $product = Product::create($data);
            InventoryItem::create(['product_id' => $product->id, 'quantity' => rand(10, 100), 'reorder_level' => 10]);
            $products[] = $product;
        }

        // Print Template
        PrintTemplate::create(['name' => 'قالب الفاتورة الافتراضي', 'template_type' => 'sales_invoice', 'style' => 'standard', 'is_default' => true, 'show_qr_code' => true, 'show_vat' => true]);

        // Sample Invoices
        foreach (range(1, 10) as $i) {
            $customer = $customers[array_rand($customers)];
            $invoice = SaleInvoice::create([
                'number' => 'INV-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'issued_date' => now()->subDays(rand(0, 30)),
                'status' => ['paid', 'pending', 'overdue'][rand(0, 2)],
                'total' => 0,
            ]);

            $total = 0;
            foreach (range(1, rand(1, 3)) as $j) {
                $product = $products[array_rand($products)];
                $qty = rand(1, 5);
                $subtotal = $product->price * $qty;
                SaleItem::create(['invoice_id' => $invoice->id, 'product_id' => $product->id, 'quantity' => $qty, 'unit_price' => $product->price, 'subtotal' => $subtotal]);
                $total += $subtotal;
            }
            $invoice->update(['total' => $total]);
        }

        // Modules
        $modules = [
            ['name_ar' => 'لوحة التحكم', 'name_en' => 'Dashboard', 'description_ar' => 'لوحة التحكم الرئيسية', 'description_en' => 'Main Dashboard'],
            ['name_ar' => 'المبيعات', 'name_en' => 'Sales', 'description_ar' => 'إدارة المبيعات', 'description_en' => 'Sales Management'],
            ['name_ar' => 'العملاء', 'name_en' => 'Customers', 'description_ar' => 'إدارة العملاء', 'description_en' => 'Customer Management'],
            ['name_ar' => 'المنتجات', 'name_en' => 'Products', 'description_ar' => 'إدارة المنتجات', 'description_en' => 'Product Management'],
            ['name_ar' => 'المخزون', 'name_en' => 'Inventory', 'description_ar' => 'إدارة المخزون', 'description_en' => 'Inventory Management'],
            ['name_ar' => 'الموردين', 'name_en' => 'Suppliers', 'description_ar' => 'إدارة الموردين', 'description_en' => 'Supplier Management'],
            ['name_ar' => 'الإعدادات', 'name_en' => 'Settings', 'description_ar' => 'إعدادات النظام', 'description_en' => 'System Settings'],
            ['name_ar' => 'التقارير', 'name_en' => 'Reports', 'description_ar' => 'التقارير والإحصائيات', 'description_en' => 'Reports and Statistics'],
        ];
        foreach ($modules as $i => $data) {
            Module::create(array_merge($data, ['order' => $i + 1]));
        }

        // Roles
        Role::create(['name' => 'مدير', 'guard_name' => 'web']);
        Role::create(['name' => 'محاسب', 'guard_name' => 'web']);
        Role::create(['name' => 'مندوب مبيعات', 'guard_name' => 'web']);

        // Warehouses
        Warehouse::create(['name' => 'المستودع الرئيسي', 'location' => 'الرياض', 'capacity' => 75, 'status' => 'active']);
        Warehouse::create(['name' => 'مستودع جدة', 'location' => 'جدة', 'capacity' => 45, 'status' => 'active']);

        // Notifications
        Notification::create(['title' => 'مرحباً بك', 'message' => 'مرحباً بك في نظام ERP المتكامل', 'level' => 'info']);
        Notification::create(['title' => 'مخزون منخفض', 'message' => 'بعض المنتجات وصلت للحد الأدنى', 'level' => 'warning']);
    }
}
