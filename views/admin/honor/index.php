<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">HONOR<span class="text-emerald-500">WALIKELAS</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Manajemen Insentif Wali Kelas & Pengelola</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-xs font-bold shadow-sm animate-pulse">
            ✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-black text-slate-800 text-[10px] uppercase tracking-widest">Daftar Akumulasi Honor Wali Kelas</h3>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-[9px] uppercase text-slate-400 font-black tracking-widest">
                            <th class="px-6 py-4">Wali Kelas</th>
                            <th class="px-6 py-4 text-right">Total Jatah</th>
                            <th class="px-6 py-4 text-right">Sudah Cair</th>
                            <th class="px-6 py-4 text-right">Sisa Saldo</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach($data_honor as $row): ?>
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-800"><?= htmlspecialchars($row['nama_guru']) ?></div>
                                <div class="text-[9px] font-bold text-emerald-600 uppercase italic">Kelas: <?= htmlspecialchars($row['nama_kelas']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-bold text-slate-400">Rp<?= number_format($row['total_jatah'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-right text-xs font-bold text-red-400">Rp<?= number_format($row['sudah_cair'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-right text-sm font-black text-slate-800">Rp<?= number_format($row['sisa_honor'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if($row['sisa_honor'] > 0): ?>
                                <form action="<?= BASE_URL ?>/honor/cairkan" method="POST">
                                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                    <input type="hidden" name="nama_kelas" value="<?= $row['nama_kelas'] ?>">
                                    <input type="hidden" name="jumlah" value="<?= $row['sisa_honor'] ?>">
                                    <button type="submit" onclick="return confirm('Cairkan honor sekarang?')" class="px-4 py-1.5 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                                        Cairkan
                                    </button>
                                </form>
                                <?php else: ?>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic">Lunas</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl text-white">
                <h3 class="font-black text-emerald-400 text-[10px] uppercase tracking-widest border-b border-slate-800 pb-4 mb-6">Riwayat Pencairan Terakhir</h3>
                <div class="space-y-6">
                    <?php if(empty($riwayat)): ?>
                        <p class="text-xs text-slate-500 italic">Belum ada pencairan.</p>
                    <?php else: ?>
                        <?php foreach($riwayat as $r): ?>
                        <div class="border-l-2 border-emerald-500 pl-4 space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= date('d M Y', strtotime($r['tanggal_cair'])) ?></p>
                            <p class="text-xs font-bold"><?= htmlspecialchars($r['nama']) ?></p>
                            <p class="text-sm font-black text-emerald-400">+ Rp<?= number_format($r['jumlah'], 0, ',', '.') ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>