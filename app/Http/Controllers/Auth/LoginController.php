<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            SecurityLog::create([
                'user_id' => Auth::id(),
                'username' => Auth::user()->name,
                'action_type' => 'login_success',
                'description' => 'تسجيل دخول ناجح',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
            ]);

            return redirect()->intended(route('dashboard'));
        }

        SecurityLog::create([
            'username' => $request->email,
            'action_type' => 'login_failed',
            'description' => 'محاولة تسجيل دخول فاشلة',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'failed',
        ]);

        return back()->withErrors([
            'email' => 'بيانات الاعتماد غير صحيحة',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        SecurityLog::create([
            'user_id' => Auth::id(),
            'username' => Auth::user()->name,
            'action_type' => 'logout',
            'description' => 'تسجيل خروج',
            'ip_address' => $request->ip(),
            'status' => 'success',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
