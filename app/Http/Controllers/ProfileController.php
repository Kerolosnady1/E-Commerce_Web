<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // If not authenticated, show demo profile
        if (!$user) {
            $user = (object) [
                'id' => 0,
                'name' => 'مستخدم تجريبي',
                'email' => 'demo@example.com',
                'created_at' => now(),
                'role' => 'مستخدم',
            ];
            $profile = (object) [
                'phone' => '0501234567',
                'bio' => 'هذا ملف شخصي تجريبي',
                'avatar' => null,
            ];
        } else {
            $profile = $user->profile ?? UserProfile::create(['user_id' => $user->id]);
        }

        return view('profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
        ]);

        $user->update(['name' => $validated['name'], 'email' => $validated['email']]);

        $profile = $user->profile ?? UserProfile::create(['user_id' => $user->id]);
        $profile->update(['phone' => $validated['phone'], 'bio' => $validated['bio']]);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);

        $profile = auth()->user()->profile;
        if ($profile->avatar) {
            Storage::disk('public')->delete($profile->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $profile->update(['avatar' => $path]);

        return response()->json(['success' => true, 'avatar' => Storage::url($path)]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'string', 'confirmed', new \App\Rules\PasswordPolicy],
        ]);

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'كلمة المرور الحالية غير صحيحة');
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    public function exportData()
    {
        $user = auth()->user()->load(['profile', 'securityLogs', 'sessions']);

        $data = [
            'user' => $user->toArray(),
            'exported_at' => now()->toIso8601String(),
            'platform' => 'ERP System',
        ];

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="user_data_' . $user->id . '.json"',
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        // Optional: Verify password before deletion
        // $request->validate(['password' => 'required|current_password']);

        // Logout
        \Illuminate\Support\Facades\Auth::logout();

        // Delete user (and cascading relations via DB foreign keys or manual)
        // Assuming foreign keys are set to ON DELETE CASCADE or set null
        // If not, we should manually delete related profiles.
        if ($user->profile) {
            $user->profile->delete();
        }
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم حذف الحساب بنجاح. نأسف لرؤيتك تغادر.');
    }
}
