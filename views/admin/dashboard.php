<div class="max-w-7xl mx-auto space-y-6 pb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight italic uppercase">
                <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                    BST<span class="text-emerald-500">DASHBOARD</span>
                <?php else: ?>
                    HALO, <span class="text-emerald-500"><?= htmlspecialchars($_SESSION['nama']) ?>!</span>
                <?php endif; ?>
            </h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">
                <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                    Integrasi Data Real-Time Sekolah
                <?php else: ?>
                    Akses: <?= strtoupper($_SESSION['role']) ?> 
                    <?= (isset($data['is_walikelas_aktif']) && $data['is_walikelas_aktif']) ? '• WALI KELAS ' . htmlspecialchars($data['kelas_dikelola']['nama_kelas']) : '' ?>
                <?php endif; ?>
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
    // TAMPILAN DASHBOARD KHUSUS: SISWA, GURU, & WALI KELAS
    // =========================================================================
    else: 
    ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6">
            
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 rounded-[3rem] text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-10 text-8xl italic font-black">BST</div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-50 mb-2">Saldo Tabungan Pribadi</p>
                        <h3 class="text-5xl font-black mb-6 tracking-tighter text-emerald-400">Rp <?= number_format($data['saldo_pribadi'], 0, ',', '.') ?></h3>
                        <div class="flex justify-between items-end border-t border-slate-700 pt-4 mt-8">
                            <div><p class="text-[8px] uppercase tracking-widest opacity-50">Kontribusi</p><p class="text-sm font-bold"><?= number_format($data['total_pcs'], 0, ',', '.') ?> Pcs</p></div>
                            <div class="text-right px-4 py-1 bg-emerald-500/20 rounded-lg"><p class="text-[10px] font-black text-emerald-400 italic">NASABAH AKTIF</p></div>
                        </div>
                    </div>
                </div>

                <?php if(isset($data['is_walikelas_aktif']) && $data['is_walikelas_aktif']): ?>
                <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Ranking Siswa Kelas <?= htmlspecialchars($data['kelas_dikelola']['nama_kelas']) ?></h3>
                        <span class="text-[9px] font-bold text-slate-400">Top 5 Volume</span>
                    </div>
                    <div class="space-y-4">
                        <?php foreach($data['ranking_siswa'] as $idx => $s): ?>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                            <div class="flex items-center gap-4">
                                <span class="w-8 h-8 flex items-center justify-center bg-slate-900 text-white rounded-full text-xs font-black"><?= $idx+1 ?></span>
                                <span class="text-xs font-black text-slate-700 uppercase"><?= htmlspecialchars($s['nama']) ?></span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600"><?= number_format($s['total_pcs'], 0) ?> Pcs</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <?php if((isset($data['is_walikelas_aktif']) && $data['is_walikelas_aktif']) || (isset($data['honor_belum_cair']) && $data['honor_belum_cair'] > 0)): ?>
                <div class="bg-emerald-600 p-6 rounded-[2.5rem] text-white shadow-xl shadow-emerald-200">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70 mb-1">Honor Wali Kelas</p>
                    <div class="mb-4">
                        <p class="text-[8px] opacity-60 uppercase">Belum Dicairkan:</p>
                        <h4 class="text-xl font-black">Rp <?= number_format($data['honor_belum_cair'], 0, ',', '.') ?></h4>
                    </div>
                    <div class="pt-4 border-t border-emerald-500">
                        <p class="text-[9px] font-black uppercase opacity-70 mb-2 italic">Histori Pencairan Terakhir:</p>
                        <div class="space-y-2">
                            <?php if(empty($data['history_honor'])): ?>
                                <p class="text-[10px] italic opacity-50">Belum pernah cair.</p>
                            <?php else: ?>
                                <?php foreach($data['history_honor'] as $hh): ?>
                                <div class="flex justify-between text-[10px] font-bold">
                                    <span><?= date('d/m/y', strtotime($hh['tanggal_cair'])) ?></span>
                                    <span>Rp<?= number_format($hh['jumlah'], 0) ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm">
                    <h3 class="font-black text-slate-800 text-[10px] uppercase tracking-widest border-b pb-3 mb-4 italic">Setoran Terakhir Anda</h3>
                    <div class="space-y-3">
                        <?php if(empty($data['riwayat_pribadi'])): ?>
                            <p class="text-[10px] text-center italic text-slate-400 py-4">Belum ada transaksi.</p>
                        <?php else: ?>
                            <?php foreach($data['riwayat_pribadi'] as $r): ?>
                            <div class="flex justify-between items-center text-[10px]">
                                <div>
                                    <p class="font-black text-slate-700 uppercase"><?= htmlspecialchars($r['nama_sampah']) ?></p>
                                    <p class="text-slate-400"><?= date('d M', strtotime($r['created_at'])) ?></p>
                                </div>
                                <p class="font-black text-emerald-600">+<?= number_format($r['total_harga'], 0) ?></p>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </div>

    <?php endif; ?>
</div>