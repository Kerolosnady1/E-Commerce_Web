<?php

namespace App\Http\Controllers;

use App\Models\CompanySettings;
use App\Models\TaxRate;
use App\Models\PrintTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * General settings page
     */
    public function general()
    {
        $settings = CompanySettings::first() ?? new CompanySettings();

        return view('settings.general', compact('settings'));
    }

    /**
     * System settings page
     */
    public function system()
    {
        $settings = CompanySettings::first() ?? new CompanySettings();

        return view('settings.system', compact('settings'));
    }

    /**
     * Save general settings via API
     */
    public function apiSaveGeneral(Request $request)
    {
        $validated = $request->validate([
            'company_name_ar' => 'nullable|string|max:255',
            'company_name_en' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048',
        ]);

        $settings = CompanySettings::first() ?? new CompanySettings();

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('company', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ الإعدادات العامة بنجاح'
        ]);
    }

    /**
     * Tax settings page
     */
    public function taxes()
    {
        try {
            $settings = CompanySettings::first() ?? new CompanySettings();
            $taxRates = TaxRate::all();
        } catch (\Exception $e) {
            $settings = new CompanySettings();
            $taxRates = collect([]);
        }

        return view('settings.taxes', compact('settings', 'taxRates'));
    }

    /**
     * Save tax settings
     */
    public function updateTaxes(Request $request)
    {
        $validated = $request->validate([
            'tax_enabled' => 'boolean',
            'vat_number' => 'nullable|string|max:50',
            'default_tax_rate' => 'required|numeric|min:0|max:100',
            'prices_include_tax' => 'boolean',
            'show_vat_on_invoice' => 'boolean',
        ]);

        $settings = CompanySettings::first() ?? new CompanySettings();

        $settings->tax_enabled = $request->has('tax_enabled');
        $settings->vat_number = $validated['vat_number'];
        $settings->default_tax_rate = $validated['default_tax_rate'];
        $settings->prices_include_tax = $request->has('prices_include_tax');
        $settings->show_vat_on_invoice = $request->has('show_vat_on_invoice');
        $settings->save();

        return redirect()->route('settings.taxes')
            ->with('success', 'تم حفظ إعدادات الضرائب بنجاح');
    }

    /**
     * Print templates page
     */
    public function printTemplates()
    {
        try {
            $templates = PrintTemplate::all();
        } catch (\Exception $e) {
            $templates = collect([]);
        }

        return view('settings.print-templates', compact('templates'));
    }

    /**
     * Notification preferences page
     */
    public function notificationPreferences()
    {
        $settings = CompanySettings::first() ?? new CompanySettings();

        return view('settings.notifications', compact('settings'));
    }

    /**
     * Save notification preferences via API
     */
    public function apiSaveNotificationPreferences(Request $request)
    {
        $settings = CompanySettings::first() ?? new CompanySettings();

        $settings->notification_preferences = $request->all();
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ تفضيلات الإشعارات بنجاح'
        ]);
    }

    /**
     * Locale and time settings
     */
    public function localeTime()
    {
        $user = auth()->user();
        $profile = $user?->profile;
        $company = CompanySettings::first() ?? new CompanySettings();

        $settings = [
            'locale' => $profile->system_language ?? 'ar',
            'timezone' => $profile->timezone ?? 'Africa/Cairo',
            'date_format' => $profile->date_format ?? 'd/m/Y',
            'time_format' => ($profile->use_24hour_format ?? true) ? '24h' : '12h',
            'currency' => $company->currency ?? 'SAR',
        ];

        return view('settings.locale-time', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'company_name_ar' => 'nullable|string|max:255',
            'company_name_en' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048',
        ]);

        $settings = CompanySettings::first() ?? new CompanySettings();

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('company', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->route('settings.general')
            ->with('success', 'تم حفظ الإعدادات العامة بنجاح');
    }

    /**
     * Update locale and time settings
     */
    public function updateLocale(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|in:ar,en',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string|in:24h,12h',
            'currency' => 'required|string|max:10',
        ]);

        $user = auth()->user();
        if ($user) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'system_language' => $validated['locale'],
                    'timezone' => $validated['timezone'],
                    'date_format' => $validated['date_format'],
                    'use_24hour_format' => $validated['time_format'] === '24h',
                ]
            );

            // Update company currency as well
            $company = CompanySettings::first() ?? new CompanySettings();
            $company->currency = $validated['currency'];
            $company->save();

            return redirect()->route('settings.locale-time')
                ->with('success', 'تم حفظ إعدادات اللغة والوقت بنجاح');
        }

        return redirect()->route('settings.locale-time')
            ->with('error', 'حدث خطأ أثناء الحفظ');
    }

    /**
     * Store a new tax rate
     */
    public function storeTax(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'is_default' => 'boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        if ($validated['is_default']) {
            TaxRate::where('is_default', true)->update(['is_default' => false]);
        }

        TaxRate::create($validated);

        return redirect()->route('settings.taxes')->with('success', 'تم إضافة الضريبة بنجاح');
    }

    /**
     * Update an existing tax rate
     */
    public function updateTax(Request $request, TaxRate $tax)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'is_default' => 'boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        if ($validated['is_default']) {
            TaxRate::where('is_default', true)->where('id', '!=', $tax->id)->update(['is_default' => false]);
        }

        $tax->update($validated);

        return redirect()->route('settings.taxes')->with('success', 'تم تحديث الضريبة بنجاح');
    }

    /**
     * Update system settings
     */
    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'storage_quota_mb' => 'required|numeric|min:1',
        ]);

        $settings = CompanySettings::first() ?? new CompanySettings();
        $settings->storage_quota_mb = $validated['storage_quota_mb'];
        $settings->save();

        return redirect()->route('settings.system')->with('success', 'تم تحديث إعدادات النظام بنجاح');
    }

    /**
     * Store a new print template
     */
    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'template_type' => 'required|string',
            'style' => 'required|string',
            'is_default' => 'boolean',
            'show_qr_code' => 'boolean',
            'show_signature' => 'boolean',
            'header_title' => 'nullable|string|max:255',
        ]);

        $validated['is_default'] = $request->has('is_default');
        $validated['show_qr_code'] = $request->has('show_qr_code');
        $validated['show_signature'] = $request->has('show_signature');

        if ($validated['is_default']) {
            PrintTemplate::where('template_type', $validated['template_type'])
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        PrintTemplate::create($validated);

        return redirect()->route('settings.print-templates')->with('success', 'تم إضافة قالب الطباعة بنجاح');
    }

    /**
     * Update a print template
     */
    public function updateTemplate(Request $request, PrintTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'template_type' => 'required|string',
            'style' => 'required|string',
            'is_default' => 'boolean',
            'show_qr_code' => 'boolean',
            'show_signature' => 'boolean',
            'header_title' => 'nullable|string|max:255',
        ]);

        $validated['is_default'] = $request->has('is_default');
        $validated['show_qr_code'] = $request->has('show_qr_code');
        $validated['show_signature'] = $request->has('show_signature');

        if ($validated['is_default']) {
            PrintTemplate::where('template_type', $validated['template_type'])
                ->where('is_default', true)
                ->where('id', '!=', $template->id)
                ->update(['is_default' => false]);
        }

        $template->update($validated);

        return redirect()->route('settings.print-templates')->with('success', 'تم تحديث قالب الطباعة بنجاح');
    }

    /**
     * Delete a print template
     */
    public function destroyTemplate(PrintTemplate $template)
    {
        if ($template->is_default) {
            return redirect()->route('settings.print-templates')->with('error', 'لا يمكن حذف القالب الافتراضي');
        }

        $template->delete();

        return redirect()->route('settings.print-templates')->with('success', 'تم حذف قالب الطباعة بنجاح');
    }
}
