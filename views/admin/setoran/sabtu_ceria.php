<!-- views/admin/setoran/sabtu_ceria.php -->
<div class="max-w-7xl mx-auto space-y-6 pb-32">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">SABTU<span class="text-amber-500">CERIA</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Pembelian Tunai Kolektif & Multi-Kategori (Beli Putus)</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm font-bold text-emerald-800">✅ <?= $_SESSION['success'] ?></p>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <p class="text-sm font-bold text-red-800">🚨 <?= $_SESSION['error'] ?></p>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/setoran/store_sabtu_ceria" method="POST">
        <?= Security::csrf_field(); ?>
        <input type="hidden" name="user_id" value="<?= $akun_sabtu_ceria['id'] ?>">

        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Input Hasil Timbangan</p>
                <h3 class="font-black text-slate-800 uppercase italic">Form Rekapitulasi Kelas (Kilogram)</h3>
                <p class="text-xs font-bold text-slate-500 mt-1">*Abaikan / biarkan kosong pada kelas dan jenis sampah yang tidak menyetor.</p>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-100/50 border-b border-slate-200 text-[10px] font-black uppercase text-slate-600 tracking-widest">
                        <tr>
                            <th class="px-6 py-5 sticky left-0 bg-slate-100/50 z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">Nama Kelas</th>
                            
                            <!-- Bikin kolom otomatis sesuai jumlah kategori sampah -->
                            <?php foreach($kategori as $k): ?>
                                <th class="px-4 py-5 text-center border-l border-slate-200">
                                    <span class="block text-slate-800 text-xs"><?= htmlspecialchars($k['nama_sampah']) ?></span>
                                    <span class="text-[9px] text-amber-600 block mt-0.5">Rp <?= number_format($k['harga_dasar'],0,',','.') ?>/Pcs</span>
                                </th>
                            <?php endforeach; ?>
                            
                            <th class="px-6 py-5 text-right sticky right-0 bg-slate-100/50 z-20 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.1)] border-l border-slate-200">Subtotal Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach($kelas_list as $kelas): ?>
                        <tr class="hover:bg-amber-50/30 transition-colors group">
                            <td class="px-6 py-3 sticky left-0 bg-white group-hover:bg-amber-50/50 z-10 font-bold text-xs text-slate-700 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                <?= htmlspecialchars($kelas['nama_kelas']) ?>
                            </td>
                            
                            <!-- Input kolom per kategori -->
                            <?php foreach($kategori as $k): ?>
                                <td class="px-4 py-3 border-l border-slate-100">
                                    <div class="relative w-24 mx-auto">
                                        <input type="number" step="0.01" min="0" name="berat_kg[<?= htmlspecialchars($kelas['nama_kelas']) ?>][<?= $k['id'] ?>]" 
                                            class="input-kg w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-black text-center text-slate-800 focus:ring-2 focus:ring-amber-500 focus:bg-white outline-none transition-all placeholder-slate-300" 
                                            placeholder="0" data-harga="<?= $k['harga_dasar'] ?>" data-konversi="<?= $k['konversi_kg'] ?>" oninput="hitungTotal()">
                                    </div>
                                </td>
                            <?php endforeach; ?>
                            
                            <!-- Kolom Subtotal Per Kelas (Otomatis JS) -->
                            <td class="px-6 py-3 text-right sticky right-0 bg-white group-hover:bg-amber-50/50 z-10 font-black text-emerald-600 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.1)] border-l border-slate-100">
                                <span class="subtotal-rp">0</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- STICKY FOOTER ACTION BAR -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 md:p-6 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)] z-50">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-6 w-full md:w-auto">
                    <div class="bg-slate-100 px-6 py-3 rounded-2xl hidden md:block">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Estimasi Masuk Sistem</p>
                        <p class="text-xl font-black text-slate-800"><span id="grand_total_kg">0</span> <span class="text-sm">Kg</span> <span class="text-slate-400 mx-1 font-bold">~</span> <span id="grand_total_pcs" class="text-amber-500">0</span> <span class="text-sm text-amber-500">Pcs</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-1">Total Tunai Dikeluarkan</p>
                        <p class="text-3xl font-black text-emerald-600" id="grand_total_rp">Rp 0</p>
                    </div>
                </div>
                <button type="submit" onclick="return konfirmasiSimpan()" class="w-full md:w-auto px-10 py-5 bg-amber-500 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-amber-600 transition-all shadow-xl shadow-amber-500/20 flex items-center justify-center transform active:scale-95">
                    <span class="mr-2 text-lg">💸</span> Simpan Transaksi Kolektif
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function hitungTotal() {
    let grandTotalRp = 0;
    let totalPcsSeluruh = 0;
    let totalKgSeluruh = 0;

    // Hitung per baris (per kelas)
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('.input-kg');
        const subtotalDisplay = row.querySelector('.subtotal-rp');
        let subtotalRp = 0;
        
        inputs.forEach(input => {
            let kg = parseFloat(input.value);
            if (!isNaN(kg) && kg > 0) {
                let harga = parseFloat(input.getAttribute('data-harga'));
                let konversi = parseFloat(input.getAttribute('data-konversi')) || 1;
                
                let pcs = Math.round(kg * konversi);
                let rp = pcs * harga;
                
                subtotalRp += rp;
                totalPcsSeluruh += pcs;
                totalKgSeluruh += kg;
            }
        });
        
        // Update teks subtotal kelas di sebelah kanan
        if (subtotalRp > 0) {
            subtotalDisplay.innerText = new Intl.NumberFormat('id-ID').format(subtotalRp);
        } else {
            subtotalDisplay.innerText = '0';
        }
        
        grandTotalRp += subtotalRp;
    });

    // Update Banner Bawah
    document.getElementById('grand_total_rp').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotalRp);
    document.getElementById('grand_total_kg').innerText = totalKgSeluruh.toFixed(2).replace(/\.00$/, '');
    document.getElementById('grand_total_pcs').innerText = new Intl.NumberFormat('id-ID').format(totalPcsSeluruh);
}

function konfirmasiSimpan() {
    const totalRp = document.getElementById('grand_total_rp').innerText;
    if (totalRp === 'Rp 0') {
        alert('Belum ada hasil timbangan yang diinput!');
        return false;
    }
    return confirm(`Data sudah benar? \n\nTotal pengeluaran Kas Besar adalah ${totalRp}. Transaksi akan dirinci per kelas di Buku Kas.`);
}
</script>