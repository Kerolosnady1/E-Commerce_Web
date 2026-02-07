<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Subscription overview
     */
    public function index()
    {
        $subscription = Subscription::first() ?? new Subscription([
            'plan' => 'pro',
            'start_date' => now(),
            'monthly_cost' => 199,
        ]);

        $plans = \App\Models\Plan::where('is_active', true)->orderBy('price_monthly')->get();

        return view('subscription.index', compact('subscription', 'plans'));
    }

    /**
     * Choose or upgrade plan
     */
    public function choosePlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,slug',
        ]);

        $selectedPlan = \App\Models\Plan::where('slug', $request->plan_id)->firstOrFail();

        $subscription = Subscription::first() ?? new Subscription();
        $subscription->plan = $selectedPlan->slug;
        $subscription->start_date = $subscription->start_date ?? now();
        $subscription->renewal_date = Carbon::parse($subscription->start_date)->addMonth();

        $subscription->monthly_cost = $selectedPlan->price_monthly;

        $subscription->save();

        return redirect()->route('subscription')
            ->with('success', 'تم تغيير الخطة بنجاح');
    }

    /**
     * API: Add payment method
     */
    public function addPaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'card_number' => 'required|string|size:16',
            'expiry_date' => 'required|string|regex:/^\d{2}\/\d{2}$/',
            'cvv' => 'required|string|size:3',
        ]);

        $subscription = Subscription::first() ?? new Subscription();
        // For simulation, we just save the card info to the subscription (not recommended in real apps)
        $subscription->card_number = $validated['card_number'];
        $subscription->expiry_date = $validated['expiry_date'];
        $subscription->save();

        return redirect()->route('subscription')
            ->with('success', 'تم إضافة طريقة الدفع بنجاح');
    }

    /**
     * Toggle auto-renewal
     */
    public function toggleAutoRenewal()
    {
        $subscription = Subscription::first();
        if ($subscription) {
            $subscription->auto_renew = !($subscription->auto_renew ?? true);
            $subscription->save();
        }

        return redirect()->back()->with('success', 'تم تحديث إعدادات التجديد');
    }

    /**
     * Cancel subscription
     */
    public function cancel()
    {
        $subscription = Subscription::first();
        if ($subscription) {
            $subscription->delete();
        }

        return redirect()->route('dashboard')->with('success', 'تم إلغاء الاشتراك');
    }
}
