<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">RIWAYAT<span class="text-emerald-500">TABUNGAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Seluruh Aktivitas Transaksi Nasabah Siswa</p>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-6">Waktu Setor</th>
                        <th class="px-8 py-6">Nama Siswa</th>
                        <th class="px-8 py-6">Jenis Sampah</th>
                        <th class="px-8 py-6 text-center">Volume</th>
                        <th class="px-8 py-6 text-right">Rp Nasabah</th>
                        <th class="px-8 py-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($setoran)): ?>
                        <tr><td colspan="6" class="px-8 py-12 text-center text-slate-400 text-xs italic">Belum ada aktivitas tabungan terekam.</td></tr>
                    <?php else: ?>
                        <?php foreach($setoran as $s): ?>
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-5 text-[10px] font-bold text-slate-500 uppercase"><?= date('d M Y | H:i', strtotime($s['created_at'])) ?></td>
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($s['nama_siswa'] ?? $s['nama']) ?></div>
                                <div class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Kelas <?= htmlspecialchars($s['nama_kelas'] ?? '-') ?></div>
                            </td>
                            <td class="px-8 py-5 font-bold text-slate-600 text-xs uppercase tracking-tight"><?= htmlspecialchars($s['nama_sampah']) ?></td>
                            <td class="px-8 py-5 text-center font-black text-slate-700 text-xs"><?= number_format($s['berat'], 0) ?> PCS</td>
                            <td class="px-8 py-5 text-right font-black text-emerald-600 text-sm">Rp<?= number_format($s['total_harga'], 0, ',', '.') ?></td>
                            <td class="px-8 py-5 text-center">
                                <?php if($s['status'] == 'valid'): ?>
                                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100">Divalidasi</span>
                                <?php else: ?>
                                    <span class="px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-100">Menunggu</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 🧭 Navigasi Pagination -->
        <?php if(!empty($pagination) && $pagination['total_pages'] > 1): ?>
        <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total: <?= $pagination['total_data'] ?> Transaksi</p>
            <div class="flex gap-2">
                <?php for($i=1; $i<=$pagination['total_pages']; $i++): ?>
                    <a href="?page=<?= $i ?>" class="w-8 h-8 flex items-center justify-center text-xs font-black rounded-lg transition-all <?= ($i == $pagination['current_page']) ? 'bg-emerald-500 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>