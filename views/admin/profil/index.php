<div class="max-w-5xl mx-auto space-y-8 pb-10">
    <div>
        <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">PROFIL<span class="text-emerald-500">SAYA</span></h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Pengaturan Identitas & Keamanan Akun</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3 text-lg">✅</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3 text-lg">⚠️</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-1 space-y-6">
            <div class="bg-slate-900 rounded-[3rem] p-8 text-center text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-emerald-500/20 to-transparent"></div>
                <div class="w-24 h-24 mx-auto bg-emerald-500 rounded-full flex items-center justify-center text-4xl font-black shadow-lg shadow-emerald-500/30 relative z-10 border-4 border-slate-900 uppercase">
                    <?= substr($profil['nama'], 0, 1) ?>
                </div>
                <h3 class="text-lg font-black mt-4 uppercase tracking-tighter"><?= htmlspecialchars($profil['nama']) ?></h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">@<?= htmlspecialchars($profil['username']) ?></p>
                
                <div class="mt-8 pt-6 border-t border-slate-800 text-left space-y-4">
                    <div>
                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Hak Akses Sistem</p>
                        <p class="text-xs font-bold text-emerald-400 uppercase italic mt-1"><?= $profil['role'] ?></p>
                    </div>
                    <?php if($profil['role'] === 'siswa'): ?>
                    <div>
                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Kelas / Angkatan</p>
                        <p class="text-xs font-bold text-slate-200 mt-1"><?= htmlspecialchars($profil['nama_kelas'] ?? '-') ?> / <?= htmlspecialchars($profil['angkatan'] ?? '-') ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
            <form action="<?= BASE_URL ?>/profil/update" method="POST" class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm space-y-8">
                
                <div class="border-b border-slate-50 pb-4">
                    <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Perbarui Identitas Login</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($profil['nama']) ?>" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Username Login</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($profil['username']) ?>" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-slate-800">
                    </div>
                </div>

                <div class="border-b border-slate-50 pb-4 mt-8 pt-4">
                    <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Ubah Kata Sandi Baru</h3>
                    <p class="text-[9px] text-slate-400 font-bold mt-2">*Biarkan form sandi ini kosong jika Anda tidak ingin mengubahnya.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Kata Sandi Baru</label>
                        <input type="password" name="password" placeholder="Ketik sandi baru..." class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl text-xs font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-slate-800 placeholder:text-slate-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Konfirmasi Sandi Baru</label>
                        <input type="password" name="konfirmasi_password" placeholder="Ketik ulang sandi..." class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl text-xs font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-slate-800 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-5 bg-emerald-600 text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-emerald-500/20 hover:bg-emerald-700 transition-all transform active:scale-95">
                        💾 Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>