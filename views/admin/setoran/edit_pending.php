<div class="max-w-3xl mx-auto space-y-8 pb-10">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KOREKSI<span class="text-amber-500">DATA</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Edit Setoran Sebelum Validasi</p>
        </div>
        <a href="<?= BASE_URL ?>/setoran/validasi" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all">
            🔙 Kembali
        </a>
    </div>

    <form action="<?= BASE_URL ?>/setoran/update_pending/<?= $setoran['id'] ?>" method="POST" class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm space-y-6">
        
        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl mb-6 flex items-center">
            <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl mr-4">
                👤
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Nasabah Terkait</p>
                <p class="font-black text-slate-800 uppercase italic tracking-tighter">
                    Setoran ID #<?= $setoran['id'] ?>
                </p>
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Kategori Sampah</label>
            <select name="kategori_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                <?php foreach($sampah as $kat): ?>
                    <option value="<?= $kat['id'] ?>" <?= ($setoran['kategori_id'] == $kat['id']) ? 'selected' : '' ?>>
                        ♻️ <?= htmlspecialchars($kat['nama_sampah']) ?> (Rp<?= number_format($kat['harga_dasar'], 0, ',', '.') ?>/Pcs)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Jumlah Disetor (Pcs)</label>
            <div class="relative">
                <input type="number" name="berat" value="<?= number_format($setoran['berat'], 0, '', '') ?>" required min="1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xl font-black text-slate-800 focus:ring-2 focus:ring-amber-500 outline-none transition-all">
                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 font-black uppercase tracking-widest">Pcs</span>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 mt-6">
            <button type="submit" class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-amber-500/20 transition-all transform active:scale-95">
                💾 Simpan Koreksi Data
            </button>
            <p class="text-[9px] text-center text-slate-400 font-bold uppercase mt-3 italic">*Setelah disimpan, Anda bisa memvalidasi data ini di menu Validasi.</p>
        </div>
    </form>

</div>