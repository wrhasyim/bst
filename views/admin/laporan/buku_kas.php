<div class="max-w-7xl mx-auto space-y-6 pb-12 print:pb-0 print:space-y-0">
    
    <!-- HEADER WEB (DISEMBUNYIKAN SAAT CETAK) -->
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">BUKU KAS<span class="text-emerald-500">UMUM</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Laporan Arus Kas Fisik / Rekening Bank Sampah</p>
        </div>
        <button onclick="window.print()" class="px-8 py-3.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center">
            <span class="mr-2">🖨️</span> Cetak / Tutup Buku
        </button>
    </div>

    <!-- FILTER PENCARIAN (DISEMBUNYIKAN SAAT CETAK) -->
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

    <!-- AREA UTAMA CETAK & WEB -->
    <div id="print-area" class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:shadow-none print:border-none print:p-0 print:rounded-none">
        
        <!-- JUDUL LAPORAN (KOP CETAK) -->
        <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-10 print:pb-3 print:mb-4">
            <h1 class="text-2xl print:text-lg font-black uppercase tracking-[0.2em] text-slate-900 print:text-black">REKAPITULASI BUKU KAS UMUM</h1>
            <p class="text-sm print:text-[10px] font-bold text-slate-500 print:text-black uppercase tracking-widest mt-1">Sistem Manajemen Bank Sampah TKM (BST SYSTEM)</p>
            
            <!-- Badge Web -->
            <div class="no-print inline-block px-4 py-1.5 bg-slate-100 text-slate-800 border border-slate-200 text-[10px] font-black uppercase tracking-widest mt-4 rounded-lg">
                Periode: <?= date('d/m/Y', strtotime($_GET['start_date'] ?? date('Y-m-01'))) ?> s/d <?= date('d/m/Y', strtotime($_GET['end_date'] ?? date('Y-m-t'))) ?>
            </div>
            <!-- Teks Polos Cetak -->
            <p class="hidden print:block text-[10px] font-bold mt-2">PERIODE: <?= date('d/m/Y', strtotime($_GET['start_date'] ?? date('Y-m-01'))) ?> S/D <?= date('d/m/Y', strtotime($_GET['end_date'] ?? date('Y-m-t'))) ?></p>
        </div>

        <?php 
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

        <!-- KOTAK SUMMARY UNTUK WEB (DISEMBUNYIKAN SAAT CETAK) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 print:hidden">
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

        <!-- TABEL SUMMARY KHUSUS CETAK (MUNCUL HANYA SAAT DICETAK) -->
        <div class="hidden print:block mb-4">
            <table class="w-full text-left text-[10px] border-collapse border border-black">
                <tr>
                    <td class="border border-black px-2 py-1 font-bold w-1/4 bg-gray-100">Total Saldo Awal</td>
                    <td class="border border-black px-2 py-1 w-1/4">Rp <?= number_format($total_saldo_awal_gabungan, 0, ',', '.') ?></td>
                    <td class="border border-black px-2 py-1 font-bold w-1/4 bg-gray-100">Total Saldo Fisik (Akhir)</td>
                    <td class="border border-black px-2 py-1 w-1/4 font-black">Rp <?= number_format($total_saldo_akhir_gabungan, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 font-bold">Sisa Kas Besar</td>
                    <td class="border border-black px-2 py-1">Rp <?= number_format($saldo_berjalan_besar, 0, ',', '.') ?></td>
                    <td class="border border-black px-2 py-1 font-bold">Sisa Kas Tutup Botol</td>
                    <td class="border border-black px-2 py-1">Rp <?= number_format($saldo_berjalan_tb, 0, ',', '.') ?></td>
                </tr>
            </table>
        </div>

        <!-- TABEL RINCIAN TRANSAKSI -->
        <div class="overflow-x-auto print:overflow-visible">
            <table class="w-full text-left border-collapse border border-slate-900 print:border-black">
                <thead class="bg-slate-50 print:bg-gray-100 print:text-black">
                    <tr class="text-[10px] print:text-[9px] font-black text-slate-900 print:text-black uppercase tracking-widest border-b border-slate-900 print:border-black">
                        <th class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-1.5 text-center w-8">No</th>
                        <th class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-1.5 text-center">Tanggal</th>
                        <th class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-1.5">Keterangan Transaksi</th>
                        <th class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-1.5 text-center">Sumber Kas</th>
                        <th class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-1.5 text-right">Debit (Rp)</th>
                        <th class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-1.5 text-right">Kredit (Rp)</th>
                        <th class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-1.5 text-right">Total Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-[11px] print:text-[9px] print:text-black">
                    <tr class="bg-slate-50 print:bg-transparent font-bold italic print:font-semibold">
                        <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-center">-</td>
                        <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-center"><?= date('d/m/Y', strtotime($_GET['start_date'] ?? date('Y-m-01'))) ?></td>
                        <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 uppercase">SALDO SEBELUM PERIODE INI</td>
                        <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-center">-</td>
                        <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-right">-</td>
                        <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-right">-</td>
                        <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-right">Rp <?= number_format($total_saldo_awal_gabungan, 0, ',', '.') ?></td>
                    </tr>

                    <?php 
                    $saldo_v = $total_saldo_awal_gabungan;
                    if(empty($buku_kas)): 
                    ?>
                        <tr><td colspan="7" class="border border-slate-900 print:border-black px-4 py-20 text-center text-slate-400 print:text-black italic font-black uppercase tracking-widest">Tidak ada aktivitas transaksi pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($buku_kas as $i => $k): 
                            $saldo_v += $k['debit'];
                            $saldo_v -= $k['kredit'];
                        ?>
                        <tr class="hover:bg-slate-50 print:hover:bg-transparent relative group">
                            <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-center align-top"><?= $i+1 ?></td>
                            <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-center align-top"><?= date('d/m/Y H:i', strtotime($k['waktu'])) ?></td>
                            
                            <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 uppercase font-semibold align-top">
                                <?= htmlspecialchars($k['uraian']) ?>
                                
                                <?php if(strpos($k['detail'], 'Sabtu Ceria') !== false): ?>
                                    <?php 
                                        $parts = explode(' | ', $k['detail']);
                                        $rincian_string = isset($parts[1]) ? $parts[1] : $k['detail'];
                                        $kelas_list = explode(' || ', $rincian_string);
                                    ?>
                                    
                                    <!-- Mode Web -->
                                    <div x-data="{ open: false }" class="mt-1.5 no-print">
                                        <button @click="open = !open" type="button" class="text-[9px] bg-amber-100 text-amber-700 px-2 py-1 rounded shadow-sm border border-amber-200 focus:outline-none flex items-center gap-1 w-max">
                                            <span x-text="open ? 'Tutup Rincian' : 'Lihat Rincian Kelas'"></span>
                                        </button>
                                        <div x-show="open" x-collapse x-cloak class="mt-2 space-y-1">
                                            <?php foreach($kelas_list as $kls):
                                                    $k_parts = explode(' => ', $kls);
                                                    $nama_kls = $k_parts[0] ?? '';
                                                    $detail_kls = $k_parts[1] ?? '';
                                            ?>
                                                <div class="bg-white border border-slate-200 rounded p-1.5 flex flex-col text-[9px] lowercase">
                                                    <span class="font-black text-slate-800 uppercase tracking-tight"><?= htmlspecialchars($nama_kls) ?></span>
                                                    <span class="text-slate-500 italic mt-0.5"><?= htmlspecialchars($detail_kls) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Mode Cetak (Format Teks Padat) -->
                                    <div class="hidden print:block text-[8px] mt-1 font-normal lowercase italic text-black">
                                        <?php foreach($kelas_list as $kls):
                                            $k_parts = explode(' => ', $kls);
                                            $nama_kls = $k_parts[0] ?? '';
                                            $detail_kls = $k_parts[1] ?? '';
                                        ?>
                                            <div class="mb-0.5">- <strong class="uppercase font-bold"><?= htmlspecialchars($nama_kls) ?>:</strong> <?= htmlspecialchars($detail_kls) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                
                                <?php else: ?>
                                    <!-- Transaksi Biasa -->
                                    <span class="block text-[8px] font-normal text-slate-400 print:text-black mt-0.5 lowercase italic"><?= htmlspecialchars($k['detail'] ?: '-') ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-center font-bold align-top">
                                <!-- Versi Web -->
                                <?php if($k['sumber_kas'] === 'kas_tutup_botol'): ?>
                                    <span class="no-print bg-blue-100 text-blue-700 px-2 py-1 rounded text-[9px]">TUTUP BOTOL</span>
                                <?php else: ?>
                                    <span class="no-print bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-[9px]">KAS BESAR</span>
                                <?php endif; ?>
                                
                                <!-- Versi Cetak -->
                                <span class="hidden print:inline text-[9px]">
                                    <?= $k['sumber_kas'] === 'kas_tutup_botol' ? 'TUTUP BOTOL' : 'KAS BESAR' ?>
                                </span>
                            </td>
                            
                            <!-- Hapus warna text-emerald dan text-red saat cetak -->
                            <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-right text-emerald-600 print:text-black font-bold align-top">
                                <?= $k['debit'] > 0 ? number_format($k['debit'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-right text-red-600 print:text-black font-bold align-top">
                                <?= $k['kredit'] > 0 ? number_format($k['kredit'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="border border-slate-900 print:border-black px-4 py-3 print:px-2 print:py-1 text-right font-black text-slate-900 print:text-black align-top">
                                <?= number_format($saldo_v, 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-slate-100 print:bg-gray-100 text-slate-800 print:text-black font-black text-xs print:text-[10px] uppercase tracking-widest">
                    <tr>
                        <td colspan="4" class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-2 text-right">TOTAL MUTASI & SALDO AKHIR</td>
                        <td class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-2 text-right text-emerald-600 print:text-black"><?= number_format($total_debit, 0, ',', '.') ?></td>
                        <td class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-2 text-right text-red-600 print:text-black"><?= number_format($total_kredit, 0, ',', '.') ?></td>
                        <td class="border border-slate-900 print:border-black px-4 py-4 print:px-2 print:py-2 text-right text-slate-900 print:text-black">Rp <?= number_format($total_saldo_akhir_gabungan, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-16 print:mt-6 grid grid-cols-2 text-center text-xs print:text-[10px] text-slate-900 print:text-black">
            <div class="space-y-24 print:space-y-16">
                <div>
                    <p class="font-bold">Mengetahui,</p>
                    <p class="font-black uppercase italic mt-1">Kepala Sekolah</p>
                </div>
                <div>
                    <p class="font-black underline">( ___________________________ )</p>
                    <p class="text-[10px] print:text-[8px] mt-1 uppercase opacity-50 print:opacity-100">Tanda tangan & Stempel</p>
                </div>
            </div>
            <div class="space-y-24 print:space-y-16">
                <div>
                    <p class="font-bold">Disusun Oleh,</p>
                    <p class="font-black uppercase italic mt-1">Pengelola Bank Sampah</p>
                </div>
                <div>
                    <p class="font-black underline">( <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?> )</p>
                    <p class="text-[10px] print:text-[8px] mt-1 opacity-50 print:opacity-100 italic uppercase">Dicetak pada: <?= date('d/m/Y H:i') ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Diperlukan untuk animasi x-collapse Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 1cm; /* Margin yang jauh lebih sempit */
        }
        
        aside, header, nav, footer, .no-print, [x-data] button { 
            display: none !important; 
        }

        body, html {
            background-color: white !important;
            height: auto !important;
            overflow: visible !important;
            margin: 0 !important;
            padding: 0 !important;
            color: black !important;
        }

        main, .flex-1, .flex.h-screen {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            height: auto !important;
            display: block !important;
            overflow: visible !important;
        }

        /* Mengunci warna agar tetap hitam putih/grayscale */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        table, th, td {
            border: 1px solid black !important;
            border-collapse: collapse !important;
        }

        .break-inside-avoid {
            break-inside: avoid;
        }
    }
</style>