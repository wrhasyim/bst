<div class="max-w-2xl mx-auto space-y-8 pb-10">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-black text-slate-800 italic uppercase tracking-tight">FORM<span class="text-emerald-500">PENJUALAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Input Data Penjualan & Negosiasi Harga</p>
        </div>
        <a href="<?= BASE_URL ?>/penjualan" class="text-[10px] font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition-all">Batal & Kembali</a>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 p-8 shadow-sm"
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
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">1. Pilih Barang di Gudang</label>
                <select name="kategori_id" x-model="katId" required 
                        @change="harga = detail ? detail.harga_estimasi : 0"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-emerald-500 transition-all font-black text-slate-800 uppercase italic">
                    <option value="">-- Cek Stok Gudang --</option>
                    <template x-for="k in list" :key="k.id">
                        <option :value="k.id" x-text="k.nama_sampah + ' (Tersedia: ' + k.stok_pcs + ' Pcs)'"></option>
                    </template>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">2. Jumlah Jual (Pcs)</label>
                    <div class="relative">
                        <input type="number" name="total_berat" x-model.number="jumlah" required min="1"
                               :class="isError ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'"
                               class="w-full px-5 py-4 rounded-2xl font-black text-xl outline-none transition-all text-slate-800">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400 uppercase">Pcs</span>
                    </div>
                    <p x-show="isError" class="text-[10px] text-red-600 font-bold mt-2 ml-1 italic">❌ Stok tidak cukup! Maksimal: <span x-text="detail.stok_pcs"></span> Pcs.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">3. Harga Nego / Pcs (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                        <input type="number" name="harga_per_kg" x-model.number="harga" required 
                               class="w-full pl-12 pr-5 py-4 bg-emerald-50 border border-emerald-100 rounded-2xl font-black text-xl text-emerald-700 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold mt-2 ml-1 uppercase italic">*Isi harga hasil kesepakatan dengan pengepul.</p>
                </div>
            </div>

            <div x-show="jumlah * harga > 0 && !isError" x-collapse
                 class="p-6 bg-slate-900 rounded-[2rem] flex justify-between items-center text-white shadow-2xl">
                <div>
                    <span class="block text-[10px] font-bold uppercase opacity-50 tracking-widest mb-1">Total Kas Akan Diterima</span>
                    <span class="block text-3xl font-black text-emerald-400 tracking-tighter">Rp <span x-text="new Intl.NumberFormat('id-ID').format(jumlah * harga)"></span></span>
                </div>
                <div class="text-4xl">💰</div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Keterangan Opsional</label>
                <textarea name="keterangan" rows="2" placeholder="Contoh: Dijual ke Pengepul Pak Agus..." 
                          class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all"></textarea>
            </div>

            <button type="submit" 
                    :disabled="isError || jumlah <= 0 || !katId"
                    :class="isError || jumlah <= 0 || !katId ? 'opacity-30 cursor-not-allowed bg-slate-300' : 'bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-500/20'"
                    class="w-full py-5 text-white font-black text-xs uppercase tracking-[0.3em] rounded-2xl transition-all transform active:scale-95">
                Konfirmasi & Simpan Penjualan
            </button>
        </form>
    </div>
</div>