<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">BUKU KAS<span class="text-emerald-500">UMUM</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Laporan Arus Kas Fisik / Rekening Bank Sampah</p>
        </div>
    </div>

    <div class="no-print bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/laporan/buku_kas" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Bulan Pembukuan</label>
                <div class="flex gap-4">
                    <select name="bulan" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                        <?php 
                        $nama_bulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                        foreach($nama_bulan as $m => $nama): 
                        ?>
                            <option value="<?= $m ?>" <?= ($m == $bulan) ? 'selected' : '' ?>><?= $nama ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="tahun" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                        <?php for($y = date('Y')-2; $y <= date('Y')+1; $y++): ?>
                            <option value="<?= $y ?>" <?= ($y == $tahun) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-lg">
                Tampilkan Kas
            </button>
        </form>
    </div>

    <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:border-none print:p-0">
        <div class="flex justify-between items-start border-b-4 border-slate-900 pb-8 mb-8">
            <div class="space-y-1">
                <h1 class="text-2xl font-black uppercase italic tracking-tighter text-slate-900">REKAPITULASI BUKU KAS</h1>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Periode: <?= $nama_bulan[$bulan] ?> <?= $tahun ?></p>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black uppercase text-slate-400 mb-1 tracking-widest">Saldo Awal Bulan</p>
                <p class="text-xl font-black text-slate-800">Rp <?= number_format($saldo_awal, 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-y-2 border-slate-100 text-[9px] uppercase font-black text-slate-400 tracking-widest">
                        <th class="px-6 py-4">Tanggal Waktu</th>
                        <th class="px-6 py-4">Uraian Transaksi</th>
                        <th class="px-6 py-4 text-right">Masuk (Debit)</th>
                        <th class="px-6 py-4 text-right">Keluar (Kredit)</th>
                        <th class="px-6 py-4 text-right">Sisa Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="bg-slate-50/50">
                        <td class="px-6 py-4 text-[10px] font-bold text-slate-400">01/<?= $bulan ?>/<?= $tahun ?></td>
                        <td class="px-6 py-4 font-black text-slate-600 text-xs italic">SALDO PINDAHAN BULAN LALU</td>
                        <td class="px-6 py-4 text-right">-</td>
                        <td class="px-6 py-4 text-right">-</td>
                        <td class="px-6 py-4 text-right font-black text-slate-800">Rp <?= number_format($saldo_awal, 0, ',', '.') ?></td>
                    </tr>

                    <?php 
                    $saldo_berjalan = $saldo_awal;
                    $total_debit = 0;
                    $total_kredit = 0;

                    if(empty($buku_kas)): 
                    ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 italic font-bold uppercase tracking-widest">Tidak ada transaksi fisik di bulan ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($buku_kas as $kas): 
                            $saldo_berjalan += $kas['debit'];
                            $saldo_berjalan -= $kas['kredit'];
                            $total_debit += $kas['debit'];
                            $total_kredit += $kas['kredit'];
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-[10px] font-bold text-slate-500">
                                <?= date('d/m/Y H:i', strtotime($kas['waktu'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-black text-slate-800 uppercase italic text-xs tracking-tighter"><?= htmlspecialchars($kas['uraian']) ?></p>
                                <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase"><?= htmlspecialchars($kas['detail'] ?: '-') ?></p>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-black text-emerald-600">
                                <?= $kas['debit'] > 0 ? '+ Rp ' . number_format($kas['debit'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-black text-red-500">
                                <?= $kas['kredit'] > 0 ? '- Rp ' . number_format($kas['kredit'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-black text-slate-800">
                                Rp <?= number_format($saldo_berjalan, 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-slate-900 text-white font-black text-xs uppercase tracking-widest border-t-4 border-slate-900">
                        <td colspan="2" class="px-6 py-5 text-right">TOTAL MUTASI BULAN INI:</td>
                        <td class="px-6 py-5 text-right text-emerald-400">+ Rp <?= number_format($total_debit, 0, ',', '.') ?></td>
                        <td class="px-6 py-5 text-right text-red-400">- Rp <?= number_format($total_kredit, 0, ',', '.') ?></td>
                        <td class="px-6 py-5 text-right text-white text-base">Rp <?= number_format($saldo_berjalan, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-8 p-6 <?= $saldo_berjalan < 0 ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200' ?> border rounded-2xl flex items-center">
            <div class="text-3xl mr-4"><?= $saldo_berjalan < 0 ? '🚨' : '🛡️' ?></div>
            <div>
                <h4 class="font-black text-[11px] uppercase tracking-widest <?= $saldo_berjalan < 0 ? 'text-red-700' : 'text-emerald-700' ?>">
                    STATUS KAS FISIK: <?= $saldo_berjalan < 0 ? 'DEFISIT / UANG KURANG' : 'SEHAT & AMAN' ?>
                </h4>
                <p class="text-[10px] font-bold mt-1 <?= $saldo_berjalan < 0 ? 'text-red-500' : 'text-emerald-600' ?>">
                    Angka Rp <?= number_format($saldo_berjalan, 0, ',', '.') ?> di atas harus SAMA PERSIS dengan jumlah uang fisik yang ada di dalam laci kasir/rekening bank sekolah saat ini.
                </p>
            </div>
        </div>

        <div class="mt-10 no-print flex justify-end">
            <button onclick="window.print()" class="px-8 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all">
                🖨️ Cetak / Tutup Buku Bulan Ini
            </button>
        </div>
    </div>
</div>

<style>
    @media print { 
        .no-print { display: none !important; } 
        body { background: white !important; }
        main { padding: 0 !important; }
    }
</style>