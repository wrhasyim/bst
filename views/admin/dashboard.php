<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight italic">BST<span class="text-emerald-500">DASHBOARD</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Integrasi Data Real-Time</p>
        </div>
        <div class="flex gap-2">
            <div class="px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center">
                <span class="text-[10px] font-bold text-slate-500 uppercase mr-3">Status Kas:</span>
                <span class="text-xs font-black <?= $kas_masuk >= $total_tabungan ? 'text-emerald-600' : 'text-red-600' ?>">
                    <?= $kas_masuk >= $total_tabungan ? '📈 SEHAT' : '⚠️ DEFISIT' ?>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-slate-900 p-5 rounded-2xl text-white shadow-xl">
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Kas Masuk</p>
            <h3 class="text-xl font-black text-emerald-400">Rp<?= number_format($kas_masuk, 0, ',', '.') ?></h3>
            <p class="text-[8px] text-slate-400 mt-3 font-medium italic">*Uang tunai dari pengepul</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tabungan</p>
            <h3 class="text-xl font-black text-slate-800">Rp<?= number_format($total_tabungan, 0, ',', '.') ?></h3>
            <p class="text-[8px] text-red-500 mt-3 font-bold uppercase">*Kewajiban Bayar BST</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stok Gudang</p>
            <h3 class="text-xl font-black text-slate-800"><?= number_format($stok_gudang, 0, ',', '.') ?> <span class="text-xs font-normal">Pcs</span></h3>
            <p class="text-[8px] text-emerald-600 mt-3 font-bold uppercase">*Barang Siap Jual</p>
        </div>

        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-[9px] font-bold text-emerald-700 uppercase tracking-widest mb-1">Keuntungan Bersih</p>
            <h3 class="text-xl font-black text-emerald-800">Rp<?= number_format($keuntungan_bersih, 0, ',', '.') ?></h3>
            <p class="text-[8px] text-emerald-600 mt-3 font-medium">*Laba dari barang terjual</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Informasi Anggota Aktif</h4>
                <a href="<?= BASE_URL ?>/user" class="text-[10px] font-bold text-emerald-600 hover:underline">Kelola Data</a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="block text-3xl font-black text-slate-800"><?= $jml_siswa ?></span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Siswa Menabung</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="block text-3xl font-black text-slate-800"><?= $jml_guru ?></span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Guru Menabung</span>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="p-6 bg-emerald-600 rounded-3xl shadow-lg shadow-emerald-200 text-white group">
                <h4 class="font-bold text-sm mb-1 italic">Butuh Input Cepat?</h4>
                <p class="text-[10px] opacity-80 mb-4">Input timbangan per kelas hanya butuh waktu 5 menit.</p>
                <a href="<?= BASE_URL ?>/setoran/siswa_kelas" class="flex items-center justify-center py-2 bg-white text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-transform group-hover:scale-105">
                    Timbang Kelas
                </a>
            </div>
            <div class="p-6 bg-slate-800 rounded-3xl shadow-lg text-white group">
                <h4 class="font-bold text-sm mb-1 italic">Pengepul Datang?</h4>
                <p class="text-[10px] opacity-80 mb-4">Catat penjualan sekarang untuk menambah kas tunai.</p>
                <a href="<?= BASE_URL ?>/penjualan/create" class="flex items-center justify-center py-2 bg-slate-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-transform group-hover:scale-105">
                    Proses Penjualan
                </a>
            </div>
        </div>
    </div>
</div>