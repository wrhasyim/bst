<div class="max-w-7xl mx-auto space-y-6 pb-10 print:space-y-0 print:pb-0">
    
    <!-- Bagian Header Laporan (Disembunyikan saat cetak) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">LAPORAN<span class="text-blue-500">TARIK GURU</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Rekapitulasi Pencairan Tabungan Staf</p>
        </div>
        
        <!-- Tombol Aksi (Print & Export Excel) -->
        <div class="flex space-x-3">
            <button onclick="exportToExcel('tabelRekap', 'Laporan_Penarikan_Guru_<?= $bulan_filter ?>')" class="px-5 py-2.5 bg-green-600 text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-green-700 transition-all flex items-center shadow-md">
                <span class="mr-2 text-base">📊</span> Export Excel
            </button>
            <button onclick="window.print()" class="px-5 py-2.5 bg-slate-800 text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-900 transition-all flex items-center shadow-md">
                <span class="mr-2 text-base">🖨️</span> Cetak Data
            </button>
        </div>
    </div>

    <!-- Bagian Filter Bulan (Disembunyikan saat cetak) -->
    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm print:hidden">
        <form action="<?= BASE_URL ?>/laporan/penarikan_guru" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 max-w-xs">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Bulan Laporan</label>
                <input type="month" name="bulan" value="<?= htmlspecialchars($bulan_filter) ?>" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 transition-all">
                Tampilkan Data
            </button>
        </form>
    </div>

    <!-- Bagian Tabel Rekapitulasi -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden p-8 print:p-0 print:border-none print:shadow-none print:rounded-none print:bg-transparent">
        
        <!-- Header Judul yang Hanya Muncul Saat Kertas Dicetak (Print) -->
        <div class="hidden print:block text-center mb-6 border-b-2 border-black pb-4">
            <h1 class="text-2xl font-black uppercase tracking-widest text-black">Laporan Penarikan Tabungan Staf & Guru</h1>
            <p class="text-sm font-bold text-gray-700 mt-1">Periode Bulan: <?= date('F Y', strtotime($bulan_filter . '-01')) ?></p>
        </div>

        <div class="overflow-x-auto print:overflow-visible">
            <table id="tabelRekap" class="w-full text-left border-collapse print:border-collapse print:w-full">
                <thead>
                    <tr class="bg-slate-100 border-b-2 border-slate-300 text-[10px] uppercase text-slate-600 font-black tracking-widest print:bg-gray-200 print:text-black">
                        <th class="px-6 py-4 border border-slate-200 text-center w-12 print:border-black print:px-4 print:py-3">No</th>
                        <th class="px-6 py-4 border border-slate-200 w-40 print:border-black print:px-4 print:py-3">Tanggal & Waktu</th>
                        <th class="px-6 py-4 border border-slate-200 print:border-black print:px-4 print:py-3">Nama Guru / Staf</th>
                        <th class="px-6 py-4 border border-slate-200 print:border-black print:px-4 print:py-3">Keterangan</th>
                        <th class="px-6 py-4 border border-slate-200 text-right w-48 print:border-black print:px-4 print:py-3">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="print:text-black">
                    <?php if(empty($laporan)): ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 text-xs font-bold italic border border-slate-200 print:border-black print:text-black">Tidak ada data penarikan pada bulan ini.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($laporan as $row): ?>
                        <tr class="hover:bg-slate-50 transition-colors print:hover:bg-transparent">
                            <td class="px-6 py-3 border border-slate-200 text-xs font-bold text-center text-slate-500 print:border-black print:text-black print:px-4 print:py-2"><?= $no++ ?></td>
                            <td class="px-6 py-3 border border-slate-200 text-xs font-bold text-slate-700 print:border-black print:text-black print:px-4 print:py-2"><?= date('d/m/Y H:i', strtotime($row['tanggal_tarik'])) ?></td>
                            <td class="px-6 py-3 border border-slate-200 text-xs font-black uppercase text-slate-800 print:border-black print:text-black print:px-4 print:py-2"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="px-6 py-3 border border-slate-200 text-[10px] font-bold text-slate-500 uppercase print:border-black print:text-black print:px-4 print:py-2"><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="px-6 py-3 border border-slate-200 text-sm font-black text-right text-red-600 print:border-black print:text-black print:px-4 print:py-2"><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="print:text-black">
                    <tr class="bg-slate-50 print:bg-gray-100">
                        <td colspan="4" class="px-6 py-4 border border-slate-200 text-right text-xs font-black uppercase tracking-widest text-slate-800 print:border-black print:text-black print:px-4 print:py-3">Grand Total Penarikan Bulan Ini</td>
                        <td class="px-6 py-4 border border-slate-200 text-base font-black text-right text-slate-900 border-t-4 border-t-slate-800 print:border-black print:border-t-4 print:border-t-black print:text-black print:px-4 print:py-3">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Script Logika untuk Export Excel -->
<script>
    function exportToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>

<!-- Pengaturan Style Khusus Kertas Print -->
<style>
    @media print {
        /* Mengatur kertas cetak agar konsisten A4 Portrait */
        @page {
            size: A4 portrait;
            margin: 15mm; /* Memberikan margin bersih di setiap sisi */
        }
        
        /* Menyembunyikan sidebar dan navigasi dari layout utama (admin.php) */
        aside, header {
            display: none !important;
        }

        /* Mereset background layar agar berwarna putih bersih */
        body, main, .bg-slate-50\/50, .flex-1, .container {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
        }
        
        /* Memaksa browser mencetak warna background tabel */
        th, tfoot tr {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>