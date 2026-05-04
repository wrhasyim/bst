<!-- views/admin/akademik/kelulusan.php -->
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    
    <!-- HEADER SECTION -->
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-3xl shadow-lg shadow-indigo-500/30">
            🎓
        </div>
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">Kelulusan<span class="text-indigo-500">Alumni</span></h2>
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">Nonaktifkan Siswa Tingkat Akhir (Lulus)</p>
        </div>
    </div>

    <!-- ALERT NOTIFICATIONS -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-5 rounded-r-2xl flex items-center gap-4 shadow-sm animate-fade-in-down">
            <div class="text-indigo-500 text-xl">✅</div>
            <div>
                <p class="text-indigo-800 font-bold text-sm"><?= $_SESSION['success'] ?></p>
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
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-bl-full opacity-50 pointer-events-none"></div>
        <div class="absolute -right-8 -bottom-8 opacity-[0.03] text-[12rem] pointer-events-none transform -rotate-12">📜</div>
        
        <div class="p-8 md:p-10 relative z-10">
            <div class="mb-8 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-white font-black text-xs">!</span>
                <div>
                    <h3 class="font-black text-slate-800 uppercase tracking-widest text-sm text-red-600">Perhatian: Tindakan Permanen</h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium italic">Siswa pada kelas yang dipilih akan diubah statusnya menjadi <b>Alumni (Non-aktif)</b>. Saldo mereka tetap aman di sistem.</p>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/akademik/proses_kelulusan" method="POST" class="space-y-8" id="formKelulusan">
                
                <!-- BOX: KELAS YANG AKAN DILULUSKAN -->
                <div class="w-full bg-slate-50 rounded-3xl p-6 md:p-8 border-2 border-dashed border-slate-200 relative group hover:border-indigo-300 transition-colors">
                    <div class="absolute -top-3 left-6 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md">
                        Pilih Tingkat Akhir (XII / IX)
                    </div>
                    <div class="space-y-3 mt-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase">Pilih Kelas yang Akan Diluluskan</label>
                        <div class="relative max-w-xl mx-auto">
                            <select name="kelas_id" id="kelas_id" required class="w-full appearance-none px-6 py-4 bg-white border border-slate-200 rounded-2xl text-lg font-black text-slate-700 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 transition-all cursor-pointer shadow-sm text-center">
                                <?php 
                                // LOGIKA PENCEGAHAN: Cek apakah ada kelas XII atau IX di database
                                $ada_kelas_akhir = false;
                                foreach($kelas_list as $k) {
                                    $nama_upper = strtoupper(trim($k['nama_kelas']));
                                    if (strpos($nama_upper, 'XII') === 0 || strpos($nama_upper, 'IX') === 0) {
                                        $ada_kelas_akhir = true;
                                        break;
                                    }
                                }
                                ?>

                                <?php if(!$ada_kelas_akhir): ?>
                                    <option value="" disabled selected>-- Belum ada Kelas Tingkat Akhir (XII / IX) --</option>
                                <?php else: ?>
                                    <option value="" disabled selected>-- Pilih Kelas Tingkat Akhir --</option>
                                    
                                    <?php 
                                    // LOOPING KHUSUS KELAS AKHIR
                                    foreach($kelas_list as $k): 
                                        $nama_kelas_upper = strtoupper(trim($k['nama_kelas']));
                                        
                                        // Filter Mutlak: Hanya kelas yang berawalan "XII" atau "IX" yang boleh dirender!
                                        // Kas Kesiswaan juga otomatis tersingkir dengan aturan ini.
                                        if (strpos($nama_kelas_upper, 'XII') === 0 || strpos($nama_kelas_upper, 'IX') === 0): 
                                    ?>
                                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                                    <?php 
                                        endif; 
                                    endforeach; 
                                    ?>
                                <?php endif; ?>
                            </select>
                            
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-6 border-t border-slate-100">
                    <button type="button" id="btn_submit" <?= !$ada_kelas_akhir ? 'disabled class="w-full md:w-auto md:px-12 py-4 bg-slate-300 text-slate-500 text-xs font-black uppercase tracking-[0.2em] rounded-2xl mx-auto flex items-center justify-center cursor-not-allowed"' : 'class="w-full md:w-auto md:px-12 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-indigo-600/30 hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300 active:scale-95 flex items-center justify-center gap-3 mx-auto"' ?>>
                        <span>🎓 Eksekusi Kelulusan Alumni</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SWEETALERT-STYLE CONFIRMATION VIA VANILLA JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnSubmit = document.getElementById('btn_submit');
    const selectKelas = document.getElementById('kelas_id');
    const form = document.getElementById('formKelulusan');

    // Jika tombol disabled (karena tidak ada kelas akhir), matikan fungsi klik
    if(btnSubmit.disabled) return;

    btnSubmit.addEventListener('click', function() {
        if (selectKelas.value === "") {
            alert("⚠️ Harap pilih kelas terlebih dahulu!");
            selectKelas.focus();
            return;
        }

        const namaKelas = selectKelas.options[selectKelas.selectedIndex].text;
        const warningText = `TINDAKAN PERMANEN!\n\nAnda yakin ingin meluluskan seluruh siswa di kelas ${namaKelas}?\n\nStatus mereka akan diubah menjadi Alumni (Non-aktif) dan tidak akan muncul lagi di kelas reguler.`;

        if (confirm(warningText)) {
            // Animasi loading pada tombol
            btnSubmit.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses Kelulusan...`;
            btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');
            form.submit();
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