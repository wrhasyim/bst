<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Koreksi Data</h2>
            <p class="text-xs text-gray-500">Memperbaiki jumlah barang untuk: <strong><?= htmlspecialchars($setoran['nama']) ?></strong></p>
        </div>
        <a href="<?= BASE_URL ?>/setoran/validasi" class="text-xs font-bold text-gray-400 hover:text-gray-600">Batal</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="<?= BASE_URL ?>/setoran/update_pending/<?= $setoran['id'] ?>" method="POST" class="space-y-5">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5">Kategori Barang</label>
                <select name="kategori_id" required class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    <?php foreach($sampah as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($setoran['kategori_id'] == $s['id']) ? 'selected' : '' ?>>
                            ♻️ <?= htmlspecialchars($s['nama_sampah']) ?> 
                            (Rp<?= ($setoran['role'] == 'guru') ? number_format($s['harga_guru'],0) : number_format($s['harga_siswa'],0) ?>/pcs)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5">Jumlah Barang (Pcs)</label>
                <div class="relative">
                    <input type="number" name="berat" step="1" min="1" required value="<?= (int)$setoran['berat'] ?>"
                           class="w-full pl-4 pr-12 py-2.5 bg-gray-50 border border-gray-200 rounded-lg font-black text-emerald-700 outline-none">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">Pcs</span>
                </div>
            </div>
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-10 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-lg transition-all">SIMPAN KOREKSI</button>
            </div>
        </form>
    </div>
</div>