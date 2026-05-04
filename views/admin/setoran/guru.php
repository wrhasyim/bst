<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">SETORAN<span class="text-blue-500">GURU</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Transaksi Tabungan Nasabah Internal (Staf/Guru)</p>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg flex items-center shadow-sm">
            <span class="text-emerald-500 mr-3 text-lg">✅</span>
            <p class="text-sm font-bold text-emerald-800"><?= $_SESSION['success'] ?></p>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg flex items-center shadow-sm">
            <span class="text-red-500 mr-3 text-lg">🚨</span>
            <p class="text-sm font-bold text-red-800"><?= $_SESSION['error'] ?></p>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <form action="<?= BASE_URL ?>/setoran/guru_store" method="POST" class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm space-y-6 border-t-4 border-t-blue-500">
                
                <?= Security::csrf_field(); ?>

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
                        <?php foreach($all_sampah as $kat): 
                            $harga_tampil = (!empty($kat['harga_guru']) && $kat['harga_guru'] > 0) ? $kat['harga_guru'] : $kat['harga_dasar'];
                        ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_sampah']) ?> (Rp<?= number_format($harga_tampil, 0, ',', '.') ?>/Pcs)</option>
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
            <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden h-full flex flex-col">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30 font-black text-slate-800 text-[10px] uppercase tracking-widest italic underline">Aktivitas Staf</div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[9px] uppercase text-slate-400 font-black tracking-widest">
                                <th class="px-6 py-5">Tanggal</th>
                                <th class="px-6 py-5">Nama</th>
                                <th class="px-6 py-5">Barang</th>
                                <th class="px-6 py-5 text-right">Rp Nasabah</th>
                                <th class="px-6 py-5 text-center">Status</th> <!-- 🛠️ KOLOM BARU -->
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(empty($setoran)): ?>
                                <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 text-xs italic">Belum ada riwayat setoran guru.</td></tr>
                            <?php else: ?>
                                <?php foreach($setoran as $s): ?>
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="px-6 py-4 text-[10px] font-bold text-slate-500"><?= date('d/m/y', strtotime($s['created_at'])) ?></td>
                                    <td class="px-6 py-4 font-black text-slate-800 text-xs uppercase italic tracking-tighter"><?= htmlspecialchars($s['nama_guru'] ?? $s['nama']) ?></td>
                                    <td class="px-6 py-4"><div class="font-bold text-slate-600 text-xs"><?= htmlspecialchars($s['nama_sampah']) ?></div><div class="text-[9px] font-black text-slate-400"><?= number_format($s['berat'], 0) ?> PCS</div></td>
                                    <td class="px-6 py-4 text-right font-black text-blue-600">Rp<?= number_format($s['total_harga'], 0, ',', '.') ?></td>
                                    
                                    <!-- 🛠️ BADGE STATUS BARU -->
                                    <td class="px-6 py-4 text-center">
                                        <?php if($s['status'] === 'valid'): ?>
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded text-[9px] font-black uppercase tracking-widest">Valid</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded text-[9px] font-black uppercase tracking-widest animate-pulse">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(!empty($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total: <?= $pagination['total_data'] ?> Data</p>
                    <div class="flex gap-2">
                        <?php for($i=1; $i<=$pagination['total_pages']; $i++): ?>
                            <a href="?page=<?= $i ?>" class="w-8 h-8 flex items-center justify-center text-xs font-black rounded-lg transition-all <?= ($i == $pagination['current_page']) ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>