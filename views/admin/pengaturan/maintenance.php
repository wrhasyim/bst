<div class="max-w-4xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">PEMELIHARAAN<span class="text-blue-500">DATA</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Backup, Restore, & Reset Database Sistem</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3">✅</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3">⚠️</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center border-b border-slate-50 pb-4">
                <span class="text-2xl mr-3">📦</span>
                <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-wider italic">Database Utility Tool</h3>
            </div>
            
            <div class="space-y-4">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2">Simpan Salinan Data:</p>
                <a href="<?= BASE_URL ?>/pengaturan/backup" class="block w-full py-4 bg-slate-900 text-white text-center text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all shadow-lg shadow-slate-200">
                    Download SQL Backup
                </a>
            </div>

            <hr class="border-slate-50">

            <form action="<?= BASE_URL ?>/pengaturan/restore" method="POST" enctype="multipart/form-data" class="space-y-4">
                <p class="text-[10px] text-slate-500 font-bold uppercase italic text-center tracking-widest">Restore dari File Backup:</p>
                <div class="relative">
                    <input type="file" name="sql_file" required class="w-full text-[10px] file:py-3 file:px-6 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700 file:font-black font-bold text-slate-400">
                </div>
                <button type="submit" onclick="return confirm('Peringatan! Seluruh data saat ini akan ditimpa oleh file backup. Lanjutkan?')" class="w-full py-4 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    Jalankan Restore Database
                </button>
            </form>
        </div>

        <div class="bg-red-50 p-8 rounded-[2.5rem] border border-red-100 space-y-8 flex flex-col justify-between shadow-sm">
            <div>
                <div class="flex items-center border-b border-red-200 pb-4 mb-6">
                    <span class="text-2xl mr-3">🚨</span>
                    <h3 class="font-black text-red-600 text-[11px] uppercase tracking-wider italic">Danger Zone (Hapus Data)</h3>
                </div>
                
                <div class="space-y-3 mb-8">
                    <p class="text-[9px] text-red-700 font-bold uppercase leading-relaxed italic tracking-wide">
                        1. RESET TRANSAKSI: Menghapus seluruh riwayat tabungan & penjualan. Data Pengguna (User) dan Kategori Sampah tetap aman.
                    </p>
                    <a href="<?= BASE_URL ?>/pengaturan/reset_transaksi" 
                       onclick="return confirm('HANYA RESET TRANSAKSI? Data User & Kategori tidak akan hilang.')"
                       class="block w-full py-3 bg-white border-2 border-red-200 text-red-600 text-center text-[10px] font-black uppercase rounded-xl hover:bg-red-100 transition-all">
                        Reset Data Transaksi Saja
                    </a>
                </div>

                <hr class="border-red-200">

                <div class="space-y-3 mt-8">
                    <p class="text-[9px] text-red-700 font-bold uppercase leading-relaxed italic tracking-wide">
                        2. RESET TOTAL: Menghapus SEMUA data (Kelas, Sampah, Siswa, Guru). Hanya menyisakan Akun Administrator.
                    </p>
                    <a href="<?= BASE_URL ?>/pengaturan/reset_total" 
                       onclick="return confirm('YAKIN RESET TOTAL SISTEM? Tindakan ini tidak dapat dibatalkan. Seluruh data (Kelas, Sampah, User) akan hilang kecuali akun Administrator!')"
                       class="block w-full py-5 bg-red-600 text-white text-center text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-red-200 hover:bg-red-700 transition-all transform active:scale-95">
                        RESET TOTAL SISTEM
                    </a>
                </div>
            </div>

            <div class="pt-4">
                <p class="text-[8px] text-center text-red-400 font-black uppercase tracking-widest italic">*Gunakan dengan sangat hati-hati!</p>
            </div>
        </div>
    </div>
</div>