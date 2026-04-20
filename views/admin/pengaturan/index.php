<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Pengaturan Sistem</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola identitas instansi dan persentase pembagian honor margin penjualan secara dinamis.</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <svg class="h-6 w-6 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            <span class="text-emerald-800 font-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="text-red-800 font-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/pengaturan/update" method="POST" 
          x-data="{ 
              p1: <?= $settings['persen_pengelola'] ?? 20 ?>, 
              p2: <?= $settings['persen_walikelas'] ?? 10 ?>, 
              p3: <?= $settings['persen_kas_sekolah'] ?? 5 ?>, 
              p4: <?= $settings['persen_kas_banksampah'] ?? 65 ?>,
              get total() { return Number(this.p1) + Number(this.p2) + Number(this.p3) + Number(this.p4); }
          }">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 relative z-10">Identitas Instansi</h3>
                    
                    <div class="space-y-4 relative z-10">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Instansi / Sekolah</label>
                            <input type="text" name="nama_sekolah" value="<?= htmlspecialchars($settings['nama_sekolah'] ?? '') ?>" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat_sekolah" rows="4" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 resize-none"><?= htmlspecialchars($settings['alamat_sekolah'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Distribusi Margin (Keuangan)</h3>
                            <p class="text-xs text-gray-500 mt-1">Pembagian dari (Harga Pengepul - Harga Penyetor).</p>
                        </div>
                        
                        <div class="mt-3 sm:mt-0 px-4 py-2 rounded-xl text-sm font-bold flex items-center shadow-inner" 
                             :class="total === 100 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'">
                            <span>Total Distribusi:</span>
                            <span class="ml-2 text-lg" x-text="total + '%'"></span>
                            <svg x-show="total === 100" class="w-5 h-5 ml-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <svg x-show="total !== 100" class="w-5 h-5 ml-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="relative group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Honor Pengelola</label>
                            <div class="relative">
                                <input type="number" name="persen_pengelola" x-model.number="p1" required min="0" max="100" step="0.1" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">Insentif untuk staff lapangan.</p>
                        </div>
                        
                        <div class="relative group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Honor Wali Kelas</label>
                            <div class="relative">
                                <input type="number" name="persen_walikelas" x-model.number="p2" required min="0" max="100" step="0.1" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">Apresiasi guru wali kelas aktif.</p>
                        </div>

                        <div class="relative group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kas Sekolah Utama</label>
                            <div class="relative">
                                <input type="number" name="persen_kas_sekolah" x-model.number="p3" required min="0" max="100" step="0.1" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">Disetor ke rekening sekolah.</p>
                        </div>

                        <div class="relative group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kas Bank Sampah (BST)</label>
                            <div class="relative">
                                <input type="number" name="persen_kas_banksampah" x-model.number="p4" required min="0" max="100" step="0.1" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium text-gray-800">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">Operasional internal BST.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" 
                            :disabled="total !== 100" 
                            :class="total === 100 ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30' : 'bg-gray-400 cursor-not-allowed'" 
                            class="px-8 py-3 text-white font-bold rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        Simpan Konfigurasi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>