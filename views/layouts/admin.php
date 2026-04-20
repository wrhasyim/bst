<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'BST' ?> - Bank Sampah TKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Custom Scrollbar untuk Sidebar agar lebih elegan */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #065f46; border-radius: 10px; }
        .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: #047857; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden font-sans text-gray-800" x-data="{ sidebarOpen: false }">
    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 w-64 bg-emerald-900 text-white transition-transform duration-300 md:relative md:translate-x-0 flex flex-col z-50 shadow-2xl">
        
        <div class="h-16 flex items-center justify-center bg-emerald-950 border-b border-emerald-800/50">
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                <h1 class="text-2xl font-extrabold tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-emerald-100 to-emerald-400">BST</h1>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto sidebar-scroll">
            
            <a href="<?= BASE_URL ?>/dashboard" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-50 hover:text-white group">
                <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">📊</span>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            
            <?php if(in_array($_SESSION['role'], ['admin', 'staff'])): ?>
                <div class="pt-5 pb-2">
                    <p class="px-4 text-[10px] font-bold text-emerald-500/70 uppercase tracking-wider">Operasional</p>
                </div>
                <a href="<?= BASE_URL ?>/setoran/siswa" class="flex items-center py-2px px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group mb-1">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">📝</span>
                    <span class="font-medium text-sm">Setoran Siswa</span>
                </a>
                <a href="<?= BASE_URL ?>/setoran/guru" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">📝</span>
                    <span class="font-medium text-sm">Setoran Guru</span>
                </a>
            <?php endif; ?>

            <?php if($_SESSION['role'] === 'admin'): ?>
                
                <div class="pt-5 pb-2">
                    <p class="px-4 text-[10px] font-bold text-emerald-500/70 uppercase tracking-wider">Keuangan & Validasi</p>
                </div>
                <a href="<?= BASE_URL ?>/validasi" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group mb-1">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">✅</span>
                    <span class="font-medium text-sm">Validasi Setoran</span>
                </a>
                <a href="<?= BASE_URL ?>/pencairan" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">💰</span>
                    <span class="font-medium text-sm">Pencairan Honor</span>
                </a>

                <div class="pt-5 pb-2">
                    <p class="px-4 text-[10px] font-bold text-emerald-500/70 uppercase tracking-wider">Laporan</p>
                </div>
                <a href="<?= BASE_URL ?>/laporan/setoran" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group mb-1">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">📄</span>
                    <span class="font-medium text-sm">Laporan Setoran</span>
                </a>
                <a href="<?= BASE_URL ?>/laporan/keuangan" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">📈</span>
                    <span class="font-medium text-sm">Laporan Keuangan</span>
                </a>

                <div class="pt-5 pb-2">
                    <p class="px-4 text-[10px] font-bold text-emerald-500/70 uppercase tracking-wider">Akademik</p>
                </div>
                <a href="<?= BASE_URL ?>/akademik/kenaikan" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group mb-1">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">🎓</span>
                    <span class="font-medium text-sm">Kenaikan Kelas</span>
                </a>
                <a href="<?= BASE_URL ?>/akademik/kelulusan" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">🚀</span>
                    <span class="font-medium text-sm">Kelulusan Alumni</span>
                </a>

                <div class="pt-5 pb-2">
                    <p class="px-4 text-[10px] font-bold text-emerald-500/70 uppercase tracking-wider">Master Data</p>
                </div>
                <a href="<?= BASE_URL ?>/sampah" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group mb-1">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">♻️</span>
                    <span class="font-medium text-sm">Kategori Sampah</span>
                </a>
                <a href="<?= BASE_URL ?>/kelas" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group mb-1">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">🏫</span>
                    <span class="font-medium text-sm">Data Kelas</span>
                </a>
                <a href="<?= BASE_URL ?>/user" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group mb-1">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">👥</span>
                    <span class="font-medium text-sm">Data Pengguna</span>
                </a>
                <a href="<?= BASE_URL ?>/pengaturan" class="flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200 hover:bg-emerald-800 text-emerald-100 hover:text-white group">
                    <span class="mr-3 text-emerald-400 group-hover:text-emerald-300">⚙️</span>
                    <span class="font-medium text-sm">Pengaturan Sistem</span>
                </a>

            <?php endif; ?>
            
            <div class="h-10"></div> </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden w-full bg-gray-50/50">
        
        <header class="h-16 bg-white shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border-b border-gray-100 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden mr-4 text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h2 class="text-xl font-bold text-gray-800 tracking-tight"><?= $title ?? 'Dashboard' ?></h2>
            </div>
            
            <div class="flex items-center space-x-5">
                <div class="flex flex-col text-right hidden sm:block">
                    <span class="text-sm font-bold text-gray-700 block leading-tight"><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></span>
                    <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block"><?= htmlspecialchars($_SESSION['role'] ?? 'Guest') ?></span>
                </div>
                
                <div class="h-8 w-px bg-gray-200"></div> <a href="<?= BASE_URL ?>/auth/logout" class="flex items-center text-sm font-bold text-red-500 hover:text-red-700 transition-colors group">
                    <span class="hidden sm:inline mr-2">Keluar</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </a>
            </div>
        </header>
        
        <main class="flex-1 p-6 lg:p-8 overflow-auto">
            <?php require_once $content; ?>
        </main>
    </div>
</body>
</html>