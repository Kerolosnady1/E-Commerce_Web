<?php

namespace App\Http\Controllers;

use App\Models\SaleInvoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingController extends Controller
{
    /**
     * Accounting dashboard
     */
    public function index()
    {
        $currentMonth = Carbon::now()->startOfMonth();

        // Financial stats
        $stats = [
            'total_revenue' => SaleInvoice::where('status', 'paid')->sum('total'),
            'accounts_receivable' => SaleInvoice::where('status', 'pending')->sum('total'),
            'total_assets' => Account::where('type', 'asset')->sum('balance'),
            'total_liabilities' => Account::where('type', 'liability')->sum('balance'),
            'total_equity' => Account::where('type', 'equity')->sum('balance'),
            'total_expenses' => Account::where('type', 'expense')->sum('balance'),
        ];

        // Monthly revenue trend
        $revenueByMonth = SaleInvoice::where('status', 'paid')
            ->selectRaw('strftime("%Y-%m", issued_date) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('accounting.index', compact('stats', 'revenueByMonth'));
    }

    /**
     * Chart of Accounts
     */
    public function chartOfAccounts()
    {
        $accounts = Account::orderBy('code')->get();
        return view('accounting.coa', compact('accounts'));
    }

    /**
     * Journal entries
     */
    public function journal()
    {
        $entries = JournalEntry::with('items.account')->latest()->paginate(20);
        return view('accounting.journal', compact('entries'));
    }

    /**
     * Create Journal Entry Form
     */
    public function createJournal()
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        return view('accounting.journal_form', compact('accounts'));
    }

    /**
     * Store Journal Entry
     */
    public function storeJournal(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'required|numeric|min:0',
            'items.*.credit' => 'required|numeric|min:0',
        ]);

        // Simple validation for balanced entry
        $totalDebit = collect($request->items)->sum('debit');
        $totalCredit = collect($request->items)->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with('error', 'القيد غير متوازن، يجب أن يتساوى المدين مع الدائن')->withInput();
        }

        DB::beginTransaction();
        try {
            $entry = JournalEntry::create([
                'entry_date' => $validated['entry_date'],
                'description' => $validated['description'],
            ]);

            foreach ($validated['items'] as $item) {
                JournalItem::create([
                    'entry_id' => $entry->id,
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'],
                    'credit' => $item['credit'],
                ]);

                // Update account balance
                $account = Account::find($item['account_id']);
                if ($account->type == 'asset' || $account->type == 'expense') {
                    $account->balance += ($item['debit'] - $item['credit']);
                } else {
                    $account->balance += ($item['credit'] - $item['debit']);
                }
                $account->save();
            }

            DB::commit();
            return redirect()->route('accounting.journal')->with('success', 'تم تسجيل القيد بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Create Account Form
     */
    public function createAccount()
    {
        return view('accounting.coa_form');
    }

    /**
     * Store Account
     */
    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        Account::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'balance' => $validated['initial_balance'],
            'is_active' => true,
        ]);

        return redirect()->route('accounting.coa')->with('success', 'تم إضافة الحساب بنجاح');
    }
}
