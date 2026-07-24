<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">VALIDASI<span class="text-amber-500">ANTREAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Verifikasi Setoran Sebelum Masuk Saldo</p>
        </div>
    </div>

    <!-- Tampilan Pesan Success/Error -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm">
            ✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm">
            🚨 <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- TOMBOL VALIDASI MASSAL SEMUA ANTREAN -->
    <?php if (!empty($pending)): ?>
    <div class="flex justify-end mb-2">
        <form action="<?= BASE_URL ?>/setoran/proses_validasi_semua" method="POST" onsubmit="return confirm('Peringatan: Anda akan memvalidasi SEMUA antrean yang ada di tabel ini secara massal. Lanjutkan?');">
            <?= Security::csrf_field(); ?>
            <button type="submit" class="px-6 py-3 bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-emerald-700 transition-all flex items-center">
                <span class="mr-2 text-base">✅</span> Validasi Semua Antrean
            </button>
        </form>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-6">Informasi Nasabah</th>
                        <th class="px-8 py-6">Barang & Volume</th>
                        <th class="px-8 py-6 text-right">Nilai Rupiah</th>
                        <th class="px-8 py-6 text-center">Tindakan Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($pending)): ?>
                        <tr><td colspan="4" class="px-8 py-16 text-center">
                            <div class="text-4xl mb-4">🎉</div>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Semua antrean bersih. Tidak ada data pending!</p>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach($pending as $p): ?>
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-6">
                                <div class="text-[10px] font-bold text-slate-400 uppercase mb-1"><?= date('d/m/y | H:i', strtotime($p['created_at'])) ?></div>
                                <div class="font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($p['nama_siswa']) ?></div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-700 text-xs uppercase"><?= htmlspecialchars($p['nama_sampah']) ?></div>
                                <div class="px-3 py-0.5 bg-slate-100 text-slate-600 rounded text-[9px] font-black mt-1 inline-block"><?= number_format($p['berat'], 0) ?> PCS</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="text-[9px] font-black text-slate-400 uppercase">Estimasi Saldo:</div>
                                <div class="font-black text-amber-600 text-lg tracking-tighter">Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= BASE_URL ?>/setoran/proses_validasi/<?= $p['id'] ?>" class="px-5 py-2 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all">Validasi</a>
                                    <a href="<?= BASE_URL ?>/setoran/edit_pending/<?= $p['id'] ?>" class="px-5 py-2 bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">Edit</a>
                                    <a href="<?= BASE_URL ?>/setoran/hapus_pending/<?= $p['id'] ?>" onclick="return confirm('Hapus setoran ini?')" class="px-5 py-2 bg-red-50 text-red-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-red-600 hover:text-white transition-all">Batal</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>