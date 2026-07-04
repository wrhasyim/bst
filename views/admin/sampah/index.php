<div class="max-w-7xl mx-auto space-y-8 pb-10" x-data="{ showModal: false, isEdit: false, formData: { id: '', nama_sampah: '', harga_dasar: '', harga_guru: '', harga_pengepul: '', konversi_kg: '1' } }">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">MASTER<span class="text-emerald-500">KATEGORI</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Kategori & Harga Sampah</p>
        </div>
        <button @click="showModal = true; isEdit = false; formData = {id:'', nama_sampah:'', harga_dasar:'', harga_guru:'', harga_pengepul:'', konversi_kg:'1'}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center">
            <span class="mr-2 text-lg">+</span> Tambah Kategori Baru
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm flex items-center">
            <span class="mr-2 text-lg">✅</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm flex items-center">
            <span class="mr-2 text-lg">⚠️</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
            <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Daftar Harga & Margin Profit</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-5">Nama Kategori</th>
                        <th class="px-6 py-5 text-right">Beli (Siswa/Pcs)</th>
                        <th class="px-6 py-5 text-right">Beli (Guru/Pcs)</th>
                        <th class="px-6 py-5 text-right">Jual (Pengepul/KG)</th>
                        <th class="px-8 py-5 text-center">Margin per KG</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($kategori)): ?>
                        <tr><td colspan="6" class="px-8 py-10 text-center text-slate-400 text-xs italic">Belum ada kategori sampah.</td></tr>
                    <?php else: ?>
                        <?php foreach($kategori as $k): 
                            // 🛠️ FIX Kalkulasi PHP Margin Akurat 
                            $harga_guru = isset($k['harga_guru']) ? $k['harga_guru'] : $k['harga_dasar'];
                            $harga_beli_tertinggi = max($k['harga_dasar'], $harga_guru);
                            $konversi = $k['konversi_kg'] ?? 1;
                            
                            $modal_per_kg = $harga_beli_tertinggi * $konversi;
                            $margin = $k['harga_pengepul'] - $modal_per_kg;
                        ?>
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($k['nama_sampah']) ?></div>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="text-[9px] font-bold text-amber-600 uppercase tracking-widest bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">
                                        ⚖️ 1 KG = <?= $konversi ?> PCS
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right font-black text-red-500 text-sm">Rp<?= number_format($k['harga_dasar'], 0, ',', '.') ?> <span class="text-[9px] text-slate-400 font-bold block mt-1">/ Pcs</span></td>
                            <td class="px-6 py-5 text-right font-black text-purple-500 text-sm">Rp<?= number_format($harga_guru, 0, ',', '.') ?> <span class="text-[9px] text-slate-400 font-bold block mt-1">/ Pcs</span></td>
                            <td class="px-6 py-5 text-right font-black text-blue-600 text-sm">Rp<?= number_format($k['harga_pengepul'], 0, ',', '.') ?> <span class="text-[9px] text-slate-400 font-bold block mt-1">/ KG</span></td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1.5 <?= $margin >= 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' ?> rounded-xl text-[10px] font-black uppercase tracking-widest">
                                    <?= $margin >= 0 ? '+' : '' ?> Rp<?= number_format($margin, 0, ',', '.') ?>
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center space-x-2">
                                <button @click="showModal = true; isEdit = true; formData = { id: '<?= $k['id'] ?>', nama_sampah: '<?= addslashes($k['nama_sampah']) ?>', harga_dasar: '<?= $k['harga_dasar'] ?>', harga_guru: '<?= $harga_guru ?>', harga_pengepul: '<?= $k['harga_pengepul'] ?>', konversi_kg: '<?= $konversi ?>' }" 
                                        class="px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-800 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                    Edit
                                </button>
                                <a href="<?= BASE_URL ?>/sampah/delete?id=<?= $k['id'] ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?')" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
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

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-in fade-in duration-200">
        <div @click.away="showModal = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden transform scale-100 transition-all"
             x-data="{ 
                 get modalPerKg() { return Math.max(this.formData.harga_dasar || 0, this.formData.harga_guru || this.formData.harga_dasar || 0) * (this.formData.konversi_kg || 1); },
                 get marginPerKg() { return this.formData.harga_pengepul - this.modalPerKg; }
             }">
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg uppercase italic tracking-tighter" x-text="isEdit ? 'Edit Kategori Sampah' : 'Tambah Kategori Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
            </div>

            <form :action="isEdit ? '<?= BASE_URL ?>/sampah/update' : '<?= BASE_URL ?>/sampah/store'" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="id" x-model="formData.id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama / Jenis Sampah</label>
                        <input type="text" name="nama_sampah" x-model="formData.nama_sampah" required placeholder="Contoh: Botol Plastik" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Konversi Pcs ke 1 KG</label>
                        <input type="number" name="konversi_kg" x-model.number="formData.konversi_kg" required min="1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-red-400 uppercase mb-2 ml-1">Beli Siswa (Rp / Pcs)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="harga_dasar" x-model.number="formData.harga_dasar" required min="0" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black text-red-500 focus:ring-2 focus:ring-red-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-purple-400 uppercase mb-2 ml-1">Beli Guru (Rp / Pcs)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="harga_guru" x-model.number="formData.harga_guru" min="0" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black text-purple-500 focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-blue-400 uppercase mb-2 ml-1">Jual Pengepul (Rp / KG)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="harga_pengepul" x-model.number="formData.harga_pengepul" required min="0" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl border-2 flex items-center justify-between transition-all" :class="marginPerKg >= 0 ? 'border-emerald-100 bg-emerald-50/50' : 'border-red-100 bg-red-50/50'">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Margin Minimal / 1 KG :</span>
                        <span class="block text-[8px] text-slate-400 mt-0.5">Jual 1 KG - Modal <span x-text="formData.konversi_kg"></span> Pcs</span>
                    </div>
                    <span class="text-lg font-black" :class="marginPerKg >= 0 ? 'text-emerald-600' : 'text-red-600'">
                        Rp<span x-text="new Intl.NumberFormat('id-ID').format(marginPerKg)"></span>
                    </span>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all">
                        Simpan Data Sampah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>