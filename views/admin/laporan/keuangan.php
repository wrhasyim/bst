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

    <div class="no-print bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/laporan/keuangan" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Filter Rentang Waktu (Kosongkan Untuk Semua Waktu)</label>
                <div class="flex gap-4 items-center">
                    <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    <span class="text-slate-400 font-bold">S/D</span>
                    <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto px-10 py-4 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg">
                Terapkan Filter
            </button>
            <?php if(!empty($_GET['start_date'])): ?>
                <a href="<?= BASE_URL ?>/laporan/keuangan" class="w-full md:w-auto px-6 py-4 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 text-center transition-all">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="no-print p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3">✅</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="no-print p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3">⚠️</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div id="print-area" class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:shadow-none print:border-none print:p-0">
        
        <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-10">
            <h1 class="text-2xl font-black uppercase tracking-[0.2em] text-slate-900">LAPORAN LABA/RUGI & DISTRIBUSI</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Manajemen Bank Sampah TKM (BST SYSTEM)</p>
            <div class="inline-block px-4 py-1.5 bg-slate-100 text-slate-800 border border-slate-200 text-[10px] font-black uppercase tracking-widest mt-4 rounded-lg">
                <?php 
                $periode_teks = (!empty($_GET['start_date']) && !empty($_GET['end_date'])) 
                    ? date('d M Y', strtotime($_GET['start_date'])) . ' s/d ' . date('d M Y', strtotime($_GET['end_date'])) 
                    : 'Semua Waktu (All Time)'; 
                ?>
                Periode Data: <?= $periode_teks ?> | Dicetak: <?= date('d F Y') ?>
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
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-200 pb-4 mb-6 mt-10">Alokasi Distribusi Margin</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="p-6 bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col justify-center">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Kas Bank Sampah (<?= $laporan['persen_bst'] ?>%)</h4>
                            <p class="text-xs text-slate-400 font-medium italic mt-1">Untuk operasional & pemeliharaan</p>
                        </div>
                        <div class="text-xl font-black text-slate-800">Rp <?= number_format($laporan['kas_bst'], 0, ',', '.') ?></div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col justify-between group transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Sumbangan Kas Sekolah (<?= $laporan['persen_sekolah'] ?>%)</h4>
                            <p class="text-xs text-slate-400 font-medium italic mt-1">Pendapatan institusi sekolah</p>
                        </div>
                        <div class="text-xl font-black text-slate-800">Rp <?= number_format($laporan['kas_sekolah'], 0, ',', '.') ?></div>
                    </div>
                    <?php $cek_sisa_sekolah = round($laporan['sisa_sekolah']); ?>
                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-emerald-500">Telah Disetor: Rp <?= number_format($laporan['cair_sekolah'], 0, ',', '.') ?></div>
                        
                        <?php if($cek_sisa_sekolah < 0): ?>
                            <div class="text-[10px] font-black text-orange-500">Lebih Bayar: Rp <?= number_format(abs($cek_sisa_sekolah), 0, ',', '.') ?></div>
                        <?php elseif($cek_sisa_sekolah == 0): ?>
                            <div class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md">LUNAS</div>
                        <?php else: ?>
                            <div class="text-[10px] font-black text-red-500">Sisa: Rp <?= number_format($cek_sisa_sekolah, 0, ',', '.') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($cek_sisa_sekolah > 0): ?>
                    <form action="<?= BASE_URL ?>/laporan/cairkan_kas_sekolah" method="POST" class="mt-4 hidden group-hover:block transition-all no-print">
                        <?= Security::csrf_field(); ?>
                        <input type="hidden" name="nominal" value="<?= $cek_sisa_sekolah ?>">
                        <button type="submit" onclick="return confirm('Tandai lunas sisa Rp <?= number_format($cek_sisa_sekolah,0,',','.') ?> ke Sekolah?')" class="w-full py-2 bg-slate-900 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-black transition-all">Tandai Telah Disetor (Lunas)</button>
                    </form>
                    <?php elseif($cek_sisa_sekolah < 0): ?>
                    <form action="<?= BASE_URL ?>/laporan/refund_lebih_bayar" method="POST" class="mt-4 hidden group-hover:block transition-all no-print">
                        <?= Security::csrf_field(); ?>
                        <input type="hidden" name="nominal" value="<?= abs($cek_sisa_sekolah) ?>">
                        <input type="hidden" name="jenis_refund" value="sekolah">
                        <button type="submit" onclick="return confirm('Tarik kembali kelebihan Rp <?= number_format(abs($cek_sisa_sekolah),0,',','.') ?> ke Kas Utama?')" class="w-full py-2 bg-orange-500 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-orange-600 transition-all">Kembalikan Ke Kas Utama</button>
                    </form>
                    <?php endif; ?>
                </div>

                <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl shadow-sm flex flex-col justify-between group transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Honor Pengelola (<?= $laporan['persen_pengelola'] ?>%)</h4>
                            <p class="text-xs text-blue-400 font-medium italic mt-1">Jatah untuk staf dan pengurus</p>
                        </div>
                        <div class="text-xl font-black text-blue-800">Rp <?= number_format($laporan['honor_pengelola'], 0, ',', '.') ?></div>
                    </div>
                    <?php $cek_sisa_pengelola = round($laporan['sisa_pengelola']); ?>
                    <div class="pt-3 border-t border-blue-200/50 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-emerald-600">Telah Cair: Rp <?= number_format($laporan['cair_pengelola'], 0, ',', '.') ?></div>
                        
                        <?php if($cek_sisa_pengelola < 0): ?>
                            <div class="text-[10px] font-black text-orange-500">Lebih Bayar: Rp <?= number_format(abs($cek_sisa_pengelola), 0, ',', '.') ?></div>
                        <?php elseif($cek_sisa_pengelola == 0): ?>
                            <div class="text-[10px] font-black text-emerald-600 bg-emerald-100 px-2 py-1 rounded-md">LUNAS</div>
                        <?php else: ?>
                            <div class="text-[10px] font-black text-red-500">Sisa: Rp <?= number_format($cek_sisa_pengelola, 0, ',', '.') ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if($cek_sisa_pengelola > 0): ?>
                    <form action="<?= BASE_URL ?>/laporan/cairkan_honor_pengelola" method="POST" class="mt-4 hidden group-hover:block transition-all no-print">
                        <?= Security::csrf_field(); ?>
                        <input type="hidden" name="nominal" value="<?= $cek_sisa_pengelola ?>">
                        <button type="submit" onclick="return confirm('Cairkan Honor Pengelola sebesar Rp <?= number_format($cek_sisa_pengelola,0,',','.') ?>? Uang akan tercatat atas nama Anda.')" class="w-full py-2 bg-blue-600 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-blue-700 transition-all">Cairkan Honor Pengelola</button>
                    </form>
                    <?php elseif($cek_sisa_pengelola < 0): ?>
                    <form action="<?= BASE_URL ?>/laporan/refund_lebih_bayar" method="POST" class="mt-4 hidden group-hover:block transition-all no-print">
                        <?= Security::csrf_field(); ?>
                        <input type="hidden" name="nominal" value="<?= abs($cek_sisa_pengelola) ?>">
                        <input type="hidden" name="jenis_refund" value="pengelola">
                        <button type="submit" onclick="return confirm('Tarik kembali kelebihan Rp <?= number_format(abs($cek_sisa_pengelola),0,',','.') ?> ke Kas Utama?')" class="w-full py-2 bg-orange-500 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-orange-600 transition-all">Kembalikan Ke Kas Utama</button>
                    </form>
                    <?php endif; ?>
                </div>

                <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Honor Wali Kelas (<?= $laporan['persen_wali'] ?>%)</h4>
                            <p class="text-xs text-blue-400 font-medium italic mt-1">Insentif per volume setoran</p>
                        </div>
                        <div class="text-xl font-black text-blue-800">Rp <?= number_format($laporan['honor_walikelas'], 0, ',', '.') ?></div>
                    </div>
                    <?php $cek_sisa_wali = round($laporan['sisa_wali']); ?>
                    <div class="pt-3 border-t border-blue-200/50 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-emerald-600">Telah Cair: Rp <?= number_format($laporan['cair_wali'], 0, ',', '.') ?></div>
                        
                        <?php if($cek_sisa_wali < 0): ?>
                            <div class="text-[10px] font-black text-orange-500">Lebih Bayar: Rp <?= number_format(abs($cek_sisa_wali), 0, ',', '.') ?></div>
                        <?php elseif($cek_sisa_wali == 0): ?>
                            <div class="text-[10px] font-black text-emerald-600 bg-emerald-100 px-2 py-1 rounded-md">LUNAS</div>
                        <?php else: ?>
                            <div class="text-[10px] font-black text-red-500">Sisa: Rp <?= number_format($cek_sisa_wali, 0, ',', '.') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-6 bg-amber-50/50 border border-amber-200 rounded-2xl shadow-sm flex flex-col justify-between group transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Honor Siswa Piket (<?= $laporan['persen_piket'] ?>%)</h4>
                            <p class="text-xs text-amber-500 font-medium italic mt-1">Insentif tenaga operasional siswa</p>
                        </div>
                        <div class="text-xl font-black text-amber-800">Rp <?= number_format($laporan['honor_piket'], 0, ',', '.') ?></div>
                    </div>
                    <?php $cek_sisa_piket = round($laporan['sisa_piket']); ?>
                    <div class="pt-3 border-t border-amber-200 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-emerald-600">Telah Cair: Rp <?= number_format($laporan['cair_piket'], 0, ',', '.') ?></div>
                        
                        <?php if($cek_sisa_piket < 0): ?>
                            <div class="text-[10px] font-black text-orange-500">Lebih Bayar: Rp <?= number_format(abs($cek_sisa_piket), 0, ',', '.') ?></div>
                        <?php elseif($cek_sisa_piket == 0): ?>
                            <div class="text-[10px] font-black text-emerald-600 bg-emerald-100 px-2 py-1 rounded-md">LUNAS</div>
                        <?php else: ?>
                            <div class="text-[10px] font-black text-red-500">Sisa: Rp <?= number_format($cek_sisa_piket, 0, ',', '.') ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if($cek_sisa_piket > 0): ?>
                    <form action="<?= BASE_URL ?>/laporan/cairkan_honor_piket" method="POST" class="mt-4 hidden group-hover:block transition-all no-print">
                        <?= Security::csrf_field(); ?>
                        <input type="hidden" name="nominal" value="<?= $cek_sisa_piket ?>">
                        <button type="submit" onclick="return confirm('Cairkan Honor Piket sebesar Rp <?= number_format($cek_sisa_piket,0,',','.') ?>?')" class="w-full py-2 bg-amber-500 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-amber-600 transition-all">Cairkan Honor Piket</button>
                    </form>
                    <?php elseif($cek_sisa_piket < 0): ?>
                    <form action="<?= BASE_URL ?>/laporan/refund_lebih_bayar" method="POST" class="mt-4 hidden group-hover:block transition-all no-print">
                        <?= Security::csrf_field(); ?>
                        <input type="hidden" name="nominal" value="<?= abs($cek_sisa_piket) ?>">
                        <input type="hidden" name="jenis_refund" value="piket">
                        <button type="submit" onclick="return confirm('Tarik kembali kelebihan Rp <?= number_format(abs($cek_sisa_piket),0,',','.') ?> ke Kas Utama?')" class="w-full py-2 bg-orange-500 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-orange-600 transition-all">Kembalikan Ke Kas Utama</button>
                    </form>
                    <?php endif; ?>
                </div>

            </div>

            <div class="p-6 bg-blue-50 border border-blue-200 rounded-3xl mt-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-black text-blue-800 uppercase tracking-widest">KAS TUTUP BOTOL</h3>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mt-1">Laporan Pemasukan & Pengeluaran Khusus Tutup Botol</p>
                    </div>
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-2xl">
                        🧊
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-blue-600">Total Masuk (Otomatis & Manual)</span>
                        <span class="font-black text-blue-800">Rp <?= number_format($tutup_botol_in ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-red-500">Total Keluar / Cair (Bonus)</span>
                        <span class="font-black text-red-600">Rp <?= number_format($tutup_botol_out ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="pt-3 border-t border-blue-200 flex justify-between items-center">
                        <span class="font-black text-blue-900 uppercase text-xs">SISA KAS TUTUP BOTOL SAAT INI</span>
                        <span class="font-black text-blue-700 text-lg">Rp <?= number_format($sisa_tutup_botol ?? 0, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-4 flex items-center no-print">
                <span class="text-lg mr-2">💡</span> Info: Pencairan & Refund uang untuk Wali Kelas dikelola melalui menu "Pencairan Honor" di sidebar.
            </p>
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
                            <td class="border border-slate-900 px-4 py-3 text-center font-bold"><?= number_format($h['total_pcs'], 0) ?> Pcs</td>
                            <td class="border border-slate-900 px-4 py-3 text-right">
                                <div class="font-black text-emerald-700">Rp <?= number_format($h['total_pendapatan'], 0, ',', '.') ?></div>
                                <!-- ✨ Penambahan Indikator Kas Tutup Botol -->
                                <?php if(isset($h['kas_tutup_botol_rp']) && $h['kas_tutup_botol_rp'] > 0): ?>
                                    <div class="text-[9px] font-bold text-blue-600 uppercase mt-1">+ TB: Rp <?= number_format($h['kas_tutup_botol_rp'], 0, ',', '.') ?></div>
                                <?php endif; ?>
                            </td>
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
                    <p class="font-black underline">( <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?> )</p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
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
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
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