<!-- views/admin/setoran/kesiswaan_create.php -->
<div class="max-w-3xl mx-auto space-y-6 pb-12">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">DENDA<span class="text-red-500">KEDISIPLINAN</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Input Botol Denda & Hukuman Siswa</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-4 rounded-r-xl font-bold text-sm shadow-sm animate-pulse">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-xl font-bold text-sm shadow-sm">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute -right-10 -top-10 opacity-5 text-[15rem] pointer-events-none">⚖️</div>
        
        <div class="mb-8 border-b border-slate-100 pb-4 relative z-10">
            <h3 class="font-black text-slate-800 uppercase italic">Form Setoran Cepat</h3>
            <p class="text-xs text-slate-500 mt-1">Botol yang diinput di sini akan langsung dikonversi menjadi saldo **KAS KESISWAAN**.</p>
        </div>

        <form action="<?= BASE_URL ?>/setoran/store_kesiswaan" method="POST" class="space-y-6 relative z-10">
            
            <?= Security::csrf_field(); ?> <!-- 🛡️ CSRF Protection -->

            <!-- Hidden Input ID Kesiswaan -->
            <input type="hidden" name="user_id" value="<?= $akun_kesiswaan['id'] ?>">

            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Jenis Botol</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <?php foreach($kategori as $k): ?>
                    <label class="cursor-pointer relative group">
                        <input type="radio" name="kategori_id" value="<?= $k['id'] ?>" class="peer sr-only" required>
                        <div class="px-4 py-4 bg-slate-50 border-2 border-slate-200 rounded-2xl text-center peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-slate-100 transition-all">
                            <span class="block text-sm font-black text-slate-700 peer-checked:text-red-700 mb-1"><?= htmlspecialchars($k['nama_sampah']) ?></span>
                            <span class="text-[9px] font-bold text-slate-400">Rp<?= number_format($k['harga_dasar'],0) ?> / <?= $k['satuan'] ?></span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Jumlah (Pcs)</label>
                <input type="number" name="berat" placeholder="Contoh: 15" min="1" required class="w-full px-6 py-5 bg-slate-50 border-2 border-slate-200 rounded-2xl text-3xl font-black text-center text-slate-900 focus:border-red-400 focus:ring-0 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Keterangan / Nama Pelanggar (Opsional)</label>
                <input type="text" name="keterangan" placeholder="Contoh: Budi (X RPL 1) - Terlambat" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:border-red-400 outline-none transition-colors">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-red-500 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-red-200 hover:bg-red-600 transition-transform active:scale-95">
                    🚨 Simpan Botol Denda
                </button>
            </div>
        </form>
    </div>
</div>