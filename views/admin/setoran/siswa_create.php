<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Input Setoran Tabungan</h2>
            <p class="text-gray-500 text-sm mt-1">Catat hasil timbangan sampah dari siswa.</p>
        </div>
        <a href="<?= BASE_URL ?>/setoran/siswa" class="text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
         x-data="{ 
             berat: 0,
             katId: '',
             daftarSampah: <?= htmlspecialchars(json_encode($sampah)) ?>,
             get detailSampah() {
                 return this.daftarSampah.find(s => s.id == this.katId) || null;
             },
             get estimasiTabungan() {
                 if (!this.detailSampah || this.berat <= 0) return 0;
                 return (this.berat * this.detailSampah.harga_siswa);
             }
         }">
         
        <form action="<?= BASE_URL ?>/setoran/siswa_store" method="POST" class="p-6 sm:p-8 space-y-6">
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Penyetor (Siswa) <span class="text-red-500">*</span></label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Ketik / Pilih Nama Siswa --</option>
                        <?php foreach($siswa as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Sampah <span class="text-red-500">*</span></label>
                        <select name="kategori_id" x-model="katId" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500">
                            <option value="">-- Pilih Sampah --</option>
                            <?php foreach($sampah as $kat): ?>
                                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_sampah']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p x-show="detailSampah" class="text-[11px] text-emerald-600 font-semibold mt-1" x-text="'Harga Dasar: Rp ' + new Intl.NumberFormat('id-ID').format(detailSampah.harga_siswa) + ' / Kg'"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hasil Timbangan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="berat" x-model.number="berat" step="0.1" min="0.1" required class="w-full pl-4 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 font-bold text-lg text-gray-800">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Kg</span>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="estimasiTabungan > 0" x-transition class="mt-6 p-5 bg-emerald-900 rounded-xl shadow-inner flex items-center justify-between text-white">
                <div>
                    <span class="block text-emerald-300 text-xs font-bold uppercase tracking-wider mb-1">Estimasi Masuk Tabungan</span>
                    <span class="block text-3xl font-black">Rp <span x-text="new Intl.NumberFormat('id-ID').format(estimasiTabungan)"></span></span>
                </div>
                <div class="hidden sm:block text-5xl opacity-20">💰</div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-lg rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>