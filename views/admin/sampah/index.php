<div class="max-w-7xl mx-auto space-y-8 pb-10" x-data="{ showModal: false, isEdit: false, formData: { id: '', nama_sampah: '', harga_dasar: '', harga_pengepul: '' } }">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">MASTER<span class="text-emerald-500">KATEGORI</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Kategori & Harga Sampah</p>
        </div>
        <button @click="showModal = true; isEdit = false; formData = {id:'', nama_sampah:'', harga_dasar:'', harga_pengepul:''}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center">
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
                        <th class="px-8 py-5 text-right">Harga Dasar (Nasabah)</th>
                        <th class="px-8 py-5 text-right">Harga Jual (Pengepul)</th>
                        <th class="px-8 py-5 text-center">Margin / Pcs</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($kategori)): ?>
                        <tr><td colspan="5" class="px-8 py-10 text-center text-slate-400 text-xs italic">Belum ada kategori sampah.</td></tr>
                    <?php else: ?>
                        <?php foreach($kategori as $k): 
                            $margin = $k['harga_pengepul'] - $k['harga_dasar'];
                        ?>
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($k['nama_sampah']) ?></div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Satuan: <?= htmlspecialchars($k['satuan']) ?></div>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-red-500 text-sm">Rp<?= number_format($k['harga_dasar'], 0, ',', '.') ?></td>
                            <td class="px-8 py-5 text-right font-black text-blue-600 text-sm">Rp<?= number_format($k['harga_pengepul'], 0, ',', '.') ?></td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    + Rp<?= number_format($margin, 0, ',', '.') ?>
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center space-x-2">
                                <button @click="showModal = true; isEdit = true; formData = { id: '<?= $k['id'] ?>', nama_sampah: '<?= addslashes($k['nama_sampah']) ?>', harga_dasar: '<?= $k['harga_dasar'] ?>', harga_pengepul: '<?= $k['harga_pengepul'] ?>' }" 
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
        <div @click.away="showModal = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-100 transition-all">
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg uppercase italic tracking-tighter" x-text="isEdit ? 'Edit Kategori Sampah' : 'Tambah Kategori Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
            </div>

            <form :action="isEdit ? '<?= BASE_URL ?>/sampah/update' : '<?= BASE_URL ?>/sampah/store'" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="id" x-model="formData.id">
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama / Jenis Sampah</label>
                    <input type="text" name="nama_sampah" x-model="formData.nama_sampah" required placeholder="Contoh: Botol Plastik" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-red-400 uppercase mb-2 ml-1">Harga Dasar (Nasabah)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="harga_dasar" x-model="formData.harga_dasar" required min="0" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black text-red-500 focus:ring-2 focus:ring-red-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-blue-400 uppercase mb-2 ml-1">Harga Jual (Pengepul)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="harga_pengepul" x-model="formData.harga_pengepul" required min="0" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black text-blue-600 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl border-2 flex items-center justify-between transition-all" :class="(formData.harga_pengepul - formData.harga_dasar) >= 0 ? 'border-emerald-100 bg-emerald-50/50' : 'border-red-100 bg-red-50/50'">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Margin (Profit) / Pcs :</span>
                    <span class="text-lg font-black" :class="(formData.harga_pengepul - formData.harga_dasar) >= 0 ? 'text-emerald-600' : 'text-red-600'">
                        Rp<span x-text="formData.harga_pengepul - formData.harga_dasar || 0"></span>
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