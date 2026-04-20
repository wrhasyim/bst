<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-800 italic uppercase">PEMELIHARAAN<span class="text-blue-500">DATA</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Backup, Restore, & Reset Database</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-wider flex items-center border-b pb-4"><span class="mr-2">📦</span> Database Tool</h3>
            <a href="<?= BASE_URL ?>/pengaturan/backup" class="block w-full py-4 bg-slate-800 text-white text-center text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all">Download SQL Backup</a>
            <hr class="border-slate-100">
            <form action="<?= BASE_URL ?>/pengaturan/restore" method="POST" enctype="multipart/form-data" class="space-y-4">
                <p class="text-[10px] text-slate-500 font-bold uppercase italic text-center">Restore dari File:</p>
                <input type="file" name="sql_file" required class="w-full text-[10px] file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700 font-bold">
                <button type="submit" onclick="return confirm('Peringatan! Data lama akan hilang. Lanjutkan?')" class="w-full py-3 bg-blue-600 text-white text-[10px] font-black uppercase rounded-xl hover:bg-blue-700 transition-all">Restore Sekarang</button>
            </form>
        </div>

        <div class="bg-red-50 p-8 rounded-3xl border border-red-100 flex flex-col justify-between">
            <div>
                <h3 class="font-black text-red-600 text-[11px] uppercase tracking-wider flex items-center border-b border-red-200 pb-4 mb-4"><span class="mr-2">🚨</span> Danger Zone</h3>
                <p class="text-[10px] text-red-700 font-bold italic leading-relaxed mb-6 uppercase">RESET TRANSAKSI AKAN MENGHAPUS SEMUA TABUNGAN SISWA DAN PENJUALAN PENGEPUL. DATA USER DAN KATEGORI TETAP AMAN.</p>
            </div>
            <a href="<?= BASE_URL ?>/pengaturan/reset_transaksi" 
               onclick="return confirm('YAKIN RESET TOTAL?')"
               class="block w-full py-4 bg-red-600 text-white text-center text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-red-200">
                RESET SEMUA TRANSAKSI
            </a>
        </div>
    </div>
</div>