<div class="max-w-2xl mx-auto space-y-8 pb-10">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-black text-slate-800 italic uppercase tracking-tight">FORM<span class="text-emerald-500">PENJUALAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Input Data Penjualan Historis / Manual</p>
        </div>
        <a href="<?= BASE_URL ?>/penjualan" class="text-[10px] font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition-all">Batal & Kembali</a>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 p-8 shadow-sm"
         x-data="{ 
             jumlahPcs: 0, 
             jumlahKg: 0, 
             harga: 0, 
             katId: '', 
             list: <?= htmlspecialchars(json_encode($kategori_ready ?? [])) ?>,
             get detail() { return this.list.find(i => i.id == this.katId) || null },
             get konversi() { return this.detail && this.detail.konversi_kg ? parseInt(this.detail.konversi_kg) : 1; },
             get stokTersedia() { return this.detail ? parseFloat(this.detail.stok_tersedia) : 0; },
             get isError() { return this.jumlahPcs > this.stokTersedia; },
             updateFromKg() {
                 // Jika kolom KG diketik, update Pcs
                 this.jumlahPcs = Math.round(this.jumlahKg * this.konversi);
             },
             updateFromPcs() {
                 // Jika kolom Pcs diketik, update KG
                 this.jumlahKg = this.jumlahPcs / this.konversi;
             },
             setAllStock() {
                 if(this.detail) {
                     this.jumlahPcs = this.stokTersedia;
                     this.updateFromPcs();
                     this.harga = parseFloat(this.detail.harga_pengepul);
                 } else {
                     this.jumlahPcs = 0;
                     this.jumlahKg = 0;
                     this.harga = 0;
                 }
             }
         }">
        
        <form action="<?= BASE_URL ?>/penjualan/store" method="POST" class="space-y-6">
            
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">1. Pilih Barang di Gudang</label>
                <select name="kategori_id" x-model="katId" required 
                        @change="setAllStock()"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-emerald-500 transition-all font-black text-slate-800 uppercase italic">
                    <option value="">-- Cek Stok Gudang --</option>
                    <template x-for="k in list" :key="k.id">
                        <option :value="k.id" x-text="k.nama_sampah + ' (Stok: ' + k.stok_tersedia + ' Pcs)'"></option>
                    </template>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">2. Jual Berapa KG?</label>
                    <div class="relative">
                        <input type="number" step="any" x-model.number="jumlahKg" @input="updateFromKg()" required
                               class="w-full px-5 py-4 bg-emerald-50 border border-emerald-100 rounded-2xl font-black text-xl outline-none transition-all focus:ring-2 focus:ring-emerald-500 text-emerald-800 placeholder-slate-300" placeholder="0">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-emerald-600 uppercase">KG</span>
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold mt-2 ml-1 uppercase italic">*Isi manual berat dalam hitungan KG.</p>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Ekuivalen Setara (Pcs)</label>
                    <div class="relative">
                        <input type="number" step="1" name="total_pcs" x-model.number="jumlahPcs" @input="updateFromPcs()" required
                               :class="isError ? 'border-red-500 bg-red-50 text-red-600' : 'border-slate-200 bg-slate-100 text-slate-600'"
                               class="w-full px-5 py-4 rounded-2xl font-black text-xl outline-none transition-all focus:ring-2 focus:ring-emerald-500">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400 uppercase">Pcs</span>
                    </div>
                    <p x-show="isError" class="text-[10px] text-red-600 font-bold mt-2 ml-1 italic">❌ Melebihi stok! Maksimal: <span x-text="stokTersedia"></span> Pcs.</p>
                    <p x-show="!isError && katId" class="text-[10px] text-emerald-600 font-bold mt-2 ml-1 italic">✅ Tersedia <span x-text="stokTersedia"></span> Pcs (1 KG = <span x-text="konversi"></span> Pcs).</p>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">3. Harga Nego / KG (Rp)</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                    <input type="number" name="harga_per_kg" x-model.number="harga" required 
                           class="w-full pl-12 pr-5 py-4 bg-blue-50 border border-blue-100 rounded-2xl font-black text-xl text-blue-700 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>

            <div x-show="jumlahPcs * harga > 0 && katId && !isError" x-collapse
                 class="p-6 bg-slate-900 rounded-[2rem] flex justify-between items-center text-white shadow-2xl transition-all">
                <div>
                    <span class="block text-[10px] font-bold uppercase opacity-50 tracking-widest mb-1">Total Kas Akan Diterima</span>
                    <span class="block text-3xl font-black text-emerald-400 tracking-tighter">Rp <span x-text="new Intl.NumberFormat('id-ID').format(Math.round(jumlahKg * harga))"></span></span>
                </div>
                <div class="text-4xl">💰</div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Keterangan Opsional</label>
                <textarea name="keterangan" rows="2" placeholder="Contoh: Penjualan Manual Bulan Kemarin..." 
                          class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all"></textarea>
            </div>

            <button type="submit" 
                    :disabled="isError || jumlahPcs <= 0 || !katId"
                    :class="isError || jumlahPcs <= 0 || !katId ? 'opacity-30 cursor-not-allowed bg-slate-300' : 'bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-500/20'"
                    class="w-full py-5 text-white font-black text-xs uppercase tracking-[0.3em] rounded-2xl transition-all transform active:scale-95">
                Konfirmasi & Simpan Penjualan
            </button>
        </form>
    </div>
</div>