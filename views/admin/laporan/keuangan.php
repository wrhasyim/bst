<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">LAPORAN<span class="text-emerald-500">KEUANGAN</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Rekapitulasi Laba & Distribusi Kas</p>
        </div>
        <button onclick="window.print()" class="px-5 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg">🖨️ Cetak</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pendapatan Kotor</p>
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter">Rp<?= number_format($laporan['total_kotor'], 0, ',', '.') ?></h3>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Beban Tabungan</p>
            <h3 class="text-3xl font-black text-red-500 tracking-tighter">Rp<?= number_format($laporan['beban_nasabah'], 0, ',', '.') ?></h3>
        </div>
        <div class="bg-emerald-600 p-8 rounded-[2.5rem] text-white shadow-xl shadow-emerald-100">
            <p class="text-[10px] font-bold text-emerald-200 uppercase tracking-widest mb-2">Margin Bersih (Laba)</p>
            <h3 class="text-3xl font-black tracking-tighter">Rp<?= number_format($laporan['margin_total'], 0, ',', '.') ?></h3>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden p-8">
        <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest mb-8 border-b pb-4 italic">Alokasi Dana Berdasarkan Kebijakan</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase italic">Kas Bank Sampah</span>
                <div class="text-xl font-black text-slate-800 tracking-tighter">Rp<?= number_format($laporan['kas_bst'], 0, ',', '.') ?></div>
            </div>
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase italic">Kas Sekolah</span>
                <div class="text-xl font-black text-slate-800 tracking-tighter">Rp<?= number_format($laporan['kas_sekolah'], 0, ',', '.') ?></div>
            </div>
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase italic">Honor Pengelola</span>
                <div class="text-xl font-black text-slate-800 tracking-tighter">Rp<?= number_format($laporan['honor_pengelola'], 0, ',', '.') ?></div>
            </div>
            <div class="space-y-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase italic">Honor Wali Kelas</span>
                <div class="text-xl font-black text-slate-800 tracking-tighter">Rp<?= number_format($laporan['honor_walikelas'], 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
</div>