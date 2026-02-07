<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\PosController;
// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard_alt');

// Global Search
Route::get('/search', [DashboardController::class, 'search'])->name('search');

// Customers CRUD
Route::resource('customers', CustomerController::class);

// Suppliers CRUD
Route::resource('suppliers', SupplierController::class);
Route::post('/suppliers/quick-create', [SupplierController::class, 'quickCreate'])->name('suppliers.quick-create');

// Products CRUD
Route::resource('products', ProductController::class);

// Categories CRUD
Route::resource('categories', CategoryController::class);

// Invoices (Sales)
Route::get('/invoices/statement', function () {
    return view('invoices.statement');
})->name('invoices.statement');
Route::resource('invoices', InvoiceController::class);
Route::get('/sales', [InvoiceController::class, 'index'])->name('sales');

// Inventory
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
Route::get('/inventory/{inventory}/edit', [InventoryController::class, 'form'])->name('inventory.form');
Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
Route::get('/inventory/export/csv', [InventoryController::class, 'exportCsv'])->name('inventory.export.csv');
Route::post('/inventory/import/csv', [InventoryController::class, 'importCsv'])->name('inventory.import.csv');

// Warehouses CRUD
Route::resource('warehouses', WarehouseController::class);

// Users CRUD
Route::resource('users', UserController::class);

// Notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

// Settings
Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
Route::post('/settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
Route::get('/settings/taxes', [SettingsController::class, 'taxes'])->name('settings.taxes');
Route::post('/settings/taxes', [SettingsController::class, 'updateTaxes'])->name('settings.taxes.save');
Route::get('/settings/print-templates', [SettingsController::class, 'printTemplates'])->name('settings.print-templates');
Route::post('/settings/print-templates', [SettingsController::class, 'storeTemplate'])->name('settings.print-templates.store');
Route::put('/settings/print-templates/{template}', [SettingsController::class, 'updateTemplate'])->name('settings.print-templates.update');
Route::delete('/settings/print-templates/{template}', [SettingsController::class, 'destroyTemplate'])->name('settings.print-templates.destroy');
Route::get('/settings/locale-time', [SettingsController::class, 'localeTime'])->name('settings.locale-time');
Route::post('/settings/locale-time', [SettingsController::class, 'updateLocale'])->name('settings.locale-time.update');

// Security
Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
Route::get('/security/dashboard', [SecurityController::class, 'index'])->name('security'); // Alias
Route::get('/security/2fa', [SecurityController::class, 'twoFactor'])->name('security.2fa');
Route::post('/security/2fa', [SecurityController::class, 'enableTwoFactor'])->name('security.2fa.enable');
Route::delete('/security/2fa', [SecurityController::class, 'disableTwoFactor'])->name('security.2fa.disable');
Route::get('/security/password-policy', [SecurityController::class, 'passwordPolicy'])->name('security.password-policy');
Route::post('/security/password-policy', [SecurityController::class, 'updatePasswordPolicy'])->name('security.password-policy.update');
Route::get('/security/logs', [SecurityController::class, 'logs'])->name('security.logs');

// Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/account/export', [ProfileController::class, 'exportData'])->name('account.export');
Route::delete('/account/delete', [ProfileController::class, 'deleteAccount'])->name('account.delete');
Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

// Reports
Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
Route::get('/reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
Route::get('/reports/profit', [ReportsController::class, 'profit'])->name('reports.profit');
Route::get('/reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');
Route::get('/reports/tax', [ReportsController::class, 'tax'])->name('reports.tax');

// Statement & Purchases
Route::get('/invoices/statement', [InvoiceController::class, 'statement'])->name('invoices.statement');
Route::get('/purchases/bulk', function () {
    return view('purchases.bulk');
})->name('purchases.bulk');

// POS (Point of Sale)
Route::get('/pos', [PosController::class, 'index'])->name('pos');
Route::get('/pos/search', [PosController::class, 'searchProducts'])->name('pos.search');
Route::post('/pos/cancel', [PosController::class, 'cancelOrder'])->name('pos.cancel');
Route::post('/pos/submit', [PosController::class, 'submitOrder'])->name('pos.submit');

// Accounting
Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting');
Route::get('/accounting/coa', [AccountingController::class, 'chartOfAccounts'])->name('accounting.coa');
Route::get('/accounting/journal', [AccountingController::class, 'journal'])->name('accounting.journal');
Route::get('/accounting/journal/create', [AccountingController::class, 'createJournal'])->name('accounting.journal.create');
Route::post('/accounting/journal', [AccountingController::class, 'storeJournal'])->name('accounting.journal.store');
Route::get('/accounting/coa/create', [AccountingController::class, 'createAccount'])->name('accounting.coa.create');
Route::post('/accounting/coa', [AccountingController::class, 'storeAccount'])->name('accounting.coa.store');

// Employees
Route::get('/employees', function () {
    return view('employees.index');
})->name('employees');

// Subscription
Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
Route::post('/subscription/toggle-auto-renewal', [SubscriptionController::class, 'toggleAutoRenewal'])->name('subscription.toggle-auto-renewal');
Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
Route::post('/subscription/add-payment-method', [SubscriptionController::class, 'addPaymentMethod'])->name('subscription.add-payment-method');
Route::post('/subscription/choose-plan', [SubscriptionController::class, 'choosePlan'])->name('subscription.choose-plan');

// Account Management
Route::get('/account', function () {
    return view('account.index');
})->name('account');

// Individual Tax Rates CRUD
Route::get('/settings/tax-rates/create', function () {
    return view('settings.tax-form');
})->name('taxes.create');
Route::post('/settings/tax-rates', [SettingsController::class, 'storeTax'])->name('taxes.store');
Route::get('/settings/tax-rates/{tax}/edit', function ($tax) {
    return view('settings.tax-form', ['tax' => App\Models\TaxRate::find($tax)]);
})->name('taxes.edit');
Route::put('/settings/tax-rates/{tax}', [SettingsController::class, 'updateTax'])->name('taxes.update');

// Purchases
Route::get('/purchases/create', function () {
    return view('purchases.form');
})->name('purchases.create');
Route::post('/purchases', function () {
    return redirect()->route('purchases.bulk')->with('success', 'تم حفظ أمر الشراء بنجاح');
})->name('purchases.store');

// Notification Form
Route::get('/notifications/create', function () {
    return view('notifications.form');
})->name('notifications.create');
Route::post('/notifications/store', function () {
    return redirect()->route('notifications.index')->with('success', 'تم إرسال الإشعار بنجاح');
})->name('notifications.store');

// Static Pages
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'pages.terms-of-service')->name('terms-of-service');
Route::view('/support', 'pages.support')->name('support');
Route::view('/help', 'pages.help')->name('help');

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/summary', [DashboardController::class, 'apiSummary'])->name('api.summary');
    Route::get('/alerts', [DashboardController::class, 'apiAlerts'])->name('api.alerts');
    Route::get('/dashboard', [DashboardController::class, 'apiDashboardData'])->name('api.dashboard');
    Route::get('/search-products', [ProductController::class, 'apiSearch'])->name('api.search-products');
    Route::post('/submit-invoice', [InvoiceController::class, 'apiSubmit'])->name('api.submit-invoice');
    Route::get('/sales/stats', [InvoiceController::class, 'apiStats'])->name('api.sales.stats');
    Route::post('/settings/general/save', [SettingsController::class, 'apiSaveGeneral'])->name('api.settings.general.save');
    Route::post('/settings/notification-preferences/save', [SettingsController::class, 'apiSaveNotificationPreferences'])->name('api.notification-preferences.save');
    Route::delete('/customers/{customer}', [CustomerController::class, 'apiDelete'])->name('api.customers.delete');
    Route::post('/roles/add', [SecurityController::class, 'addRole'])->name('api.roles.add');
    Route::delete('/roles/delete', [SecurityController::class, 'deleteRole'])->name('api.roles.delete');
});

// Auth Routes (using Laravel's built-in)
require __DIR__ . '/auth.php';
