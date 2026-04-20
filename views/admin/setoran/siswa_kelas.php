<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Input Setoran Per Kelas</h2>
            <p class="text-xs text-gray-500">Pilih kelas dan jenis sampah untuk mulai mencatat (Satuan: Pcs).</p>
        </div>
        <a href="<?= BASE_URL ?>/setoran/siswa" class="text-xs font-bold text-emerald-600 hover:underline">
            &larr; Riwayat Setoran
        </a>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-5">
        <form action="<?= BASE_URL ?>/setoran/siswa_kelas" method="GET" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">1. Pilih Kelas</label>
                <select name="kelas_id" required class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all outline-none">
                    <option value="">-- Kelas --</option>
                    <?php foreach($all_kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                            🏫 <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 ml-1">2. Jenis Sampah</label>
                <select name="kategori_id" required class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all outline-none">
                    <option value="">-- Jenis Sampah --</option>
                    <?php foreach($all_sampah as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (isset($_GET['kategori_id']) && $_GET['kategori_id'] == $s['id']) ? 'selected' : '' ?>>
                            ♻️ <?= htmlspecialchars($s['nama_sampah']) ?> (Rp<?= number_format($s['harga_siswa'],0) ?>/pcs)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-gray-800 hover:bg-black text-white text-sm font-bold rounded-lg transition-all">
                Tampilkan Siswa
            </button>
        </form>
    </div>

    <?php if (isset($_GET['kelas_id']) && !empty($siswa_list)): ?>
        <form action="<?= BASE_URL ?>/setoran/siswa_batch_store" method="POST">
            <input type="hidden" name="kategori_id" value="<?= htmlspecialchars($_GET['kategori_id']) ?>">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[10px] uppercase text-gray-400 font-bold">
                            <th class="px-5 py-3 w-16">No</th>
                            <th class="px-5 py-3">Nama Lengkap Siswa</th>
                            <th class="px-5 py-3 text-right">Jumlah (Pcs)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $no=1; foreach($siswa_list as $siswa): ?>
                        <tr>
                            <td class="px-5 py-2.5 text-xs text-gray-400"><?= $no++ ?></td>
                            <td class="px-5 py-2.5">
                                <span class="font-bold text-gray-700 text-sm"><?= htmlspecialchars($siswa['nama']) ?></span>
                            </td>
                            <td class="px-5 py-2.5">
                                <div class="flex justify-end">
                                    <div class="relative w-28">
                                        <input type="number" name="berat[<?= $siswa['id'] ?>]" min="0" placeholder="0" class="w-full pl-3 pr-10 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 font-bold text-right text-sm text-emerald-700 outline-none transition-all">
                                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[9px] font-bold text-gray-400">Pcs</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <p class="text-[10px] text-gray-400 italic">*Isi jumlah pcs. Jika kosong tidak akan disimpan.</p>
                    <button type="submit" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-md transition-all transform hover:-translate-y-0.5">
                        SIMPAN DATA KELAS
                    </button>
                </div>
            </div>
        </form>
    <?php elseif(isset($_GET['kelas_id'])): ?>
        <div class="text-center p-10 bg-white rounded-xl border border-dashed border-gray-300 text-gray-400 text-sm">
            Tidak ada siswa aktif di kelas ini.
        </div>
    <?php endif; ?>
</div>