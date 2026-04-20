<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Data Pengguna</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui profil atau pindahkan kelas siswa di sini.</p>
        </div>
        <a href="<?= BASE_URL ?>/user" class="text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= BASE_URL ?>/user/update/<?= $user['id'] ?>" method="POST" class="p-6 sm:p-8 space-y-6" x-data="{ selectedRole: '<?= htmlspecialchars($user['role']) ?>' }">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Peran (Role) <span class="text-red-500">*</span></label>
                    <select name="role" x-model="selectedRole" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                        <option value="siswa" <?= $user['role'] == 'siswa' ? 'selected' : '' ?>>Siswa</option>
                        <option value="guru" <?= $user['role'] == 'guru' ? 'selected' : '' ?>>Guru</option>
                        <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff Operasional</option>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrator</option>
                        <option value="alumni" <?= $user['role'] == 'alumni' ? 'selected' : '' ?>>Alumni</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Akun</label>
                    <select name="is_active" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all">
                        <option value="1" <?= $user['is_active'] == 1 ? 'selected' : '' ?>>Aktif (Bisa Login)</option>
                        <option value="0" <?= $user['is_active'] == 0 ? 'selected' : '' ?>>Nonaktif (Diblokir)</option>
                    </select>
                </div>
            </div>

            <div x-show="selectedRole === 'siswa' || selectedRole === 'alumni'" x-transition class="p-5 bg-emerald-50 rounded-xl border border-emerald-100 grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-emerald-800 mb-2">Pilih Kelas / Pindah Kelas <span class="text-red-500">*</span></label>
                    <select name="kelas_id" class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all" :required="selectedRole === 'siswa'">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ($user['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama_kelas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-emerald-800 mb-2">Tahun Angkatan</label>
                    <input type="text" name="angkatan" value="<?= htmlspecialchars($user['angkatan'] ?? '') ?>" placeholder="Contoh: 2024" class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    Update Pengguna
                </button>
            </div>
        </form>
    </div>
</div>