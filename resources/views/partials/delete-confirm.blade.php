{{-- Delete Confirmation Component --}}
{{-- Usage: @include('partials.delete-confirm', ['route' => route('customers.destroy', $customer), 'title' => 'حذف
العميل', 'name' => $customer->name]) --}}

<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-card-dark border border-border-dark rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto bg-red-500/10 rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-red-400 text-4xl">warning</span>
            </div>

            <h2 class="text-2xl font-bold text-white mb-2" id="deleteTitle">{{ $title ?? 'تأكيد الحذف' }}</h2>
            <p class="text-slate-400 mb-6">
                هل أنت متأكد من رغبتك في حذف <span class="text-white font-bold"
                    id="deleteName">{{ $name ?? 'هذا العنصر' }}</span>؟
                <br>
                <span class="text-red-400 text-sm">هذا الإجراء لا يمكن التراجع عنه.</span>
            </p>

            <form id="deleteForm" method="POST" action="{{ $route ?? '#' }}">
                @csrf
                @method('DELETE')

                <div class="flex gap-4 justify-center">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-6 py-3 bg-slate-700 text-white rounded-xl font-medium hover:bg-slate-600 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition-colors">
                        <span class="material-symbols-outlined text-sm ml-2 align-middle">delete</span>
                        حذف نهائياً
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(route, name, title = 'تأكيد الحذف') {
        document.getElementById('deleteForm').action = route;
        document.getElementById('deleteName').textContent = name;
        document.getElementById('deleteTitle').textContent = title;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    // Close on backdrop click
    document.getElementById('deleteModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>