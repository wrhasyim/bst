<div class="max-w-6xl mx-auto space-y-6">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-800">Setoran Guru</h2>
        <p class="text-xs text-gray-500">Input jumlah barang (Pcs) dan pantau riwayat tabungan guru.</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded-r-lg mb-4 text-xs text-emerald-800 font-medium">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm sticky top-6">
                <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2 uppercase tracking-wider">Input Baru</h3>
                <form action="<?= BASE_URL ?>/setoran/guru_store" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Pilih Guru</label>
                        <select name="user_id" required class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <option value="">-- Nama Guru --</option>
                            <?php 
                            $gurus = Database::getInstance()->getConnection()->query("SELECT id, nama FROM users WHERE role = 'guru' AND is_active = 1 ORDER BY nama ASC")->fetchAll();
                            foreach($gurus as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis Barang</label>
                        <select name="kategori_id" required class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <option value="">-- Pilih Sampah --</option>
                            <?php 
                            $sampahs = Database::getInstance()->getConnection()->query("SELECT id, nama_sampah, harga_guru FROM kategori_sampah ORDER BY nama_sampah ASC")->fetchAll();
                            foreach($sampahs as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama_sampah']) ?> (Rp<?= number_format($s['harga_guru'],0) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jumlah (Pcs)</label>
                        <div class="relative">
                            <input type="number" name="berat" step="1" min="1" required placeholder="0" class="w-full pl-3 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg font-bold text-emerald-700 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400 uppercase">Pcs</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                        SIMPAN SETORAN
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[10px] uppercase text-gray-400 font-bold">
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Nama Guru</th>
                            <th class="px-5 py-3 text-center">Jumlah</th>
                            <th class="px-5 py-3 text-right">Tabungan</th>
                            <th class="px-5 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($setoran)): ?>
                            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm italic">Belum ada riwayat.</td></tr>
                        <?php else: ?>
                            <?php foreach ($setoran as $row): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 text-[10px] text-gray-500"><?= date('d/m/y H:i', strtotime($row['created_at'])) ?></td>
                                <td class="px-5 py-3 font-bold text-gray-700 text-sm"><?= htmlspecialchars($row['nama_guru']) ?></td>
                                <td class="px-5 py-3 text-center font-bold text-gray-800 text-sm">
                                    <?= number_format($row['berat'], 0) ?> <span class="text-[9px] font-normal text-gray-400 uppercase">Pcs</span>
                                </td>
                                <td class="px-5 py-3 text-right font-black text-blue-600 text-sm">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2 py-0.5 text-[8px] font-bold uppercase rounded-full border <?= $row['status'] == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200' ?>">
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
</div>