<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Pengguna</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data akses Admin, Staff, Guru, dan Siswa.</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?= BASE_URL ?>/user/import" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Import CSV
            </a>
            <a href="<?= BASE_URL ?>/user/create" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition-all">
                + Tambah Baru
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <span class="text-emerald-800 font-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center">
            <span class="text-red-800 font-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                        <th class="px-6 py-4">Nama & Username</th>
                        <th class="px-6 py-4">Peran (Role)</th>
                        <th class="px-6 py-4">Kelas / Angkatan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                                <div class="text-xs text-gray-500 mt-1">@<?= htmlspecialchars($row['username']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                    // Pewarnaan dinamis berdasarkan role
                                    $colors = [
                                        'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'staff' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'guru' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'siswa' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'alumni' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    ];
                                    $c = $colors[$row['role']] ?? $colors['alumni'];
                                ?>
                                <span class="px-3 py-1 text-[11px] font-bold uppercase rounded-full border <?= $c ?>">
                                    <?= htmlspecialchars($row['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($row['role'] === 'siswa' || $row['role'] === 'alumni'): ?>
                                    <div class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($row['nama_kelas'] ?? 'Belum ada kelas') ?></div>
                                    <div class="text-[11px] text-gray-500">Angkatan: <?= htmlspecialchars($row['angkatan'] ?? '-') ?></div>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($row['is_active']): ?>
                                    <span class="w-3 h-3 inline-block bg-green-500 rounded-full shadow-sm" title="Aktif"></span>
                                <?php else: ?>
                                    <span class="w-3 h-3 inline-block bg-red-500 rounded-full shadow-sm" title="Nonaktif"></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="<?= BASE_URL ?>/user/edit/<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700 transition">Edit</a>
                                    <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                        <a href="<?= BASE_URL ?>/user/delete/<?= $row['id'] ?>" onclick="return confirm('Yakin hapus akun ini?')" class="text-red-500 hover:text-red-700 transition">Hapus</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>