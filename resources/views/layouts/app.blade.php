<!DOCTYPE html>
<html class="dark" dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام ERP')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#11a4d4",
                        "background-light": "#f6f8f8",
                        "background-dark": "#101d22",
                        "surface-dark": "#111722",
                        "card-dark": "#1b2333",
                        "border-dark": "#232f48"
                    },
                    fontFamily: {
                        "display": ["Manrope", "system-ui", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.375rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <!-- Base Styles -->
    <style>
        body {
            font-family: 'Manrope', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }

        html.dark {
            color-scheme: dark;
        }

        html {
            color-scheme: light;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-background-dark text-slate-100 min-h-screen transition-colors">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header / Navbar -->
            <header class="bg-surface-dark border-b border-border-dark sticky top-0 z-30 transition-colors">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center gap-4 lg:hidden">
                            <div class="p-2 bg-primary/20 rounded-lg">
                                <span class="material-icons text-primary">menu</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <form action="{{ route('search') }}" method="GET" class="hidden lg:flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-400">search</span>
                                <input type="text" name="q" placeholder="بحث سريع..."
                                    class="bg-transparent border-none focus:ring-0 text-sm w-64 text-right text-slate-300">
                            </form>
                        </div>
                        <div class="flex items-center gap-3">
                            @auth
                                <a href="{{ route('notifications.index') }}"
                                    class="relative p-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-icons">notifications</span>
                                </a>
                                <a href="{{ route('profile') }}"
                                    class="flex items-center gap-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-icons">account_circle</span>
                                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 transition-colors">
                                        <span class="material-icons">logout</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-slate-400 hover:text-primary transition-colors">تسجيل
                                    الدخول</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-8 bg-background-dark custom-scrollbar">
                @if (session('success'))
                    <div id="flash-message"
                        class="fixed bottom-4 right-4 bg-green-500 text-slate-900 px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3 transition-all transform animate-in fade-in slide-in-from-right-4">
                        <span class="material-icons">check_circle</span>
                        <span class="font-bold cursor-default">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div id="flash-message"
                        class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3 transition-all transform animate-in fade-in slide-in-from-right-4">
                        <span class="material-icons">error</span>
                        <span class="font-bold cursor-default">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div id="flash-message"
                        class="fixed bottom-4 right-4 bg-amber-500 text-slate-900 px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3 transition-all transform animate-in fade-in slide-in-from-right-4">
                        <span class="material-icons">warning</span>
                        <div class="flex flex-col">
                            <span class="font-bold">يرجى تصحيح الأخطاء التالية:</span>
                            <ul class="text-xs mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-surface-dark border-t border-border-dark mt-16 transition-colors">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-400">
                    نظام ERP المتكامل - الإصدار 4.2.0 • جميع الحقوق محفوظة © 2026
                </p>
                <div class="flex gap-6 text-xs text-slate-400 font-medium">
                    <a class="hover:text-primary transition-colors" href="{{ route('privacy-policy') }}">سياسة
                        الخصوصية</a>
                    <a class="hover:text-primary transition-colors" href="{{ route('terms-of-service') }}">شروط
                        الخدمة</a>
                    <a class="hover:text-primary transition-colors" href="{{ route('support') }}">الدعم الفني</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.opacity = '0';
                flash.style.transform = 'translateX(100%)';
                setTimeout(() => flash.remove(), 300);
            }
        }, 3000);

        // Show notification helper
        function showNotification(message, type = 'info') {
            const notif = document.createElement('div');
            notif.className = 'fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3 transition-all font-semibold animate-in fade-in slide-in-from-right-4';

            if (type === 'success') {
                notif.classList.add('bg-green-500', 'text-slate-900');
                notif.innerHTML = `<span class="material-icons">check_circle</span><span>${message}</span>`;
            } else if (type === 'error') {
                notif.classList.add('bg-red-500', 'text-slate-900');
                notif.innerHTML = `<span class="material-icons">error</span><span>${message}</span>`;
            } else {
                notif.classList.add('bg-blue-500', 'text-slate-900');
                notif.innerHTML = `<span class="material-icons">info</span><span>${message}</span>`;
            }

            document.body.appendChild(notif);

            setTimeout(() => {
                notif.style.opacity = '0';
                notif.style.transform = 'translateX(100%)';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        }

        // CSRF token for AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>

    @stack('scripts')
</body>

</html>