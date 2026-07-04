<div class="max-w-7xl mx-auto space-y-6 pb-12">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KAS<span class="text-red-500">KESISWAAN</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Rekapitulasi Denda Botol & Hukuman Disiplin</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-10 text-8xl">🛡️</div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Saldo Bisa Ditarik (OSIS)</p>
                <h3 class="text-4xl font-black text-emerald-400">Rp <?= number_format($data['saldo_aktif'], 0, ',', '.') ?></h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">📥</div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Akumulasi Uang Denda</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 ml-14">Rp <?= number_format($data['total_uang_masuk'], 0, ',', '.') ?></h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl">⚖️</div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Botol Terkumpul</p>
            </div>
            <h3 class="text-2xl font-black text-slate-800 ml-14"><?= number_format($data['total_botol_pcs'], 0, ',', '.') ?> <span class="text-sm font-bold text-slate-400">Pcs</span></h3>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm mt-8 relative">
        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-black text-slate-800 uppercase italic">Riwayat Mutasi Kesiswaan</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1">Catatan anak terlambat dan penarikan dana oleh OSIS.</p>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="fixed top-24 right-8 z-50 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-xl rounded-r-xl flex items-center animate-in fade-in slide-in-from-right-8 duration-300">
                    <span class="mr-3">✅</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="fixed top-24 right-8 z-50 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-xl rounded-r-xl flex items-center animate-in fade-in slide-in-from-right-8 duration-300">
                    <span class="mr-3">⚠️</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div x-data="{ openTarikKesiswaan: false }">
                <div class="flex gap-2">
                    <a href="<?= BASE_URL ?>/setoran/create_kesiswaan" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-colors shadow-sm">
                        + Input Denda
                    </a>
                    <button @click="openTarikKesiswaan = true" type="button" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-sm">
                        💸 Tarik Saldo
                    </button>
                </div>

                <div x-show="openTarikKesiswaan" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4" x-transition.opacity>
                    <div @click.away="openTarikKesiswaan = false" class="bg-white rounded-[2rem] p-8 w-full max-w-md shadow-2xl transform transition-all" x-transition>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-black text-slate-800 text-xl italic uppercase tracking-tight">Tarik Kas<span class="text-red-500">Kesiswaan</span></h3>
                            <button @click="openTarikKesiswaan = false" type="button" class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <form action="<?= BASE_URL ?>/penarikan/kesiswaan_store" method="POST">
                            <?= Security::csrf_field(); ?>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Jumlah Penarikan (Rp)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400">Rp</span>
                                        <input type="number" name="jumlah" required min="1" max="<?= $data['saldo_aktif'] ?>" class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="Contoh: 150000">
                                    </div>
                                    <p class="text-[9px] text-emerald-600 font-bold mt-1.5 ml-1">Saldo Tersedia: Rp <?= number_format($data['saldo_aktif'], 0, ',', '.') ?></p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Keterangan / Tujuan Dana</label>
                                    <input type="text" name="keterangan" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="Contoh: Pembelian hadiah lomba">
                                </div>
                            </div>
                            
                            <div class="mt-8 flex gap-3">
                                <button type="button" @click="openTarikKesiswaan = false" class="flex-1 px-4 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
                                <button type="submit" class="flex-1 px-4 py-3.5 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition-colors transform active:scale-95">Proses Penarikan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[9px] uppercase font-black text-slate-400 tracking-widest">
                        <th class="px-4 py-3 rounded-l-xl">Tanggal</th>
                        <th class="px-4 py-3">Transaksi / Keterangan</th>
                        <th class="px-4 py-3 text-center">Volume</th>
                        <th class="px-4 py-3 text-right rounded-r-xl">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    <?php if(empty($data['mutasi'])): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-slate-400 font-bold italic">Belum ada riwayat denda botol kesiswaan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['mutasi'] as $m): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 font-bold text-slate-500 whitespace-nowrap">
                                    <?= date('d M Y, H:i', strtotime($m['tanggal'])) ?>
                                </td>
                                <td class="px-4 py-4">
                                    <?php if($m['tipe'] == 'setoran'): ?>
                                        <p class="font-black text-slate-800 uppercase">HUKUMAN: <?= htmlspecialchars($m['jenis_botol']) ?></p>
                                        <p class="text-[9px] font-bold text-red-500 uppercase mt-0.5 tracking-widest">Oleh: <?= htmlspecialchars($m['ket'] ?? 'Siswa Tidak Diketahui') ?></p>
                                    <?php else: ?>
                                        <p class="font-black text-emerald-700 uppercase">PENARIKAN DANA OSIS</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5 tracking-widest">Ket: <?= htmlspecialchars($m['ket']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4 text-center font-bold text-slate-600">
                                    <?= $m['qty'] > 0 ? $m['qty'] . ' Pcs' : '-' ?>
                                </td>
                                <td class="px-4 py-4 text-right font-black <?= $m['tipe'] == 'setoran' ? 'text-emerald-600' : 'text-red-600' ?>">
                                    <?= $m['tipe'] == 'setoran' ? '+' : '-' ?> <?= number_format($m['jumlah'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>