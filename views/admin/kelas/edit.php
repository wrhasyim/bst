<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Data Kelas</h2>
            <p class="text-gray-500 text-sm mt-1">Ubah nama kelas atau ganti wali kelas yang menjabat.</p>
        </div>
        <a href="<?= BASE_URL ?>/kelas" class="text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= BASE_URL ?>/kelas/update/<?= $kelas['id'] ?>" method="POST" class="p-6 sm:p-8 space-y-6">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kelas" value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Wali Kelas <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <div class="relative">
                    <select name="walikelas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent appearance-none transition-all">
                        <option value="">-- Pilih Guru Wali Kelas (Kosongkan jika tidak ada) --</option>
                        <?php foreach($gurus as $guru): ?>
                            <option value="<?= $guru['id'] ?>" <?= ($kelas['walikelas_id'] == $guru['id']) ? 'selected' : '' ?>>
                                👨‍🏫 <?= htmlspecialchars($guru['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    Update Kelas
                </button>
            </div>
        </form>
    </div>
</div>