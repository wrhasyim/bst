<div class="max-w-7xl mx-auto space-y-6 pb-12">
    
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">BUKU KAS<span class="text-emerald-500">UMUM</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Laporan Arus Kas Fisik / Rekening Bank Sampah</p>
        </div>
        <button onclick="window.print()" class="px-8 py-3.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center">
            <span class="mr-2">🖨️</span> Cetak / Tutup Buku
        </button>
    </div>

    <div class="no-print bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/laporan/buku_kas" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Periode Pembukuan</label>
                <div class="flex gap-4 items-center">
                    <input type="date" name="start_date" required value="<?= $_GET['start_date'] ?? date('Y-m-01') ?>" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    <span class="text-slate-400 font-bold">S/D</span>
                    <input type="date" name="end_date" required value="<?= $_GET['end_date'] ?? date('Y-m-t') ?>" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto px-10 py-4 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg">
                Tampilkan Data
            </button>
        </form>
    </div>

    <div id="print-area" class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:shadow-none print:border-none print:p-0">
        
        <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-10">
            <h1 class="text-2xl font-black uppercase tracking-[0.2em] text-slate-900">REKAPITULASI BUKU KAS UMUM</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Manajemen Bank Sampah TKM (BST SYSTEM)</p>
            <div class="inline-block px-4 py-1.5 bg-slate-100 text-slate-800 border border-slate-200 text-[10px] font-black uppercase tracking-widest mt-4 rounded-lg">
                Periode: <?= date('d/m/Y', strtotime($_GET['start_date'] ?? date('Y-m-01'))) ?> s/d <?= date('d/m/Y', strtotime($_GET['end_date'] ?? date('Y-m-t'))) ?>
            </div>
        </div>

        <?php 
            // Kalkulasi Saldo Berjalan secara terpisah
            $saldo_berjalan_besar = $saldo_awal_besar ?? 0;
            $saldo_berjalan_tb = $saldo_awal_tutup_botol ?? 0;
            
            $total_debit = 0;
            $total_kredit = 0;
            
            foreach($buku_kas as $kas) {
                $total_debit += $kas['debit'];
                $total_kredit += $kas['kredit'];

                if ($kas['sumber_kas'] === 'kas_tutup_botol') {
                    $saldo_berjalan_tb += $kas['debit'] - $kas['kredit'];
                } else {
                    $saldo_berjalan_besar += $kas['debit'] - $kas['kredit'];
                }
            }
            
            $total_saldo_awal_gabungan = ($saldo_awal_besar ?? 0) + ($saldo_awal_tutup_botol ?? 0);
            $total_saldo_akhir_gabungan = $saldo_berjalan_besar + $saldo_berjalan_tb;
        ?>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-3xl">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Saldo Awal</p>
                <p class="text-sm font-black text-slate-800">Rp <?= number_format($total_saldo_awal_gabungan, 0, ',', '.') ?></p>
            </div>
            <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-3xl">
                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Sisa Kas Besar</p>
                <p class="text-sm font-black text-emerald-700">Rp <?= number_format($saldo_berjalan_besar, 0, ',', '.') ?></p>
            </div>
            <div class="p-5 bg-blue-50 border border-blue-200 rounded-3xl">
                <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Sisa Kas Tutup Botol</p>
                <p class="text-sm font-black text-blue-700">Rp <?= number_format($saldo_berjalan_tb, 0, ',', '.') ?></p>
            </div>
            <div class="p-5 bg-slate-800 rounded-3xl shadow-lg shadow-slate-200 border border-slate-700">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Total Saldo Fisik</p>
                <p class="text-sm font-black text-white">Rp <?= number_format($total_saldo_akhir_gabungan, 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-slate-900">
                <thead class="bg-slate-50">
                    <tr class="text-[10px] font-black text-slate-900 uppercase tracking-widest border-b border-slate-900">
                        <th class="border border-slate-900 px-4 py-4 text-center w-12">No</th>
                        <th class="border border-slate-900 px-4 py-4 text-center">Tanggal</th>
                        <th class="border border-slate-900 px-4 py-4">Keterangan Transaksi</th>
                        <th class="border border-slate-900 px-4 py-4 text-center">Sumber Kas</th>
                        <th class="border border-slate-900 px-4 py-4 text-right">Debit (Rp)</th>
                        <th class="border border-slate-900 px-4 py-4 text-right">Kredit (Rp)</th>
                        <th class="border border-slate-900 px-4 py-4 text-right">Total Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-[11px]">
                    <tr class="bg-slate-50 font-bold italic">
                        <td class="border border-slate-900 px-4 py-3 text-center">-</td>
                        <td class="border border-slate-900 px-4 py-3 text-center"><?= date('d/m/Y', strtotime($_GET['start_date'] ?? date('Y-m-01'))) ?></td>
                        <td class="border border-slate-900 px-4 py-3 uppercase">SALDO SEBELUM PERIODE INI</td>
                        <td class="border border-slate-900 px-4 py-3 text-center">-</td>
                        <td class="border border-slate-900 px-4 py-3 text-right">-</td>
                        <td class="border border-slate-900 px-4 py-3 text-right">-</td>
                        <td class="border border-slate-900 px-4 py-3 text-right">Rp <?= number_format($total_saldo_awal_gabungan, 0, ',', '.') ?></td>
                    </tr>

                    <?php 
                    $saldo_v = $total_saldo_awal_gabungan;
                    if(empty($buku_kas)): 
                    ?>
                        <tr><td colspan="7" class="border border-slate-900 px-4 py-20 text-center text-slate-400 italic font-black uppercase tracking-widest">Tidak ada aktivitas transaksi pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($buku_kas as $i => $k): 
                            // Saldo gabungan berjalan
                            $saldo_v += $k['debit'];
                            $saldo_v -= $k['kredit'];
                        ?>
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-900 px-4 py-3 text-center"><?= $i+1 ?></td>
                            <td class="border border-slate-900 px-4 py-3 text-center"><?= date('d/m/Y H:i', strtotime($k['waktu'])) ?></td>
                            <td class="border border-slate-900 px-4 py-3 uppercase font-semibold">
                                <?= htmlspecialchars($k['uraian']) ?>
                                <span class="block text-[8px] font-normal text-slate-400 mt-0.5 lowercase italic"><?= htmlspecialchars($k['detail'] ?: '-') ?></span>
                            </td>
                            <td class="border border-slate-900 px-4 py-3 text-center font-bold">
                                <?php if($k['sumber_kas'] === 'kas_tutup_botol'): ?>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-[9px]">TUTUP BOTOL</span>
                                <?php else: ?>
                                    <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-[9px]">KAS BESAR</span>
                                <?php endif; ?>
                            </td>
                            <td class="border border-slate-900 px-4 py-3 text-right text-emerald-600 font-bold">
                                <?= $k['debit'] > 0 ? number_format($k['debit'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="border border-slate-900 px-4 py-3 text-right text-red-600 font-bold">
                                <?= $k['kredit'] > 0 ? number_format($k['kredit'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="border border-slate-900 px-4 py-3 text-right font-black text-slate-900">
                                <?= number_format($saldo_v, 0, ',', '.') ?>
                            </td>
                            
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-slate-100 text-slate-800 font-black text-xs uppercase tracking-widest">
                    <tr>
                        <td colspan="4" class="border border-slate-900 px-4 py-4 text-right">TOTAL MUTASI & SALDO AKHIR</td>
                        <td class="border border-slate-900 px-4 py-4 text-right text-emerald-600"><?= number_format($total_debit, 0, ',', '.') ?></td>
                        <td class="border border-slate-900 px-4 py-4 text-right text-red-600"><?= number_format($total_kredit, 0, ',', '.') ?></td>
                        <td class="border border-slate-900 px-4 py-4 text-right text-slate-900 text-sm">Rp <?= number_format($total_saldo_akhir_gabungan, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-16 grid grid-cols-2 text-center text-xs text-slate-900">
            <div class="space-y-24">
                <div>
                    <p class="font-bold">Mengetahui,</p>
                    <p class="font-black uppercase italic mt-1">Kepala Sekolah</p>
                </div>
                <div>
                    <p class="font-black underline">( ___________________________ )</p>
                    <p class="text-[10px] mt-1 uppercase opacity-50">Tanda tangan & Stempel</p>
                </div>
            </div>
            <div class="space-y-24">
                <div>
                    <p class="font-bold">Disusun Oleh,</p>
                    <p class="font-black uppercase italic mt-1">Pengelola Bank Sampah</p>
                </div>
                <div>
                    <p class="font-black underline">( <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?> )</p>
                    <p class="text-[10px] mt-1 opacity-50 italic uppercase">Dicetak pada: <?= date('d/m/Y H:i') ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        /* Sembunyikan elemen navigasi utama dari layout admin */
        aside, header, nav, footer, .no-print, [x-data] button { 
            display: none !important; 
        }

        /* Reset paksa layout body agar full kertas */
        body, html {
            background-color: white !important;
            height: auto !important;
            overflow: visible !important;
            margin: 0 !important;
            padding: 0 !important;
            color: black !important;
        }

        /* Membebaskan kontainer utama */
        main, .flex-1, .flex.h-screen {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            height: auto !important;
            display: block !important;
            overflow: visible !important;
        }

        /* Pengaturan Kertas A4 */
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        /* Garis tabel cetak */
        table, th, td {
            border: 1pt solid black !important;
            border-collapse: collapse !important;
        }
        
        .break-inside-avoid {
            break-inside: avoid;
        }

        #print-area {
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }
    }
</style>