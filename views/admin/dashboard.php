<div class="max-w-7xl mx-auto space-y-6 pb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight italic">
                <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                    BST<span class="text-emerald-500">DASHBOARD</span>
                <?php else: ?>
                    HALO,<span class="text-emerald-500 ml-2 uppercase"><?= htmlspecialchars($_SESSION['nama']) ?>!</span>
                <?php endif; ?>
            </h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">
                <?= ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff') ? 'Integrasi Data Real-Time Sekolah' : 'Ringkasan Tabungan Pribadi Anda' ?>
            </p>
        </div>
    </div>

    <?php 
    // =========================================================================
    // TAMPILAN DASHBOARD KHUSUS: ADMIN & STAFF
    // =========================================================================
    if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): 
    ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-slate-900 p-6 rounded-3xl text-white shadow-xl">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Kas Masuk</p>
                <h3 class="text-2xl font-black text-emerald-400">Rp<?= number_format($data['kas_masuk'], 0, ',', '.') ?></h3>
                <p class="text-[8px] text-slate-400 mt-3 font-medium italic">*Uang tunai dari pengepul</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tabungan</p>
                <h3 class="text-2xl font-black text-slate-800">Rp<?= number_format($data['total_tabungan'], 0, ',', '.') ?></h3>
                <p class="text-[8px] text-red-500 mt-3 font-bold uppercase">*Kewajiban Bayar Nasabah</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stok Gudang</p>
                <h3 class="text-2xl font-black text-slate-800"><?= number_format($data['stok_gudang'], 0, ',', '.') ?> <span class="text-xs font-normal">Pcs</span></h3>
                <p class="text-[8px] text-emerald-600 mt-3 font-bold uppercase">*Sampah Siap Jual</p>
            </div>
            <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 shadow-sm">
                <p class="text-[9px] font-bold text-emerald-700 uppercase tracking-widest mb-1">Keuntungan Bersih</p>
                <h3 class="text-2xl font-black text-emerald-800">Rp<?= number_format($data['keuntungan_bersih'], 0, ',', '.') ?></h3>
                <p class="text-[8px] text-emerald-600 mt-3 font-medium">*Laba nyata operasional</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-[3rem] p-8 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Anggota Aktif</h4>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <span class="block text-4xl font-black text-slate-800 mb-2"><?= $data['jml_siswa'] ?></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa Terdaftar</span>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <span class="block text-4xl font-black text-slate-800 mb-2"><?= $data['jml_guru'] ?></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Guru & Staf</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-8 bg-emerald-600 rounded-[3rem] shadow-lg shadow-emerald-200 text-white group h-[48%] flex flex-col justify-center">
                    <h4 class="font-black text-base mb-1 italic">Input Setoran?</h4>
                    <p class="text-[10px] font-medium opacity-80 mb-6">Catat tabungan siswa per kelas.</p>
                    <a href="<?= BASE_URL ?>/setoran/siswa_kelas" class="flex items-center justify-center py-3 bg-white text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-transform group-hover:scale-105">Mulai Timbang</a>
                </div>
                <?php if($_SESSION['role'] === 'admin'): ?>
                <div class="p-8 bg-slate-800 rounded-[3rem] shadow-lg text-white group h-[48%] flex flex-col justify-center">
                    <h4 class="font-black text-base mb-1 italic">Proses Penjualan?</h4>
                    <p class="text-[10px] font-medium opacity-80 mb-6">Konversi stok ke pengepul.</p>
                    <a href="<?= BASE_URL ?>/penjualan" class="flex items-center justify-center py-3 bg-slate-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-transform group-hover:scale-105">Jual Sekarang</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

    <?php 
    // =========================================================================
    // TAMPILAN DASHBOARD KHUSUS: SISWA & GURU (NASABAH)
    // =========================================================================
    else: 
    ?>
        <?php if($data['is_walikelas']): ?>
            <div class="p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 text-xs font-bold shadow-sm rounded-xl mb-6 flex items-center">
                <span class="text-xl mr-3">👨‍🏫</span> Anda menjabat sebagai Wali Kelas untuk Kelas <?= htmlspecialchars($data['data_kelas']['nama_kelas']) ?>.
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-8 rounded-[3rem] text-white shadow-2xl shadow-emerald-500/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10 text-8xl">💳</div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70 mb-2">Saldo Bersih Tersedia</p>
                    <h3 class="text-5xl font-black mb-6 tracking-tighter">Rp <?= number_format($data['saldo_pribadi'], 0, ',', '.') ?></h3>
                    <div class="flex justify-between items-end border-t border-emerald-500/50 pt-4 mt-8">
                        <div>
                            <p class="text-[8px] uppercase tracking-widest opacity-60">Total Disetor</p>
                            <p class="text-sm font-bold"><?= number_format($data['total_pcs'], 0, ',', '.') ?> Pcs Sampah</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[8px] uppercase tracking-widest opacity-60">Status Akun</p>
                            <p class="text-sm font-bold text-emerald-200 italic">AKTIF</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm flex flex-col">
                <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic border-b border-slate-100 pb-4 mb-4">Riwayat Setoran Terbaru</h3>
                <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
                    <?php if(empty($data['riwayat_pribadi'])): ?>
                        <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                            <span class="text-4xl mb-2">🍃</span>
                            <p class="text-[10px] font-bold uppercase tracking-widest">Belum ada transaksi</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($data['riwayat_pribadi'] as $r): ?>
                            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div>
                                    <p class="text-xs font-black text-slate-800 uppercase italic"><?= htmlspecialchars($r['nama_sampah']) ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-1"><?= date('d M Y', strtotime($r['created_at'])) ?> • <?= $r['berat'] ?> Pcs</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-emerald-600">+ Rp<?= number_format($r['total_harga'], 0, ',', '.') ?></p>
                                    <?php if($r['status'] == 'valid'): ?>
                                        <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">Selesai</span>
                                    <?php else: ?>
                                        <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest">Pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>