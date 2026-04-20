<div class="max-w-6xl mx-auto space-y-8">
    <div class="no-print space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">BST<span class="text-emerald-500">REKAP</span></h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Laporan Kolektif Tabungan Siswa</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
            <form action="<?= BASE_URL ?>/laporan/setoran" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Kelas Untuk Direkap</label>
                    <select name="kelas_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        <option value="">-- Pilih Daftar Kelas --</option>
                        <?php foreach($kelas_list as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                                🏫 Kelas <?= htmlspecialchars($k['nama_kelas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto px-10 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">
                    Tampilkan Data
                </button>
            </form>
        </div>
    </div>

    <?php if($kelas_id): ?>
        <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:shadow-none print:border-none print:p-0">
            <div class="text-center border-b-4 border-slate-900 pb-6 mb-8">
                <h1 class="text-3xl font-black uppercase italic tracking-tighter text-slate-900">REKAPITULASI TABUNGAN NASABAH</h1>
                <div class="flex justify-center gap-4 mt-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Kombinasi: Kelas <?= htmlspecialchars($nama_kelas_aktif) ?></span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">|</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Periode: <?= date('F Y') ?></span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2 border-slate-900 text-[10px] uppercase font-black tracking-widest">
                            <th class="py-4 w-12 text-center">No</th>
                            <th class="py-4">Nama Lengkap Siswa</th>
                            <th class="py-4 text-center">Total Item (Pcs)</th>
                            <th class="py-4 text-right">Saldo Tabungan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(empty($data_rekap)): ?>
                            <tr><td colspan="4" class="py-10 text-center text-slate-400 italic">Tidak ada data siswa ditemukan di kelas ini.</td></tr>
                        <?php else: ?>
                            <?php $no=1; foreach($data_rekap as $d): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 text-[10px] font-bold text-slate-400 text-center"><?= $no++ ?></td>
                                <td class="py-4 text-sm font-black text-slate-800 uppercase italic tracking-tighter">
                                    <?= htmlspecialchars($d['nama']) ?>
                                </td>
                                <td class="py-4 text-center text-sm font-bold text-slate-700">
                                    <?= number_format($d['total_pcs'], 0) ?> <span class="text-[9px] text-slate-400">PCS</span>
                                </td>
                                <td class="py-4 text-right text-sm font-black text-emerald-600">
                                    Rp<?= number_format($d['total_rp'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-20 hidden print:grid grid-cols-2 text-center text-[10px] font-black uppercase tracking-[0.2em]">
                <div>
                    <p class="mb-20">Mengetahui,<br>Kepala Unit Bank Sampah</p>
                    <p class="border-t border-slate-900 inline-block px-10 pt-2">( ............................................ )</p>
                </div>
                <div>
                    <p class="mb-20">Dicetak Pada: <?= date('d M Y') ?><br>Petugas Operasional</p>
                    <p class="border-t border-slate-900 inline-block px-10 pt-2">( <?= $_SESSION['nama'] ?> )</p>
                </div>
            </div>

            <div class="mt-10 no-print flex justify-end">
                <button onclick="window.print()" class="px-8 py-3 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">
                    🖨️ Cetak Laporan Kelas
                </button>
            </div>
        </div>
    <?php elseif(isset($_GET['kelas_id'])): ?>
        <div class="p-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-300">
            <span class="text-4xl block mb-4">🏫</span>
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Silakan pilih kelas terlebih dahulu untuk melihat rekapitulasi.</p>
        </div>
    <?php endif; ?>
</div>

<style>
    @media print { 
        .no-print { display: none !important; } 
        body { background: white !important; }
        main { padding: 0 !important; }
    }
</style>