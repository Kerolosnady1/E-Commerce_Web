@extends('layouts.app')

@section('title', 'الملف الشخصي - نظام ERP')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">الملف الشخصي</h1>
            <p class="text-slate-400 mt-1">إدارة معلومات حسابك</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="bg-card-dark border border-border-dark rounded-xl p-6 text-center">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-surface-dark overflow-hidden">
                    @if($profile && $profile->avatar)
                        <img src="{{ Storage::url($profile->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <span class="material-icons text-6xl text-slate-600 leading-[96px]">account_circle</span>
                    @endif
                </div>
                <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                <p class="text-slate-400 text-sm">{{ $user->email }}</p>
                <label
                    class="mt-4 inline-block px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg cursor-pointer transition-colors">
                    <span>تغيير الصورة</span>
                    <input type="file" accept="image/*" class="hidden" id="avatar-input">
                </label>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2 bg-card-dark border border-border-dark rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">المعلومات الشخصية</h3>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-500/20 border border-green-500/30 rounded-lg text-green-300">
                        {{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-slate-400 text-sm mb-2">الاسم</label>
                            <input type="text" name="name" value="{{ $user->name }}"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"
                                required>
                        </div>
                        <div>
                            <label class="block text-slate-400 text-sm mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ $user->email }}"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"
                                required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-slate-400 text-sm mb-2">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ $profile->phone ?? '' }}"
                                class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-slate-400 text-sm mb-2">نبذة</label>
                        <textarea name="bio" rows="3"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none">{{ $profile->bio ?? '' }}</textarea>
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors">
                        حفظ التغييرات
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="mt-6 bg-card-dark border border-border-dark rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">تغيير كلمة المرور</h3>
            <form method="POST" action="{{ route('profile.change-password') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">كلمة المرور الحالية</label>
                        <input type="password" name="current_password"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">كلمة المرور الجديدة</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-sm mb-2">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3 bg-surface-dark border border-border-dark rounded-lg text-white focus:border-primary outline-none"
                            required>
                    </div>
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">
                    تغيير كلمة المرور
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('avatar-input').addEventListener('change', async function () {
                const formData = new FormData();
                formData.append('avatar', this.files[0]);
                formData.append('_token', window.csrfToken);

                const response = await fetch('{{ route("profile.upload-avatar") }}', { method: 'POST', body: formData });
                const data = await response.json();

                if (data.success) {
                    showNotification('تم تحديث الصورة', 'success');
                    location.reload();
                }
            });
        </script>
    @endpush
@endsection