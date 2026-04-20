<div class="max-w-4xl mx-auto space-y-8 pb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KENAIKAN<span class="text-emerald-500">KELAS</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Pindah Kelas Massal Akhir Tahun Ajaran</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm">⚠️ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden p-10">
        <form action="<?= BASE_URL ?>/akademik/proses_kenaikan" method="POST" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Dari Kelas (Asal)</label>
                    <select name="kelas_asal" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">-- Pilih Kelas Saat Ini --</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>">Kelas <?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="hidden md:flex justify-center text-2xl opacity-30">➡️</div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest ml-1">Ke Kelas (Tujuan)</label>
                    <select name="kelas_tujuan" required class="w-full px-5 py-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none text-emerald-800">
                        <option value="">-- Pilih Kelas Baru --</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>">Kelas <?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <div class="p-6 bg-slate-900 rounded-[2rem] text-white flex items-start gap-4">
                <span class="text-2xl">⚠️</span>
                <p class="text-[10px] font-medium leading-relaxed opacity-80 uppercase tracking-wider">
                    Peringatan: Seluruh siswa di kelas asal akan dipindahkan ke kelas tujuan dalam satu kali klik. Pastikan data sudah benar sebelum memproses.
                </p>
            </div>

            <button type="submit" onclick="return confirm('Proses kenaikan kelas massal?')" class="w-full py-5 bg-slate-900 text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-black transition-all shadow-xl">
                Konfirmasi Kenaikan Kelas
            </button>
        </form>
    </div>
</div>