<div class="max-w-7xl mx-auto space-y-8 pb-12">
    
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">LAPORAN<span class="text-emerald-500">KEUANGAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Laporan Laba/Rugi & Distribusi Margin Global</p>
        </div>
        <button onclick="window.print()" class="px-8 py-3.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center transform active:scale-95">
            <span class="mr-2">🖨️</span> Cetak Laporan Keuangan
        </button>
    </div>

    <div id="print-area" class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:shadow-none print:border-none print:p-0">
        
        <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-10">
            <h1 class="text-2xl font-black uppercase tracking-[0.2em] text-slate-900">LAPORAN LABA/RUGI & DISTRIBUSI</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Manajemen Bank Sampah TKM (BST SYSTEM)</p>
            <div class="inline-block px-4 py-1.5 bg-slate-100 text-slate-800 border border-slate-200 text-[10px] font-black uppercase tracking-widest mt-4 rounded-lg">
                Tanggal Unduh: <?= date('d F Y') ?>
            </div>
        </div>

        <div class="space-y-6">
            <div class="p-6 bg-emerald-50 border border-emerald-200 rounded-3xl flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-black text-emerald-800 uppercase tracking-widest">Pendapatan Kotor (Gross Revenue)</h3>
                    <p class="text-[10px] text-emerald-600 mt-1 italic">Total seluruh uang tunai yang diterima dari Pengepul atas penjualan sampah.</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-black text-emerald-700">Rp <?= number_format($laporan['total_kotor'], 0, ',', '.') ?></p>
                </div>
            </div>

            <div class="p-6 bg-red-50 border border-red-200 rounded-3xl flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-black text-red-800 uppercase tracking-widest">Beban Tabungan Nasabah (HPP)</h3>
                    <p class="text-[10px] text-red-600 mt-1 italic">Kewajiban bayar ke siswa/guru sesuai harga dasar (Harga Beli Bank Sampah).</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-black text-red-600">- Rp <?= number_format($laporan['beban_nasabah'], 0, ',', '.') ?></p>
                </div>
            </div>

            <div class="p-6 bg-slate-900 rounded-3xl flex justify-between items-center shadow-lg">
                <div>
                    <h3 class="text-sm font-black text-emerald-400 uppercase tracking-widest">Laba / Margin Bersih Operasional</h3>
                    <p class="text-[10px] text-slate-400 mt-1 italic">Keuntungan murni (Pendapatan Kotor dikurangi Beban Nasabah) yang siap didistribusikan.</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-white">Rp <?= number_format($laporan['margin_total'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <div class="border-b-2 border-slate-900 pb-3 mb-6">
                <h3 class="font-black text-slate-800 text-sm uppercase tracking-widest">Alokasi Distribusi Margin</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-5 border border-slate-200 rounded-2xl bg-slate-50 flex justify-between items-center">
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Kas Bank Sampah (<?= $config['persen_kas_bst'] ?? 0 ?>%)</p>
                        <p class="text-xs font-bold text-slate-400 italic">Untuk operasional & pemeliharaan</p>
                    </div>
                    <p class="text-lg font-black text-slate-800">Rp <?= number_format($laporan['kas_bst'], 0, ',', '.') ?></p>
                </div>
                
                <div class="p-5 border border-slate-200 rounded-2xl bg-slate-50 flex justify-between items-center">
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Sumbangan Kas Sekolah (<?= $config['persen_kas_sekolah'] ?? 0 ?>%)</p>
                        <p class="text-xs font-bold text-slate-400 italic">Pendapatan untuk institusi sekolah</p>
                    </div>
                    <p class="text-lg font-black text-slate-800">Rp <?= number_format($laporan['kas_sekolah'], 0, ',', '.') ?></p>
                </div>

                <div class="p-5 border border-slate-200 rounded-2xl bg-blue-50 flex justify-between items-center">
                    <div>
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Honor Pengelola (<?= $config['persen_honor_pengelola'] ?? 0 ?>%)</p>
                        <p class="text-xs font-bold text-blue-400 italic">Jatah untuk staf dan pengurus</p>
                    </div>
                    <p class="text-lg font-black text-blue-800">Rp <?= number_format($laporan['honor_pengelola'], 0, ',', '.') ?></p>
                </div>

                <div class="p-5 border border-slate-200 rounded-2xl bg-blue-50 flex justify-between items-center">
                    <div>
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Honor Wali Kelas (<?= $config['persen_honor_walikelas'] ?? 0 ?>%)</p>
                        <p class="text-xs font-bold text-blue-400 italic">Insentif wali kelas per volume setoran</p>
                    </div>
                    <p class="text-lg font-black text-blue-800">Rp <?= number_format($laporan['honor_walikelas'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="mt-12 break-inside-avoid">
            <div class="border-b-2 border-slate-900 pb-3 mb-6">
                <h3 class="font-black text-slate-800 text-sm uppercase tracking-widest">Lampiran: Histori Penjualan Terakhir</h3>
            </div>
            <table class="w-full text-left border-collapse border border-slate-900">
                <thead class="bg-slate-100">
                    <tr class="text-[10px] font-black text-slate-900 uppercase tracking-widest border-b border-slate-900">
                        <th class="border border-slate-900 px-4 py-3 text-center w-12">No</th>
                        <th class="border border-slate-900 px-4 py-3 text-center">Tanggal</th>
                        <th class="border border-slate-900 px-4 py-3">Kategori Sampah</th>
                        <th class="border border-slate-900 px-4 py-3 text-center">Volume (Pcs)</th>
                        <th class="border border-slate-900 px-4 py-3 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <?php if(empty($history)): ?>
                        <tr><td colspan="5" class="border border-slate-900 px-4 py-8 text-center text-slate-500 italic">Belum ada riwayat penjualan ke pengepul.</td></tr>
                    <?php else: ?>
                        <?php foreach($history as $i => $h): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-900 px-4 py-3 text-center font-bold text-slate-700"><?= $i+1 ?></td>
                            <td class="border border-slate-900 px-4 py-3 text-center font-bold text-slate-600"><?= date('d/m/Y', strtotime($h['tanggal_jual'])) ?></td>
                            <td class="border border-slate-900 px-4 py-3 font-semibold uppercase"><?= htmlspecialchars($h['nama_sampah']) ?></td>
                            <td class="border border-slate-900 px-4 py-3 text-center font-bold"><?= number_format($h['total_berat'], 0) ?> Pcs</td>
                            <td class="border border-slate-900 px-4 py-3 text-right font-black text-emerald-700">Rp <?= number_format($h['total_pendapatan'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-16 grid grid-cols-2 text-center text-xs text-slate-900 break-inside-avoid">
            <div class="space-y-24">
                <div>
                    <p class="font-bold">Mengetahui,</p>
                    <p class="font-black uppercase italic mt-1">Kepala Sekolah / Direktur</p>
                </div>
                <div>
                    <p class="font-black underline">( ___________________________ )</p>
                </div>
            </div>
            <div class="space-y-24">
                <div>
                    <p class="font-bold">Disusun Oleh,</p>
                    <p class="font-black uppercase italic mt-1">Bendahara Utama</p>
                </div>
                <div>
                    <p class="font-black underline">( <?= htmlspecialchars($_SESSION['nama']) ?> )</p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        /* Sembunyikan elemen web yang tidak perlu */
        aside, header, nav, footer, .no-print, [x-data] button { 
            display: none !important; 
        }

        /* Paksa body untuk mereset scroll dan tinggi */
        body, html {
            background-color: white !important;
            height: auto !important;
            overflow: visible !important;
            margin: 0 !important;
            padding: 0 !important;
            color: black !important;
        }

        /* Hancurkan container flex-h-screen dari layout admin */
        main, .flex-1, .flex.h-screen {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            height: auto !important;
            display: block !important;
            overflow: visible !important;
        }

        /* Pengaturan Kertas */
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        /* Paksa border hitam pada tabel */
        table, th, td {
            border: 1pt solid black !important;
            border-collapse: collapse !important;
        }

        /* Mencegah elemen terpotong aneh di antara halaman */
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