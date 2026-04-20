<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">SETORAN<span class="text-blue-500">GURU</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Transaksi Tabungan Nasabah Internal (Staf/Guru)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <form action="<?= BASE_URL ?>/setoran/guru_store" method="POST" class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm space-y-6 border-t-4 border-t-blue-500">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Guru / Staf</label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- Pilih Nama --</option>
                        <?php foreach($guru_list as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Jenis Sampah</label>
                    <select name="kategori_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($all_sampah as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_sampah']) ?> (Rp<?= number_format($kat['harga_dasar'], 0) ?>/Pcs)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Jumlah (Pcs)</label>
                    <input type="number" name="berat" required min="1" placeholder="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xl font-black focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-blue-700 transition-all">💾 Simpan Setoran</button>
            </form>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden h-full">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30 font-black text-slate-800 text-[10px] uppercase tracking-widest italic underline">10 Aktivitas Terakhir Staf</div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[9px] uppercase text-slate-400 font-black tracking-widest">
                                <th class="px-8 py-5">Tanggal</th>
                                <th class="px-8 py-5">Nama</th>
                                <th class="px-8 py-5">Barang</th>
                                <th class="px-8 py-5 text-right">Rp Nasabah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(empty($setoran)): ?>
                                <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-xs italic">Belum ada riwayat setoran guru.</td></tr>
                            <?php else: ?>
                                <?php foreach($setoran as $s): ?>
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="px-8 py-4 text-[10px] font-bold text-slate-500"><?= date('d/m/y', strtotime($s['created_at'])) ?></td>
                                    <td class="px-8 py-4 font-black text-slate-800 text-xs uppercase italic tracking-tighter"><?= htmlspecialchars($s['nama_guru']) ?></td>
                                    <td class="px-8 py-4"><div class="font-bold text-slate-600 text-xs"><?= htmlspecialchars($s['nama_sampah']) ?></div><div class="text-[9px] font-black text-slate-400"><?= number_format($s['berat'], 0) ?> PCS</div></td>
                                    <td class="px-8 py-4 text-right font-black text-blue-600">Rp<?= number_format($s['total_harga'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>