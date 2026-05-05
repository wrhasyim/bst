<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifest Honor Wali Kelas - BST System</title>
    <!-- Memanggil Tailwind dari CDN untuk kebutuhan cetak -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 2cm;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="bg-white text-slate-900 font-serif" onload="window.print()">

    <div class="max-w-4xl mx-auto py-10">
        <!-- HEADER KOP SURAT -->
        <div class="text-center border-b-4 border-double border-slate-900 pb-6 mb-8">
            <h1 class="text-2xl font-black uppercase tracking-[0.1em]">Daftar Penerimaan Honor Wali Kelas</h1>
            <h2 class="text-lg font-bold uppercase tracking-widest mt-1">Sistem Bank Sampah TKM (BST System)</h2>
            <p class="text-sm italic text-slate-600 mt-2 font-sans">Periode Cetak: <?= date('d F Y', strtotime($_GET['tanggal'] ?? date('Y-m-d'))) ?></p>
        </div>

        <!-- TABEL UTAMA -->
        <table class="w-full text-left border-collapse border border-slate-900 mb-16 font-sans">
            <thead>
                <tr class="bg-slate-100 text-[10px] font-black uppercase tracking-widest border-b-2 border-slate-900">
                    <th class="border border-slate-900 px-4 py-4 text-center w-12">No</th>
                    <th class="border border-slate-900 px-4 py-4 text-center">Nama Wali Kelas</th>
                    <th class="border border-slate-900 px-4 py-4 text-center w-48">Nominal Cair</th>
                    <th class="border border-slate-900 px-4 py-4 text-center w-72">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $total_cair = 0; 
                    $total_data = count($data_honor);
                ?>
                <?php if($total_data > 0): ?>
                    <?php foreach($data_honor as $index => $row): ?>
                    <?php 
                        $i = $index + 1; 
                        $total_cair += $row['jumlah'];
                        // Cek apakah ada data genap setelah data ganjil ini
                        $hasNext = isset($data_honor[$index + 1]); 
                    ?>
                    <tr>
                        <td class="border border-slate-900 px-4 py-4 text-center font-black text-sm"><?= $i ?></td>
                        <td class="border border-slate-900 px-6 py-4 font-bold uppercase tracking-wide text-sm"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="border border-slate-900 px-4 py-4 text-center font-bold text-sm">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        
                        <!-- 🛠️ IMPLEMENTASI MERGE ZIG-ZAG (ROWSPAN) -->
                        <?php if ($index % 2 == 0): // Eksekusi TD ini hanya untuk baris ganjil (1, 3, 5, dst) ?>
                        <td rowspan="<?= $hasNext ? 2 : 1 ?>" class="border border-slate-900 p-0 w-72">
                            <!-- Container Flexbox Sepenuh Tinggi (Menggabungkan 2 baris) -->
                            <div class="flex w-full h-full min-h-[6rem]">
                                <!-- Kotak Kiri (Orang Pertama) -->
                                <div class="w-1/2 border-r border-slate-900 relative">
                                    <span class="font-black text-xs text-slate-800 absolute top-3 left-3"><?= $i ?>.</span>
                                </div>
                                <!-- Kotak Kanan (Orang Kedua) -->
                                <div class="w-1/2 relative">
                                    <?php if ($hasNext): ?>
                                        <!-- Top-[50%] menjamin angka genap turun persis di awal garis baris kedua -->
                                        <span class="font-black text-xs text-slate-800 absolute top-[50%] mt-3 left-3"><?= $i + 1 ?>.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <?php endif; ?>
                        
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="border border-slate-900 px-4 py-12 text-center italic text-slate-500">Tidak ada manifest tagihan honor.</td>
                    </tr>
                <?php endif; ?>
                
                <!-- TOTAL KESELURUHAN -->
                <tr class="bg-slate-100 border-t-2 border-slate-900">
                    <td colspan="2" class="border border-slate-900 px-6 py-4 text-right font-black uppercase tracking-widest text-sm">Total Dana Dikeluarkan</td>
                    <td class="border border-slate-900 px-4 py-4 text-center font-black text-sm">Rp <?= number_format($total_cair, 0, ',', '.') ?></td>
                    <td class="border border-slate-900 bg-slate-200/50"></td>
                </tr>
            </tbody>
        </table>

        <!-- AREA TANDA TANGAN BAWAH -->
        <div class="grid grid-cols-2 text-center text-sm font-sans mt-20 break-inside-avoid">
            <div class="space-y-24">
                <div>
                    <p class="font-medium text-slate-600">Mengetahui,</p>
                    <p class="font-black uppercase mt-1">Kepala Sekolah</p>
                </div>
                <div>
                    <p class="font-black underline">( ___________________________ )</p>
                </div>
            </div>
            <div class="space-y-24">
                <div>
                    <p class="font-medium text-slate-600">Diserahkan Oleh,</p>
                    <p class="font-black uppercase mt-1">Pengelola Bank Sampah</p>
                </div>
                <div>
                    <p class="font-black underline">( <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?> )</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>