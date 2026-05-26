<div class="max-w-4xl mx-auto space-y-8 pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">IMPORT<span class="text-emerald-500">NASABAH</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Pendaftaran Nasabah Massal dengan Auto-Mapping Kelas</p>
        </div>
        <a href="<?= BASE_URL ?>/user" class="px-5 py-2 bg-slate-100 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all">🔙 Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-widest border-b pb-4 mb-4">Panduan Auto-Mapping</h3>
                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <span class="w-5 h-5 bg-emerald-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shrink-0">1</span>
                        <p class="text-[10px] font-bold text-slate-500 uppercase leading-relaxed">Unduh template CSV terbaru yang telah memuat 4 kolom (Nama, Username, Kelas, Angkatan).</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 bg-emerald-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shrink-0">2</span>
                        <p class="text-[10px] font-bold text-slate-500 uppercase leading-relaxed">Jika nama kelas di CSV belum terdaftar di sistem, <b>sistem akan otomatis membuatkannya</b>.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 bg-emerald-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shrink-0">3</span>
                        <p class="text-[10px] font-bold text-slate-500 uppercase leading-relaxed">Simpan file (.csv) dan upload untuk memproses ratusan siswa lintas kelas sekaligus.</p>
                    </li>
                </ul>
            </div>

            <a href="<?= BASE_URL ?>/user/download_template" class="block w-full py-4 bg-slate-900 text-white text-center text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all shadow-lg">
                📥 Download Template CSV
            </a>
        </div>

        <div class="md:col-span-2">
            <form action="<?= BASE_URL ?>/user/proses_import" method="POST" enctype="multipart/form-data" class="bg-white p-10 rounded-[3rem] border border-slate-200 shadow-sm space-y-8">
                
                <div class="border-4 border-dashed border-slate-100 rounded-[2rem] p-10 text-center space-y-4">
                    <div class="text-5xl">📁</div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pilih File CSV Data Siswa</label>
                        <input type="file" name="file_csv" accept=".csv" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    </div>
                </div>

                <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                    <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide italic">
                        *Sistem Auto-Mappers Aktif: Anda bisa meng-import siswa dari berbagai kelas yang berbeda sekaligus dalam 1 file CSV. (Password Default Siswa: 123456)
                    </p>
                </div>

                <button type="submit" class="w-full py-5 bg-emerald-600 text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100">
                    🚀 Jalankan Import Terintegrasi
                </button>
            </form>
        </div>
    </div>
</div>