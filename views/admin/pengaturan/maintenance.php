<div class="max-w-7xl mx-auto space-y-8 pb-12">
    
    <!-- HEADER SECTION -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">PEMELIHARAAN<span class="text-blue-500">DATA</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Backup, Restore, & Reset Database Sistem</p>
        </div>
    </div>

    <!-- FLASH MESSAGES (ALERT SUCCESS / ERROR) -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg flex items-center shadow-sm">
            <span class="text-emerald-500 mr-3 text-lg">✅</span>
            <p class="text-sm font-bold text-emerald-800"><?= $_SESSION['success'] ?></p>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg flex items-center shadow-sm">
            <span class="text-red-500 mr-3 text-lg">🚨</span>
            <p class="text-sm font-bold text-red-800"><?= $_SESSION['error'] ?></p>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- MAIN GRID CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        
        <!-- LEFT CARD: DATABASE UTILITY -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col">
            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                <span class="text-2xl">📦</span>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Database Utility Tool</h3>
            </div>
            
            <div class="space-y-8 flex-1">
                <!-- Backup Button -->
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 text-center">Simpan Salinan Data:</p>
                    <a href="<?= BASE_URL ?>/pengaturan/backup" onclick="return alert('Fitur Backup SQL sedang disiapkan. Pastikan pengaturan server sudah sesuai.')" class="w-full block text-center py-4 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-lg">Download SQL Backup</a>
                </div>
                
                <!-- Restore Form -->
                <div class="border-t border-slate-100 pt-6">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 text-center">Restore Dari File Backup:</p>
                    <form action="<?= BASE_URL ?>/pengaturan/restore" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="file" name="backup_file" accept=".sql" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-slate-200 rounded-lg cursor-pointer">
                        <button type="submit" onclick="return confirm('Yakin ingin menimpa database dengan file ini? Pastikan file SQL valid!')" class="w-full py-4 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/30">Jalankan Restore Database</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT CARD: DANGER ZONE -->
        <div class="bg-red-50 p-8 rounded-[2rem] border border-red-100 shadow-sm flex flex-col relative overflow-hidden">
            <!-- Decorative Top Red Bar -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-red-500"></div>
            
            <div class="flex items-center gap-3 mb-6 border-b border-red-200/50 pb-4">
                <span class="text-2xl animate-pulse">🚨</span>
                <h3 class="text-sm font-black text-red-600 uppercase tracking-widest">Danger Zone (Hapus Data)</h3>
            </div>

            <div class="space-y-8 flex-1 flex flex-col justify-center">
                
                <!-- 1. FORM RESET TRANSAKSI -->
                <div>
                    <p class="text-[10px] font-bold text-red-800 uppercase leading-relaxed mb-3">1. Reset Transaksi: Menghapus seluruh riwayat tabungan & penjualan. Data pengguna (User) dan Kategori sampah tetap aman.</p>
                    <!-- ACTION DIARAHKAN KE PENGATURAN CONTROLLER -->
                    <form action="<?= BASE_URL ?>/pengaturan/reset_transaksi" method="POST">
                        <button type="submit" onclick="return confirm('⚠️ YAKIN INGIN MENGHAPUS SEMUA DATA TRANSAKSI?\n\nSeluruh data setoran, penarikan, dan penjualan akan dikosongkan. Tindakan ini tidak bisa dibatalkan!')" class="w-full py-3.5 bg-white text-red-600 border-2 border-red-100 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-50 hover:border-red-200 transition-all">Reset Data Transaksi Saja</button>
                    </form>
                </div>

                <!-- Divider -->
                <div class="w-full h-px bg-red-200/50 my-2"></div>

                <!-- 2. FORM RESET TOTAL -->
                <div>
                    <p class="text-[10px] font-bold text-red-800 uppercase leading-relaxed mb-3">2. Reset Total: Menghapus semua data (Kelas, Sampah, Siswa, Guru). Hanya menyisakan akun Administrator & Kas Kesiswaan.</p>
                    <!-- ACTION DIARAHKAN KE PENGATURAN CONTROLLER -->
                    <form action="<?= BASE_URL ?>/pengaturan/reset_total" method="POST">
                        <button type="submit" onclick="return confirm('⛔ PERINGATAN KERAS!!!\n\nAnda akan melakukan NUKLIR (Reset Total) pada sistem.\nSeluruh data Kelas, Siswa, Guru, dan Transaksi akan MUSNAH secara permanen.\n\nApakah Anda benar-benar yakin ingin melanjutkan?')" class="w-full py-4 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-600/30">Reset Total Sistem</button>
                    </form>
                </div>
            </div>

            <!-- Footer Warning -->
            <p class="text-[9px] font-black text-red-400 text-center uppercase tracking-widest mt-8 italic">* Gunakan dengan sangat hati-hati!</p>
        </div>

    </div>
</div>