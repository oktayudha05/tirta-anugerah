# Fitur Pembayaran Pemasangan (Cicilan + DP)
## Latar Belakang
Saat ini, ketika warga baru didaftarkan, tidak ada mekanisme tagihan biaya pemasangan. Fitur ini akan menambahkan sistem **tagihan pemasangan** yang otomatis muncul setelah warga/rumah baru terdaftar, dengan dukungan **DP (Down Payment)** dan **cicilan bulanan**.
Menu "Pembayaran" di navbar akan dipecah menjadi dropdown/sub-menu:
- **Pembayaran Air** (fitur yang sudah ada)
- **Pembayaran Pemasangan** (fitur baru)
---
## User Review Required
> [!IMPORTANT]
> **Perubahan Navbar**: Menu "Pembayaran" akan berubah menjadi dropdown menu dengan 2 sub-menu. Ini mengubah navigasi yang sudah ada.
> [!IMPORTANT]
> **Trigger Tagihan**: Tagihan pemasangan otomatis dibuat saat `WargaController@store` dijalankan (saat tambah warga baru). Warga yang sudah terdaftar sebelumnya TIDAK akan mendapat tagihan pemasangan.
> [!WARNING]
> **Migration baru**: Akan ada 2 tabel baru (`biaya_pemasangans` dan `pembayaran_pemasangans`). Pastikan `php artisan migrate` dijalankan setelah implementasi.
---
## Open Questions
> [!IMPORTANT]
> **Default biaya pemasangan**: Apakah perlu ada nilai default awal untuk biaya pemasangan, atau pengelola harus set dulu sebelum bisa mendaftarkan warga baru?
> [!IMPORTANT]
> **Minimum DP**: Apakah ada minimum persentase/nominal DP, atau bebas? (Dalam plan ini saya asumsikan DP bebas, termasuk Rp 0.)
> [!IMPORTANT]
> **Riwayat cicilan**: Apakah perlu tampilkan detail riwayat tiap cicilan (tanggal bayar, nominal per cicilan), atau cukup total sudah dibayar dan sisa?
---
## Proposed Changes
### Ringkasan Alur Kerja
```
1. Pengelola → Set biaya pemasangan (harga + bisa di-update kapan saja)
2. Pengelola → Daftarkan warga baru (WargaController@store)
3. Sistem → Otomatis buat record tagihan pemasangan untuk warga tsb
4. Petugas/Pengelola → Buka halaman "Pembayaran Pemasangan"
5. Petugas/Pengelola → Input pembayaran (DP atau cicilan)
6. Sistem → Update sisa tagihan, catat riwayat bayar
```
---
### Database (2 Migration Baru)
#### [NEW] [create_biaya_pemasangans_table.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/database/migrations/)
Tabel untuk menyimpan **setting harga pemasangan** (dynamic pricing oleh pengelola).
```php
Schema::create('biaya_pemasangans', function (Blueprint $table) {
    $table->id();
    $table->integer('biaya');           // Nominal biaya pemasangan (misal: 2000000)
    $table->string('berlaku_mulai');    // Format 'YYYY-MM', kapan harga ini mulai berlaku
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BigInt | Primary Key |
| biaya | Integer | Nominal biaya pemasangan (Rp) |
| berlaku_mulai | String | Format `YYYY-MM`, periode mulai berlaku |
| is_active | Boolean | Default true |
---
#### [NEW] [create_pembayaran_pemasangans_table.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/database/migrations/)
Tabel untuk menyimpan **tagihan & riwayat pembayaran pemasangan** per warga.
```php
Schema::create('pembayaran_pemasangans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('warga_id')->constrained('wargas')->cascadeOnDelete();
    $table->integer('total_biaya');       // Total biaya pemasangan saat didaftarkan
    $table->integer('total_dibayar')->default(0);  // Akumulasi semua pembayaran
    $table->string('status')->default('belum_lunas'); // 'belum_lunas' atau 'lunas'
    $table->timestamps();
});
```
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BigInt | Primary Key |
| warga_id | ForeignId | Relasi ke `wargas` |
| total_biaya | Integer | Total biaya pemasangan yang harus dibayar |
| total_dibayar | Integer | Akumulasi semua pembayaran (DP + cicilan) |
| status | String | `belum_lunas` atau `lunas` |
> **Catatan**: Kita TIDAK membuat tabel terpisah untuk riwayat cicilan agar tetap simpel. Kolom `total_dibayar` cukup di-update setiap kali ada pembayaran masuk. Jika nanti perlu detail riwayat, bisa ditambahkan tabel baru.
---
### Model (2 Model Baru)
#### [NEW] [BiayaPemasangan.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/app/Models/BiayaPemasangan.php)
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BiayaPemasangan extends Model
{
    protected $table = 'biaya_pemasangans';
    protected $fillable = ['biaya', 'berlaku_mulai', 'is_active'];
    // Ambil biaya pemasangan yang aktif saat ini
    public static function getBiayaAktif()
    {
        return self::where('berlaku_mulai', '<=', date('Y-m'))
                   ->orderBy('berlaku_mulai', 'desc')
                   ->first();
    }
}
```
- Pola sama persis seperti [Pembayaran.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/app/Models/Pembayaran.php) (`getTarifAktif`)
- Method `getBiayaAktif()` → return biaya pemasangan yang berlaku saat ini
---
#### [NEW] [PembayaranPemasangan.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/app/Models/PembayaranPemasangan.php)
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PembayaranPemasangan extends Model
{
    protected $table = 'pembayaran_pemasangans';
    protected $fillable = [
        'warga_id', 'total_biaya', 'total_dibayar', 'status',
    ];
    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
    // Hitung sisa tagihan
    public function getSisaAttribute()
    {
        return $this->total_biaya - $this->total_dibayar;
    }
}
```
---
#### [MODIFY] [Warga.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/app/Models/Warga.php)
Tambahkan relasi ke `PembayaranPemasangan`:
```diff
+    public function pembayaranPemasangan()
+    {
+        return $this->hasOne(PembayaranPemasangan::class);
+    }
```
---
### Controller (1 Baru, 1 Modify)
#### [NEW] [PembayaranPemasanganController.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/app/Http/Controllers/PembayaranPemasanganController.php)
Controller baru dengan 3 method:
**1. `index(Request $request)`** — Halaman utama pembayaran pemasangan
- Akses: pengelola & petugas
- Query semua warga yang punya tagihan pemasangan (`pembayaran_pemasangans`)
- Filter: search (nama/no. meteran), status (lunas/belum_lunas)
- Return view `pembayaran-pemasangans.index`
**2. `bayar(Request $request, $id)`** — Proses pembayaran cicilan/DP
- Akses: pengelola & petugas
- Validasi: `jumlah` harus numeric, min 1, max sisa tagihan
- Update `total_dibayar` += jumlah
- Jika `total_dibayar` >= `total_biaya` → set `status` = `lunas`
- Redirect back dengan flash message
**3. `updateBiaya(Request $request)`** — Set/update biaya pemasangan
- Akses: **pengelola saja**
- Validasi: `biaya` required integer min 0, `berlaku_mulai` required format Y-m
- `updateOrCreate` di tabel `biaya_pemasangans`
- Redirect back dengan flash message
---
#### [MODIFY] [WargaController.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/app/Http/Controllers/WargaController.php)
Di method `store()`, tambahkan logika auto-create tagihan pemasangan setelah warga berhasil dibuat:
```diff
  public function store(Request $request)
  {
      // ... validasi tetap sama ...
      $warga = Warga::create($request->only('nama', 'dusun', 'rt', 'rw', 'nomor_meteran'));
+     // Auto-buat tagihan pemasangan
+     $biaya = \App\Models\BiayaPemasangan::getBiayaAktif();
+     if ($biaya) {
+         \App\Models\PembayaranPemasangan::create([
+             'warga_id' => $warga->id,
+             'total_biaya' => $biaya->biaya,
+             'total_dibayar' => 0,
+             'status' => 'belum_lunas',
+         ]);
+     }
      return redirect()->route('wargas.index')->with('success', 'Data warga berhasil ditambahkan.');
  }
```
> Jika belum ada biaya pemasangan yang di-set, tagihan TIDAK dibuat (agar tidak error).
---
### Routes
#### [MODIFY] [web.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/routes/web.php)
Tambahkan route baru:
```diff
+ use App\Http\Controllers\PembayaranPemasanganController;
  // Route khusus pengelola (set biaya pemasangan)
  Route::middleware(['auth', 'role:pengelola'])->group(function () {
      // ... route existing ...
+     Route::post('pembayaran-pemasangan/biaya', [PembayaranPemasanganController::class, 'updateBiaya'])
+         ->name('pembayaran-pemasangan.update-biaya');
  });
  // Route pengelola + petugas (lihat & bayar)
  Route::middleware(['auth', 'role:pengelola,petugas'])->group(function () {
      // ... route existing ...
+     Route::get('pembayaran-pemasangan', [PembayaranPemasanganController::class, 'index'])
+         ->name('pembayaran-pemasangan.index');
+     Route::patch('pembayaran-pemasangan/{id}/bayar', [PembayaranPemasanganController::class, 'bayar'])
+         ->name('pembayaran-pemasangan.bayar');
  });
```
---
### Views (1 Baru, 1 Modify)
#### [MODIFY] [navigation.blade.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/resources/views/layouts/navigation.blade.php)
Ubah link "Pembayaran" menjadi **dropdown** dengan 2 sub-menu:
**Desktop Navbar** (line ~34-38): Ganti link `<a>` tunggal menjadi dropdown Alpine.js:
```html
<!-- Dropdown Pembayaran -->
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" @click.away="open = false"
        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 inline-flex items-center gap-1
               {{ request()->routeIs('pembayaran.*') || request()->routeIs('pembayaran-pemasangan.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
        Pembayaran
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-transition class="absolute left-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
        <a href="{{ route('pembayaran.index') }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#F0F8A4]/30 {{ request()->routeIs('pembayaran.*') ? 'bg-[#F0F8A4]/20 font-semibold' : '' }}">
            Pembayaran Air
        </a>
        <a href="{{ route('pembayaran-pemasangan.index') }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#F0F8A4]/30 {{ request()->routeIs('pembayaran-pemasangan.*') ? 'bg-[#F0F8A4]/20 font-semibold' : '' }}">
            Pembayaran Pemasangan
        </a>
    </div>
</div>
```
**Mobile Navbar** (line ~130-137): Ubah menjadi group dengan 2 link indent:
```html
<!-- Label Group -->
<div class="px-4 py-2 text-[10px] text-white/50 uppercase font-bold tracking-wider">Pembayaran</div>
<a href="{{ route('pembayaran.index') }}" class="...pl-8...">Pembayaran Air</a>
<a href="{{ route('pembayaran-pemasangan.index') }}" class="...pl-8...">Pembayaran Pemasangan</a>
```
---
#### [NEW] [index.blade.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/resources/views/pembayaran-pemasangans/index.blade.php)
Halaman baru, mengikuti **desain yang sama persis** dengan [pembayarans/index.blade.php](file:///home/oktayudha05/Documents/coding/pamsimas/pamsimas/resources/views/pembayarans/index.blade.php):
**Layout halaman terdiri dari:**
1. **Header** — Judul "Pembayaran Pemasangan" + filter search + filter status (dropdown: Semua / Belum Lunas / Lunas)
2. **Panel Setting Biaya** (hanya pengelola) — Form untuk set biaya pemasangan baru
   - Input: `biaya` (nominal Rp) + `berlaku_mulai` (month picker)
   - Tabel riwayat biaya pemasangan yang pernah di-set
   - Desain identik dengan panel "Pengaturan Tarif Air Baru" yang sudah ada
3. **Tabel Desktop** (`hidden md:block`) — Kolom:
   | Warga/Dusun | Total Biaya | Sudah Dibayar | Sisa Tagihan | Status | Aksi |
   - Status badge: hijau (Lunas) / merah (Belum Lunas)
   - Tombol "Bayar" → buka modal
4. **Card List Mobile** (`md:hidden`) — Mirip card pembayaran air:
   - Nama warga + badge dusun
   - Total biaya pemasangan (angka besar)
   - Progress: Sudah dibayar / Sisa
   - Status badge
   - Tombol "Bayar" full width
5. **Modal Pembayaran** (desktop & mobile) — Sama pattern-nya:
   - Info: Nama warga, total biaya, sudah dibayar, sisa tagihan
   - Input: Jumlah dibayarkan (Rp)
   - Catatan: "Pembayaran ini akan mengurangi sisa tagihan pemasangan"
   - Tombol: Batal / Simpan Pembayaran
**Pola desain yang dipakai (copy dari existing):**
- Warna: `bg-[#36656B]`, `text-[#36656B]`, `bg-[#F0F8A4]`, `border-[#DAD887]`, `bg-[#75B06F]`
- Rounded: `rounded-2xl` untuk card, `rounded-xl` untuk input/button
- Font: Inter (sudah di layout)
- Shadow: `shadow-sm`
- Table header: `bg-[#36656B] text-white text-[10px] uppercase tracking-wider`
- Modal: `<dialog>` element dengan `backdrop:bg-black/50`
---
## Verification Plan
### Automated Tests
Tidak ada automated test yang dijalankan. Verifikasi dilakukan secara manual.
### Manual Verification
1. **Jalankan migration:**
   ```bash
   php artisan migrate
   ```
   Pastikan 2 tabel baru (`biaya_pemasangans`, `pembayaran_pemasangans`) berhasil dibuat.
2. **Test set biaya pemasangan (sebagai pengelola):**
   - Buka halaman Pembayaran Pemasangan
   - Isi form biaya pemasangan (misal Rp 2.000.000, berlaku mulai 2026-07)
   - Pastikan muncul di tabel riwayat biaya
3. **Test pendaftaran warga baru:**
   - Daftarkan warga baru di halaman Daftar Rumah
   - Buka halaman Pembayaran Pemasangan
   - Pastikan warga baru muncul dengan tagihan sesuai biaya yang di-set
4. **Test pembayaran DP + cicilan:**
   - Bayar DP (misal Rp 500.000 dari total Rp 2.000.000)
   - Pastikan sisa tagihan terupdate (Rp 1.500.000)
   - Bayar cicilan berikutnya (misal Rp 300.000)
   - Pastikan sisa tagihan terupdate (Rp 1.200.000)
   - Bayar sisa (Rp 1.200.000)
   - Pastikan status berubah jadi "Lunas"
5. **Test akses role:**
   - Login sebagai **petugas** → pastikan TIDAK bisa set biaya, tapi BISA bayar
   - Login sebagai **pengelola** → pastikan BISA set biaya DAN bayar
6. **Test navbar:**
   - Hover "Pembayaran" di desktop → pastikan dropdown muncul dengan 2 sub-menu
   - Buka hamburger menu di mobile → pastikan ada 2 link pembayaran
7. **Test responsive:**
   - Buka di browser mobile (atau resize window)
   - Pastikan card mobile tampil rapi, modal responsive
