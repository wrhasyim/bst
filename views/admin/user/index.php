<div class="max-w-7xl mx-auto space-y-8 pb-10" x-data="{ showModal: false, isEdit: false, formData: { id: '', username: '', nama: '', role: 'siswa', kelas_id: '', angkatan: '', is_active: 1 } }">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">MANAJEMEN<span class="text-emerald-500">USER</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Kelola Admin, Staff, Guru, dan Siswa</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= BASE_URL ?>/user/import" class="px-6 py-3 bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-sm hover:bg-blue-600 hover:text-white transition-all flex items-center">
                📥 Import CSV
            </a>
            <button @click="showModal = true; isEdit = false; formData = {id:'', username:'', nama:'', role:'siswa', kelas_id:'', angkatan:'', is_active: 1}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all flex items-center">
                + Tambah User
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm flex items-center">✅ <span class="ml-2"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm flex items-center">⚠️ <span class="ml-2"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span></div>
    <?php endif; ?>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                        <th class="px-8 py-5">Identitas</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5">Role & Penempatan</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach($users as $u): ?>
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-8 py-5">
                            <div class="font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($u['nama']) ?></div>
                            <div class="text-[10px] font-bold text-slate-400 mt-1">@<?= htmlspecialchars($u['username']) ?></div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <?php if($u['is_active']): ?>
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">Aktif</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-slate-100 text-slate-400 rounded-full text-[9px] font-black uppercase tracking-widest">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-slate-600 uppercase mb-1 flex items-center">
                                <?php 
                                    $bgRole = 'bg-emerald-500';
                                    if ($u['role'] === 'admin') $bgRole = 'bg-red-500';
                                    if ($u['role'] === 'staff') $bgRole = 'bg-amber-500';
                                    if ($u['role'] === 'guru') $bgRole = 'bg-blue-500';
                                ?>
                                <span class="px-2 py-0.5 rounded-md text-[8px] font-black text-white mr-2 <?= $bgRole ?>"><?= strtoupper($u['role']) ?></span>
                                
                                <?php
                                    if ($u['role'] === 'siswa') echo 'KLS ' . htmlspecialchars($u['nama_kelas'] ?? '-');
                                    else if ($u['role'] === 'guru') echo 'GURU / NASABAH';
                                    else if ($u['role'] === 'staff') echo 'PETUGAS INPUT';
                                    else echo 'SUPER ADMIN';
                                ?>
                            </div>
                            <?php if($u['role'] === 'siswa' && !empty($u['angkatan'])): ?>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Angkatan: <?= htmlspecialchars($u['angkatan']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-center space-x-2">
                            <button @click="showModal = true; isEdit = true; formData = { id: '<?= $u['id'] ?>', username: '<?= htmlspecialchars($u['username']) ?>', nama: '<?= addslashes($u['nama']) ?>', role: '<?= $u['role'] ?>', kelas_id: '<?= $u['kelas_id'] ?>', angkatan: '<?= htmlspecialchars($u['angkatan']) ?>', is_active: '<?= $u['is_active'] ?>' }" class="px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-800 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Edit</button>
                            <a href="<?= BASE_URL ?>/user/delete?id=<?= $u['id'] ?>" onclick="return confirm('Hapus pengguna ini permanen?')" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-in fade-in duration-200">
        <div @click.away="showModal = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden h-[90vh] flex flex-col">
            
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center shrink-0">
                <h3 class="font-black text-slate-800 text-lg uppercase italic tracking-tighter" x-text="isEdit ? 'Update Data User' : 'Daftarkan User Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-red-500 font-bold text-2xl">&times;</button>
            </div>

            <div class="overflow-y-auto custom-scrollbar p-6">
                <form :action="isEdit ? '<?= BASE_URL ?>/user/update' : '<?= BASE_URL ?>/user/store'" method="POST" class="space-y-5">
                    <input type="hidden" name="id" x-model="formData.id">
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" x-model="formData.nama" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Username Login</label>
                            <input type="text" name="username" x-model="formData.username" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1" x-text="isEdit ? 'Ganti Password (Opsional)' : 'Password Awal'"></label>
                            <input type="password" name="password" :required="!isEdit" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Peran Akses (Role)</label>
                            <select name="role" x-model="formData.role" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="siswa">Siswa (Nasabah)</option>
                                <option value="guru">Guru (Nasabah Pribadi)</option>
                                <option value="staff">Staff (Petugas Input)</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Status Akun</label>
                            <select name="is_active" x-model="formData.is_active" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif / Lulus</option>
                            </select>
                        </div>
                    </div>

                    <div x-show="formData.role === 'siswa'" x-collapse class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl space-y-4">
                        <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest border-b border-emerald-200 pb-2">Informasi Akademik Nasabah</p>
                        <div>
                            <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-2 ml-1">Kelas Saat Ini</label>
                            <select name="kelas_id" x-model="formData.kelas_id" :required="formData.role === 'siswa'" class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach($kelas as $k): ?>
                                    <option value="<?= $k['id'] ?>">Kelas <?= htmlspecialchars($k['nama_kelas']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-2 ml-1">Tahun Angkatan</label>
                            <input type="text" name="angkatan" x-model="formData.angkatan" placeholder="Misal: 2024" class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div x-show="formData.role === 'staff' || formData.role === 'admin'" x-collapse class="p-3 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-[9px] font-bold text-amber-700 italic">*Perhatian: Akun ini memiliki akses ke sistem internal operasional (bukan nasabah).</p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-black transition-all transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>