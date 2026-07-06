<?php 
// 🛡️ Variabel sakelar untuk mempermudah pengecekan hak akses di bawah
$role = $_SESSION['role'] ?? ''; 
?>
<div class="max-w-7xl mx-auto space-y-6 pb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight italic uppercase">
                <?php if($role === 'admin' || $role === 'staff'): ?>
                    BST<span class="text-emerald-500">DASHBOARD</span>
                <?php else: ?>
                    HALO, <span class="text-emerald-500"><?= htmlspecialchars($_SESSION['nama']) ?>!</span>
                <?php endif; ?>
            </h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">
                <?php if($role === 'admin' || $role === 'staff'): ?>
                    Integrasi Data Real-Time Sekolah
                <?php else: ?>
                    Akses: <?= strtoupper($role) ?> 
                    <?= (isset($is_walikelas_aktif) && $is_walikelas_aktif) ? '• WALI KELAS ' . htmlspecialchars($kelas_dikelola['nama_kelas']) : '' ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-8 rounded-[3rem] text-white shadow-xl shadow-orange-200/50 relative overflow-hidden mt-6">
        <div class="absolute -right-4 -top-8 opacity-20 text-[10rem] pointer-events-none">🏆</div>
        <div class="relative z-20 flex flex-col lg:flex-row gap-8 items-center">
            <div class="lg:w-1/3">
                <h3 class="font-black text-3xl uppercase tracking-tighter mb-1 drop-shadow-md">TOP NASABAH</h3>
                <p class="text-xs font-bold uppercase tracking-widest opacity-90 mb-4">Penyetor Terbanyak • Sejak <?= date('d M Y', strtotime($tgl_mulai_reward)) ?></p>
                <div class="inline-block px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl border border-white/30 text-[10px] font-bold uppercase tracking-widest shadow-sm">
                    🎁 Hadiah menanti di akhir Triwulan!
                </div>
            </div>
            
            <div class="lg:w-2/3 w-full grid grid-cols-1 md:grid-cols-3 gap-6 relative z-30">
                <?php if(empty($leaderboard)): ?>
                    <div class="col-span-3 text-center py-4 bg-white/10 rounded-2xl border border-white/20">
                        <p class="text-sm italic font-bold">Belum ada setoran pada periode reward kali ini. Ayo mulai menabung!</p>
                    </div>
                <?php else: ?>
                    <?php foreach($leaderboard as $idx => $lb): if($idx > 2) break; ?>
                        <div class="relative group">
                            <div class="bg-white/20 backdrop-blur-md p-5 rounded-2xl border border-white/30 flex items-center gap-4 transform transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-xl group-hover:bg-white/30 cursor-default">
                                <div class="w-12 h-12 shrink-0 rounded-full bg-white flex items-center justify-center font-black text-2xl shadow-inner <?= $idx === 0 ? 'text-amber-500' : ($idx === 1 ? 'text-slate-400' : 'text-orange-700') ?>">
                                    <?= $idx === 0 ? '🥇' : ($idx === 1 ? '🥈' : '🥉') ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-black uppercase truncate text-white" title="<?= htmlspecialchars($lb['nama']) ?>"><?= htmlspecialchars($lb['nama']) ?></p>
                                    <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1 truncate">KLS <?= htmlspecialchars($lb['nama_kelas'] ?? '-') ?></p>
                                    <p class="text-sm font-black text-yellow-100 drop-shadow-md"><?= number_format($lb['total_pcs'], 0) ?> Pcs</p>
                                </div>
                            </div>
                            
                            <?php if($role === 'admin'): ?>
                            <div class="absolute -bottom-4 left-0 right-0 flex justify-center opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-300 z-[60]">
                                <button onclick="bukaModalReward(<?= $lb['id'] ?>, '<?= htmlspecialchars($lb['nama']) ?>')" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black shadow-lg shadow-black/20 transform active:scale-95 whitespace-nowrap">
                                    🎁 Beri Hadiah
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($role === 'admin'): ?>
    <div id="modalReward" class="fixed inset-0 z-[100] hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl border border-slate-200 overflow-hidden transform transition-all relative">
            
            <div class="p-8 text-center bg-slate-50 border-b border-slate-100">
                <div class="w-16 h-16 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border-4 border-white shadow-sm">🎁</div>
                <h3 class="text-xl font-black text-slate-800 uppercase italic">Klaim Hadiah Prestasi</h3>
                <p class="text-xs font-bold text-slate-500 mt-2">Suntikkan saldo otomatis ke rekening <br><span id="namaSiswaTarget" class="text-emerald-600 uppercase font-black text-sm"></span></p>
            </div>

            <div class="p-8">
                <form action="<?= BASE_URL ?>/setoran/reward" method="POST">
                    <input type="hidden" name="user_id" id="userIdTarget">
                    <input type="hidden" name="nama_siswa" id="namaSiswaInput">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tentukan Nominal Hadiah (Rp)</label>
                            
                            <input type="number" id="inputNominal" name="nominal" placeholder="Contoh: 15000" required class="w-full px-5 py-4 bg-white border-2 border-slate-200 rounded-2xl text-xl font-black text-slate-800 text-center focus:border-amber-400 focus:ring-0 outline-none mb-3">
                            
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" onclick="document.getElementById('inputNominal').value = 10000" class="py-2 bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-bold text-slate-600 hover:bg-slate-100 transition-colors">10K</button>
                                <button type="button" onclick="document.getElementById('inputNominal').value = 25000" class="py-2 bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-bold text-slate-600 hover:bg-slate-100 transition-colors">25K</button>
                                <button type="button" onclick="document.getElementById('inputNominal').value = 50000" class="py-2 bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-bold text-slate-600 hover:bg-slate-100 transition-colors">50K</button>
                            </div>
                            <p class="text-[9px] italic text-slate-400 mt-4 text-center">Nominal ini akan ditambahkan ke Tabungan Siswa dan tercatat sebagai <strong class="text-amber-600">Pengeluaran Kas Sekolah</strong>.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="tutupModalReward()" class="flex-1 px-4 py-4 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-4 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-colors">
                            🚀 Eksekusi
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>

    <script>
        function bukaModalReward(id, nama) {
            document.getElementById('userIdTarget').value = id;
            document.getElementById('namaSiswaTarget').innerText = nama;
            document.getElementById('namaSiswaInput').value = nama;
            
            const modal = document.getElementById('modalReward');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalReward() {
            const modal = document.getElementById('modalReward');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('inputNominal').value = ''; 
        }
    </script>
    <?php endif; ?>

    <?php 
    // =========================================================================
    // TAMPILAN DASHBOARD KHUSUS: ADMIN & STAFF
    // =========================================================================
    if($role === 'admin' || $role === 'staff'): 
    ?>
        
        <?php if($role === 'admin'): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-slate-900 p-6 rounded-3xl text-white shadow-xl">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Kas Masuk</p>
                <h3 class="text-2xl font-black text-emerald-400">Rp<?= number_format($kas_masuk ?? 0, 0, ',', '.') ?></h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tabungan</p>
                <h3 class="text-2xl font-black text-slate-800">Rp<?= number_format($total_tabungan ?? 0, 0, ',', '.') ?></h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 text-8xl">📦</div>
                <div class="relative z-10">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stok Gudang (Siap Jual)</p>
                    <h3 class="text-2xl font-black text-slate-800"><?= number_format($stok_gudang ?? 0, 0, ',', '.') ?> <span class="text-xs font-normal">Pcs</span></h3>
                </div>
            </div>
            <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 shadow-sm">
                <p class="text-[9px] font-bold text-emerald-700 uppercase tracking-widest mb-1">Keuntungan Bersih</p>
                <h3 class="text-2xl font-black text-emerald-800">Rp<?= number_format($keuntungan_bersih ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-[3rem] p-8 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Anggota Aktif</h4>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <span class="block text-4xl font-black text-slate-800 mb-2"><?= $jml_siswa ?? 0 ?></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa Terdaftar</span>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <span class="block text-4xl font-black text-slate-800 mb-2"><?= $jml_guru ?? 0 ?></span>
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
                
                <?php if($role === 'admin'): ?>
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
                        <h3 class="text-5xl font-black mb-6 tracking-tighter text-emerald-400">Rp <?= number_format($saldo_pribadi ?? 0, 0, ',', '.') ?></h3>
                        <div class="flex justify-between items-end border-t border-slate-700 pt-4 mt-8">
                            <div><p class="text-[8px] uppercase tracking-widest opacity-50">Kontribusi Total</p><p class="text-sm font-bold"><?= number_format($total_pcs ?? 0, 0, ',', '.') ?> Pcs</p></div>
                            <div class="text-right px-4 py-1 bg-emerald-500/20 rounded-lg"><p class="text-[10px] font-black text-emerald-400 italic">NASABAH AKTIF</p></div>
                        </div>
                    </div>
                </div>

                <?php if(isset($is_walikelas_aktif) && $is_walikelas_aktif): ?>
                <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Ranking Internal Kelas <?= htmlspecialchars($kelas_dikelola['nama_kelas']) ?></h3>
                        <span class="text-[9px] font-bold text-slate-400">Periode: Sejak <?= date('d M Y', strtotime($tgl_mulai_reward)) ?></span>
                    </div>
                    <div class="space-y-4">
                        <?php foreach($ranking_siswa as $idx => $s): ?>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-4">
                                <span class="w-8 h-8 flex items-center justify-center bg-slate-900 text-white rounded-full text-xs font-black"><?= $idx+1 ?></span>
                                <span class="text-xs font-black text-slate-700 uppercase"><?= htmlspecialchars($s['nama']) ?></span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600"><?= number_format($s['total_pcs'], 0) ?> Pcs</span>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($ranking_siswa)): ?>
                            <p class="text-[10px] italic text-slate-400 text-center py-4">Belum ada penyetoran pada periode ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <?php if((isset($is_walikelas_aktif) && $is_walikelas_aktif) || (isset($honor_belum_cair) && $honor_belum_cair > 0)): ?>
                <div class="bg-emerald-600 p-6 rounded-[2.5rem] text-white shadow-xl shadow-emerald-200">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70 mb-1">Honor Wali Kelas</p>
                    <div class="mb-4">
                        <p class="text-[8px] opacity-60 uppercase">Menunggu Pencairan:</p>
                        <h4 class="text-xl font-black">Rp <?= number_format($honor_belum_cair ?? 0, 0, ',', '.') ?></h4>
                    </div>
                    <div class="pt-4 border-t border-emerald-500">
                        <p class="text-[9px] font-black uppercase opacity-70 mb-2 italic">Histori Cair Terakhir:</p>
                        <div class="space-y-2">
                            <?php if(empty($history_honor)): ?>
                                <p class="text-[10px] italic opacity-50">Belum pernah cair.</p>
                            <?php else: ?>
                                <?php foreach($history_honor as $hh): ?>
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
                        <?php if(empty($riwayat_pribadi)): ?>
                            <p class="text-[10px] text-center italic text-slate-400 py-4">Belum ada transaksi.</p>
                        <?php else: ?>
                            <?php foreach($riwayat_pribadi as $r): ?>
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