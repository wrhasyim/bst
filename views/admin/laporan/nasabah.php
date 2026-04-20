<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">BUKU<span class="text-emerald-500">TABUNGAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Laporan Aktivitas Nasabah Individu</p>
        </div>
    </div>

    <div class="no-print bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/laporan/nasabah" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Nama Siswa / Nasabah</label>
                <select name="user_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">-- Cari Nasabah --</option>
                    <?php foreach($siswa_list as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $s['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nama']) ?> (Kelas <?= $s['nama_kelas'] ?? 'Alumni' ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto px-10 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">Buka Buku</button>
        </form>
    </div>

    <?php if(isset($user_id) && $detail_siswa): ?>
        <div class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm print:border-none print:p-0">
            <div class="flex justify-between items-start border-b-4 border-slate-900 pb-8 mb-8">
                <div>
                    <h1 class="text-2xl font-black uppercase italic tracking-tighter text-slate-900">BUKU TABUNGAN DIGITAL</h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Bank Sampah TKM (BST SYSTEM)</p>
                </div>
                <div class="text-right">
                    <div class="px-6 py-4 bg-emerald-600 rounded-2xl text-white">
                        <p class="text-[9px] font-black uppercase opacity-70 mb-1">Total Saldo Bersih</p>
                        <p class="text-2xl font-black">Rp<?= number_format($total_saldo, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10 text-[11px] font-bold uppercase tracking-widest text-slate-500">
                <div><p class="mb-1 opacity-50">Nama Nasabah</p><p class="text-slate-900 text-sm font-black"><?= htmlspecialchars($detail_siswa['nama']) ?></p></div>
                <div><p class="mb-1 opacity-50">Username</p><p class="text-slate-900 text-sm font-black">@<?= htmlspecialchars($detail_siswa['username']) ?></p></div>
                <div><p class="mb-1 opacity-50">Kelas / Angkatan</p><p class="text-slate-900 text-sm font-black"><?= $detail_siswa['nama_kelas'] ?? '-' ?> / <?= $detail_siswa['angkatan'] ?? '-' ?></p></div>
                <div><p class="mb-1 opacity-50">Status Akun</p><p class="text-emerald-500 text-sm font-black italic"><?= $detail_siswa['is_active'] ? 'AKTIF' : 'NON-AKTIF' ?></p></div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-y-2 border-slate-100 text-[9px] uppercase font-black text-slate-400 tracking-widest">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Keterangan Transaksi</th>
                            <th class="px-6 py-4 text-center">Volume</th>
                            <th class="px-6 py-4 text-right">Debit / Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach($riwayat as $r): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-[10px] font-bold text-slate-500"><?= date('d/m/Y | H:i', strtotime($r['created_at'])) ?></td>
                            <td class="px-6 py-4 font-black text-slate-800 uppercase italic text-xs"><?= htmlspecialchars($r['nama_sampah']) ?></td>
                            <td class="px-6 py-4 text-center text-xs font-bold text-slate-700"><?= number_format($r['berat'], 0) ?> Pcs</td>
                            <td class="px-6 py-4 text-right text-sm font-black text-emerald-600">+ Rp<?= number_format($r['total_harga'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>
            </div>

            <div class="mt-10 no-print flex justify-end">
                <button onclick="window.print()" class="px-8 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-black transition-all">🖨️ Cetak Buku</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<style> @media print { .no-print { display: none !important; } body { background: white !important; } main { padding: 0 !important; } } </style>