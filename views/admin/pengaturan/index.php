<div class="max-w-6xl mx-auto space-y-8 pb-10" x-data="{ 
    bst: <?= $data['persen_kas_bst'] ?? 0 ?>, 
    sekolah: <?= $data['persen_kas_sekolah'] ?? 0 ?>, 
    pengelola: <?= $data['persen_honor_pengelola'] ?? 0 ?>, 
    wali: <?= $data['persen_honor_walikelas'] ?? 0 ?>, 
    piket: <?= $data['persen_honor_piket'] ?? 0 ?>, 
    get total() { return parseFloat(this.bst) + parseFloat(this.sekolah) + parseFloat(this.pengelola) + parseFloat(this.wali) + parseFloat(this.piket) } 
}">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">PUSAT<span class="text-emerald-500">PENGATURAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Identitas & Kebijakan Distribusi Honor (5 Entitas)</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-xs font-bold animate-pulse flex items-center shadow-sm">
            ✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-800 text-xs font-bold animate-pulse flex items-center shadow-sm">
            🚨 <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/pengaturan/update_identitas" method="POST" class="space-y-8">
        
        <?= Security::csrf_field(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm space-y-6">
                <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-wider border-b pb-4">🏢 Identitas Aplikasi</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Web/Sekolah</label>
                        <input type="text" name="nama_sekolah" value="<?= htmlspecialchars($data['nama_sekolah'] ?? '') ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Alamat Lengkap</label>
                        <textarea name="alamat_sekolah" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all"><?= htmlspecialchars($data['alamat_sekolah'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex flex-col justify-between">
                <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-wider border-b pb-4 mb-6">💰 Distribusi Margin (Wajib 100%)</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Kas Bank Sampah (%)</label>
                        <input type="number" step="0.1" name="persen_kas_bst" x-model="bst" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-xl font-black outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Kas Sekolah (%)</label>
                        <input type="number" step="0.1" name="persen_kas_sekolah" x-model="sekolah" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-xl font-black outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Honor Pengelola (%)</label>
                        <input type="number" step="0.1" name="persen_honor_pengelola" x-model="pengelola" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-xl font-black outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 italic underline">Honor Wali Kelas (%)</label>
                        <input type="number" step="0.1" name="persen_honor_walikelas" x-model="wali" class="w-full px-4 py-4 bg-slate-900 text-emerald-400 rounded-3xl text-xl font-black outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                    <div class="col-span-2 md:col-span-1 border-l-4 border-amber-500 pl-4">
                        <label class="block text-[10px] font-bold text-amber-500 uppercase mb-2 ml-1 italic underline">Honor Siswa Piket (%)</label>
                        <input type="number" step="0.1" name="persen_honor_piket" x-model="piket" class="w-full px-4 py-4 bg-amber-50 text-amber-600 border-2 border-amber-200 rounded-3xl text-xl font-black outline-none focus:ring-2 focus:ring-amber-500 transition-all">
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

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm mt-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-4 mb-6 gap-4">
            <div>
                <h3 class="font-black text-slate-800 text-lg uppercase italic tracking-tighter">System <span class="text-emerald-500">Updater (OTA)</span></h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Upload file Patch (.zip) untuk perbaikan/update fitur</p>
            </div>
            <div class="w-12 h-12 bg-slate-900 text-white rounded-full flex items-center justify-center text-xl shadow-lg">
                🚀
            </div>
        </div>

        <form action="<?= BASE_URL ?>/pengaturan/apply_patch" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= Security::csrf_field(); ?>

            <div class="border-4 border-dashed border-slate-100 rounded-[2rem] p-8 text-center bg-slate-50 relative group hover:border-emerald-200 transition-all">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 group-hover:text-emerald-500 transition-colors">Pilih File Patch (*.zip)</label>
                <input type="file" name="file_patch" accept=".zip" required class="block w-full max-w-sm mx-auto text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 cursor-pointer transition-all">
            </div>

            <div class="bg-amber-50 p-5 rounded-2xl border border-amber-100 flex gap-4 items-start">
                <span class="text-2xl">⚠️</span>
                <div>
                    <h4 class="text-xs font-black text-amber-800 uppercase tracking-widest mb-1">Peringatan Struktur File</h4>
                    <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wide leading-relaxed">
                        Pastikan struktur folder di dalam file ZIP persis sama dengan struktur sistem (contoh: <code>app/Controllers/...</code>). File lama dengan nama yang sama akan otomatis tertimpa (overwrite). Jangan sertakan file config.php!
                    </p>
                </div>
            </div>

            <button type="submit" onclick="return confirm('⚡ YAKIN INGIN INSTALL PATCH INI? Pastikan struktur folder ZIP sudah benar. Kesalahan dapat membuat sistem error.')" class="w-full py-5 bg-slate-900 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-black transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 transform">
                ⚡ Install Patch Sekarang
            </button>
        </form>
    </div>
</div>