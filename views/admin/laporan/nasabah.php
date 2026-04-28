<div class="max-w-7xl mx-auto space-y-8 pb-10">
    
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">BUKU<span class="text-emerald-500">TABUNGAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Laporan Aktivitas Nasabah Individu</p>
        </div>
        <?php if(isset($user_id) && $detail_siswa): ?>
        <button onclick="window.print()" class="px-8 py-3.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center transform active:scale-95">
            <span class="mr-2">🖨️</span> Cetak Buku
        </button>
        <?php endif; ?>
    </div>

    <div class="no-print bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/laporan/nasabah" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Nama Siswa / Nasabah</label>
                <select name="user_id" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">-- Cari Nasabah --</option>
                    <?php foreach($siswa_list as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $s['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nama']) ?> (Kelas <?= $s['nama_kelas'] ?? 'Alumni' ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto px-10 py-4 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg">
                Buka Buku
            </button>
        </form>
    </div>

    <?php if(isset($user_id) && $detail_siswa): ?>
        <div id="print-area" class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:shadow-none print:border-none print:p-0">
            
            <div class="flex justify-between items-start border-b-4 border-double border-slate-900 pb-6 mb-8">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black uppercase tracking-[0.2em] text-slate-900">REKENING KORAN NASABAH</h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistem Bank Sampah TKM (BST SYSTEM)</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Saldo Tersedia</p>
                    <p class="text-2xl font-black text-slate-900">Rp <?= number_format($total_saldo, 0, ',', '.') ?></p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 p-6 bg-slate-50 border border-slate-900">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500 mb-1">Nama Nasabah</p>
                    <p class="text-xs font-black uppercase text-slate-900"><?= htmlspecialchars($detail_siswa['nama']) ?></p>
                </div>
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500 mb-1">Identitas (Username)</p>
                    <p class="text-xs font-black uppercase text-slate-900">@<?= htmlspecialchars($detail_siswa['username']) ?></p>
                </div>
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500 mb-1">Kelas / Angkatan</p>
                    <p class="text-xs font-black uppercase text-slate-900"><?= $detail_siswa['nama_kelas'] ?? '-' ?> / <?= $detail_siswa['angkatan'] ?? '-' ?></p>
                </div>
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500 mb-1">Status Akun</p>
                    <p class="text-xs font-black uppercase text-slate-900"><?= $detail_siswa['is_active'] ? 'AKTIF' : 'NON-AKTIF' ?></p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-slate-900">
                    <thead class="bg-slate-100">
                        <tr class="text-[10px] uppercase font-black text-slate-900 tracking-widest border-b border-slate-900">
                            <th class="border border-slate-900 px-4 py-3 text-center w-12">No</th>
                            <th class="border border-slate-900 px-4 py-3 text-center w-36">Tanggal</th>
                            <th class="border border-slate-900 px-4 py-3 text-center">Uraian / Keterangan Transaksi</th>
                            <th class="border border-slate-900 px-4 py-3 text-center">Volume</th>
                            <th class="border border-slate-900 px-4 py-3 text-right w-40">Mutasi (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        <?php if(empty($mutasi)): ?>
                            <tr>
                                <td colspan="5" class="border border-slate-900 px-4 py-16 text-center text-slate-500 italic font-bold uppercase tracking-widest">
                                    Belum ada catatan mutasi transaksi pada rekening ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($mutasi as $i => $m): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="border border-slate-900 px-4 py-3 text-center font-bold text-sm text-slate-900"><?= $i+1 ?></td>
                                <td class="border border-slate-900 px-4 py-3 text-center text-[10px] font-bold text-slate-600">
                                    <?= date('d/m/Y', strtotime($m['tanggal'])) ?><br>
                                    <span class="text-[8px] font-normal italic"><?= date('H:i:s', strtotime($m['tanggal'])) ?></span>
                                </td>
                                <td class="border border-slate-900 px-4 py-3 font-semibold uppercase text-slate-800">
                                    <?= htmlspecialchars($m['ket']) ?>
                                    <?php if($m['tipe'] == 'setoran'): ?>
                                        <span class="ml-2 text-[8px] px-1.5 py-0.5 bg-emerald-100 text-emerald-800 rounded font-black border border-emerald-300">SETORAN</span>
                                    <?php else: ?>
                                        <span class="ml-2 text-[8px] px-1.5 py-0.5 bg-red-100 text-red-800 rounded font-black border border-red-300">PENARIKAN</span>
                                    <?php endif; ?>
                                </td>
                                <td class="border border-slate-900 px-4 py-3 text-center font-bold text-slate-600 text-sm">
                                    <?= $m['qty'] > 0 ? number_format($m['qty'], 0).' Pcs' : '-' ?>
                                </td>
                                <td class="border border-slate-900 px-4 py-3 text-right font-black text-sm <?= $m['tipe'] == 'setoran' ? 'text-emerald-700' : 'text-red-700' ?>">
                                    <?= $m['tipe'] == 'setoran' ? '+' : '-' ?> <?= number_format($m['jumlah'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-slate-100 font-black text-sm text-slate-900 uppercase">
                        <tr>
                            <td colspan="4" class="border border-slate-900 px-4 py-4 text-right">SALDO AKHIR REKENING</td>
                            <td class="border border-slate-900 px-4 py-4 text-right">Rp <?= number_format($total_saldo, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="hidden print:grid grid-cols-2 mt-16 text-center text-xs text-slate-900">
                <div class="space-y-24">
                    <div>
                        <p class="font-bold">Mengetahui,</p>
                        <p class="font-black uppercase italic mt-1">Wali Kelas / Nasabah</p>
                    </div>
                    <div>
                        <p class="font-black underline">( ___________________________ )</p>
                    </div>
                </div>
                <div class="space-y-24">
                    <div>
                        <p class="font-bold">Disusun Oleh,</p>
                        <p class="font-black uppercase italic mt-1">Petugas Bank Sampah TKM</p>
                    </div>
                    <div>
                        <p class="font-black underline">( <?= htmlspecialchars($_SESSION['nama']) ?> )</p>
                        <p class="text-[10px] mt-1 opacity-50 italic uppercase">Dicetak pada: <?= date('d/m/Y H:i') ?></p>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

<style>
    @media print {
        /* 1. Sembunyikan elemen navigasi utama dari layout admin */
        aside, header, nav, footer, .no-print, [x-data] button { 
            display: none !important; 
        }

        /* 2. Reset paksa layout body agar full kertas */
        body, html {
            background-color: white !important;
            height: auto !important;
            overflow: visible !important;
            margin: 0 !important;
            padding: 0 !important;
            color: black !important;
        }

        /* 3. Membebaskan kontainer utama */
        main, .flex-1, .flex.h-screen {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            height: auto !important;
            display: block !important;
            overflow: visible !important;
        }

        /* 4. Pengaturan Kertas A4 */
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        /* 5. Garis tabel cetak tegas */
        table, th, td {
            border: 1pt solid black !important;
            border-collapse: collapse !important;
        }
        
        /* 6. Hilangkan styling tambahan di area cetak */
        #print-area {
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }
    }
</style>