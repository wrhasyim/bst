<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Riwayat Tabungan Siswa</h2>
            <p class="text-xs text-gray-500 mt-0.5">Daftar transaksi masuk (Satuan: Pcs).</p>
        </div>
        <a href="<?= BASE_URL ?>/setoran/siswa_kelas" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all">
            <span class="mr-2">🏫</span> Input Setoran Per Kelas
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-lg shadow-sm mb-4">
            <span class="text-emerald-800 text-sm font-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] uppercase tracking-wider text-gray-500 font-bold">
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3 text-center">Jumlah</th>
                        <th class="px-5 py-3 text-right">Tabungan</th>
                        <th class="px-5 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($setoran)): ?>
                        <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm italic">Belum ada transaksi terekam.</td></tr>
                    <?php else: ?>
                        <?php foreach ($setoran as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-xs text-gray-500">
                                <span class="font-semibold text-gray-700"><?= date('d/m/y', strtotime($row['created_at'])) ?></span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                                <div class="text-[10px] text-emerald-600 font-bold uppercase"><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></div>
                            </td>
                            <td class="px-5 py-3 text-center font-bold text-emerald-700 text-sm">
                                <?= number_format($row['berat'], 0) ?> <span class="text-[10px] font-normal text-gray-400 uppercase">Pcs</span>
                            </td>
                            <td class="px-5 py-3 text-right font-bold text-gray-800 text-sm">
                                Rp<?= number_format($row['total_harga'], 0, ',', '.') ?>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-2.5 py-0.5 text-[9px] font-bold uppercase rounded-full border <?= $row['status'] == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200' ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>