<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Validasi Setoran</h2>
        <p class="text-xs text-gray-500 mt-1">Konfirmasi jumlah barang (Pcs) sebelum masuk ke saldo resmi.</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-lg shadow-sm mb-4 text-sm text-emerald-800 font-medium">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] uppercase text-gray-400 font-bold">
                    <th class="px-5 py-3">Tanggal</th>
                    <th class="px-5 py-3">Penyetor</th>
                    <th class="px-5 py-3 text-center">Jumlah</th>
                    <th class="px-5 py-3 text-right">Nilai</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($pending)): ?>
                    <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400 text-sm italic">Kosong.</td></tr>
                <?php else: ?>
                    <?php foreach ($pending as $row): ?>
                    <tr class="hover:bg-amber-50/30 transition-colors">
                        <td class="px-5 py-3 text-xs text-gray-500"><?= date('d/m/y H:i', strtotime($row['created_at'])) ?></td>
                        <td class="px-5 py-3">
                            <div class="font-bold text-gray-700 text-sm"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                            <div class="text-[10px] text-emerald-600 uppercase font-bold"><?= htmlspecialchars($row['nama_kelas'] ?? 'GURU') ?></div>
                        </td>
                        <td class="px-5 py-3 text-center font-bold text-gray-800 text-sm">
                            <?= number_format($row['berat'], 0) ?> <span class="text-[10px] text-gray-400 uppercase">Pcs</span>
                        </td>
                        <td class="px-5 py-3 text-right font-black text-emerald-700 text-sm">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex justify-center gap-1.5">
                                <a href="<?= BASE_URL ?>/setoran/proses_validasi/<?= $row['id'] ?>" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg transition-all">Validasi</a>
                                <a href="<?= BASE_URL ?>/setoran/edit_pending/<?= $row['id'] ?>" class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-lg transition-all border border-blue-200">✏️ Edit</a>
                                <a href="<?= BASE_URL ?>/setoran/hapus_pending/<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="px-2 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg transition-all border border-red-200">🗑️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>