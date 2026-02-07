@extends('layouts.app')

@section('title', 'الإشعارات - نظام ERP')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">الإشعارات</h1>
            <p class="text-slate-400 mt-1">جميع الإشعارات والتنبيهات</p>
        </div>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-card-dark border border-border-dark rounded-lg text-slate-300">
                {{ $stats['unread'] }} غير مقروء
            </span>
            @if($stats['critical'] > 0)
                <span class="px-3 py-1 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
                    {{ $stats['critical'] }} حرج
                </span>
            @endif
        </div>
    </div>

    <div class="bg-card-dark border border-border-dark rounded-xl overflow-hidden">
        @forelse($notifications as $notification)
            <div
                class="flex items-start gap-4 p-4 border-b border-border-dark hover:bg-surface-dark/50 {{ !$notification->is_read ? 'bg-primary/5' : '' }}">
                <div
                    class="p-2 rounded-full {{ $notification->level == 'critical' ? 'bg-red-500/20' : ($notification->level == 'warning' ? 'bg-yellow-500/20' : 'bg-blue-500/20') }}">
                    <span
                        class="material-icons text-sm {{ $notification->level == 'critical' ? 'text-red-400' : ($notification->level == 'warning' ? 'text-yellow-400' : 'text-blue-400') }}">
                        {{ $notification->level == 'critical' ? 'error' : ($notification->level == 'warning' ? 'warning' : 'info') }}
                    </span>
                </div>
                <div class="flex-1">
                    <h3 class="text-white font-medium">{{ $notification->title }}</h3>
                    <p class="text-slate-400 text-sm mt-1">{{ $notification->message }}</p>
                    <p class="text-slate-500 text-xs mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if(!$notification->is_read)
                        <button onclick="markRead({{ $notification->id }})" class="p-1 text-slate-400 hover:text-green-400">
                            <span class="material-icons text-sm">check</span>
                        </button>
                    @endif
                    <button onclick="deleteNotification({{ $notification->id }})" class="p-1 text-slate-400 hover:text-red-400">
                        <span class="material-icons text-sm">delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-slate-400">لا توجد إشعارات</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $notifications->links() }}</div>

    @push('scripts')
        <script>
            async function markRead(id) {
                await fetch(`/notifications/${id}/mark-read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': window.csrfToken } });
                location.reload();
            }
            async function deleteNotification(id) {
                if (confirm('حذف الإشعار؟')) {
                    await fetch(`/notifications/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': window.csrfToken } });
                    location.reload();
                }
            }
        </script>
    @endpush
@endsection