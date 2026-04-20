<div class="max-w-7xl mx-auto space-y-8 pb-10" x-data="{ showModal: false, isEdit: false, formData: { id: '', nama_kelas: '', walikelas_id: '' } }">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">MASTER<span class="text-emerald-500">KELAS</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Manajemen Ruang & Wali Kelas</p>
        </div>
        <button @click="showModal = true; isEdit = false; formData = {id:'', nama_kelas:'', walikelas_id:''}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all">
            + Tambah Kelas
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?><div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?><div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm">⚠️ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-5">Nama Kelas</th>
                        <th class="px-8 py-5">Wali Kelas</th>
                        <th class="px-8 py-5 text-center">Total Siswa</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if(empty($kelas)): ?>
                        <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-xs italic">Belum ada data kelas.</td></tr>
                    <?php else: ?>
                        <?php foreach($kelas as $k): ?>
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-5 font-black text-slate-800 text-sm uppercase italic tracking-tighter">
                                KELAS <?= htmlspecialchars($k['nama_kelas']) ?>
                            </td>
                            <td class="px-8 py-5">
                                <?php if(!empty($k['nama_walikelas'])): ?>
                                    <div class="font-bold text-slate-700 text-sm"><?= htmlspecialchars($k['nama_walikelas']) ?></div>
                                    <div class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mt-1">PJ Honorarium</div>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-red-50 text-red-500 rounded-full text-[9px] font-black uppercase tracking-widest italic">Belum Diatur</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-lg font-black <?= ($k['total_siswa'] ?? 0) > 0 ? 'text-slate-800' : 'text-slate-300' ?>">
                                    <?= $k['total_siswa'] ?? 0 ?> <span class="text-[9px] uppercase text-slate-400">Siswa</span>
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center space-x-2">
                                <button @click="showModal = true; isEdit = true; formData = { id: '<?= $k['id'] ?>', nama_kelas: '<?= addslashes($k['nama_kelas']) ?>', walikelas_id: '<?= $k['walikelas_id'] ?>' }" class="px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-800 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Edit</button>
                                <a href="<?= BASE_URL ?>/kelas/delete?id=<?= $k['id'] ?>" onclick="return confirm('Hapus kelas ini?')" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-in fade-in duration-200">
        <div @click.away="showModal = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-lg uppercase italic tracking-tighter" x-text="isEdit ? 'Edit Kelas' : 'Tambah Kelas'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-red-500 font-bold text-2xl">&times;</button>
            </div>
            <form :action="isEdit ? '<?= BASE_URL ?>/kelas/update' : '<?= BASE_URL ?>/kelas/store'" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="id" x-model="formData.id">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Kelas</label>
                    <input type="text" name="nama_kelas" x-model="formData.nama_kelas" required placeholder="Contoh: X IPA 1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Wali Kelas</label>
                    <select name="walikelas_id" x-model="formData.walikelas_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">-- Kosongkan / Pilih Guru --</option>
                        <?php foreach($guru as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-emerald-700 transition-all">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>