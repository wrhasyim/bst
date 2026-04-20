<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">LAPORAN<span class="text-emerald-500">HONOR</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Detail Insentif Per Wali Kelas</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Margin Gudang</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter">Rp<?= number_format($total_margin_potensi, 0, ',', '.') ?></h3>
            </div>
            <div class="text-2xl">📦</div>
        </div>
        <div class="bg-emerald-600 p-6 rounded-[2.5rem] text-white flex items-center justify-between">
            <div>
                <p class="text-[9px] font-bold text-emerald-200 uppercase tracking-widest mb-1">Margin Terjual (Tunai)</p>
                <h3 class="text-2xl font-black tracking-tighter">Rp<?= number_format($total_margin_realisasi, 0, ',', '.') ?></h3>
            </div>
            <div class="text-2xl">💰</div>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                    <th class="px-8 py-5">Nama Wali Kelas</th>
                    <th class="px-8 py-5">Kelas</th>
                    <th class="px-8 py-5 text-right">Potensi Honor</th>
                    <th class="px-8 py-5 text-right">Honor Siap Cair</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach($rekap_honor as $w): ?>
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-8 py-5 font-bold text-slate-700 text-sm"><?= htmlspecialchars($w['nama_guru']) ?></td>
                    <td class="px-8 py-5"><span class="px-3 py-1 bg-slate-100 rounded-full text-[9px] font-black text-slate-500 uppercase italic"><?= htmlspecialchars($w['nama_kelas']) ?></span></td>
                    <td class="px-8 py-5 text-right text-xs font-bold text-slate-400">Rp<?= number_format($w['total_potensi'], 0, ',', '.') ?></td>
                    <td class="px-8 py-5 text-right font-black text-emerald-600 text-sm">Rp<?= number_format($w['total_realisasi'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>