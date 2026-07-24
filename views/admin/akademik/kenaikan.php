<!-- views/admin/akademik/kenaikan.php -->
<div class="max-w-5xl mx-auto space-y-8 pb-12">
    
    <!-- HEADER SECTION -->
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-3xl shadow-lg shadow-emerald-500/30">
            🎓
        </div>
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">Kenaikan<span class="text-emerald-500">Kelas</span></h2>
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">Migrasi Data Siswa Antar Tingkat Akademik</p>
        </div>
    </div>

    <!-- ALERT NOTIFICATIONS -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-5 rounded-r-2xl flex items-center gap-4 shadow-sm animate-fade-in-down">
            <div class="text-emerald-500 text-xl">✅</div>
            <div>
                <p class="text-emerald-800 font-bold text-sm"><?= $_SESSION['success'] ?></p>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-r-2xl flex items-center gap-4 shadow-sm animate-fade-in-down">
            <div class="text-red-500 text-xl">⚠️</div>
            <div>
                <p class="text-red-800 font-bold text-sm"><?= $_SESSION['error'] ?></p>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- MAIN FORM CARD -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden">
        
        <!-- DECORATIVE BACKGROUND -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-bl-full opacity-50 pointer-events-none"></div>
        <div class="absolute -right-12 -bottom-12 opacity-[0.03] text-[15rem] pointer-events-none transform rotate-12">🚀</div>
        
        <div class="p-8 md:p-10 relative z-10">
            <div class="mb-8 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-white font-black text-xs">1</span>
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-widest text-sm">Form Kenaikan Massal</h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium italic">Seluruh riwayat tabungan siswa akan otomatis terbawa ke kelas baru.</p>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/akademik/proses_kenaikan" method="POST" class="space-y-10" onsubmit="return confirm('Proses ini bersifat permanen. Apakah Anda yakin ingin memigrasi seluruh siswa di kelas tersebut?')">
                <?= Security::csrf_field(); ?>
                
                <!-- HORIZONTAL FLOW LAYOUT -->
                <div class="flex flex-col lg:flex-row items-center gap-6 lg:gap-8">
                    
                    <!-- BOX: KELAS ASAL -->
                    <div class="w-full lg:w-5/12 bg-slate-50 rounded-3xl p-6 md:p-8 border-2 border-dashed border-slate-200 relative group hover:border-slate-300 transition-colors">
                        <div class="absolute -top-3 left-6 bg-slate-200 text-slate-600 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                            Tingkat Saat Ini
                        </div>
                        <div class="space-y-3 mt-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase">Dari Kelas (Lama)</label>
                            <div class="relative">
                                <select name="dari_kelas" id="dari_kelas" required class="w-full appearance-none px-6 py-4 bg-white border border-slate-200 rounded-2xl text-base font-black text-slate-700 outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-400 transition-all cursor-pointer shadow-sm">
                                    <option value="" disabled selected>-- Pilih Kelas Asal --</option>
                                    <?php foreach($kelas_list as $k): ?>
                                        <?php 
                                        $nama_upper = strtoupper($k['nama_kelas']);
                                        // Filter: Sembunyikan KESISWAAN dan Kelas Tingkat Akhir (XII / IX)
                                        if(strpos($nama_upper, 'KESISWAAN') === false && strpos($nama_upper, 'XII') !== 0 && strpos($nama_upper, 'IX') !== 0): 
                                        ?>
                                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ARROW DIVIDER -->
                    <div class="flex-shrink-0 flex items-center justify-center">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 text-white rounded-full flex items-center justify-center text-2xl shadow-lg shadow-emerald-500/40 transform lg:rotate-0 rotate-90 transition-transform duration-500 hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>

                    <!-- BOX: KELAS TUJUAN -->
                    <div class="w-full lg:w-5/12 bg-emerald-50/50 rounded-3xl p-6 md:p-8 border-2 border-dashed border-emerald-200 relative group hover:border-emerald-300 transition-colors">
                        <div class="absolute -top-3 left-6 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                            Tingkat Selanjutnya
                        </div>
                        <div class="space-y-3 mt-2">
                            <label class="block text-xs font-bold text-emerald-600 uppercase">Ke Kelas (Baru)</label>
                            <div class="relative">
                                <select name="ke_kelas" id="ke_kelas" required class="w-full appearance-none px-6 py-4 bg-white border border-emerald-200 rounded-2xl text-base font-black text-slate-800 outline-none focus:ring-4 focus:ring-emerald-50 focus:border-emerald-400 transition-all cursor-pointer shadow-sm opacity-50" disabled>
                                    <option value="" disabled selected>Menunggu Pilihan Asal...</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-emerald-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" id="btn_submit" disabled class="w-full md:w-auto md:px-12 py-4 bg-slate-900 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-slate-900/20 hover:bg-black hover:-translate-y-1 transition-all duration-300 active:scale-95 flex items-center justify-center gap-3 opacity-50 cursor-not-allowed mx-auto">
                        <span>Eksekusi Migrasi Kelas</span>
                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SMART SCRIPT UNTUK STRICT PREDIKSI KELAS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dariKelas = document.getElementById('dari_kelas');
    const keKelas = document.getElementById('ke_kelas');
    const btnSubmit = document.getElementById('btn_submit');
    
    // Master Kelas tetap memuat Kelas XII agar bisa digunakan sebagai kelas tujuan
    const masterKelas = [
        <?php foreach($kelas_list as $k): ?>
            { id: "<?= $k['id'] ?>", nama: "<?= htmlspecialchars($k['nama_kelas']) ?>" },
        <?php endforeach; ?>
    ];

    dariKelas.addEventListener('change', function() {
        const asalNama = this.options[this.selectedIndex].text.trim();
        
        // Reset UI State
        keKelas.innerHTML = '';
        keKelas.disabled = false;
        keKelas.classList.remove('opacity-50');
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');

        let targetNama = "";
        if (asalNama.startsWith("X ")) {
            targetNama = asalNama.replace("X ", "XI ");
        } else if (asalNama.startsWith("XI ")) {
            targetNama = asalNama.replace("XI ", "XII ");
        }

        let targetDitemukan = masterKelas.find(k => k.nama === targetNama);

        if (targetDitemukan) {
            const option = document.createElement('option');
            option.value = targetDitemukan.id;
            option.text = targetDitemukan.nama;
            option.selected = true;
            keKelas.appendChild(option);
            
            // Tambahkan animasi pulse sukses pada field tujuan
            keKelas.classList.add('ring-4', 'ring-emerald-100', 'border-emerald-400');
            setTimeout(() => keKelas.classList.remove('ring-4', 'ring-emerald-100'), 1000);
            
        } else {
            keKelas.innerHTML = `<option value="" disabled selected>-- Error: Kelas ${targetNama} belum dibuat --</option>`;
            keKelas.disabled = true;
            keKelas.classList.add('opacity-50');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            alert(`Sistem mendeteksi bahwa kelas tujuan (${targetNama}) belum ada di database. Silakan buat kelas tersebut terlebih dahulu di menu Master Kelas.`);
        }
    });
});
</script>

<style>
/* Animasi masuk sederhana */
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-down {
    animation: fadeInDown 0.4s ease-out forwards;
}
</style>