<!-- views/admin/laporan/kas_kesiswaan.php -->
<div class="max-w-7xl mx-auto space-y-6 pb-12">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KAS<span class="text-red-500">KESISWAAN</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Rekapitulasi Denda Botol & Hukuman Disiplin</p>
    </div>

    <!-- METRIK DASHBOARD KESISWAAN -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Saldo Aktif -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-10 text-8xl">🛡️</div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Saldo Bisa Ditarik (OSIS)</p>
                <h3 class="text-4xl font-black text-emerald-400">Rp <?= number_format($data['saldo_aktif'], 0, ',', '.') ?></h3>
            </div>
        </div>

        <!-- Total Uang Masuk -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">📥</div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Akumulasi Uang Denda</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 ml-14">Rp <?= number_format($data['total_uang_masuk'], 0, ',', '.') ?></h3>
        </div>

        <!-- Total Pcs Botol Denda -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl">⚖️</div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Botol Terkumpul</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 ml-14"><?= number_format($data['total_botol_pcs'], 0, ',', '.') ?> <span class="text-sm font-bold text-slate-400">Pcs</span></h3>
        </div>
    </div>

    <!-- TABEL RIWAYAT -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm mt-8">
        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-black text-slate-800 uppercase italic">Riwayat Mutasi Kesiswaan</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1">Catatan anak terlambat dan penarikan dana oleh OSIS.</p>
            </div>
            <!-- Tombol Pintasan -->
            <div class="flex gap-2">
                <a href="<?= BASE_URL ?>/setoran/create_kesiswaan" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-colors">
                    + Input Denda
                </a>
                <a href="<?= BASE_URL ?>/penarikan" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-colors">
                    💸 Tarik Saldo
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[9px] uppercase font-black text-slate-400 tracking-widest">
                        <th class="px-4 py-3 rounded-l-xl">Tanggal</th>
                        <th class="px-4 py-3">Transaksi / Keterangan</th>
                        <th class="px-4 py-3 text-center">Volume</th>
                        <th class="px-4 py-3 text-right rounded-r-xl">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    <?php if(empty($data['mutasi'])): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-slate-400 font-bold italic">Belum ada riwayat denda botol kesiswaan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['mutasi'] as $m): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 font-bold text-slate-500 whitespace-nowrap">
                                    <?= date('d M Y, H:i', strtotime($m['tanggal'])) ?>
                                </td>
                                <td class="px-4 py-4">
                                    <?php if($m['tipe'] == 'setoran'): ?>
                                        <p class="font-black text-slate-800 uppercase">HUKUMAN: <?= htmlspecialchars($m['jenis_botol']) ?></p>
                                        <p class="text-[9px] font-bold text-red-500 uppercase mt-0.5 tracking-widest">Oleh: <?= htmlspecialchars($m['ket'] ?? 'Siswa Tidak Diketahui') ?></p>
                                    <?php else: ?>
                                        <p class="font-black text-emerald-700 uppercase">PENARIKAN DANA OSIS</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5 tracking-widest">Ket: <?= htmlspecialchars($m['ket']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4 text-center font-bold text-slate-600">
                                    <?= $m['qty'] > 0 ? $m['qty'] . ' Pcs' : '-' ?>
                                </td>
                                <td class="px-4 py-4 text-right font-black <?= $m['tipe'] == 'setoran' ? 'text-emerald-600' : 'text-red-600' ?>">
                                    <?= $m['tipe'] == 'setoran' ? '+' : '-' ?> <?= number_format($m['jumlah'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>