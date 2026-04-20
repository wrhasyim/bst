<div class="max-w-6xl mx-auto space-y-8 pb-10" x-data="{ bst: <?= $data['persen_kas_bst'] ?? 0 ?>, sekolah: <?= $data['persen_kas_sekolah'] ?? 0 ?>, pengelola: <?= $data['persen_honor_pengelola'] ?? 0 ?>, wali: <?= $data['persen_honor_walikelas'] ?? 0 ?>, get total() { return parseFloat(this.bst) + parseFloat(this.sekolah) + parseFloat(this.pengelola) + parseFloat(this.wali) } }">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">PUSAT<span class="text-emerald-500">PENGATURAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Identitas & Kebijakan Distribusi Honor</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-xs font-bold animate-pulse flex items-center shadow-sm">
            ✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/pengaturan/update_identitas" method="POST" class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
                <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-wider border-b pb-4">🏢 Identitas Aplikasi</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Web/Sekolah</label>
                        <input type="text" name="nama_web" value="<?= $data['nama_web'] ?? '' ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Alamat Lengkap</label>
                        <textarea name="alamat_web" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none"><?= $data['alamat_web'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex flex-col justify-between">
                <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-wider border-b pb-4 mb-6">💰 Distribusi Margin (Wajib 100%)</h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Kas Bank Sampah (%)</label>
                        <input type="number" name="persen_kas_bst" x-model="bst" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-2xl font-black outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Kas Sekolah (%)</label>
                        <input type="number" name="persen_kas_sekolah" x-model="sekolah" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-2xl font-black outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Honor Pengelola (%)</label>
                        <input type="number" name="persen_honor_pengelola" x-model="pengelola" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-2xl font-black outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Honor Wali Kelas (%)</label>
                        <input type="number" name="persen_honor_walikelas" x-model="wali" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-2xl font-black outline-none">
                    </div>
                </div>

                <div class="mt-8 p-6 rounded-3xl border-2 flex items-center justify-between transition-all" :class="total == 100 ? 'border-emerald-500 bg-emerald-50' : 'border-red-500 bg-red-50'">
                    <h4 class="text-2xl font-black" :class="total == 100 ? 'text-emerald-700' : 'text-red-700'">Total: <span x-text="total"></span>%</h4>
                    <button type="submit" :disabled="total != 100" :class="total == 100 ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20' : 'bg-slate-300 cursor-not-allowed'" class="px-8 py-3 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl transition-all transform active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>