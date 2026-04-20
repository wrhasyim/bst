<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tambah Pengguna</h2>
        </div>
        <a href="<?= BASE_URL ?>/user" class="text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= BASE_URL ?>/user/store" method="POST" class="p-6 sm:p-8 space-y-6" x-data="{ selectedRole: 'siswa' }">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Peran (Role) <span class="text-red-500">*</span></label>
                    <select name="role" x-model="selectedRole" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="staff">Staff Operasional</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Akun</label>
                    <select name="is_active" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500">
                        <option value="1">Aktif (Bisa Login)</option>
                        <option value="0">Nonaktif (Diblokir)</option>
                    </select>
                </div>
            </div>

            <div x-show="selectedRole === 'siswa' || selectedRole === 'alumni'" x-transition class="p-5 bg-emerald-50 rounded-xl border border-emerald-100 grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-emerald-800 mb-2">Pilih Kelas <span class="text-red-500">*</span></label>
                    <select name="kelas_id" class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500" :required="selectedRole === 'siswa'">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-[11px] text-emerald-600 mt-1">Mengubah ini di menu Edit = Fitur Pindah Kelas.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-emerald-800 mb-2">Tahun Angkatan</label>
                    <input type="text" name="angkatan" placeholder="Contoh: 2024" class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all duration-300">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>