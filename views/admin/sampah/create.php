<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tambah Kategori Baru</h2>
            <p class="text-gray-500 text-sm mt-1">Masukkan data jenis sampah beserta skema harganya.</p>
        </div>
        <a href="<?= BASE_URL ?>/sampah" class="text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= BASE_URL ?>/sampah/store" method="POST" class="p-6 sm:p-8"
              x-data="{ 
                  hargaSiswa: 0, 
                  hargaGuru: 0, 
                  hargaPengepul: 0,
                  get marginSiswa() { return this.hargaPengepul - this.hargaSiswa; },
                  get marginGuru() { return this.hargaPengepul - this.hargaGuru; }
              }">
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Jenis Sampah</label>
                    <input type="text" name="nama_sampah" required placeholder="Contoh: Kardus Bekas, Botol Plastik, dll" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100">
                    <div class="relative group">
                        <label class="block text-sm font-bold text-gray-700 mb-2 text-emerald-700">Harga Jual (Pengepul)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="harga_pengepul" x-model.number="hargaPengepul" required min="0" class="w-full pl-10 pr-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-800">
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Harga yang dibayarkan pengepul ke BST.</p>
                    </div>

                    <div class="relative group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Harga Beli dari Siswa</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <!-- FIX: name="harga_siswa" diubah menjadi name="harga_dasar" agar terbaca oleh Controller -->
                            <input type="number" name="harga_dasar" x-model.number="hargaSiswa" required min="0" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                        </div>
                        <p class="text-[11px] mt-1 font-semibold" :class="marginSiswa >= 0 ? 'text-emerald-600' : 'text-red-500'" x-text="marginSiswa >= 0 ? 'Margin: +Rp' + marginSiswa + '/Pcs' : 'Rugi! Cek Harga'"></p>
                    </div>

                    <div class="relative group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Harga Beli dari Guru</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="harga_guru" x-model.number="hargaGuru" required min="0" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                        </div>
                        <p class="text-[11px] mt-1 font-semibold" :class="marginGuru >= 0 ? 'text-emerald-600' : 'text-red-500'" x-text="marginGuru >= 0 ? 'Margin: +Rp' + marginGuru + '/Pcs' : 'Rugi! Cek Harga'"></p>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>