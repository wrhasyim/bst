<div class="max-w-3xl mx-auto space-y-8 pb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KELULUSAN<span class="text-blue-500">ALUMNI</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Pengarsipan Siswa Tingkat Akhir</p>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-10 space-y-6">
            <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Form Kelulusan Massal</h3>
            
            <form action="<?= BASE_URL ?>/akademik/proses_kelulusan" method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Kelas Yang Lulus (Misal: Kelas XII atau IX)</label>
                    <select name="kelas_id" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- Pilih Kelas Alumni --</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>">Kelas <?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="p-6 bg-blue-50 border border-blue-100 rounded-[2rem] text-blue-800">
                    <h4 class="font-black text-[10px] uppercase mb-2">Informasi Penting:</h4>
                    <ul class="text-[10px] font-bold uppercase tracking-wide space-y-1 opacity-80">
                        <li>• Siswa akan berubah role menjadi 'Alumni'</li>
                        <li>• Status akun menjadi Non-Aktif (Tidak bisa login)</li>
                        <li>• Riwayat transaksi tetap tersimpan dalam sistem</li>
                    </ul>
                </div>

                <button type="submit" onclick="return confirm('Yakin ingin meluluskan semua siswa di kelas ini?')" class="w-full py-5 bg-blue-600 text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-200">
                    Proses Kelulusan Massal
                </button>
            </form>
        </div>
    </div>
</div>