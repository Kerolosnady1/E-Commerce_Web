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
                            <button class="p-1 text-slate-400 hover:text-yellow-400"
                                onclick="managePermissions({{ $role->id }})" title="صلاحيات">
                                <span class="material-icons text-sm">vpn_key</span>
                            </button>
                            <button class="p-1 text-slate-400 hover:text-primary" onclick="editRole({{ $role->id }})"
                                title="تعديل">
                                <span class="material-icons text-sm">edit</span>
                            </button>
                            <button class="p-1 text-slate-400 hover:text-red-400" onclick="deleteRole({{ $role->id }})"
                                title="حذف">
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



    <!-- Permissions Modal -->
    <div id="permissionsModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-card-dark border border-border-dark rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-6 border-b border-border-dark flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">إدارة الصلاحيات</h2>
                <button onclick="closePermissionsModal()" class="text-slate-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <form id="permissionsForm">
                    <input type="hidden" id="permRoleId">
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-surface-dark">
                                <tr>
                                    <th class="px-4 py-3 text-sm text-slate-400">الوحدة (Module)</th>
                                    <th class="px-4 py-3 text-sm text-slate-400 text-center">عرض (View)</th>
                                    <th class="px-4 py-3 text-sm text-slate-400 text-center">إضافة (Add)</th>
                                    <th class="px-4 py-3 text-sm text-slate-400 text-center">تعديل (Edit)</th>
                                    <th class="px-4 py-3 text-sm text-slate-400 text-center">حذف (Delete)</th>
                                </tr>
                            </thead>
                            <tbody id="permissionsTableBody">
                                <!-- Populated via JS -->
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <div class="p-6 border-t border-border-dark flex justify-end gap-3">
                <button onclick="closePermissionsModal()"
                    class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-bold">إلغاء</button>
                <button onclick="savePermissions()"
                    class="px-6 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg font-bold">حفظ الصلاحيات</button>
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
                const name = prompt('الاسم الجديد للدور:');
                if (name) {
                    fetch('{{ route("api.roles.add") }}', {
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

            // Permissions Management
            const permModal = document.getElementById('permissionsModal');
            const permTable = document.getElementById('permissionsTableBody');
            const permRoleId = document.getElementById('permRoleId');

            function managePermissions(roleId) {
                permRoleId.value = roleId;
                permTable.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-slate-400">جاري التحميل...</td></tr>';
                permModal.classList.remove('hidden');

                fetch(`/api/roles/${roleId}/permissions`)
                    .then(r => r.json())
                    .then(data => {
                        renderPermissionsTable(data.modules, data.permissions);
                    });
            }

            function closePermissionsModal() {
                permModal.classList.add('hidden');
            }

            function renderPermissionsTable(modules, permissions) {
                permTable.innerHTML = '';
                const actions = ['view', 'add', 'change', 'delete'];

                modules.forEach(module => {
                    const row = document.createElement('tr');
                    row.className = 'border-t border-border-dark hover:bg-surface-dark/50 transition-colors';

                    let html = `<td class="px-4 py-3 text-white font-medium">${module.name_ar || module.name_en}</td>`;

                    actions.forEach(action => {
                        const perm = permissions.find(p => p.module_id === module.id && p.action === action);
                        const isChecked = perm ? perm.is_allowed : false; // Default false if not found

                        html += `
                                            <td class="text-center px-4 py-3">
                                                <input type="checkbox" 
                                                    class="w-5 h-5 rounded accent-primary cursor-pointer perm-check" 
                                                    data-module="${module.id}" 
                                                    data-action="${action}"
                                                    ${isChecked ? 'checked' : ''}>
                                            </td>
                                        `;
                    });

                    row.innerHTML = html;
                    permTable.appendChild(row);
                });
            }

            function savePermissions() {
                const roleId = permRoleId.value;
                const checkboxes = document.querySelectorAll('.perm-check');
                const permissions = [];

                checkboxes.forEach(cb => {
                    permissions.push({
                        module_id: cb.dataset.module,
                        action: cb.dataset.action,
                        allowed: cb.checked
                    });
                });

                fetch(`/api/roles/${roleId}/permissions`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
                    body: JSON.stringify({ permissions })
                })
                    .then(r => r.json())
                    .then(data => {
                        showNotification(data.message, 'success');
                        closePermissionsModal();
                        setTimeout(() => location.reload(), 1000);
                    });
            }
        </script>
    @endpush
@endsection