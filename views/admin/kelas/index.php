<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Kelas</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola daftar kelas, tentukan wali kelas, dan pantau populasi siswa.</p>
        </div>
        <a href="<?= BASE_URL ?>/kelas/create" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition-all duration-200 transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Kelas Baru
        </a>
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
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kelas</th>
                        <th class="px-6 py-4">Wali Kelas</th>
                        <th class="px-6 py-4 text-center">Total Siswa</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php 
                    $no = 1;
                    foreach ($kelas as $row): 
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $no++ ?></td>
                        <td class="px-6 py-4 font-bold text-gray-800"><?= htmlspecialchars($row['nama_kelas']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($row['nama_wali']): ?>
                                <span class="inline-flex items-center text-sm font-medium text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                    👨‍🏫 <?= htmlspecialchars($row['nama_wali']) ?>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center text-sm font-medium text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                                    Belum Diatur
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold <?= $row['total_siswa'] > 0 ? 'text-gray-800' : 'text-gray-400' ?>">
                                <?= $row['total_siswa'] ?> Anak
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="<?= BASE_URL ?>/kelas/edit/<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700 transition">Edit</a>
                                <a href="<?= BASE_URL ?>/kelas/delete/<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus kelas ini?')" class="text-red-500 hover:text-red-700 transition">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($kelas)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data kelas.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>