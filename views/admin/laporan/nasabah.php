<!-- views/admin/laporan/nasabah.php -->
<div class="max-w-7xl mx-auto space-y-6 pb-12 print:p-0 print:m-0 print:space-y-0">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 no-print mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">BUKU<span class="text-emerald-500">TABUNGAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Cetak Mutasi Transaksi Nasabah</p>
        </div>
        
        <?php if(isset($_GET['user_id']) || isset($_GET['kelas_id'])): ?>
        <button onclick="window.print()" class="px-5 py-3 bg-emerald-500 text-white text-xs font-black uppercase rounded-xl hover:bg-emerald-600 transition-colors flex items-center gap-2 shadow-lg shadow-emerald-500/30">
            <span class="text-lg">🖨️</span> Cetak Laporan
        </button>
        <?php endif; ?>
    </div>

    <!-- TRIPLE FILTER SECTION (WEB ONLY) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 no-print mb-8">
        
        <!-- Filter 1: Kolektif Kelas -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 text-7xl group-hover:scale-110 transition-transform">🏫</div>
            <h3 class="font-black text-slate-800 uppercase italic mb-1 text-sm">Cetak Rekap Kelas</h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-4">Pencairan akhir semester</p>
            
            <form action="" method="GET" class="flex gap-2 relative z-10">
                <select name="kelas_id" required class="flex-1 min-w-0 w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-emerald-500 focus:bg-white transition-colors truncate">
                    <option value="" disabled selected>-- Pilih Kelas --</option>
                    <?php foreach($kelas_list as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="shrink-0 px-4 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <!-- Filter 2: Mutasi Individu Siswa -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 text-7xl group-hover:scale-110 transition-transform">👤</div>
            <h3 class="font-black text-slate-800 uppercase italic mb-1 text-sm">Mutasi Siswa</h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-4">Buku tabungan individu siswa</p>
            
            <form action="" method="GET" class="flex gap-2 relative z-10">
                <select name="user_id" required class="flex-1 min-w-0 w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-emerald-500 focus:bg-white transition-colors truncate">
                    <option value="" disabled selected>-- Cari Nama Siswa --</option>
                    <?php foreach($siswa_list as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $s['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nama']) ?> <?= $s['nama_kelas'] ? ' ('.$s['nama_kelas'].')' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="shrink-0 px-4 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-colors">
                    Cari
                </button>
            </form>
        </div>
        
        <!-- Filter 3: Mutasi Guru/Staf -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 text-7xl group-hover:scale-110 transition-transform">💼</div>
            <h3 class="font-black text-slate-800 uppercase italic mb-1 text-sm">Mutasi Guru & Staf</h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-4">Buku tabungan internal staf</p>
            
            <form action="" method="GET" class="flex gap-2 relative z-10">
                <select name="user_id" required class="flex-1 min-w-0 w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-emerald-500 focus:bg-white transition-colors truncate">
                    <option value="" disabled selected>-- Cari Nama Guru --</option>
                    <?php foreach($guru_list as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $g['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="shrink-0 px-4 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-colors">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <!-- AREA KOSONG -->
    <?php if(!isset($_GET['user_id']) && !isset($_GET['kelas_id'])): ?>
        <div class="bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm text-center no-print">
            <div class="text-6xl mb-4 opacity-50">📖</div>
            <h3 class="font-black text-slate-800 uppercase italic">Pilih Filter Laporan</h3>
            <p class="text-xs text-slate-500 mt-2 font-medium">Gunakan kotak pencarian di atas untuk melihat Mutasi Individu atau Rekap Kelas.</p>
        </div>
    <?php endif; ?>

    <!-- AREA CETAK (PRINT AREA) -->
    <?php if(isset($_GET['user_id']) || isset($_GET['kelas_id'])): ?>
    <div id="printArea" class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-200 shadow-sm print:shadow-none print:border-none print:p-0">
        
        <?php if(isset($_GET['kelas_id']) && $detail_kelas): ?>
        <!-- ======================================================= -->
        <!-- TAMPILAN 1: REKAP KOLEKTIF KELAS                        -->
        <!-- ======================================================= -->
            <div class="hidden print:block text-center border-b-2 border-slate-800 pb-4 mb-6">
                <h1 class="text-2xl font-black uppercase tracking-widest">REKAPITULASI TABUNGAN KELAS</h1>
                <h2 class="text-lg font-bold uppercase tracking-widest text-slate-700">BANK SAMPAH TKM</h2>
            </div>

            <div class="flex justify-between items-end mb-8 print:mb-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Daftar Nasabah Kelas</p>
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight"><?= htmlspecialchars($detail_kelas['nama_kelas']) ?></h3>
                    <p class="text-xs font-bold text-slate-500 uppercase mt-1">WALI KELAS: <span class="text-slate-700"><?= htmlspecialchars($detail_kelas['nama_wali'] ?? '-') ?></span></p>
                </div>
            </div>

            <div class="overflow-x-auto print:overflow-visible">
                <table class="w-full text-left border-collapse border border-slate-800 print-table">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] uppercase font-black text-slate-700 tracking-widest border-b border-slate-800">
                            <th class="px-4 py-4 border border-slate-800 text-center w-12">NO</th>
                            <th class="px-4 py-4 border border-slate-800">NAMA NASABAH (SISWA)</th>
                            <th class="px-4 py-4 border border-slate-800 text-right">TOTAL SETOR (RP)</th>
                            <th class="px-4 py-4 border border-slate-800 text-right">TOTAL TARIK (RP)</th>
                            <th class="px-4 py-4 border border-slate-800 text-right">SISA SALDO (RP)</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-slate-800">
                        <?php 
                        $no = 1; $sum_masuk = 0; $sum_keluar = 0; $sum_saldo = 0;
                        if(empty($rekap_kelas)): 
                        ?>
                            <tr><td colspan="5" class="px-4 py-8 text-center font-bold italic border border-slate-800">Belum ada nasabah terdaftar di kelas ini.</td></tr>
                        <?php else: ?>
                            <?php foreach($rekap_kelas as $rk): 
                                $saldo_siswa = $rk['total_masuk'] - $rk['total_keluar'];
                                $sum_masuk += $rk['total_masuk'];
                                $sum_keluar += $rk['total_keluar'];
                                $sum_saldo += $saldo_siswa;
                            ?>
                            <tr class="border-b border-slate-800 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 border border-slate-800 text-center"><?= $no++ ?></td>
                                <td class="px-4 py-3 border border-slate-800 font-bold uppercase"><?= htmlspecialchars($rk['nama']) ?></td>
                                <td class="px-4 py-3 border border-slate-800 text-right text-emerald-600 font-bold"><?= number_format($rk['total_masuk'], 0, ',', '.') ?></td>
                                <td class="px-4 py-3 border border-slate-800 text-right text-red-600 font-bold"><?= number_format($rk['total_keluar'], 0, ',', '.') ?></td>
                                <td class="px-4 py-3 border border-slate-800 text-right font-black text-slate-900 bg-slate-50/50"><?= number_format($saldo_siswa, 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- BARIS TOTAL -->
                        <tr class="bg-slate-900 text-white font-black border-b border-slate-800">
                            <td colspan="2" class="px-4 py-4 border border-slate-800 text-right uppercase tracking-widest text-[10px]">TOTAL KESELURUHAN</td>
                            <td class="px-4 py-4 border border-slate-800 text-right text-emerald-400"><?= number_format($sum_masuk, 0, ',', '.') ?></td>
                            <td class="px-4 py-4 border border-slate-800 text-right text-red-400"><?= number_format($sum_keluar, 0, ',', '.') ?></td>
                            <td class="px-4 py-4 border border-slate-800 text-right text-base text-white">Rp <?= number_format($sum_saldo, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- TTD REKAP KELAS -->
            <div class="hidden print:flex justify-between px-10 mt-16 text-center text-xs font-bold text-slate-800">
                <div>
                    <p class="mb-1">Mengetahui,</p>
                    <p class="font-black uppercase mb-20">WALI KELAS</p>
                    <p class="border-b border-slate-800 pb-1 w-56 mx-auto uppercase"><?= htmlspecialchars($detail_kelas['nama_wali'] ?? '...........................') ?></p>
                </div>
                <div>
                    <p class="mb-1">Disusun Oleh,</p>
                    <p class="font-black uppercase mb-20">PENGELOLA BANK SAMPAH</p>
                    <p class="border-b border-slate-800 pb-1 w-56 mx-auto uppercase"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                </div>
            </div>

        <?php elseif(isset($_GET['user_id']) && $detail_siswa): ?>
        <!-- ======================================================= -->
        <!-- TAMPILAN 2: MUTASI INDIVIDU NASABAH / GURU              -->
        <!-- ======================================================= -->
            <div class="hidden print:block text-center border-b-2 border-slate-800 pb-4 mb-6">
                <h1 class="text-2xl font-black uppercase tracking-widest">BUKU TABUNGAN NASABAH</h1>
                <h2 class="text-lg font-bold uppercase tracking-widest text-slate-700">BANK SAMPAH TKM</h2>
            </div>

            <div class="flex justify-between items-end mb-8 print:mb-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Informasi Pemilik Rekening</p>
                    <h3 class="text-xl font-black text-slate-800 uppercase"><?= htmlspecialchars($detail_siswa['nama']) ?></h3>
                    <!-- Label Status / Kelas yang Dinamis -->
                    <p class="text-sm font-bold text-slate-500 uppercase mt-0.5">STATUS / KELAS: <span class="text-slate-700"><?= htmlspecialchars($detail_siswa['nama_kelas'] ?? 'STAF / GURU') ?></span></p>
                </div>
                <div class="text-right bg-slate-50 print:bg-transparent p-4 print:p-0 rounded-2xl border border-slate-100 print:border-none">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Saldo Akhir</p>
                    <h3 class="text-3xl font-black text-emerald-500">Rp <?= number_format($total_saldo, 0, ',', '.') ?></h3>
                </div>
            </div>

            <div class="overflow-x-auto print:overflow-visible">
                <table class="w-full text-left border-collapse border border-slate-800 print-table">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] uppercase font-black text-slate-700 tracking-widest border-b border-slate-800">
                            <th class="px-4 py-4 border border-slate-800 text-center w-12">NO</th>
                            <th class="px-4 py-4 border border-slate-800 text-center whitespace-nowrap">TANGGAL</th>
                            <th class="px-4 py-4 border border-slate-800">KETERANGAN TRANSAKSI</th>
                            <th class="px-4 py-4 border border-slate-800 text-right whitespace-nowrap">MASUK (DEBIT)</th>
                            <th class="px-4 py-4 border border-slate-800 text-right whitespace-nowrap">KELUAR (KREDIT)</th>
                            <th class="px-4 py-4 border border-slate-800 text-right whitespace-nowrap">SALDO BERJALAN</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-slate-800">
                        <?php 
                        $saldo_berjalan = 0; // 🛠️ Mulai akumulasi dari 0
                        $no = 1;
                        if(empty($mutasi)): 
                        ?>
                            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400 font-bold italic border border-slate-800">Nasabah ini belum memiliki riwayat transaksi.</td></tr>
                        <?php else: ?>
                            <?php foreach($mutasi as $m): ?>
                            <?php 
                                // 🛠️ Menghitung saldo berjalan ke depan
                                if($m['tipe'] == 'setoran') { 
                                    $saldo_berjalan += $m['jumlah']; 
                                } else { 
                                    $saldo_berjalan -= $m['jumlah']; 
                                }
                            ?>
                            <tr class="border-b border-slate-800 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 border border-slate-800 text-center"><?= $no++ ?></td>
                                <td class="px-4 py-3 border border-slate-800 text-center font-bold text-slate-600 whitespace-nowrap">
                                    <?= date('d/m/Y', strtotime($m['tanggal'])) ?><br>
                                    <span class="text-[9px] opacity-70"><?= date('H:i', strtotime($m['tanggal'])) ?></span>
                                </td>
                                <td class="px-4 py-3 border border-slate-800">
                                    <p class="font-black uppercase <?= $m['tipe'] == 'setoran' ? 'text-emerald-700' : 'text-red-600' ?>">
                                        <?= $m['tipe'] == 'setoran' ? 'SETORAN SAMPAH' : 'PENARIKAN TUNAI' ?>
                                    </p>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase mt-0.5 italic">
                                        <?= htmlspecialchars($m['ket']) ?> <?= $m['tipe'] == 'setoran' ? '('.$m['qty'].' Pcs)' : '' ?>
                                    </p>
                                </td>
                                <td class="px-4 py-3 border border-slate-800 text-right font-bold text-emerald-600">
                                    <?= $m['tipe'] == 'setoran' ? '+ ' . number_format($m['jumlah'], 0, ',', '.') : '<span class="text-slate-300">-</span>' ?>
                                </td>
                                <td class="px-4 py-3 border border-slate-800 text-right font-bold text-red-600">
                                    <?= $m['tipe'] == 'penarikan' ? '- ' . number_format($m['jumlah'], 0, ',', '.') : '<span class="text-slate-300">-</span>' ?>
                                </td>
                                <td class="px-4 py-3 border border-slate-800 text-right font-black text-slate-900 bg-slate-50/50">
                                    Rp <?= number_format($saldo_berjalan, 0, ',', '.') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- TTD MUTASI INDIVIDU -->
            <div class="hidden print:flex justify-between px-10 mt-16 text-center text-xs font-bold text-slate-800">
                <div>
                    <!-- Tanda Tangan Universal -->
                    <p class="mb-1">Nasabah / Pihak Terkait,</p>
                    <p class="font-black uppercase mb-20 text-transparent select-none">Tanda Tangan</p>
                    <p class="border-b border-slate-800 pb-1 w-48 mx-auto uppercase"><?= htmlspecialchars($detail_siswa['nama']) ?></p>
                </div>
                <div>
                    <p class="mb-1">Mengetahui,</p>
                    <p class="font-black uppercase mb-20">PENGELOLA BANK SAMPAH</p>
                    <p class="border-b border-slate-800 pb-1 w-56 mx-auto uppercase"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<style>
@media print {
    @page { size: portrait; margin: 10mm; }
    body * { visibility: hidden; }
    .no-print, aside, header, form, button { display: none !important; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { 
        position: absolute; left: 0; top: 0; width: 100%; border: none; box-shadow: none; padding: 0 !important; background-color: white !important;
    }
    .print\:block { display: block !important; }
    .print\:flex { display: flex !important; }
    .print-table th, .print-table tr.bg-slate-50 { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .print-table tr.bg-slate-50\/50 { background-color: #f8fafc80 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .print-table tr.bg-slate-900 { background-color: #0f172a !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-emerald-600 { color: #059669 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-emerald-400 { color: #34d399 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-emerald-700 { color: #047857 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-red-600 { color: #dc2626 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-red-400 { color: #f87171 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>