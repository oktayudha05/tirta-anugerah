# 📋 Issue: Fitur Edit Pencatatan Meteran Air

## 🎯 Tujuan
Saat ini, setelah data meteran disimpan, tidak ada cara untuk mengubahnya jika ada salah input.  
Fitur ini memungkinkan pengelola/petugas untuk **mengedit angka meteran** yang sudah diinput sebelumnya.

---

## 🧩 Konteks Sistem (Baca Dulu Sebelum Coding!)

- **Halaman utama:** `resources/views/pencatatans/index.blade.php`
- **Controller:** `app/Http/Controllers/PencatatanController.php`
- **Model:** `app/Models/Pencatatan.php`
- **Tabel database:** `pencatatans` — kolom penting:
  - `id`, `warga_id`, `bulan`, `angka_meteran`, `pemakaian`, `dibayar`, `titip`, `user_id`
- **Perhatian khusus:** Saat `store`, sistem otomatis hitung `pemakaian`, `titip` (tagihan), dan `saldoAwal` dari bulan lalu. Logika ini harus dipakai ulang saat `update`.

---

## 📌 Aturan Bisnis yang Harus Dipatuhi

1. **Angka meteran baru tidak boleh lebih kecil dari angka meteran bulan sebelumnya.**
2. **Pemakaian** dihitung otomatis: `angka_baru - angka_lalu`
3. **Tagihan (titip)** dihitung ulang berdasarkan pemakaian baru × tarif aktif + dana meter + saldo bulan lalu
4. **Jika tagihan sudah dibayar (`dibayar > 0`):** perlu diskusi apakah tetap boleh edit atau diblokir.

---

## 🗂️ Daftar Tugas

### Tahap 1 — Backend (PHP/Laravel)

#### 1.1. Tambah method `edit` di PencatatanController

**File:** `app/Http/Controllers/PencatatanController.php`

```php
public function edit($id)
{
    $pencatatan = Pencatatan::with('warga')->findOrFail($id);
    $bulan = $pencatatan->bulan;
    return view('pencatatans.edit', compact('pencatatan', 'bulan'));
}
```

---

#### 1.2. Tambah method `update` di PencatatanController

**File:** `app/Http/Controllers/PencatatanController.php`

```php
public function update(Request $request, $id)
{
    $pencatatan = Pencatatan::with('warga')->findOrFail($id);

    $request->validate([
        'angka_meteran' => ['required', 'integer', 'min:0'],
    ]);

    $wargaId   = $pencatatan->warga_id;
    $bulan     = $pencatatan->bulan;
    $angkaBaru = $request->angka_meteran;

    // Ambil pencatatan bulan sebelumnya
    $pencatatanLalu = Pencatatan::where('warga_id', $wargaId)
        ->where('bulan', '<', $bulan)
        ->orderBy('bulan', 'desc')
        ->first();

    $angkaLalu = $pencatatanLalu ? $pencatatanLalu->angka_meteran : 0;

    // Validasi: angka baru tidak boleh lebih kecil dari angka lalu
    if ($angkaBaru < $angkaLalu) {
        return back()->withErrors([
            'angka_meteran' => "Angka meteran baru ($angkaBaru) tidak boleh lebih kecil dari angka meteran sebelumnya ($angkaLalu)."
        ])->withInput();
    }

    // Hitung ulang pemakaian
    $pemakaian = $angkaBaru - $angkaLalu;

    // Hitung ulang tagihan
    $warga      = $pencatatan->warga;
    $tarif      = \App\Models\Pembayaran::getTarifAktif($warga->dusun, $bulan);
    $hargaMeter = $tarif ? $tarif->harga_per_meter : 0;
    $danaMeter  = $tarif ? $tarif->dana_meter : 0;
    $tagihanBaru = ($pemakaian * $hargaMeter) + $danaMeter;

    $saldoAwal         = $pencatatanLalu ? $pencatatanLalu->titip : 0;
    $totalHarusDibayar = $tagihanBaru + $saldoAwal;

    // Simpan perubahan
    $pencatatan->update([
        'angka_meteran' => $angkaBaru,
        'pemakaian'     => $pemakaian,
        'titip'         => $totalHarusDibayar,
        'user_id'       => auth()->id(),
    ]);

    return redirect()->route('pencatatans.index', ['bulan' => $bulan])
        ->with('success', 'Pencatatan berhasil diperbarui.');
}
```

---

#### 1.3. Daftarkan route `edit` dan `update`

**File:** `routes/web.php`

```php
// SEBELUM:
Route::resource('pencatatans', PencatatanController::class)->only(['index', 'store']);

// SESUDAH:
Route::resource('pencatatans', PencatatanController::class)->only(['index', 'store', 'edit', 'update']);
```

> ℹ️ `Route::resource` dengan `edit` dan `update` secara otomatis membuat:
> - `GET /pencatatans/{pencatatan}/edit` → method `edit()`
> - `PUT/PATCH /pencatatans/{pencatatan}` → method `update()`

---

### Tahap 2 — Frontend (Blade/HTML)

#### 2.1. Tambah tombol "Edit" di tabel Desktop

**File:** `resources/views/pencatatans/index.blade.php`

Di bagian kolom **Aksi / Pencatat** (sekitar baris 138), ubah dari hanya nama pencatat menjadi:

```blade
@if($warga->pencatatan_sekarang)
    <div class="flex flex-col items-center gap-1">
        <div class="text-xs text-gray-400">
            Dicatat: {{ $warga->pencatatan_sekarang->user->nama ?? 'Sistem' }}
        </div>
        <a href="{{ route('pencatatans.edit', $warga->pencatatan_sekarang->id) }}"
           class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold px-3 py-1 rounded-lg shadow-sm transition">
            ✏️ Edit
        </a>
    </div>
```

---

#### 2.2. Tambah tombol "Edit" di kartu Mobile

**File:** `resources/views/pencatatans/index.blade.php`

Di bagian mobile (sekitar baris 231), tambahkan tombol di bawah teks "Dicatat oleh":

```blade
<div class="text-xs text-center text-gray-400 ...">
    Dicatat oleh: <span>{{ $warga->pencatatan_sekarang->user->nama ?? 'Sistem' }}</span>
</div>
<a href="{{ route('pencatatans.edit', $warga->pencatatan_sekarang->id) }}"
   class="w-full text-center bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition block mt-2">
    ✏️ Edit Pencatatan
</a>
```

---

#### 2.3. Buat halaman form edit (file baru)

**File baru:** `resources/views/pencatatans/edit.blade.php`

```blade
<x-app-layout>
    <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm mb-6">
        <h1 class="text-xl font-bold text-[#36656B] mb-1">Edit Pencatatan Meteran</h1>
        <p class="text-xs text-gray-400">
            Warga: <strong>{{ $pencatatan->warga->nama }}</strong> |
            Bulan: <strong>{{ $bulan }}</strong>
        </p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 text-sm px-4 py-3 rounded-xl border border-red-200">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
        <form action="{{ route('pencatatans.update', $pencatatan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Angka Meteran Baru
                </label>
                <input type="number"
                       name="angka_meteran"
                       value="{{ old('angka_meteran', $pencatatan->angka_meteran) }}"
                       min="0"
                       required
                       class="w-full px-4 py-2 border border-[#DAD887] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B]">
                <p class="text-xs text-gray-400 mt-1">
                    Nilai sebelumnya: {{ $pencatatan->angka_meteran }}
                </p>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-6 py-2 rounded-xl shadow-sm transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('pencatatans.index', ['bulan' => $bulan]) }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2 rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
```

---

## ✅ Checklist Implementasi

- [x] **1.1** Tambah method `edit()` di `PencatatanController.php`
- [x] **1.2** Tambah method `update()` di `PencatatanController.php`
- [x] **1.3** Daftarkan route `edit` dan `update` di `routes/web.php`
- [x] **2.1** Tambah tombol Edit di tabel desktop (`index.blade.php`)
- [x] **2.2** Tambah tombol Edit di kartu mobile (`index.blade.php`)
- [x] **2.3** Buat file `resources/views/pencatatans/edit.blade.php`
- [ ] **Test manual:** Buka halaman pencatatan → klik Edit → ubah angka → klik Simpan → cek data berubah dan pemakaian terhitung ulang

---

## ⚠️ Hal yang Perlu Diperhatikan

### Apakah boleh edit jika tagihan sudah dibayar?

Saat ini tidak ada pembatasan. Ada dua pilihan:

- **Opsi A (Aman):** Blokir edit jika `dibayar > 0`
- **Opsi B (Fleksibel):** Tetap izinkan, tapi hitung ulang pemakaian dan tagihan

> 💡 Rekomendasi: Gunakan **Opsi A** untuk versi pertama. Lebih aman.

Jika memilih Opsi A, tambahkan di awal method `update()`:
```php
if ($pencatatan->dibayar > 0) {
    return back()->withErrors([
        'angka_meteran' => 'Data ini sudah dibayar dan tidak bisa diubah.'
    ]);
}
```

### Dampak perubahan ke data lain
Mengubah angka meteran akan langsung mempengaruhi:
- Kolom `pemakaian` di halaman rekap
- Kolom `titip` (tagihan) yang tampil di halaman pembayaran

---

## 🔐 Akses (Role)

Fitur edit sebaiknya hanya bisa diakses oleh role `pengelola` dan `petugas`. Pastikan route berada di dalam group middleware yang tepat di `routes/web.php`:

```php
Route::middleware(['auth', 'role:pengelola,petugas'])->group(function () {
    Route::resource('pencatatans', PencatatanController::class)
        ->only(['index', 'store', 'edit', 'update']);
});
```

---

## 📁 Ringkasan File yang Diubah/Dibuat

| Status   | File                                              | Keterangan                              |
|----------|---------------------------------------------------|-----------------------------------------|
| ✏️ Ubah  | `app/Http/Controllers/PencatatanController.php`  | Tambah method `edit()` dan `update()`   |
| ✏️ Ubah  | `routes/web.php`                                 | Daftarkan route `edit` dan `update`     |
| ✏️ Ubah  | `resources/views/pencatatans/index.blade.php`    | Tambah tombol Edit di tabel & kartu     |
| 🆕 Buat  | `resources/views/pencatatans/edit.blade.php`     | Halaman form edit meteran               |

---

*Dibuat: 2026-08-04 | Prioritas: Medium | Estimasi pengerjaan: 2–4 jam*
