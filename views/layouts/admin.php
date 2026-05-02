<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'BST SYSTEM'; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
        }
        [x-cloak] { display: none !important; }
        
        /* Sidebar Active Link Style */
        .sidebar-link.active { 
            background-color: rgba(30, 41, 59, 1) !important; 
            color: #ffffff !important; 
            border-left: 4px solid #10b981 !important;
            box-shadow: inset 10px 0 20px -10px rgba(16, 185, 129, 0.6);
        }

        /* Custom Scrollbar for Sidebar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        
        .nav-header { font-style: italic; letter-spacing: 0.15em; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl flex flex-col">
            
            <div class="flex items-center justify-center h-20 bg-slate-950 border-b border-slate-800 flex-shrink-0">
                <span class="text-2xl font-black text-white italic tracking-tighter uppercase">BST<span class="text-emerald-500">SYSTEM</span></span>
            </div>

            <nav class="flex-1 mt-4 px-4 space-y-1 overflow-y-auto custom-scrollbar">
                
                <div class="pb-3">
                    <p class="nav-header text-[10px] font-bold text-slate-500 uppercase px-4 mb-2">Utama</p>
                    <a href="<?= BASE_URL ?>/dashboard" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">📊</span> Dashboard
                    </a>
                </div>

                <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                <div class="py-3">
                    <p class="nav-header text-[10px] font-bold text-slate-500 uppercase px-4 mb-2">Operasional</p>
                    <a href="<?= BASE_URL ?>/setoran/siswa_kelas" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">🎒</span> Setoran Siswa
                    </a>
                    <a href="<?= BASE_URL ?>/setoran/guru" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">📝</span> Setoran Guru
                    </a>
                    <a href="<?= BASE_URL ?>/setoran/create_kesiswaan" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
    <span class="mr-3 text-lg">⚖️</span> Denda Kesiswaan
</a>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/setoran/siswa" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">📋</span> Riwayat Tabungan
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if($_SESSION['role'] === 'admin'): ?>
                <div class="py-3">
                    <p class="nav-header text-[10px] font-bold text-slate-500 uppercase px-4 mb-2">Keuangan & Validasi</p>
                    <a href="<?= BASE_URL ?>/setoran/validasi" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">✅</span> Validasi Setoran
                    </a>
                    <a href="<?= BASE_URL ?>/penjualan" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">🚛</span> Penjualan Pengepul
                    </a>
                    <a href="<?= BASE_URL ?>/penarikan" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">🏧</span> Penarikan Saldo
                    </a>
                    <a href="<?= BASE_URL ?>/honor" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">💰</span> Pencairan Honor
                    </a>
                </div>

                <div class="py-3 border-t border-slate-800 mt-4">
                    <p class="nav-header text-[10px] font-bold text-slate-500 uppercase px-4 mb-2">Sistem Laporan</p>
                    <a href="<?= BASE_URL ?>/laporan/keuangan" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">📈</span> Laporan Keuangan
                    </a>
                    <a href="<?= BASE_URL ?>/laporan/buku_kas" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">📓</span> Buku Kas Umum
                    </a>
                    <!-- Fitur Baru: KAS MANUAL -->
                    <a href="<?= BASE_URL ?>/kas" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
    <span class="mr-3 text-lg">🧾</span> Kas Lain-lain
</a>
<a href="<?= BASE_URL ?>/laporan/kas_kesiswaan" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
    <span class="mr-3 text-lg">🛡️</span> Kas Kesiswaan
</a>
                    <!-- Akhir Fitur Baru -->
                    <a href="<?= BASE_URL ?>/laporan/honor" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">🏅</span> Laporan Honor
                    </a>
                    <a href="<?= BASE_URL ?>/laporan/nasabah" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">📖</span> Buku Tabungan
                    </a>
                </div>

                <div class="py-3 border-t border-slate-800 mt-4">
                    <p class="nav-header text-[10px] font-bold text-slate-500 uppercase px-4 mb-2">Manajemen Akademik</p>
                    <a href="<?= BASE_URL ?>/akademik/kenaikan" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">⏫</span> Kenaikan Kelas
                    </a>
                    <a href="<?= BASE_URL ?>/akademik/kelulusan" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">🎓</span> Kelulusan Alumni
                    </a>
                </div>

                <div class="py-3 border-t border-slate-800 mt-4">
                    <p class="nav-header text-[10px] font-bold text-slate-500 uppercase px-4 mb-2">Master Data</p>
                    <a href="<?= BASE_URL ?>/sampah" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">♻️</span> Kategori Sampah
                    </a>
                    <a href="<?= BASE_URL ?>/kelas" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">🏫</span> Data Kelas
                    </a>
                    <a href="<?= BASE_URL ?>/user" class="sidebar-link flex items-center px-4 py-2.5 text-xs font-semibold rounded-xl hover:bg-slate-800 transition-all">
                        <span class="mr-3">👥</span> Data Pengguna
                    </a>
                </div>
                <?php endif; ?>

            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="flex items-center justify-between h-16 px-8 bg-white border-b border-gray-200 flex-shrink-0 shadow-sm">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <div class="ml-auto relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 p-1 rounded-2xl hover:bg-gray-50 transition-all focus:outline-none">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-black text-slate-800 leading-none"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                            <p class="text-[9px] text-emerald-500 font-bold uppercase mt-1 tracking-widest italic">Akses: <?= strtoupper($_SESSION['role']) ?></p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white text-sm font-black shadow-lg uppercase">
                            <?= substr($_SESSION['nama'], 0, 1) ?>
                        </div>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false" 
                         x-cloak 
                         class="absolute right-0 mt-3 w-64 bg-white border border-gray-200 rounded-[2rem] shadow-2xl z-50 overflow-hidden py-2 animate-in fade-in zoom-in duration-200">
                        
                        <div class="px-6 py-2 border-b border-gray-50 bg-gray-50/50">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Akun Saya</p>
                        </div>

                        <a href="<?= BASE_URL ?>/profil" class="flex items-center px-6 py-3.5 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 font-bold transition-all">
                            <span class="mr-3 text-lg">👤</span> Profil & Sandi
                        </a>
                        
                        <?php if($_SESSION['role'] === 'admin'): ?>
                            <hr class="border-gray-50 my-1">
                            <a href="<?= BASE_URL ?>/pengaturan" class="flex items-center px-6 py-3.5 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 font-bold transition-all">
                                <span class="mr-3 text-lg">⚙️</span> Pengaturan Sistem
                            </a>
                            <a href="<?= BASE_URL ?>/pengaturan/maintenance" class="flex items-center px-6 py-3.5 text-xs text-slate-700 hover:bg-blue-50 hover:text-blue-600 font-bold transition-all">
                                <span class="mr-3 text-lg">📦</span> Pemeliharaan Data
                            </a>
                        <?php endif; ?>

                        <hr class="border-gray-50 my-1">
                        <a href="<?= BASE_URL ?>/auth/logout" class="flex items-center px-6 py-3.5 text-xs text-red-600 hover:bg-red-50 font-bold transition-all">
                            <span class="mr-3 text-lg">🚪</span> Keluar Aplikasi
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8 bg-slate-50/50">
                <div class="container mx-auto">
                    <?php require_once $content; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.href.split(/[?#]/)[0];
            const links = document.querySelectorAll('.sidebar-link');
            
            links.forEach(link => {
                const linkUrl = link.href.split(/[?#]/)[0];
                // Mengecek kecocokan URL tepat atau jika URL saat ini adalah sub-halaman dari link tersebut
                if (currentUrl === linkUrl || (currentUrl.startsWith(linkUrl) && linkUrl !== window.location.origin + '/')) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>