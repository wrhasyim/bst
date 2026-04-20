<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-xl font-bold text-gray-800">🚛 Form Jual ke Pengepul</h2>
            <p class="text-xs text-gray-500">Pastikan jumlah yang dijual tidak melebihi stok gudang (Pcs).</p>
        </div>
        <a href="<?= BASE_URL ?>/penjualan" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition-all">Batal</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8"
         x-data="{ 
             jumlah: 0, 
             harga: 0, 
             katId: '', 
             list: <?= htmlspecialchars(json_encode($kategori)) ?>,
             get detail() { return this.list.find(i => i.id == this.katId) || null },
             get isError() { return this.detail ? (this.jumlah > this.detail.stok_pcs) : false }
         }">
        
        <form action="<?= BASE_URL ?>/penjualan/store" method="POST" class="space-y-6">
            
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 tracking-widest">1. Pilih Barang di Gudang</label>
                <select name="kategori_id" x-model="katId" required 
                        class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-slate-800 transition-all font-bold">
                    <option value="">-- Cek Stok Gudang --</option>
                    <?php foreach($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>">
                            <?= htmlspecialchars($k['nama_sampah']) ?> (Tersedia: <?= $k['stok_pcs'] ?> Pcs)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 tracking-widest">2. Jumlah Jual (Pcs)</label>
                    <input type="number" name="total_berat" x-model.number="jumlah" required 
                           :class="isError ? 'border-red-500 bg-red-50 ring-red-200' : 'border-gray-200 bg-gray-50 focus:ring-slate-800'"
                           class="w-full px-4 py-3 rounded-xl font-black text-lg outline-none transition-all">
                    <p x-show="isError" class="text-[10px] text-red-600 font-bold mt-2">❌ Stok tidak cukup! Maksimal: <span x-text="detail.stok_pcs"></span> Pcs.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 tracking-widest">3. Harga Pengepul per Pcs (Rp)</label>
                    <input type="number" name="harga_per_kg" x-model.number="harga" required 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-black text-lg text-emerald-700 outline-none focus:ring-2 focus:ring-slate-800 transition-all">
                </div>
            </div>

            <div x-show="jumlah * harga > 0 && !isError" x-transition 
                 class="p-5 bg-slate-900 rounded-2xl flex justify-between items-center text-white shadow-xl">
                <div>
                    <span class="block text-[10px] font-bold uppercase opacity-50 tracking-widest mb-1">Total Pendapatan</span>
                    <span class="block text-2xl font-black text-emerald-400">Rp <span x-text="new Intl.NumberFormat('id-ID').format(jumlah * harga)"></span></span>
                </div>
                <div class="text-4xl">💰</div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 tracking-widest">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Contoh: Penjualan ke Pengepul Pak Budi" 
                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-slate-800 transition-all"></textarea>
            </div>

            <button type="submit" 
                    :disabled="isError || jumlah <= 0 || !katId"
                    :class="isError || jumlah <= 0 || !katId ? 'opacity-30 cursor-not-allowed bg-gray-400' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20'"
                    class="w-full py-4 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl transition-all transform active:scale-95">
                Konfirmasi Penjualan
            </button>
        </form>
    </div>
</div>