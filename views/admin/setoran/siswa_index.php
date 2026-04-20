<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Setoran Siswa</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau seluruh riwayat setoran tabungan sampah siswa.</p>
        </div>
        <a href="<?= BASE_URL ?>/setoran/siswa_create" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition-all duration-200 transform hover:-translate-y-0.5">
            <span class="mr-2">⚖️</span> Timbang Setoran Baru
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
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Jenis Sampah</th>
                        <th class="px-6 py-4 text-center">Berat</th>
                        <th class="px-6 py-4 text-right">Nilai Tabungan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($setoran)): ?>
                        <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi setoran.</td></tr>
                    <?php else: ?>
                        <?php foreach ($setoran as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="font-medium text-gray-800"><?= date('d M Y', strtotime($row['created_at'])) ?></div>
                                <div class="text-[11px]"><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                                <div class="text-[11px] text-gray-500"><?= htmlspecialchars($row['nama_kelas'] ?? 'Tanpa Kelas') ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                ♻️ <?= htmlspecialchars($row['nama_sampah']) ?>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-emerald-700 bg-emerald-50/30">
                                <?= $row['berat'] ?> Kg
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-800">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($row['status'] == 'pending'): ?>
                                    <span class="px-3 py-1 text-[11px] font-bold uppercase rounded-full border bg-amber-50 text-amber-700 border-amber-200">Pending</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 text-[11px] font-bold uppercase rounded-full border bg-green-50 text-green-700 border-green-200">Valid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>