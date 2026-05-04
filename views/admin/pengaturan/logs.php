<!-- views/admin/pengaturan/logs.php -->
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Audit Trail</h1>
            <p class="text-sm text-slate-500 font-medium">Log aktivitas sistem 100 transaksi terakhir.</p>
        </div>
        <div class="bg-slate-100 px-4 py-2 rounded-lg text-xs font-bold text-slate-600 tracking-widest uppercase">
            Sistem Keamanan Aktif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Waktu</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">User</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Aktivitas</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Keterangan</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada catatan aktivitas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs text-slate-600 whitespace-nowrap">
                                    <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-800"><?= htmlspecialchars($log['nama']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-black uppercase rounded-md tracking-tighter">
                                        <?= htmlspecialchars($log['activity']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <?= htmlspecialchars($log['description']) ?>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                    <?= htmlspecialchars($log['ip_address']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>