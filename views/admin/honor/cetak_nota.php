<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Honor Wali Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { 
            .no-print { display: none !important; } 
            body { background: white !important; } 
        }
        @page { size: A4 portrait; margin: 1.5cm; }
        
        /* Garis tabel hitam tegas untuk standar dokumen kantor */
        table, th, td {
            border: 1px solid black !important;
        }
    </style>
</head>
<body class="bg-slate-100 p-8 font-serif text-slate-900" onload="window.print()">
    
    <div class="max-w-4xl mx-auto bg-white p-10 shadow-2xl border border-slate-200 min-h-[297mm]">
        
        <div class="text-center border-b-4 border-double border-black pb-6 mb-8">
            <h1 class="text-2xl font-bold uppercase tracking-widest text-black">DAFTAR PENERIMAAN HONOR WALI KELAS</h1>
            <p class="text-sm font-bold uppercase tracking-widest mt-1 text-black">Sistem Bank Sampah TKM (BST SYSTEM)</p>
            <p class="text-xs italic mt-2 text-black">Periode Pencairan: <?= date('d F Y', strtotime($tanggal)) ?></p>
        </div>

        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100 uppercase text-[10px]">
                    <th class="px-4 py-3 w-12 text-center">No</th>
                    <th class="px-4 py-3 text-center">Nama Wali Kelas</th>
                    <th class="px-4 py-3 text-center">Nominal Cair</th>
                    <th class="px-4 py-3 w-64 text-center">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0; 
                if(empty($data_honor)): 
                ?>
                    <tr>
                        <td colspan="4" class="px-4 py-16 text-center italic text-slate-500">
                            Tidak ada data pencairan honor pada tanggal <?= date('d/m/Y', strtotime($tanggal)) ?>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data_honor as $i => $d): $total += $d['jumlah']; ?>
                    <tr>
                        <td class="px-4 py-8 text-center font-bold text-base"><?= $i+1 ?></td>
                        
                        <td class="px-4 py-8 text-center uppercase font-bold text-sm">
                            <?= htmlspecialchars($d['nama']) ?>
                        </td>
                        
                        <td class="px-4 py-8 text-center font-bold text-sm">
                            Rp <?= number_format($d['jumlah'], 0, ',', '.') ?>
                        </td>
                        
                        <td class="px-2 py-2 h-24 relative">
                            <div class="absolute <?= ($i % 2 == 0) ? 'top-2 left-2' : 'bottom-2 right-12' ?> text-lg font-black text-black">
                                <?= $i+1 ?>. <span class="text-xs font-normal italic text-slate-300 ml-1"></span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="font-bold bg-gray-50 text-sm">
                    <td colspan="2" class="px-4 py-5 text-center uppercase">Total Dana Dikeluarkan</td>
                    <td class="px-4 py-5 text-center text-base">Rp <?= number_format($total, 0, ',', '.') ?></td>
                    <td class="bg-gray-200"></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-16 grid grid-cols-2 text-center text-sm text-black">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold mt-1 mb-24 uppercase text-xs">Kepala Sekolah</p>
                <p class="font-bold underline uppercase text-sm">( _________________________ )</p>
            </div>
            <div>
                <p>Diserahkan Oleh,</p>
                <p class="font-bold mt-1 mb-24 uppercase text-xs">Pengelola Bank Sampah</p>
                <p class="font-bold underline uppercase text-sm">( <?= htmlspecialchars($_SESSION['nama']) ?> )</p>
            </div>
        </div>
        
        <div class="mt-16 no-print text-center border-t-2 border-dashed border-slate-300 pt-8">
            <button onclick="window.print()" class="bg-slate-900 text-white font-black uppercase tracking-widest text-[11px] px-10 py-4 rounded-2xl shadow-xl hover:bg-black transition-all transform active:scale-95">
                🖨️ Klik Untuk Cetak
            </button>
        </div>

    </div>

</body>
</html>