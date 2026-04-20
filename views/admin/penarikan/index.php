<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KASIR<span class="text-red-500">MASSAL</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Penarikan Saldo Kolektif Per Kelas</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?><div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?><div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm">⚠️ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>

    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/penarikan" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Kelas</label>
                <select name="kelas_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($all_kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>>Kelas <?= $k['nama_kelas'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto px-10 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">Tampilkan Siswa</button>
        </form>
    </div>

    <?php if(!empty($siswa_list)): ?>
    <form action="<?= BASE_URL ?>/penarikan/batch_store" method="POST" class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <input type="hidden" name="kelas_id" value="<?= $_GET['kelas_id'] ?>">
        
        <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center">
            <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Daftar Saldo Nasabah</h3>
            <div class="w-1/3">
                <input type="text" name="keterangan" placeholder="Keterangan (Contoh: Cair Semester 1)" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-red-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-5">Nama Siswa</th>
                        <th class="px-8 py-5 text-right">Saldo Tersedia</th>
                        <th class="px-8 py-5 text-right w-64">Jumlah Tarik (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach($siswa_list as $s): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-8 py-4 font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($s['nama']) ?></td>
                        <td class="px-8 py-4 text-right font-bold text-emerald-600 text-sm">Rp<?= number_format($s['saldo_tersedia'], 0, ',', '.') ?></td>
                        <td class="px-8 py-4">
                            <input type="number" name="jumlah_tarik[<?= $s['id'] ?>]" max="<?= $s['saldo_tersedia'] ?>" placeholder="0" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-right font-black focus:ring-2 focus:ring-red-500 outline-none pr-4 transition-all">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="submit" onclick="return confirm('Proses penarikan massal? Pastikan nominal benar.')" class="px-10 py-4 bg-red-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-red-200 hover:bg-red-700 transition-all">
                💾 Simpan Penarikan Massal
            </button>
        </div>
    </form>
    <?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 font-black text-slate-800 text-[10px] uppercase tracking-widest">Aktivitas Penarikan Terakhir</div>
        <table class="w-full text-left">
            <tbody class="divide-y divide-gray-50">
                <?php foreach(array_slice($riwayat, 0, 5) as $r): ?>
                <tr>
                    <td class="px-8 py-4 text-[10px] font-bold text-slate-400"><?= date('d/m/Y', strtotime($r['tanggal_tarik'])) ?></td>
                    <td class="px-8 py-4 font-bold text-slate-700 uppercase italic text-xs"><?= htmlspecialchars($r['nama']) ?> (<?= $r['nama_kelas'] ?>)</td>
                    <td class="px-8 py-4 text-right font-black text-red-600">- Rp<?= number_format($r['jumlah'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>