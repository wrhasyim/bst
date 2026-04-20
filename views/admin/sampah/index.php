<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kategori & Harga Sampah</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola jenis sampah beserta harga dasar dan harga jual pengepul.</p>
        </div>
        <a href="<?= BASE_URL ?>/sampah/create" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition-all duration-200 transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Kategori
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <span class="text-emerald-800 font-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <span class="text-red-800 font-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4 text-right">Harga Siswa (Rp)</th>
                        <th class="px-6 py-4 text-right">Harga Guru (Rp)</th>
                        <th class="px-6 py-4 text-right bg-emerald-50 text-emerald-700 rounded-tl-lg">Harga Pengepul (Rp)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php 
                    $no = 1;
                    foreach ($sampah as $row): 
                        // Menghitung Margin untuk siswa
                        $margin = $row['harga_pengepul'] - $row['harga_siswa'];
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $no++ ?></td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800"><?= htmlspecialchars($row['nama_sampah']) ?></span>
                            <?php if($margin > 0): ?>
                                <div class="text-[11px] text-emerald-600 font-medium mt-1">Margin Siswa: +Rp<?= number_format($margin, 0, ',', '.') ?>/kg</div>
                            <?php else: ?>
                                <div class="text-[11px] text-red-500 font-medium mt-1">Rugi! Cek Harga</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium text-gray-700"><?= number_format($row['harga_siswa'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right text-sm font-medium text-gray-700"><?= number_format($row['harga_guru'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-emerald-700 bg-emerald-50/30"><?= number_format($row['harga_pengepul'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="<?= BASE_URL ?>/sampah/edit/<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <a href="<?= BASE_URL ?>/sampah/delete/<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus kategori sampah ini?')" class="text-red-500 hover:text-red-700 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($sampah)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                Belum ada data kategori sampah. Silakan tambah baru.
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>