@extends('layouts.app')

@section('title', 'الأمان - نظام ERP')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">الأمان والصلاحيات</h1>
        <p class="text-slate-400 mt-1">إدارة الأدوار والصلاحيات وسجل الأمان</p>
    </div>

    <!-- Security Navigation -->
    <div class="flex flex-wrap gap-4 mb-6">
        <a href="{{ route('security.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg">نظرة عامة</a>
        <a href="{{ route('security.2fa') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">المصادقة الثنائية</a>
        <a href="{{ route('security.password-policy') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">سياسة كلمات المرور</a>
        <a href="{{ route('security.logs') }}"
            class="px-4 py-2 bg-card-dark text-slate-300 hover:bg-surface-dark rounded-lg">سجل الأمان</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Roles Management -->
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">الأدوار والصلاحيات</h2>
                <button onclick="showAddRoleModal()"
                    class="px-3 py-1 bg-primary hover:bg-primary/90 text-white text-sm rounded-lg">
                    <span class="material-icons text-sm">add</span> دور جديد
                </button>
            </div>
            <div class="space-y-3">
                @forelse($roles as $role)
                    <div class="flex items-center justify-between p-3 bg-surface-dark rounded-lg">
                        <div>
                            <p class="text-white font-medium">{{ $role->name }}</p>
                            <p class="text-slate-400 text-sm">{{ $role->permissions_count }} صلاحية</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="p-1 text-slate-400 hover:text-primary" onclick="editRole({{ $role->id }})">
                                <span class="material-icons text-sm">edit</span>
                            </button>
                            <button class="p-1 text-slate-400 hover:text-red-400" onclick="deleteRole({{ $role->id }})">
                                <span class="material-icons text-sm">delete</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-center py-4">لا توجد أدوار</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Security Logs -->
        <div class="bg-card-dark border border-border-dark rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">آخر الأحداث الأمنية</h2>
                <a href="{{ route('security.logs') }}" class="text-primary text-sm hover:underline">عرض الكل</a>
            </div>
            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="flex items-center gap-3 p-3 bg-surface-dark rounded-lg">
                        <div
                            class="p-2 rounded-full {{ $log->status == 'success' ? 'bg-green-500/20' : ($log->status == 'warning' ? 'bg-yellow-500/20' : 'bg-red-500/20') }}">
                            <span
                                class="material-icons text-sm {{ $log->status == 'success' ? 'text-green-400' : ($log->status == 'warning' ? 'text-yellow-400' : 'text-red-400') }}">
                                {{ $log->status == 'success' ? 'check' : ($log->status == 'warning' ? 'warning' : 'error') }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm">{{ $log->description }}</p>
                            <p class="text-slate-400 text-xs">{{ $log->username }} •
                                {{ $log->timestamp ? $log->timestamp->diffForHumans() : '' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-center py-4">لا توجد أحداث</p>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showAddRoleModal() {
                const name = prompt('اسم الدور الجديد:');
                if (name) {
                    fetch('{{ route("api.roles.add") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
                        body: JSON.stringify({ name })
                    })
                        .then(r => r.json())
                        .then(data => { showNotification(data.message, data.success ? 'success' : 'error'); if (data.success) location.reload(); });
                }
            }

            function editRole(id) {
                // For now, prompt for new name. In a real app, this would open a permissions modal.
                const name = prompt('الاسم الجديد للدور:');
                if (name) {
                    fetch('{{ route("api.roles.add") }}', { // Reuse add for update if it handles ID or add update route
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
                        body: JSON.stringify({ name, role_id: id })
                    })
                        .then(r => r.json())
                        .then(data => { showNotification(data.message || 'تم التحديث', 'success'); location.reload(); });
                }
            }

            function deleteRole(id) {
                if (confirm('هل أنت متأكد من حذف هذا الدور؟')) {
                    fetch('{{ route("api.roles.delete") }}', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
                        body: JSON.stringify({ role_id: id })
                    })
                        .then(r => r.json())
                        .then(data => { showNotification(data.message || 'تم الحذف', 'success'); location.reload(); });
                }
            }
        </script>
    @endpush
@endsection