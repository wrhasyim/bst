<div class="max-w-3xl mx-auto space-y-8 pb-10">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-black text-slate-800 italic uppercase tracking-tight">FORM<span class="text-emerald-500">PENJUALAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Input Data Penjualan Banyak Barang Sekaligus</p>
        </div>
        <a href="<?= BASE_URL ?>/penjualan" class="text-[10px] font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition-all">Batal & Kembali</a>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 p-8 shadow-sm"
         x-data="{ 
             tutupBotol: 0,
             list: <?= htmlspecialchars(json_encode($kategori_ready ?? [])) ?>,
             items: [
                 { katId: '', jumlahPcs: 0, jumlahKg: 0, harga: 0 }
             ],
             addItem() {
                 this.items.push({ katId: '', jumlahPcs: 0, jumlahKg: 0, harga: 0 });
             },
             removeItem(index) {
                 if(this.items.length > 1) {
                     this.items.splice(index, 1);
                 }
             },
             getDetail(katId) { 
                 return this.list.find(i => i.id == katId) || null; 
             },
             getKonversi(katId) { 
                 let detail = this.getDetail(katId);
                 return detail && detail.konversi_kg ? parseInt(detail.konversi_kg) : 1; 
             },
             getStokTersedia(katId) { 
                 let detail = this.getDetail(katId);
                 return detail ? parseFloat(detail.stok_tersedia) : 0; 
             },
             checkError(index) {
                 let item = this.items[index];
                 return item.jumlahPcs > this.getStokTersedia(item.katId);
             },
             get hasError() {
                 return this.items.some((item, index) => this.checkError(index));
             },
             get isFormValid() {
                 let validItems = this.items.every(i => i.katId !== '' && i.jumlahPcs > 0 && i.jumlahKg > 0);
                 return validItems && !this.hasError;
             },
             updateFromKg(index) {
                 let item = this.items[index];
                 let konversi = this.getKonversi(item.katId);
                 item.jumlahPcs = Math.round(item.jumlahKg * konversi);
             },
             setAllStock(index) {
                 let item = this.items[index];
                 let detail = this.getDetail(item.katId);
                 if(detail) {
                     let stok = this.getStokTersedia(item.katId);
                     let konversi = this.getKonversi(item.katId);
                     item.jumlahPcs = stok;
                     item.jumlahKg = parseFloat((stok / konversi).toFixed(2));
                     item.harga = parseFloat(detail.harga_pengepul);
                 } else {
                     item.jumlahPcs = 0;
                     item.jumlahKg = 0;
                     item.harga = 0;
                 }
             },
             get totalKeseluruhan() {
                 let total = 0;
                 this.items.forEach(item => {
                     total += (parseFloat(item.jumlahKg) || 0) * (parseFloat(item.harga) || 0);
                 });
                 return Math.round(total) + (parseFloat(this.tutupBotol) || 0);
             }
         }">
        
        <form action="<?= BASE_URL ?>/penjualan/store" method="POST" class="space-y-6">
            <!-- 🛡️ CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <!-- BLOK PERULANGAN BARANG -->
            <div class="space-y-4">
                <template x-for="(item, index) in items" :key="index">
                    <div class="p-5 border-2 border-slate-100 rounded-2xl bg-slate-50 relative">
                        <!-- Tombol Hapus Baris -->
                        <button type="button" x-show="items.length > 1" @click="removeItem(index)" class="absolute top-4 right-4 text-red-400 hover:text-red-600 font-bold text-xs uppercase tracking-widest">
                            ✕ Hapus
                        </button>

                        <h4 class="text-xs font-black text-slate-400 uppercase mb-4" x-text="'Barang #' + (index + 1)"></h4>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">1. Pilih Barang di Gudang</label>
                                <select name="kategori_id[]" x-model="item.katId" required 
                                        @change="setAllStock(index)"
                                        class="w-full px-5 py-3 bg-white border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 transition-all font-black text-slate-800 uppercase italic">
                                    <option value="">-- Cek Stok Gudang --</option>
                                    <template x-for="k in list" :key="k.id">
                                        <option :value="k.id" x-text="k.nama_sampah + ' (Stok: ' + k.stok_tersedia + ' Pcs)'"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">2. Berat (KG)</label>
                                    <div class="relative">
                                        <input type="number" step="any" name="total_kg[]" x-model.number="item.jumlahKg" @input="updateFromKg(index)" required
                                               class="w-full px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl font-black text-lg outline-none transition-all focus:ring-2 focus:ring-emerald-500 text-emerald-800" placeholder="0">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-emerald-600 uppercase">KG</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Inject (Pcs)</label>
                                    <div class="relative">
                                        <input type="number" step="1" name="total_pcs[]" x-model.number="item.jumlahPcs" required
                                               :class="checkError(index) ? 'border-red-500 bg-red-50 text-red-600' : 'border-slate-200 bg-white text-slate-600'"
                                               class="w-full px-4 py-3 rounded-xl font-black text-lg outline-none transition-all focus:ring-2 focus:ring-emerald-500">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400 uppercase">Pcs</span>
                                    </div>
                                    <p x-show="checkError(index)" class="text-[10px] text-red-600 font-bold mt-1 ml-1 italic">❌ Maks: <span x-text="getStokTersedia(item.katId)"></span></p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">3. Harga / KG</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                                        <input type="number" name="harga_per_kg[]" x-model.number="item.harga" required 
                                               class="w-full pl-10 pr-4 py-3 bg-blue-50 border border-blue-100 rounded-xl font-black text-lg text-blue-700 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Tombol Tambah Barang Baru -->
            <button type="button" @click="addItem()" class="w-full py-3 border-2 border-dashed border-emerald-300 text-emerald-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-emerald-50 transition-all">
                + Tambah Jenis Barang Lainnya
            </button>
            <hr class="border-slate-100">

            <!-- TRANSAKSI LAINNYA (Global untuk 1 Transaksi) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <!-- FITUR BARU: Input Kas Tutup Botol -->
                <div>
                    <label class="block text-[10px] font-bold text-amber-500 uppercase mb-2 ml-1 tracking-widest">4. Ekstra Kas Tutup Botol (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-amber-500 font-bold">Rp</span>
                        <input type="number" name="kas_tutup_botol_rp" x-model.number="tutupBotol" 
                               class="w-full pl-12 pr-5 py-4 bg-amber-50 border border-amber-100 rounded-2xl font-black text-xl text-amber-700 outline-none focus:ring-2 focus:ring-amber-500 transition-all" placeholder="0">
                    </div>
                    <p class="text-[9px] text-amber-600 font-bold mt-2 ml-1 uppercase italic">*Terpisah 100% ke dompet Tutup Botol.</p>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Keterangan Opsional</label>
                    <textarea name="keterangan" rows="2" placeholder="Contoh: Penjualan gabungan..." 
                              class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all"></textarea>
                </div>
            </div>

            <!-- KOTAK TOTAL -->
            <div x-show="totalKeseluruhan > 0 && !hasError" x-collapse
                 class="p-6 bg-slate-900 rounded-[2rem] flex justify-between items-center text-white shadow-2xl transition-all">
                <div>
                    <span class="block text-[10px] font-bold uppercase opacity-50 tracking-widest mb-1">Total Transaksi Pengepul</span>
                    <span class="block text-3xl font-black text-emerald-400 tracking-tighter">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(totalKeseluruhan)"></span>
                    </span>
                    <span x-show="tutupBotol > 0" class="block text-[9px] text-amber-400 uppercase tracking-widest mt-1">
                        (Termasuk Kas Tutup Botol: Rp <span x-text="new Intl.NumberFormat('id-ID').format(tutupBotol)"></span>)
                    </span>
                </div>
                <div class="text-4xl">💰</div>
            </div>

            <!-- TOMBOL SUBMIT -->
            <button type="submit" 
                    :disabled="!isFormValid"
                    :class="!isFormValid ? 'opacity-30 cursor-not-allowed bg-slate-300' : 'bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-500/20'"
                    class="w-full py-5 text-white font-black text-xs uppercase tracking-[0.3em] rounded-2xl transition-all transform active:scale-95">
                Konfirmasi & Simpan Penjualan
            </button>
        </form>
    </div>
</div>