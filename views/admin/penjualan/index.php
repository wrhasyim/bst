<div class="max-w-7xl mx-auto space-y-8 pb-10">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">TRANSAKSI<span class="text-emerald-500">PENGEPUL</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Jual Stok Gudang & Cairkan Profit</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm flex items-center animate-pulse">
            <span class="mr-2 text-lg">💰</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm flex items-center">
            <span class="mr-2 text-lg">⚠️</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="space-y-6">
            <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden h-full">
                <div class="p-8 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Stok Gudang (Siap Jual)</h3>
                    <span class="text-2xl">📦</span>
                </div>
                <div class="p-6">
                    <?php if(empty($stok)): ?>
                        <div class="text-center py-10">
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada stok valid di gudang.</p>
                            <p class="text-[10px] text-slate-400 mt-2 italic">*Validasi setoran pending terlebih dahulu.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach($stok as $s): ?>
                            <div class="p-6 border border-slate-100 rounded-[2rem] bg-slate-50 hover:bg-emerald-50 hover:border-emerald-100 transition-all group flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="text-center sm:text-left w-full">
                                    <h4 class="font-black text-slate-800 uppercase italic tracking-tighter text-lg"><?= htmlspecialchars($s['nama_sampah']) ?></h4>
                                    <div class="flex items-center justify-center sm:justify-start gap-4 mt-2">
                                        <span class="text-sm font-black text-slate-600"><?= number_format($s['total_pcs'], 0) ?> PCS</span>
                                        <span class="text-[10px] font-bold text-slate-400">|</span>
                                        <span class="text-xs font-black text-emerald-600">Rp<?= number_format($s['estimasi_pendapatan'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                                <form action="<?= BASE_URL ?>/penjualan/jual" method="POST" class="shrink-0 w-full sm:w-auto">
                                    <input type="hidden" name="kategori_id" value="<?= $s['kategori_id'] ?>">
                                    <button type="submit" onclick="return confirm('Jual <?= number_format($s['total_pcs'], 0) ?> Pcs <?= $s['nama_sampah'] ?> ke pengepul?')" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-emerald-700 transition-all transform active:scale-95">
                                        🚀 Jual Semua
                                    </button>
                                </form>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-slate-900 rounded-[3rem] shadow-2xl overflow-hidden h-full text-white">
                <div class="p-8 border-b border-slate-800 bg-slate-950 flex justify-between items-center">
                    <h3 class="font-black text-emerald-400 text-xs uppercase tracking-widest italic underline">Riwayat Transaksi Keluar</h3>
                    <span class="text-2xl">🚛</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-800 text-[9px] uppercase text-slate-500 font-black tracking-widest">
                                <th class="px-8 py-5">Tgl Jual</th>
                                <th class="px-8 py-5">Kategori</th>
                                <th class="px-8 py-5 text-right">Qty</th>
                                <th class="px-8 py-5 text-right">Rp Cair</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            <?php if(empty($riwayat)): ?>
                                <tr><td colspan="4" class="px-8 py-10 text-center text-slate-500 text-xs italic">Belum ada riwayat penjualan.</td></tr>
                            <?php else: ?>
                                <?php foreach($riwayat as $r): ?>
                                <tr class="hover:bg-slate-800 transition-all">
                                    <td class="px-8 py-4 text-[10px] font-bold text-slate-400"><?= date('d/m/y', strtotime($r['tanggal_jual'])) ?></td>
                                    <td class="px-8 py-4 font-black text-slate-200 text-xs uppercase italic tracking-tighter"><?= htmlspecialchars($r['nama_sampah']) ?></td>
                                    <td class="px-8 py-4 text-right font-bold text-slate-300 text-xs"><?= number_format($r['total_berat'], 0) ?></td>
                                    <td class="px-8 py-4 text-right font-black text-emerald-400 text-sm">Rp<?= number_format($r['total_pendapatan'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>