<div class="max-w-7xl mx-auto space-y-8 pb-10" 
     x-data="{ 
         guruId: '',
         jumlahTarik: '',
         daftarGuru: <?= htmlspecialchars(json_encode($guru_list)) ?>,
         get detailGuru() {
             return this.daftarGuru.find(g => g.id == this.guruId) || null;
         }
     }">
     
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">PENARIKAN<span class="text-blue-500">GURU</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Pencairan Saldo Tabungan Staf & Guru</p>
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
        <!-- FORM PENARIKAN -->
        <div class="lg:col-span-1">
            <form action="<?= BASE_URL ?>/penarikan/guru_store" method="POST" class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm space-y-6 border-t-4 border-t-blue-500">
                
                <?= Security::csrf_field(); ?>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Guru / Staf</label>
                    <select name="user_id" x-model="guruId" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <option value="">-- Pilih Nama --</option>
                        <?php foreach($guru_list as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tampilan Info Saldo Aktif (Muncul otomatis saat nama dipilih) -->
                <div x-show="detailGuru" x-transition class="p-4 bg-blue-50 rounded-2xl border border-blue-100 flex flex-col items-center justify-center">
                    <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-1">Saldo Tersedia</span>
                    <span class="text-2xl font-black text-blue-700">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(detailGuru ? detailGuru.saldo_tersedia : 0)"></span>
                    </span>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nominal Tarik (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                        <input type="number" name="jumlah" x-model.number="jumlahTarik" required min="1" x-bind:max="detailGuru ? detailGuru.saldo_tersedia : 0" placeholder="0" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xl font-black focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <p class="text-[9px] text-slate-400 mt-2 ml-1 italic">*Nominal tidak boleh melebihi saldo tersedia.</p>
                </div>

                <button type="submit" x-bind:disabled="!detailGuru || jumlahTarik <= 0 || jumlahTarik > detailGuru.saldo_tersedia" class="w-full py-4 bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    💸 Cairkan Dana
                </button>
            </form>
        </div>

        <!-- TABEL RIWAYAT -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden h-full flex flex-col">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30 font-black text-slate-800 text-[10px] uppercase tracking-widest italic underline">Riwayat Penarikan Staf (5 Terakhir)</div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[9px] uppercase text-slate-400 font-black tracking-widest">
                                <th class="px-6 py-5">Tanggal</th>
                                <th class="px-6 py-5">Nama Guru / Staf</th>
                                <th class="px-6 py-5">Keterangan</th>
                                <th class="px-6 py-5 text-right">Nominal Pencairan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(empty($riwayat)): ?>
                                <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 text-xs italic">Belum ada riwayat penarikan dana staf.</td></tr>
                            <?php else: ?>
                                <?php foreach($riwayat as $r): ?>
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="px-6 py-4 text-[10px] font-bold text-slate-500"><?= date('d/m/Y H:i', strtotime($r['tanggal_tarik'])) ?></td>
                                    <td class="px-6 py-4 font-black text-slate-800 text-xs uppercase italic tracking-tighter"><?= htmlspecialchars($r['nama']) ?></td>
                                    <td class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase"><?= htmlspecialchars($r['keterangan']) ?></td>
                                    <td class="px-6 py-4 text-right font-black text-red-500">- Rp<?= number_format($r['jumlah'], 0, ',', '.') ?></td>
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