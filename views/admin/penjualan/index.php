<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">TRANSAKSI<span class="text-emerald-500">PENGEPUL</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Riwayat Penjualan & Pencairan Kas Tunai</p>
        </div>
        <a href="<?= BASE_URL ?>/penjualan/create" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center">
            <span class="mr-2 text-lg">🚛</span> Jual Barang Gudang
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm">🚨 <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-5">Tanggal Jual</th>
                        <th class="px-8 py-5">Rincian Barang Terjual</th>
                        <th class="px-8 py-5 text-right">Total Pendapatan Kotor</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($penjualan_grup)): ?>
                        <tr><td colspan="4" class="px-8 py-12 text-center text-slate-400 text-xs italic">Belum ada riwayat penjualan pengepul.</td></tr>
                    <?php else: ?>
                        <?php foreach($penjualan_grup as $key => $grup): ?>
                        <tr class="hover:bg-slate-50 transition-all">
                            <!-- Kolom Tanggal -->
                            <td class="px-8 py-5 text-[11px] font-bold text-slate-500 uppercase align-top">
                                <?= date('d M Y | H:i', strtotime($grup['tanggal'])) ?>
                            </td>
                            
                            <!-- Kolom Rincian Sampah Tergabung -->
                            <td class="px-8 py-5 align-top">
                                <div class="font-black text-slate-800 text-xs uppercase tracking-widest mb-2">
                                    Ket: <?= htmlspecialchars($grup['keterangan'] ?: 'Penjualan Gabungan') ?>
                                </div>
                                <ul class="list-disc list-inside text-[11px] font-bold text-slate-500 space-y-1">
                                    <?php foreach($grup['rincian'] as $item): ?>
                                        <li><?= htmlspecialchars($item) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            
                            <!-- Kolom Uang/Pendapatan -->
                            <td class="px-8 py-5 text-right align-top">
                                <div class="font-black text-emerald-600 text-lg">
                                    Rp<?= number_format($grup['total_pendapatan'], 0, ',', '.') ?>
                                </div>
                                
                                <?php if($grup['total_kas_tutup_botol'] > 0): ?>
                                    <div class="text-[9px] font-bold text-amber-500 uppercase tracking-widest mt-1">
                                        + Tutup Botol: Rp<?= number_format($grup['total_kas_tutup_botol'], 0, ',', '.') ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom Aksi Hapus Masal -->
                            <td class="px-8 py-5 text-right align-top">
                                <form action="<?= BASE_URL ?>/penjualan/delete" method="POST" onsubmit="return confirm('Peringatan: Aksi ini akan membatalkan SEMUA barang pada riwayat penjualan di waktu ini. Lanjutkan?');">
                                    <?= Security::csrf_field(); ?>
                                    <!-- Mengirim kumpulan ID yang dipisahkan dengan koma -->
                                    <input type="hidden" name="id" value="<?= implode(',', $grup['raw_ids']) ?>">
                                    
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Batalkan Semua Penjualan Ini">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>