<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">SETORAN<span class="text-emerald-500">MASSAL</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Input Tabungan Kolektif Per Kelas</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/setoran/siswa_kelas" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">1. Pilih Kelas</label>
                <select name="kelas_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($all_kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($kelas_id == $k['id']) ? 'selected' : '' ?>>
                            Kelas <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">
                    Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    <?php if($kelas_id && !empty($siswa_list)): ?>
    <form action="<?= BASE_URL ?>/setoran/siswa_batch_store" method="POST" class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        
        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Daftar Siswa & Input (Pcs)</h3>
                
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-2 ml-1">2. Pilih Jenis Sampah Kolektif</label>
                    <select name="kategori_id" required class="w-full px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Pilih Jenis Sampah --</option>
                        <?php foreach($all_sampah as $kat): ?>
                            <option value="<?= $kat['id'] ?>">
                                ♻️ <?= htmlspecialchars($kat['nama_sampah']) ?> (Rp<?= number_format($kat['harga_dasar'], 0, ',', '.') ?>/Pcs)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-5 w-16 text-center">No</th>
                        <th class="px-8 py-5">Nama Siswa</th>
                        <th class="px-8 py-5 text-right w-64">Jumlah Setor (Pcs)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $no=1; foreach($siswa_list as $s): ?>
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-8 py-4 text-xs font-bold text-slate-400 text-center"><?= $no++ ?></td>
                        <td class="px-8 py-4 font-black text-slate-800 text-sm uppercase italic tracking-tighter">
                            <?= htmlspecialchars($s['nama']) ?>
                        </td>
                        <td class="px-8 py-4">
                            <div class="relative">
                                <input type="number" name="berat[<?= $s['id'] ?>]" min="0" placeholder="0" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-right font-black focus:ring-2 focus:ring-emerald-500 outline-none pr-12 transition-all">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400 uppercase">Pcs</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-10 py-4 bg-emerald-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-700 transition-all transform active:scale-95">
                💾 Simpan Setoran Massal
            </button>
        </div>
    </form>
    
    <?php elseif($kelas_id && empty($siswa_list)): ?>
        <div class="p-16 text-center bg-white rounded-[3rem] border border-dashed border-slate-300">
            <span class="text-4xl mb-4 block">📭</span>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada siswa aktif di kelas ini.</p>
        </div>
    <?php endif; ?>
</div>