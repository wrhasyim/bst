<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Kategori Sampah</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui harga sampah mengikuti harga pasaran terbaru.</p>
        </div>
        <a href="<?= BASE_URL ?>/sampah" class="text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= BASE_URL ?>/sampah/update" method="POST" class="p-6 sm:p-8"
              x-data="{ 
                  hargaSiswa: <?= htmlspecialchars($sampah['harga_dasar'] ?? 0) ?>, 
                  hargaGuru: <?= htmlspecialchars($sampah['harga_guru'] ?? $sampah['harga_dasar'] ?? 0) ?>, 
                  hargaPengepul: <?= htmlspecialchars($sampah['harga_pengepul'] ?? 0) ?>,
                  konversiKg: <?= htmlspecialchars($sampah['konversi_kg'] ?? 1) ?>,
                  get modalPerKg() { return Math.max(this.hargaSiswa || 0, this.hargaGuru || this.hargaSiswa || 0) * this.konversiKg; },
                  get marginPerKg() { return this.hargaPengepul - this.modalPerKg; }
              }">
            
            <input type="hidden" name="id" value="<?= htmlspecialchars($sampah['id']) ?>">
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Jenis Sampah</label>
                        <input type="text" name="nama_sampah" value="<?= htmlspecialchars($sampah['nama_sampah']) ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Konversi Pcs ke 1 KG</label>
                        <input type="number" name="konversi_kg" x-model.number="konversiKg" required min="1" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-800">
                        <p class="text-[11px] text-gray-500 mt-1">*Berapa Pcs untuk 1 KG? (Isi 1 jika satuan ukur tetap sama).</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100">
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-emerald-700 uppercase tracking-wider mb-2">Harga Jual (Rp / KG)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="harga_pengepul" x-model.number="hargaPengepul" required min="0" class="w-full pl-10 pr-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-800">
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1">Dibayar oleh Pengepul.</p>
                    </div>

                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-red-600 uppercase tracking-wider mb-2">Harga Beli Siswa (Rp / Pcs)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="harga_dasar" x-model.number="hargaSiswa" required min="0" class="w-full pl-10 pr-4 py-3 bg-red-50 border border-red-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                        </div>
                    </div>

                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-purple-600 uppercase tracking-wider mb-2">Harga Beli Guru (Rp / Pcs)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="harga_guru" x-model.number="hargaGuru" required min="0" class="w-full pl-10 pr-4 py-3 bg-purple-50 border border-purple-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 p-6 rounded-2xl border-2 flex items-center justify-between transition-all" :class="marginPerKg >= 0 ? 'border-emerald-100 bg-emerald-50/50' : 'border-red-100 bg-red-50/50'">
                <div>
                    <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Margin (Profit) Minimal per 1 KG :</span>
                    <span class="block text-[9px] text-slate-400 mt-1">Hitungan: Jual 1 KG dikurangi (Beli <span x-text="konversiKg"></span> Pcs × Harga Tertinggi)</span>
                </div>
                <span class="text-2xl font-black" :class="marginPerKg >= 0 ? 'text-emerald-600' : 'text-red-600'">
                    Rp<span x-text="new Intl.NumberFormat('id-ID').format(marginPerKg)"></span>
                </span>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    Update Kategori
                </button>
            </div>
        </form>
    </div>
</div>