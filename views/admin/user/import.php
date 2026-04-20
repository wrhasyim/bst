<div class="max-w-4xl mx-auto space-y-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Import Data Siswa (CSV)</h2>
            <p class="text-gray-500 text-sm mt-1">Masukkan data siswa dalam jumlah banyak sekaligus.</p>
        </div>
        <a href="<?= BASE_URL ?>/user" class="text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium transition-colors">
            Kembali
        </a>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-blue-800 font-bold text-lg mb-2 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Panduan File Excel (CSV)
        </h3>
        <ol class="list-decimal list-inside text-blue-700 text-sm space-y-2 mt-3">
            <li>Buat file di Microsoft Excel dengan 2 kolom saja: <strong>Kolom A (Nama)</strong> dan <strong>Kolom B (Username)</strong>.</li>
            <li>Baris pertama boleh diisi judul kolom (akan diabaikan oleh sistem).</li>
            <li><strong>Password otomatis</strong> akan diatur menjadi: <code class="bg-blue-200 px-2 py-0.5 rounded text-blue-900 font-bold">123456</code> untuk semua siswa.</li>
            <li>Pastikan saat menyimpan file di Excel, pilih format <strong>CSV (Comma delimited) (*.csv)</strong>.</li>
        </ol>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= BASE_URL ?>/user/processImport" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas Tujuan <span class="text-red-500">*</span></label>
                    <select name="kelas_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                        <option value="">-- Semua siswa di file ini masuk ke kelas: --</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Angkatan <span class="text-red-500">*</span></label>
                    <input type="text" name="angkatan" required placeholder="Contoh: 2024" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
            </div>

            <div class="pt-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Upload File CSV <span class="text-red-500">*</span></label>
                <input type="file" name="file_csv" accept=".csv" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Mulai Import Data
                </button>
            </div>
        </form>
    </div>
</div>