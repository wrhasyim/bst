<div class="max-w-7xl mx-auto space-y-8 pb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 italic uppercase tracking-tight">KASIR<span class="text-red-500">MASSAL</span></h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Penarikan Dana Tabungan Kolektif Per Kelas</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3">✅</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-bold shadow-sm flex items-center animate-in fade-in duration-300">
            <span class="mr-3">⚠️</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="<?= BASE_URL ?>/penarikan" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Langkah 1: Pilih Kelas</label>
                <select name="kelas_id" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Buka Data Kelas --</option>
                    <?php foreach($all_kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                            Kelas <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-lg">
                Tampilkan Nasabah
            </button>
        </form>
    </div>

    <?php if(isset($_GET['kelas_id'])): ?>
        <?php if(empty($siswa_list)): ?>
            <div class="p-16 text-center bg-white rounded-[3rem] border border-dashed border-slate-300">
                <span class="text-5xl mb-4 block opacity-50">📭</span>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada nasabah aktif di kelas ini.</p>
            </div>
        <?php else: ?>
            <form action="<?= BASE_URL ?>/penarikan/batch_store" method="POST" 
                  x-data="{
                      nominalGlobal: '',
                      totalSiswa: 0,
                      totalNominal: 0,
                      formatRp(angka) {
                          return new Intl.NumberFormat('id-ID').format(angka);
                      },
                      hitungRekap() {
                          this.totalSiswa = 0;
                          this.totalNominal = 0;
                          document.querySelectorAll('.input-tarik').forEach(i => {
                              let val = Number(i.value) || 0;
                              if(val > 0) {
                                  this.totalSiswa++;
                                  this.totalNominal += val;
                              }
                          });
                      },
                      terapkanSemua() {
                          document.querySelectorAll('.input-tarik').forEach(i => { 
                              if(this.nominalGlobal > 0) { 
                                  if(Number(i.max) >= Number(this.nominalGlobal)) i.value = this.nominalGlobal; 
                                  else i.value = i.max; 
                              } 
                          });
                          this.hitungRekap();
                      },
                      tarikMax() {
                          document.querySelectorAll('.input-tarik').forEach(i => i.value = i.max);
                          this.hitungRekap();
                      },
                      resetSemua() {
                          document.querySelectorAll('.input-tarik').forEach(i => i.value = '');
                          this.hitungRekap();
                      }
                  }"
                  x-init="$nextTick(() => { 
                      document.querySelectorAll('.input-tarik').forEach(i => i.addEventListener('input', () => hitungRekap()));
                      hitungRekap();
                  })"
                  class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden border-t-4 border-t-red-500">
                
                <?= Security::csrf_field(); ?> 
                <input type="hidden" name="kelas_id" value="<?= $_GET['kelas_id'] ?>">
                
                <!-- Header Keterangan -->
                <div class="p-8 border-b border-slate-50 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-6">
                    <h3 class="font-black text-slate-800 text-xs uppercase tracking-widest italic underline">Langkah 2: Input Penarikan</h3>
                    <div class="w-full md:w-1/2 lg:w-1/3">
                        <label class="block text-[10px] font-bold text-red-600 uppercase mb-2 ml-1">Keterangan Penarikan (Global)</label>
                        <input type="text" name="keterangan" required placeholder="Contoh: Pencairan Akhir Semester 1" class="w-full px-5 py-3 bg-white border border-slate-200 text-slate-800 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                    </div>
                </div>

                <!-- Panel Tombol Aksi Cepat -->
                <div class="p-6 bg-slate-50 border-b border-slate-200 flex flex-col lg:flex-row justify-between items-center gap-4">
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <input type="number" x-model="nominalGlobal" placeholder="Nominal seragam..." class="w-full sm:w-auto px-4 py-2 border border-slate-300 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-red-500 text-slate-800">
                        <button type="button" @click.prevent="terapkanSemua()" class="w-full sm:w-auto px-6 py-2 bg-slate-800 text-white text-[10px] font-black uppercase rounded-xl hover:bg-black transition-all">
                            🎯 Terapkan ke Semua
                        </button>
                    </div>
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <button type="button" @click.prevent="tarikMax()" class="flex-1 sm:flex-none px-6 py-2 bg-red-100 text-red-700 text-[10px] font-black uppercase rounded-xl hover:bg-red-200 transition-all shadow-sm">
                            💰 Tarik Saldo Max
                        </button>
                        <button type="button" @click.prevent="resetSemua()" class="flex-1 sm:flex-none px-6 py-2 bg-slate-200 text-slate-600 text-[10px] font-black uppercase rounded-xl hover:bg-slate-300 transition-all">
                            🧹 Reset
                        </button>
                    </div>
                </div>

                <!-- Tabel Nasabah -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white border-b border-gray-100 text-[10px] uppercase text-slate-400 font-black tracking-widest">
                                <th class="px-8 py-5 w-16 text-center">No</th>
                                <th class="px-8 py-5">Identitas Nasabah</th>
                                <th class="px-8 py-5 text-right">Saldo Tersedia</th>
                                <th class="px-8 py-5 text-right w-64">Jumlah Ditarik (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $no=1; foreach($siswa_list as $s): ?>
                            <tr class="hover:bg-slate-50 transition-all <?= $s['saldo_tersedia'] <= 0 ? 'opacity-50 grayscale bg-slate-50' : '' ?>">
                                <td class="px-8 py-5 text-xs font-bold text-slate-400 text-center"><?= $no++ ?></td>
                                <td class="px-8 py-5">
                                    <div class="font-black text-slate-800 text-sm uppercase italic tracking-tighter"><?= htmlspecialchars($s['nama']) ?></div>
                                    <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase">@<?= htmlspecialchars($s['username']) ?></div>
                                </td>
                                <td class="px-8 py-5 text-right font-black text-emerald-600 text-sm">
                                    Rp<?= number_format($s['saldo_tersedia'], 0, ',', '.') ?>
                                </td>
                                <td class="px-8 py-5">
                                    <?php if($s['saldo_tersedia'] > 0): ?>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400">Rp</span>
                                            <input type="number" name="jumlah_tarik[<?= $s['id'] ?>]" min="0" max="<?= $s['saldo_tersedia'] ?>" placeholder="0" class="input-tarik w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-right font-black focus:ring-2 focus:ring-red-500 outline-none transition-all text-slate-800 shadow-sm">
                                        </div>
                                    <?php else: ?>
                                        <div class="text-right text-[10px] font-bold text-red-400 uppercase tracking-widest pt-3">Saldo Kosong</div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PANEL REKAPITULASI (BARU) -->
                <div class="p-8 bg-slate-800 text-white flex flex-col md:flex-row justify-between items-center gap-6 border-t border-slate-700">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-emerald-400">Status Rekapitulasi</h4>
                        <p class="text-xs font-bold text-slate-400 mt-1">Total Nasabah: <span class="text-white text-base bg-slate-700 px-2 py-1 rounded-md ml-1" x-text="totalSiswa"></span> Orang</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Dana Disiapkan</p>
                        <p class="text-3xl font-black text-white tracking-tighter">Rp <span x-text="formatRp(totalNominal)"></span></p>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="p-8 bg-slate-50 flex justify-end">
                    <button type="submit" onclick="return confirm('Peringatan: Saldo siswa akan berkurang sesuai rekap. Lanjutkan?')" class="px-10 py-4 bg-red-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all transform active:scale-95">
                        💳 Proses & Simpan Penarikan
                    </button>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <div class="bg-slate-900 rounded-[3rem] shadow-2xl overflow-hidden text-white mt-12">
        <div class="p-8 border-b border-slate-800 bg-slate-950 flex justify-between items-center">
            <h3 class="font-black text-emerald-400 text-xs uppercase tracking-widest italic underline">Riwayat Pencairan Terakhir</h3>
            <span class="text-2xl">💸</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-[9px] uppercase text-slate-500 font-black tracking-widest">
                        <th class="px-8 py-5">Tanggal & Waktu</th>
                        <th class="px-8 py-5">Nasabah</th>
                        <th class="px-8 py-5">Keterangan</th>
                        <th class="px-8 py-5 text-right">Nominal Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    <?php if(empty($riwayat)): ?>
                        <tr><td colspan="4" class="px-8 py-10 text-center text-slate-500 text-xs italic">Belum ada riwayat penarikan uang.</td></tr>
                    <?php else: ?>
                        <?php foreach($riwayat as $r): ?>
                        <tr class="hover:bg-slate-800 transition-all">
                            <td class="px-8 py-5 text-[10px] font-bold text-slate-400"><?= date('d/m/Y | H:i', strtotime($r['tanggal_tarik'])) ?></td>
                            <td class="px-8 py-5 font-black text-slate-200 text-xs uppercase italic tracking-tighter">
                                <?= htmlspecialchars($r['nama']) ?> <span class="text-[9px] text-slate-500 not-italic ml-1">(<?= htmlspecialchars($r['nama_kelas']) ?>)</span>
                            </td>
                            <td class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase"><?= htmlspecialchars($r['keterangan']) ?></td>
                            <td class="px-8 py-5 text-right font-black text-red-400 text-sm">- Rp<?= number_format($r['jumlah'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>