<!-- views/admin/kas_manual/index.php -->
<div class="max-w-7xl mx-auto space-y-6 pb-12">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KAS<span class="text-emerald-500">MANUAL</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Input Pemasukan & Pengeluaran Lain-lain</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-4 rounded-r-xl font-bold text-sm shadow-sm">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-xl font-bold text-sm shadow-sm">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">
        <!-- KIRI: FORM -->
        <div class="lg:col-span-1">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm sticky top-6">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h3 class="font-black text-slate-800 uppercase italic">Catat Transaksi Baru</h3>
                </div>

                <!-- URL DISESUAIKAN -->
                <form action="<?= BASE_URL ?>/kas/store" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Jenis Arus Kas</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="jenis" value="pemasukan" class="peer sr-only" required>
                                <div class="px-4 py-4 bg-white border-2 border-slate-200 rounded-2xl text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                    <span class="block text-xl mb-1">📥</span>
                                    <span class="text-[10px] font-black uppercase text-slate-600 peer-checked:text-emerald-700">Pemasukan</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="jenis" value="pengeluaran" class="peer sr-only" required>
                                <div class="px-4 py-4 bg-white border-2 border-slate-200 rounded-2xl text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                    <span class="block text-xl mb-1">📤</span>
                                    <span class="text-[10px] font-black uppercase text-slate-600 peer-checked:text-red-700">Pengeluaran</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Nominal (Rp)</label>
                        <input type="number" name="nominal" placeholder="Contoh: 50000" min="1" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-xl font-black text-slate-900 focus:ring-2 focus:ring-emerald-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Uraian / Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Contoh: Beli karung 10 lembar..." required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-emerald-400 outline-none resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-transform active:scale-95">
                        💾 Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>

        <!-- KANAN: TABEL -->
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h3 class="font-black text-slate-800 uppercase italic">Riwayat Catatan Manual</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[9px] uppercase font-black text-slate-400 tracking-widest">
                                <th class="px-4 py-3 rounded-l-xl">Tanggal</th>
                                <th class="px-4 py-3">Uraian</th>
                                <th class="px-4 py-3 text-right">Nominal</th>
                                <th class="px-4 py-3 text-center rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-100">
                            <?php if(empty($data_kas)): ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-slate-400 font-bold italic">Belum ada catatan kas manual.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($data_kas as $k): ?>
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-4 py-4 font-bold text-slate-500 whitespace-nowrap">
                                            <?= date('d/m/Y', strtotime($k['tanggal'])) ?>
                                        </td>
                                        <td class="px-4 py-4">
                                            <p class="font-black text-slate-800 uppercase"><?= htmlspecialchars($k['keterangan']) ?></p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5 tracking-widest">Oleh: <?= htmlspecialchars($k['admin_nama']) ?></p>
                                        </td>
                                        <td class="px-4 py-4 text-right font-black <?= $k['jenis'] == 'pemasukan' ? 'text-emerald-600' : 'text-red-600' ?>">
                                            <?= $k['jenis'] == 'pemasukan' ? '+' : '-' ?> Rp <?= number_format($k['nominal'], 0, ',', '.') ?>
                                            <span class="block text-[8px] opacity-70 mt-1"><?= strtoupper($k['jenis']) ?></span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <!-- URL DISESUAIKAN -->
                                            <a href="<?= BASE_URL ?>/kas/delete?id=<?= $k['id'] ?>" onclick="return confirm('Yakin ingin menghapus catatan ini? Saldo Buku Kas akan menyesuaikan otomatis.')" class="opacity-0 group-hover:opacity-100 transition-opacity inline-block px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-red-200">
                                                Hapus
                                            </a>
                                        </td>
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