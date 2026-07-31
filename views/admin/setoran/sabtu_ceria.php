<!-- views/admin/setoran/sabtu_ceria.php -->
<div class="max-w-7xl mx-auto space-y-6 pb-32">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">SABTU<span class="text-amber-500">CERIA</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Pembelian Sampah Tunai (Beli Putus Kolektif)</p>
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

    <form action="<?= BASE_URL ?>/setoran/store_sabtu_ceria" method="POST" id="formSabtuCeria">
        <?= Security::csrf_field(); ?>
        <input type="hidden" name="user_id" value="<?= $akun_sabtu_ceria['id'] ?>">

        <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm mb-6">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-3 ml-1">Langkah 1: Pilih Kategori Sampah</label>
            <select name="kategori_id" id="kategori_id" required class="w-full md:w-1/2 px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black focus:ring-2 focus:ring-amber-500 outline-none transition-colors" onchange="hitungTotal()">
                <option value="" disabled selected>-- Tentukan Kategori Sampah --</option>
                <?php foreach($kategori as $k): ?>
                    <option value="<?= $k['id'] ?>" data-harga="<?= $k['harga_dasar'] ?>" data-konversi="<?= $k['konversi_kg'] ?>">
                        <?= htmlspecialchars($k['nama_sampah']) ?> (Harga Dasar: Rp <?= number_format($k['harga_dasar'], 0, ',', '.') ?>/Pcs)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-6 ml-1 border-b border-slate-100 pb-3">Langkah 2: Input Hasil Timbangan (Kilogram) Per Kelas</label>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php foreach($kelas_list as $kelas): ?>
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 focus-within:ring-2 focus-within:ring-amber-500 transition-all group hover:border-amber-300">
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-2 truncate" title="<?= htmlspecialchars($kelas['nama_kelas']) ?>">
                            <?= htmlspecialchars($kelas['nama_kelas']) ?>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" name="berat_kg[<?= htmlspecialchars($kelas['nama_kelas']) ?>]" class="input-kg w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-lg font-black text-slate-900 focus:outline-none placeholder-slate-300" placeholder="0" oninput="hitungTotal()">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-black text-slate-400 uppercase">Kg</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <p class="text-xs text-slate-400 italic mt-6">*Biarkan kosong atau isi 0 pada kelas yang tidak menyetorkan sampah.</p>
        </div>

        <!-- STICKY FOOTER ACTION BAR -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 md:p-6 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)] z-50 transform translate-y-0 transition-transform">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-6 w-full md:w-auto">
                    <div class="bg-slate-100 px-6 py-3 rounded-2xl">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Total Akumulasi</p>
                        <p class="text-xl font-black text-slate-800"><span id="grand_total_kg">0</span> <span class="text-sm">Kg</span> <span class="text-slate-400 font-medium text-sm mx-1">~</span> <span id="grand_total_pcs" class="text-amber-500">0</span> <span class="text-amber-500 text-sm">Pcs</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-1">Total Dana Dikeluarkan</p>
                        <p class="text-3xl font-black text-emerald-600" id="grand_total_rp">Rp 0</p>
                    </div>
                </div>
                <button type="submit" onclick="return konfirmasiSimpan()" class="w-full md:w-auto px-10 py-5 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all shadow-xl flex items-center justify-center transform active:scale-95">
                    <span class="mr-2 text-lg">💰</span> Bayar Tunai & Simpan Data
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function hitungTotal() {
    const select = document.getElementById('kategori_id');
    const inputs = document.querySelectorAll('.input-kg');
    const displayKg = document.getElementById('grand_total_kg');
    const displayPcs = document.getElementById('grand_total_pcs');
    const displayRp = document.getElementById('grand_total_rp');

    let totalKg = 0;
    inputs.forEach(input => {
        let val = parseFloat(input.value);
        if (!isNaN(val) && val > 0) {
            totalKg += val;
        }
    });

    if (select.selectedIndex > 0 && totalKg > 0) {
        const option = select.options[select.selectedIndex];
        const hargaDasar = parseFloat(option.getAttribute('data-harga'));
        const konversi = parseFloat(option.getAttribute('data-konversi'));

        const totalPcs = Math.round(totalKg * konversi);
        const totalRp = totalPcs * hargaDasar;

        displayKg.innerText = totalKg.toFixed(2).replace(/\.00$/, '');
        displayPcs.innerText = new Intl.NumberFormat('id-ID').format(totalPcs);
        displayRp.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalRp);
    } else {
        displayKg.innerText = '0';
        displayPcs.innerText = '0';
        displayRp.innerText = 'Rp 0';
    }
}

function konfirmasiSimpan() {
    const select = document.getElementById('kategori_id');
    const totalRp = document.getElementById('grand_total_rp').innerText;
    
    if (select.selectedIndex === 0) {
        alert('Harap pilih kategori sampah terlebih dahulu!');
        return false;
    }
    if (totalRp === 'Rp 0') {
        alert('Belum ada hasil timbangan (Kg) yang diinput!');
        return false;
    }
    
    return confirm(`Lanjutkan pembayaran tunai sebesar ${totalRp}? \n\nTindakan ini akan memotong saldo Kas Besar dan menambah stok sampah.`);
}
</script>