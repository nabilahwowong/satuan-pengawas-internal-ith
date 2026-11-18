<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SPI ITH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800 text-sm">
    <div class="min-h-screen flex p-3 gap-3">
        <!-- Sidebar Dirampingkan Menjadi w-64 -->
        <aside class="w-64 bg-blue-600 text-white flex flex-col justify-between shrink-0 rounded-3xl shadow-lg p-4">
            <div>
                <!-- Header Sidebar: Logo & Profil Aktor di Atas -->
                <div class="flex flex-col items-center text-center pb-5 border-b border-blue-500/60 mb-3">
                    <!-- Logo / Avatar Lingkaran Putih -->
                    <div class="w-14 h-14 rounded-full bg-white text-blue-600 font-black flex items-center justify-center text-base shadow-md mb-2.5 border-2 border-white/20">
                        SPI
                    </div>
                    <!-- Nama Aktor & Peran -->
                    <h3 class="text-xs font-extrabold text-white tracking-wide truncate max-w-full px-1">{{ Auth::user()->name }}</h3>
                    <span class="inline-block mt-1 px-2.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-full bg-blue-700/80 text-blue-100 border border-blue-400/30">
                        {{ Auth::user()->role }}
                    </span>
                </div>

                <!-- Navigasi Menu -->
                <nav class="space-y-1">
                    @php
                        $role = Auth::user()->role;
                    @endphp

                    <!-- Dashboard Utama -->
                    <a href="{{ route($role . '.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('*.dashboard') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                        <span class="text-sm">📊</span> <span>Dashboard</span>
                    </a>

                    <!-- Menu Admin -->
                    @if ($role === 'admin')
                        <div class="pt-3 pb-1 text-[9px] font-black text-blue-200 uppercase tracking-widest px-3">Master Data</div>
                        <a href="{{ route('admin.units.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.units.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">🏢</span> <span>Unit Kerja</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">👥</span> <span>Kelola Pengguna</span>
                        </a>
                        <a href="{{ route('admin.cms.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.cms.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">📰</span> <span>Berita & Agenda CMS</span>
                        </a>
                    @endif

                    <!-- Menu Pengawasan (Admin & Auditor) -->
                    @if (in_array($role, ['admin', 'auditor']))
                        <div class="pt-3 pb-1 text-[9px] font-black text-blue-200 uppercase tracking-widest px-3">Pengawasan</div>
                        <a href="{{ route('assignments.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('assignments.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">📋</span> <span>Penugasan Audit</span>
                        </a>
                        <div class="pt-3 pb-1 text-[9px] font-black text-blue-200 uppercase tracking-widest px-3">Konten Publik</div>
                        <a href="{{ route('content.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('content.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">✏️</span> <span>Kelola Profil & Proker</span>
                        </a>
                    @endif

                    <!-- Menu Pemantauan -->
                    @if(in_array($role, ['admin', 'auditor', 'approver']))
                        <a href="{{ route('monitoring.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('monitoring.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">📈</span> <span>Rekapitulasi Pemantauan</span>
                        </a>
                    @endif

                    <!-- Menu Jejak Audit -->
                    @if(in_array($role, ['admin', 'approver']))
                        <a href="{{ route('logs.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('logs.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">🛡️</span> <span>Jejak Audit (Activity Log)</span>
                        </a>
                    @endif

                    <!-- Menu Ruang Kerja Auditee -->
                    @if ($role === 'auditee')
                        <div class="pt-3 pb-1 text-[9px] font-black text-blue-200 uppercase tracking-widest px-3">Ruang Kerja Unit</div>
                        <a href="{{ route('auditee.documents') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('auditee.documents*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">📁</span> <span>Permintaan Dokumen</span>
                        </a>
                        <a href="{{ route('auditee.findings') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('auditee.findings*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">🔍</span> <span>Temuan & Tanggapan</span>
                        </a>
                        <a href="{{ route('auditee.followups') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('auditee.followups*') ? 'bg-white text-blue-600 shadow-sm' : 'text-blue-100 hover:bg-blue-500/50 hover:text-white' }}">
                            <span class="text-sm">✅</span> <span>Tindak Lanjut Perbaikan</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Tombol Keluar di Bawah Sidebar -->
            <div class="pt-3 border-t border-blue-500/60 mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold text-rose-200 hover:bg-rose-500/20 hover:text-white transition-all cursor-pointer">
                        <span class="text-sm">🚪</span> <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white border-b border-slate-100 h-20 flex items-center justify-between px-8">
                <h1 class="text-xl font-extrabold text-slate-800">@yield('page_heading', 'Dashboard')</h1>

                <div class="flex items-center gap-5">
                    <div class="text-xs font-medium text-slate-500 hidden md:block bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                        📅 {{ now()->translatedFormat('l, d F Y') }}
                    </div>

                    @php
                        $unreadCount = Auth::user()->unreadNotifications->count();
                        $recentNotifications = Auth::user()->notifications()->latest()->take(6)->get();
                    @endphp

                    <!-- Lonceng Notifikasi -->
                    <div class="relative" x-data="{ openNotif: false }">
                        <button @click="openNotif = !openNotif" type="button"
                                class="relative p-2.5 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded-xl transition cursor-pointer border border-slate-200">
                            <span class="text-base">🔔</span>
                            @if ($unreadCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 bg-rose-600 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-sm">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown Notifikasi -->
                        <div x-show="openNotif" @click.away="openNotif = false" x-cloak
                             class="absolute right-0 mt-3 w-80 bg-white rounded-2xl border border-slate-200 shadow-2xl z-50 overflow-hidden">
                            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                                <span class="text-sm font-bold text-slate-800">Notifikasi ({{ $unreadCount }} Baru)</span>
                                @if ($unreadCount > 0)
                                    <form action="{{ route('notifications.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-blue-600 hover:underline cursor-pointer">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                                @forelse ($recentNotifications as $notif)
                                    <a href="{{ route('notifications.read', $notif->id) }}"
                                       class="block p-4 hover:bg-slate-50 transition {{ is_null($notif->read_at) ? 'bg-blue-50/40' : '' }}">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-xs font-bold text-slate-800">{{ $notif->data['title'] ?? 'Pemberitahuan' }}</p>
                                            @if (is_null($notif->read_at))
                                                <span class="w-2 h-2 rounded-full bg-blue-600 mt-1 shrink-0"></span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $notif->data['message'] ?? '' }}</p>
                                        <span class="text-[10px] text-slate-400 mt-1.5 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    </a>
                                @empty
                                    <div class="p-6 text-center text-xs text-slate-400">
                                        Belum ada notifikasi masuk.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Profil Pengguna di Topbar -->
                    <a href="{{ route('profile.edit') }}" title="Kelola Profil"
                       class="flex items-center gap-3 px-3 py-1.5 rounded-xl hover:bg-slate-50 transition border border-slate-200 group">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                {{ Auth::user()->role }}
                            </p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-black uppercase border border-blue-200">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    </a>
                </div>
            </header>

            <!-- Page Body -->
            <div class="p-8 overflow-y-auto flex-1">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold shadow-2xs">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold shadow-2xs">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
