<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Riwayat Penjualan Pengepul</h2>
            <p class="text-xs text-gray-500 mt-0.5">Daftar transaksi penjualan barang (Satuan: Pcs).</p>
        </div>
        <a href="<?= BASE_URL ?>/penjualan/create" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-black text-white text-sm font-bold rounded-xl shadow-sm transition-all">
            <span class="mr-2">🚛</span> Jual Barang Gudang
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-lg shadow-sm mb-4 text-xs text-emerald-800 font-medium">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] uppercase text-gray-400 font-bold">
                    <th class="px-5 py-3">Tanggal Jual</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3 text-center">Jumlah Terjual</th>
                    <th class="px-5 py-3 text-right">Harga Deal</th>
                    <th class="px-5 py-3 text-right">Total Diterima</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($penjualan)): ?>
                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm italic">Belum ada data penjualan ke pengepul.</td></tr>
                <?php else: ?>
                    <?php foreach ($penjualan as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-xs text-gray-500">
                            <?= date('d M Y', strtotime($row['tanggal_jual'])) ?>
                        </td>
                        <td class="px-5 py-3">
                            <span class="font-bold text-gray-700 text-sm"><?= htmlspecialchars($row['nama_sampah']) ?></span>
                        </td>
                        <td class="px-5 py-3 text-center font-bold text-gray-800 text-sm">
                            <?= number_format($row['total_berat'], 0) ?> <span class="text-[10px] font-normal text-gray-400">Pcs</span>
                        </td>
                        <td class="px-5 py-3 text-right text-xs text-gray-600">
                            Rp<?= number_format($row['harga_per_kg'], 0, ',', '.') ?>/pcs
                        </td>
                        <td class="px-5 py-3 text-right font-black text-emerald-600 text-sm">
                            Rp<?= number_format($row['total_pendapatan'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>