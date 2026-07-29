<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">TRANSAKSI<span class="text-emerald-500">PENGEPUL</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Riwayat Penjualan & Pencairan Kas Tunai</p>
        </div>
        <a href="<?= BASE_URL ?>/penjualan/create" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center">
            <span class="mr-2 text-lg">🚛</span> Jual Barang Gudang
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm">🚨 <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-5">Tanggal Jual</th>
                        <th class="px-8 py-5">Jenis Sampah</th>
                        <th class="px-8 py-5 text-center">Volume (KG & Pcs)</th>
                        <th class="px-8 py-5 text-right">Harga Nego / KG</th>
                        <th class="px-8 py-5 text-right">Total Kas Cair</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($penjualan)): ?>
                        <tr><td colspan="5" class="px-8 py-12 text-center text-slate-400 text-xs italic">Belum ada riwayat penjualan pengepul.</td></tr>
                    <?php else: ?>
                        <?php foreach($penjualan as $r): 
                            // 🛠️ Merekontruksi angka aktual KG dengan membagi total_pendapatan / harga_per_kg
                            $harga_per_kg = (float)$r['harga_per_pcs'];
                            $jml_kg = $harga_per_kg > 0 ? ((float)$r['total_pendapatan'] / $harga_per_kg) : 0;
                        ?>
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-5 text-[11px] font-bold text-slate-500 uppercase"><?= date('d M Y | H:i', strtotime($r['tanggal_jual'])) ?></td>
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($r['nama_sampah']) ?></div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ket: <?= htmlspecialchars($r['keterangan'] ?: '-') ?></div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="font-black text-slate-700 text-sm"><?= number_format($jml_kg, 2, ',', '.') ?> KG</div>
                                <div class="text-[9px] font-bold text-slate-400 mt-1"><?= number_format($r['total_pcs'], 0, ',', '.') ?> PCS</div>
                            </td>
                            <td class="px-8 py-5 text-right font-bold text-slate-500 text-xs">Rp<?= number_format($harga_per_kg, 0, ',', '.') ?></td>
                            <td class="px-8 py-5 text-right">
                                <div class="font-black text-emerald-600 text-sm">Rp<?= number_format($r['total_pendapatan'], 0, ',', '.') ?></div>
                                <!-- ✨ Menampilkan Kas Tutup Botol jika nilainya lebih dari 0 -->
                                <?php if(isset($r['kas_tutup_botol_rp']) && $r['kas_tutup_botol_rp'] > 0): ?>
                                    <div class="text-[9px] font-bold text-amber-500 uppercase tracking-widest mt-1">
                                        + Tutup Botol: Rp<?= number_format($r['kas_tutup_botol_rp'], 0, ',', '.') ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>