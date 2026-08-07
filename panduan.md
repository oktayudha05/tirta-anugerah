# KONSEP PANDUAN PENGGUNAAN
# Sistem Informasi Air Bersih "Tirta Anugerah"

---

## METADATA DOKUMEN

```
Nama Sistem     : Sistem Informasi Air Bersih Tirta Anugerah
Versi Dokumen   : 1.0
Tujuan Dokumen  : Konsep referensi untuk pembuatan buku panduan pengguna
Target Pembaca  : AI Generator (untuk menghasilkan buku panduan lengkap)
Bahasa          : Indonesia
Tone Penulisan  : Formal, ramah, mudah dipahami pengguna non-teknis
```

---

## PETUNJUK UNTUK AI GENERATOR

> Dokumen ini adalah **konsep terstruktur** yang harus digunakan sebagai rujukan untuk menghasilkan **buku panduan lengkap**. Saat mengembangkan setiap bagian, ikuti aturan berikut:
>
> 1. **Gunakan bahasa yang sederhana** — hindari istilah teknis (PHP, Laravel, database). Ganti dengan frasa umum seperti "sistem akan mencatat otomatis" atau "klik tombol simpan".
> 2. **Tambahkan penjelasan setiap langkah** — sertakan kalimat penjelas mengapa langkah tersebut penting.
> 3. **Buat callout/catatan penting** di setiap bagian yang berpotensi membingungkan pengguna.
> 4. **Sertakan ilustrasi placeholder** dengan deskripsi jelas: `[GAMBAR: Tampilan halaman X dengan keterangan Y]`.
> 5. **Urutkan konten** sesuai alur kerja nyata pengguna, bukan urutan fitur teknis.
> 6. Setiap sub-bab harus memiliki: **Tujuan → Langkah-Langkah → Catatan Penting → Hal yang Sering Salah**.

---

## DAFTAR ISI (STRUKTUR BUKU PANDUAN)

```
BAB 1 : Pengenalan Sistem
BAB 2 : Cara Login dan Memulai
BAB 3 : Panduan Pengelola
  3.1  Manajemen Akun Pengguna
  3.2  Manajemen Data Warga
  3.3  Pengaturan Tarif Air
  3.4  Pengaturan Biaya Pemasangan
  3.5  Rekap dan Laporan Bulanan
  3.6  Export Laporan ke Excel
BAB 4 : Panduan Petugas Air
  4.1  Pencatatan Meteran Bulanan
  4.2  Pencatatan Pembayaran Tagihan
  4.3  Pembayaran Pemasangan
  4.4  Dashboard dan Statistik
BAB 5 : Fitur Bersama (Pengelola & Petugas)
  5.1  Profil Akun
  5.2  Halaman Beranda Publik
BAB 6 : Pertanyaan Umum (FAQ)
BAB 7 : Panduan Troubleshooting
```

---

## BAB 1 — PENGENALAN SISTEM

### 1.1 Apa Itu Sistem Ini?

**Konsep isi:**
Jelaskan bahwa sistem ini adalah website untuk mengelola pencatatan dan pembayaran air bersih di Dusun Sragan dan sekitarnya. Sistem menggantikan pencatatan manual menggunakan kertas dengan sistem digital berbasis web yang dapat diakses dari HP atau laptop.

**Poin yang harus dijelaskan:**
- Nama sistem: **Tirta Anugerah**
- Fungsi utama: pencatatan meteran air, penghitungan tagihan, pencatatan pembayaran, dan laporan bulanan
- Siapa yang menggunakan: ada dua jenis pengguna — **Pengelola** dan **Petugas Air**
- Keuntungan sistem: data aman, tidak hilang, bisa diakses kapan saja, tagihan dihitung otomatis

### 1.2 Perbedaan Peran Pengguna

**Konsep tabel perbandingan yang harus dibuat:**

| Fitur / Hak Akses | Pengelola | Petugas Air |
|---|---|---|
| Lihat Dashboard & Statistik | ✅ | ✅ |
| Catat Meteran Bulanan | ✅ | ✅ |
| Catat Pembayaran Tagihan | ✅ | ✅ |
| Catat Pembayaran Pemasangan | ✅ | ✅ |
| Kelola Data Warga (Tambah/Ubah/Hapus) | ✅ | ❌ |
| Kelola Akun Pengguna | ✅ | ❌ |
| Atur Tarif Air | ✅ | ❌ |
| Atur Biaya Pemasangan | ✅ | ❌ |
| Lihat Rekap & Export Excel | ✅ | ❌ |
| Edit Profil Sendiri | ✅ | ✅ |

**Catatan untuk AI:** Buat penjelasan naratif setelah tabel ini yang menjelaskan kapan seseorang diberi peran pengelola vs petugas, dan apa tanggung jawab masing-masing dalam operasional harian.

### 1.3 Halaman Publik (Beranda)

**Konsep isi:**
- Halaman yang bisa diakses siapa saja tanpa login
- Menampilkan informasi umum: total warga terlayani, total pemakaian air bulan ini, grafik tren 6 bulan terakhir, distribusi warga per wilayah (Sragan vs Luar Sragan)
- Tujuan: transparansi informasi kepada warga

---

## BAB 2 — CARA LOGIN DAN MEMULAI

### 2.1 Cara Login ke Sistem

**Konsep langkah-langkah:**

```
LANGKAH 1: Buka browser (Chrome, Firefox, atau browser lain di HP/laptop)
LANGKAH 2: Ketik alamat website di kolom alamat browser
LANGKAH 3: Halaman login akan muncul dengan dua kolom isian
LANGKAH 4: Masukkan USERNAME (bukan email, bukan nomor HP)
LANGKAH 5: Masukkan PASSWORD
LANGKAH 6: Klik tombol "Masuk" atau tekan Enter
LANGKAH 7: Jika berhasil, sistem akan mengarahkan ke halaman Dashboard
```

**Data teknis yang perlu diketahui AI:**
- Field login: `username` (bukan email) dan `password`
- Setelah login, redirect ke `/dashboard`
- Jika salah password, sistem menampilkan pesan error di halaman yang sama

**Catatan penting untuk dimasukkan ke buku:**
- Username bersifat huruf kecil semua (lowercase), contoh: `budi123` bukan `Budi123`
- Jika lupa password, hubungi pengelola untuk direset — tidak ada fitur lupa password mandiri
- Satu akun hanya boleh digunakan oleh satu orang

### 2.2 Cara Logout / Keluar dari Sistem

**Konsep langkah-langkah:**
```
LANGKAH 1: Klik nama pengguna di pojok kanan atas layar
LANGKAH 2: Pilih menu "Keluar" atau "Logout"
LANGKAH 3: Sistem akan kembali ke halaman login
```

**Catatan penting:** Selalu logout setelah selesai, terutama jika menggunakan perangkat bersama.

---

## BAB 3 — PANDUAN PENGELOLA

> Bagian ini hanya dapat dilakukan oleh pengguna dengan peran **Pengelola**. Jika Anda adalah Petugas Air, lewati ke BAB 4.

### 3.1 Manajemen Akun Pengguna

**URL Fitur:** `/akuns`
**Siapa yang bisa akses:** Pengelola saja

#### 3.1.1 Melihat Daftar Akun

**Konsep isi:**
- Menampilkan tabel semua pengguna: nama, username, dan peran (pengelola/petugas)
- Terdapat kolom pencarian untuk mencari berdasarkan nama atau username
- Setiap baris memiliki tombol **Edit** dan **Hapus**

```
LANGKAH 1: Klik menu "Akun" di navigasi
LANGKAH 2: Daftar semua akun pengguna akan ditampilkan
LANGKAH 3: Gunakan kolom pencarian jika ingin mencari akun tertentu
```

#### 3.1.2 Menambah Akun Baru

**Konsep langkah-langkah:**
```
LANGKAH 1: Di halaman daftar akun, klik tombol "Tambah Akun"
LANGKAH 2: Isi formulir:
  - Nama Lengkap (contoh: Budi Santoso)
  - Username (huruf kecil, tanpa spasi, contoh: budi123)
  - Peran: pilih "Pengelola" atau "Petugas"
  - Password baru
  - Konfirmasi password (ketik ulang password yang sama)
LANGKAH 3: Klik tombol "Simpan"
LANGKAH 4: Akun baru muncul di daftar
```

**Data validasi yang harus disampaikan ke pengguna:**
- Username harus unik (tidak boleh sama dengan yang sudah ada)
- Username harus huruf kecil semua
- Password harus memenuhi standar keamanan minimal
- Password dan konfirmasi password harus sama persis

**Catatan penting untuk buku:**
> ⚠️ Catat dan simpan username dan password akun baru dengan aman. Berikan secara langsung ke petugas yang bersangkutan, jangan kirim lewat pesan yang tidak aman.

#### 3.1.3 Mengubah Data Akun

**Konsep langkah-langkah:**
```
LANGKAH 1: Klik tombol "Edit" pada baris akun yang ingin diubah
LANGKAH 2: Ubah data yang diperlukan (nama, username, atau peran)
LANGKAH 3: Jika ingin ganti password: isi kolom "Password Baru" dan "Konfirmasi Password"
LANGKAH 4: Jika TIDAK ingin ganti password: kosongkan saja kolom password
LANGKAH 5: Klik tombol "Simpan Perubahan"
```

#### 3.1.4 Menghapus Akun

**Konsep langkah-langkah:**
```
LANGKAH 1: Klik tombol "Hapus" pada baris akun yang ingin dihapus
LANGKAH 2: Konfirmasi penghapusan
LANGKAH 3: Akun dihapus dari sistem
```

**Catatan penting:**
> ⚠️ Pengelola tidak dapat menghapus akun dirinya sendiri. Hal ini untuk mencegah sistem kehilangan semua pengelola.

---

### 3.2 Manajemen Data Warga

**URL Fitur:** `/wargas`
**Siapa yang bisa akses:** Pengelola saja

#### 3.2.1 Melihat Daftar Warga

**Konsep isi:**
- Menampilkan tabel semua warga pelanggan air
- Data yang ditampilkan: Nama, Dusun, RT, RW, Nomor Meteran
- Warga diurutkan berdasarkan: Dusun → RT → RW → Nama (A-Z)
- Terdapat fitur pencarian berdasarkan nama atau nomor meteran
- Ada tombol Edit dan Hapus di setiap baris

**Dua kategori wilayah yang perlu dijelaskan:**
- **Dusun Sragan** — warga di dalam dusun dengan RT dan RW jelas
- **Luar Sragan** — warga di luar dusun, tanpa RT/RW spesifik

#### 3.2.2 Menambah Data Warga Baru

**Konsep langkah-langkah:**
```
LANGKAH 1: Di halaman daftar warga, klik tombol "Tambah Warga"
LANGKAH 2: Isi formulir:
  - Nama Kepala Keluarga (contoh: Ahmad bin Slamet)
  - Wilayah: pilih "Dusun Sragan" atau "Luar Sragan"
  - RT (hanya untuk Dusun Sragan, contoh: 3)
  - RW (hanya untuk Dusun Sragan, contoh: 1)
  - Nomor Meteran (sesuai fisik meteran, contoh: SRG-001)
LANGKAH 3: Klik tombol "Simpan"
```

**Info sistem otomatis yang harus dijelaskan:**
> 💡 Ketika warga baru ditambahkan, sistem **otomatis membuat tagihan pemasangan** jika ada biaya pemasangan yang aktif. Anda tidak perlu membuat tagihan pemasangan secara terpisah.

**Catatan penting:**
- Pastikan nomor meteran sesuai dengan nomor meteran fisik di rumah warga
- Untuk warga Luar Sragan, kolom RT dan RW dikosongkan

#### 3.2.3 Mengubah Data Warga

**Konsep langkah-langkah:**
```
LANGKAH 1: Klik tombol "Edit" pada baris warga yang ingin diubah
LANGKAH 2: Halaman edit warga terbuka dengan data saat ini
LANGKAH 3: Ubah data yang diperlukan
LANGKAH 4: Klik tombol "Simpan Perubahan"
```

#### 3.2.4 Menghapus Data Warga

**Konsep langkah-langkah:**
```
LANGKAH 1: Klik tombol "Hapus" pada baris warga yang ingin dihapus
LANGKAH 2: Konfirmasi penghapusan
```

**Peringatan keras untuk buku:**
> ⛔ PERHATIAN: Menghapus data warga akan menghapus SELURUH riwayat pencatatan dan pembayaran warga tersebut secara permanen. Pastikan ini benar-benar disengaja.

---

### 3.3 Pengaturan Tarif Air

**URL Fitur:** `/pembayaran` (bagian panel tarif)
**Siapa yang bisa akses:** Pengelola saja

#### 3.3.1 Memahami Struktur Tarif

**Konsep penjelasan yang harus dimasukkan:**

Tarif air terdiri dari dua komponen:
1. **Harga per Meter³ (m³)** — biaya berdasarkan jumlah air yang digunakan. Semakin banyak pakai, semakin besar tagihan.
2. **Dana Meter** — biaya tetap yang dikenakan setiap bulan, tidak tergantung pemakaian. Untuk perawatan meteran.

Tarif **berbeda untuk dua wilayah:**
- Tarif **Dusun Sragan**
- Tarif **Luar Sragan**

Contoh perhitungan tagihan:
```
Pemakaian      : 10 m³
Harga per m³   : Rp 2.000
Dana Meter     : Rp 3.000

Harga Air      = 10 × Rp 2.000 = Rp 20.000
Tagihan Bulan  = Rp 20.000 + Rp 3.000 = Rp 23.000
```

#### 3.3.2 Mengubah Tarif Air

**Konsep langkah-langkah:**
```
LANGKAH 1: Buka halaman Pembayaran
LANGKAH 2: Cari panel "Pengaturan Tarif" di bagian bawah atau samping halaman
LANGKAH 3: Pilih wilayah yang tarifnya ingin diubah (Sragan atau Luar Sragan)
LANGKAH 4: Isi:
  - Harga per m³ (dalam rupiah, tanpa titik, contoh: 2000)
  - Dana Meter (dalam rupiah, tanpa titik, contoh: 3000)
  - Berlaku Mulai (pilih bulan dan tahun, contoh: 2026-08)
LANGKAH 5: Klik tombol "Simpan Tarif"
```

**Konsep perilaku sistem yang harus dijelaskan:**
- Tarif baru hanya berlaku mulai bulan yang dipilih, **tidak mengubah tagihan bulan-bulan sebelumnya**
- Sistem menyimpan riwayat perubahan tarif untuk keperluan audit
- Tarif lama tetap tercatat di sistem

**Catatan penting:**
> 💡 Jika tarif Sragan dan Luar Sragan sama, Anda perlu mengisi dan menyimpan dua kali: sekali untuk Sragan, sekali untuk Luar Sragan.

---

### 3.4 Pengaturan Biaya Pemasangan

**URL Fitur:** `/pembayaran-pemasangan` (bagian panel biaya)
**Siapa yang bisa akses:** Pengelola saja

#### 3.4.1 Memahami Biaya Pemasangan

**Konsep penjelasan:**
Biaya pemasangan adalah biaya yang dibebankan kepada **warga baru** saat pertama kali terdaftar sebagai pelanggan. Biaya ini dapat dibayar **secara mencicil** (tidak harus lunas sekaligus).

#### 3.4.2 Mengatur Biaya Pemasangan

**Konsep langkah-langkah:**
```
LANGKAH 1: Buka halaman Pembayaran Pemasangan
LANGKAH 2: Cari panel "Pengaturan Biaya Pemasangan"
LANGKAH 3: Isi:
  - Nominal Biaya (dalam rupiah, tanpa titik, contoh: 500000)
  - Berlaku Mulai (pilih bulan, contoh: 2026-01)
LANGKAH 4: Klik tombol "Simpan Biaya"
```

**Konsep perilaku sistem:**
- Biaya baru hanya berlaku untuk warga yang **didaftarkan setelah tanggal berlaku**
- Warga yang sudah terdaftar sebelumnya tidak terpengaruh perubahan biaya

---

### 3.5 Rekap dan Laporan Bulanan

**URL Fitur:** `/rekap`
**Siapa yang bisa akses:** Pengelola saja

#### 3.5.1 Memahami Halaman Rekap

**Konsep penjelasan:**
Halaman rekap menampilkan **laporan lengkap seluruh warga** untuk satu bulan tertentu dalam bentuk tabel. Ini adalah ringkasan keuangan bulanan yang paling penting untuk pengelola.

**Data yang ditampilkan per warga:**
| Kolom | Penjelasan |
|---|---|
| Nama | Nama kepala keluarga |
| Lokasi | RT/RW (Sragan) atau Luar Sragan |
| Meter Awal | Angka meteran bulan lalu |
| Meter Akhir | Angka meteran bulan ini |
| Pemakaian (m³) | Selisih = Meter Akhir - Meter Awal |
| Tarif/m³ | Harga air per kubik yang berlaku |
| Dana Meter | Biaya tetap per bulan |
| Harga Air | Pemakaian × Tarif/m³ |
| Tagihan | Harga Air + Dana Meter |
| Titip Lama | Hutang/kelebihan bayar dari bulan sebelumnya |
| Total Tagihan | Tagihan + Titip Lama |
| Terbayar | Jumlah yang sudah dibayar bulan ini |
| Hutang / Titip | Total Tagihan - Terbayar (merah=hutang, hijau=titip) |

**Memahami kolom Hutang/Titip:**
- **Merah (angka positif)** = warga masih punya hutang/tunggakan
- **Hijau (angka negatif/titip)** = warga lebih bayar, sisanya menjadi kredit bulan depan
- **Abu-abu (nol)** = lunas tepat

#### 3.5.2 Cara Menggunakan Filter Bulan

**Konsep langkah-langkah:**
```
LANGKAH 1: Buka halaman Rekap
LANGKAH 2: Di bagian atas halaman, temukan pilihan "Bulan"
LANGKAH 3: Pilih bulan dan tahun yang ingin dilihat
LANGKAH 4: Tabel akan otomatis diperbarui menampilkan data bulan tersebut
```

**Catatan:** Rekap hanya menampilkan data yang sudah dicatat. Warga yang belum dicatat meterannya akan tetap muncul di tabel tetapi kolom pemakaian dan tagihannya menampilkan nilai nol atau tanda strip (-).

---

### 3.6 Export Laporan ke Excel

**URL Fitur:** Tombol di halaman `/rekap`
**Siapa yang bisa akses:** Pengelola saja

**Konsep langkah-langkah:**
```
LANGKAH 1: Buka halaman Rekap
LANGKAH 2: Pilih bulan yang ingin diexport
LANGKAH 3: Klik tombol "Export Excel" atau "Unduh Excel"
LANGKAH 4: File Excel otomatis terunduh dengan nama: Rekap-Tagihan-[BULAN].xlsx
LANGKAH 5: Buka file Excel di Microsoft Excel atau Google Sheets
```

**Penjelasan isi file Excel:**
- Judul laporan: "REKAPITULASI PENGGUNAAN & TAGIHAN AIR TIRTA ANUGERAH"
- Periode: nama bulan dan tahun
- Kolom sama persis dengan tampilan di website
- Warna merah = hutang, warna hijau = titip/lebih bayar
- Baris terakhir berisi total keseluruhan

---

## BAB 4 — PANDUAN PETUGAS AIR

> Petugas Air memiliki akses ke fitur pencatatan dan pembayaran, namun tidak dapat mengubah data warga atau pengaturan tarif.

### 4.1 Pencatatan Meteran Bulanan

**URL Fitur:** `/pencatatans`
**Siapa yang bisa akses:** Pengelola & Petugas

#### 4.1.1 Memahami Alur Pencatatan

**Konsep penjelasan alur kerja:**

Setiap bulan, petugas pergi ke setiap rumah warga dan membaca angka di meteran air. Angka ini kemudian diinput ke sistem. Sistem akan **otomatis menghitung pemakaian** dengan rumus:

```
Pemakaian Bulan Ini = Angka Meteran Sekarang - Angka Meteran Bulan Lalu
```

Jika warga belum pernah dicatat sebelumnya (warga baru), angka meter lalu dianggap **nol (0)**, sehingga pemakaian = angka meter yang diinput.

#### 4.1.2 Melihat Status Pencatatan Bulan Ini

**Konsep isi:**
- Halaman menampilkan daftar semua warga
- Setiap warga menampilkan: nama, nomor meteran, meteran bulan lalu, status pencatatan bulan ini
- Warna hijau atau badge "Sudah Dicatat" = sudah diinput bulan ini
- Warna abu-abu atau badge "Belum Dicatat" = belum diinput

**Konsep langkah-langkah melihat status:**
```
LANGKAH 1: Klik menu "Pencatatan" di navigasi
LANGKAH 2: Pastikan bulan yang ditampilkan sudah benar (lihat filter bulan di atas)
LANGKAH 3: Cari warga menggunakan kolom pencarian jika perlu
LANGKAH 4: Lihat status di setiap baris warga
```

#### 4.1.3 Cara Input Angka Meteran

**Konsep langkah-langkah:**
```
LANGKAH 1: Buka halaman Pencatatan
LANGKAH 2: Pastikan bulan sudah sesuai (filter bulan di atas halaman)
LANGKAH 3: Cari nama warga yang akan dicatat
LANGKAH 4: Klik tombol "Catat Meteran" atau ikon pensil pada baris warga tersebut
LANGKAH 5: Formulir input muncul (bisa berupa modal/popup atau form di bawah baris)
LANGKAH 6: Periksa "Meteran Bulan Lalu" — pastikan sesuai dengan angka di buku catatan fisik
LANGKAH 7: Isi kolom "Angka Meteran Sekarang" dengan angka yang tertera di meteran fisik
LANGKAH 8: Klik tombol "Simpan"
LANGKAH 9: Sistem otomatis menghitung pemakaian dan menyimpan data
```

**Perilaku sistem yang harus dijelaskan:**
- Jika meteran bulan ini sudah pernah diinput, sistem akan menampilkan pesan error dan **menolak input ganda**
- Jika angka meteran yang diinput **lebih kecil dari bulan lalu**, sistem akan menolak dan meminta koreksi (meteran air tidak bisa mundur)
- Setelah disimpan, baris warga akan berubah menjadi status "Sudah Dicatat"

**Catatan tips:**
> 💡 Lebih baik mencatat langsung dari lapangan menggunakan HP saat masih di depan meteran warga, sehingga angka yang diinput lebih akurat dan tidak perlu kertas perantara.

**Hal yang sering salah:**
- Salah ketik angka meteran — periksa kembali angka di HP sebelum menekan Simpan
- Lupa ganti bulan di filter — pastikan bulan di halaman sudah sesuai dengan bulan pencatatan

---

### 4.2 Pencatatan Pembayaran Tagihan

**URL Fitur:** `/pembayaran`
**Siapa yang bisa akses:** Pengelola & Petugas

#### 4.2.1 Memahami Sistem Pembayaran

**Konsep penjelasan:**

Setelah meteran dicatat, warga dapat membayar tagihan air mereka. Sistem mendukung:
- **Bayar lunas** — membayar penuh sesuai total tagihan
- **Bayar sebagian** — membayar kurang dari tagihan (sisanya menjadi hutang di bulan berikutnya)
- **Lebih bayar** — membayar lebih dari tagihan (kelebihan menjadi kredit/titip di bulan berikutnya)

**Cara membaca informasi tagihan:**
- **Tagihan Bulan Ini** = Harga air + Dana meter bulan ini
- **Saldo Awal** = Hutang atau titip dari bulan sebelumnya
- **Total Harus Dibayar** = Tagihan Bulan Ini + Saldo Awal (bisa lebih besar jika ada tunggakan)

#### 4.2.2 Cara Mencatat Pembayaran

**Konsep langkah-langkah:**
```
LANGKAH 1: Buka halaman Pembayaran
LANGKAH 2: Pilih bulan yang sesuai menggunakan filter bulan
LANGKAH 3: Cari warga yang ingin dicatat pembayarannya (gunakan kolom pencarian)
LANGKAH 4: Lihat informasi tagihan warga: Tagihan Bulan Ini, Saldo Awal, Total Harus Dibayar
LANGKAH 5: Klik tombol "Bayar" atau "Catat Pembayaran" pada baris warga tersebut
LANGKAH 6: Isi kolom "Jumlah Dibayar" dengan uang yang diterima dari warga
LANGKAH 7: Klik tombol "Simpan Pembayaran"
LANGKAH 8: Sistem menampilkan konfirmasi: sisa saldo atau status lunas
```

**Perilaku sistem setelah pembayaran dicatat:**
- Jika bayar kurang: **sisa hutang** otomatis terbawa ke bulan depan sebagai "Titip Lama" dengan nilai positif
- Jika bayar lebih: **kelebihan** otomatis terbawa ke bulan depan sebagai "Titip Lama" dengan nilai negatif (kredit)
- Jika bayar pas: status menjadi lunas, saldo nol

**Catatan penting:**
> ⚠️ Warga yang belum dicatat meterannya untuk bulan ini tidak dapat diproses pembayarannya. Lakukan pencatatan meteran terlebih dahulu.

---

### 4.3 Pembayaran Pemasangan

**URL Fitur:** `/pembayaran-pemasangan`
**Siapa yang bisa akses:** Pengelola & Petugas

#### 4.3.1 Memahami Tagihan Pemasangan

**Konsep penjelasan:**
Tagihan pemasangan adalah biaya satu kali yang dikenakan saat pertama kali warga mendaftar. Tagihan ini **terpisah** dari tagihan air bulanan dan bisa dibayar secara **mencicil**.

**Status tagihan pemasangan:**
- **Belum Lunas** — masih ada sisa tagihan yang belum dibayar
- **Lunas** — tagihan sudah dibayar penuh

#### 4.3.2 Melihat Daftar Tagihan Pemasangan

**Konsep langkah-langkah:**
```
LANGKAH 1: Buka halaman Pembayaran Pemasangan
LANGKAH 2: Lihat daftar semua warga beserta status tagihan pemasangannya
LANGKAH 3: Gunakan filter "Status" untuk melihat hanya yang Belum Lunas atau Lunas
LANGKAH 4: Gunakan kolom pencarian untuk mencari warga tertentu
```

**Data yang ditampilkan:**
- Nama warga
- Total biaya pemasangan
- Sudah dibayar
- Sisa tagihan
- Status (Belum Lunas / Lunas)

#### 4.3.3 Cara Mencatat Cicilan / Pelunasan

**Konsep langkah-langkah:**
```
LANGKAH 1: Temukan baris warga yang ingin mencicil/melunasi
LANGKAH 2: Klik tombol "Bayar" pada baris tersebut
LANGKAH 3: Isi kolom "Jumlah Bayar" dengan uang yang diterima
  - Untuk cicilan: isi sebagian saja (tidak boleh melebihi sisa tagihan)
  - Untuk pelunasan: isi dengan jumlah sisa tagihan
LANGKAH 4: Klik tombol "Simpan"
LANGKAH 5: Sistem memperbarui status tagihan secara otomatis
```

**Catatan sistem:**
- Jumlah bayar **tidak boleh melebihi sisa tagihan**
- Jika sudah lunas, tombol Bayar tidak akan muncul lagi

---

### 4.4 Dashboard dan Statistik

**URL Fitur:** `/dashboard`
**Siapa yang bisa akses:** Pengelola & Petugas

#### 4.4.1 Memahami Isi Dashboard

**Konsep penjelasan setiap elemen:**

**Kartu Statistik Utama (4 kartu di bagian atas):**
1. **Total Petugas** — jumlah pengguna dengan peran petugas yang terdaftar
2. **Total Warga** — jumlah seluruh warga pelanggan yang terdaftar
3. **Total Pemakaian Bulan Ini** — total kubik air yang terpakai seluruh warga bulan yang dipilih

**Grafik Trend 6 Bulan:**
- Menampilkan total pemakaian air selama 6 bulan terakhir
- Berguna untuk melihat tren konsumsi air dari waktu ke waktu
- Jika grafik naik: konsumsi meningkat; jika turun: konsumsi berkurang

**Grafik Pemakaian per RT (Bar Chart):**
- Membandingkan pemakaian air antar RT dalam satu bulan
- Membantu mengidentifikasi RT yang konsumsinya tinggi

**Grafik Distribusi Warga per RT (Donut Chart):**
- Menampilkan proporsi jumlah warga per RT
- Membantu pengelola memahami sebaran warga pelanggan

**Status Pencatatan Bulan Ini:**
- Menampilkan berapa warga yang **sudah dicatat** vs **belum dicatat** meterannya
- Dilengkapi persentase (contoh: 85% warga sudah dicatat)

**Top 5 Warga Pemakaian Terbanyak:**
- Daftar 5 warga dengan pemakaian tertinggi bulan ini
- Berguna untuk mendeteksi pemakaian yang tidak wajar

#### 4.4.2 Cara Mengubah Filter Bulan di Dashboard

**Konsep langkah-langkah:**
```
LANGKAH 1: Di halaman Dashboard, cari filter "Bulan" di bagian atas
LANGKAH 2: Klik filter dan pilih bulan/tahun yang ingin dilihat
LANGKAH 3: Semua grafik dan statistik akan diperbarui otomatis
```

---

## BAB 5 — FITUR BERSAMA

### 5.1 Mengelola Profil Akun

**URL Fitur:** `/profile`
**Siapa yang bisa akses:** Semua pengguna yang login

**Konsep langkah-langkah:**
```
LANGKAH 1: Klik nama pengguna di pojok kanan atas
LANGKAH 2: Pilih menu "Profil" atau "Edit Profil"
LANGKAH 3: Halaman profil terbuka
```

**Yang bisa diubah:**
- Nama lengkap
- Username
- Password (isi hanya jika ingin ganti password)

**Konsep langkah ganti password:**
```
LANGKAH 1: Di halaman profil, cari bagian "Ganti Password"
LANGKAH 2: Isi "Password Saat Ini" dengan password lama
LANGKAH 3: Isi "Password Baru"
LANGKAH 4: Isi "Konfirmasi Password Baru" (ketik ulang password baru)
LANGKAH 5: Klik tombol "Simpan"
```

---

## BAB 6 — PERTANYAAN UMUM (FAQ)

> **Petunjuk untuk AI:** Kembangkan setiap pertanyaan berikut menjadi jawaban lengkap minimal 2-3 paragraf. Gunakan bahasa sederhana.

### Q1: Apa yang terjadi jika saya salah input angka meteran?

**Konsep jawaban:** Belum ada fitur edit pencatatan langsung. Solusi sementara: hubungi pengelola untuk menghapus data dan input ulang. Tekankan pentingnya memeriksa angka sebelum klik Simpan.

### Q2: Mengapa angka pemakaian warga tampak tidak wajar (terlalu besar atau nol)?

**Konsep jawaban:** Kemungkinan penyebab: (a) warga baru pertama kali dicatat — pemakaian dihitung dari angka 0, (b) meteran fisik baru diganti — angka mulai dari awal lagi, (c) salah input angka. Sarankan untuk memeriksa histori pencatatan bulan-bulan sebelumnya.

### Q3: Apa itu "Titip" dan "Hutang" di kolom rekap?

**Konsep jawaban:** Jelaskan sistem saldo berjalan — jika lebih bayar akan menjadi kredit (titip) yang dikurangi dari tagihan bulan depan. Jika kurang bayar, sisa hutang ditambahkan ke tagihan bulan depan.

### Q4: Kenapa ada warga yang tidak muncul di halaman pembayaran?

**Konsep jawaban:** Warga hanya bisa dibayar jika meterannya sudah dicatat bulan ini. Minta petugas untuk input pencatatan meteran terlebih dahulu.

### Q5: Bagaimana jika petugas lupa password?

**Konsep jawaban:** Petugas tidak bisa reset password sendiri. Pengelola perlu masuk ke menu Akun dan mengubah password petugas yang lupa.

### Q6: Apakah data rekap bulan lalu bisa dilihat?

**Konsep jawaban:** Ya, gunakan filter bulan di halaman Rekap. Sistem menyimpan semua data historis dan bisa diakses kapan saja.

### Q7: Kapan sebaiknya pencatatan meteran dilakukan?

**Konsep jawaban:** Idealnya pencatatan dilakukan di awal bulan (1-5) untuk bulan yang sama. Pastikan konsisten setiap bulannya agar data akurat.

---

## BAB 7 — PANDUAN TROUBLESHOOTING

> **Petunjuk untuk AI:** Untuk setiap masalah, berikan solusi langkah per langkah yang dapat dilakukan pengguna sendiri. Jika tidak bisa diselesaikan sendiri, jelaskan kepada siapa harus melapor.

### Masalah 1: Tidak Bisa Login

**Gejala:** Muncul pesan error saat login, halaman tidak berpindah
**Penyebab kemungkinan:**
- Username atau password salah
- Capslock aktif (username harus huruf kecil)
- Akun belum dibuat oleh pengelola

**Solusi:** Coba ketik ulang dengan hati-hati. Jika masih gagal, hubungi pengelola.

### Masalah 2: Halaman Tidak Tampil / Error

**Gejala:** Muncul halaman putih, error 500, atau "Not Found"
**Solusi:** Refresh halaman (F5 atau tarik ke bawah di HP). Jika masih error, coba logout dan login kembali. Jika masih bermasalah, hubungi teknisi.

### Masalah 3: Data Tidak Tersimpan

**Gejala:** Klik Simpan tapi data tidak masuk
**Penyebab kemungkinan:** Validasi gagal (ada field yang salah isi), koneksi internet terputus
**Solusi:** Periksa apakah ada pesan error merah di bawah form. Perbaiki isian sesuai petunjuk error. Pastikan koneksi internet stabil.

### Masalah 4: Angka Pemakaian Negatif atau Error

**Gejala:** Sistem menolak input angka meteran dengan pesan "angka lebih kecil dari sebelumnya"
**Penyebab:** Angka yang diinput lebih kecil dari angka meteran bulan lalu
**Solusi:** Periksa kembali angka meteran fisik. Jika memang berbeda (meteran diganti), hubungi pengelola untuk penanganan khusus.

### Masalah 5: Tidak Bisa Akses Menu Tertentu

**Gejala:** Menu tidak muncul atau muncul halaman "Tidak Punya Akses"
**Penyebab:** Akun hanya memiliki peran Petugas, sehingga tidak bisa mengakses fitur Pengelola
**Solusi:** Fitur tersebut memang hanya untuk Pengelola. Jika seharusnya punya akses, minta pengelola mengubah peran akun.

---

## LAMPIRAN — DATA TEKNIS UNTUK REFERENSI AI

> Bagian ini berisi data teknis internal sistem. Gunakan sebagai acuan saat menjelaskan perilaku sistem, bukan untuk ditampilkan langsung ke pengguna akhir.

### Struktur URL dan Hak Akses

| URL | Fitur | Pengelola | Petugas |
|---|---|---|---|
| `/` | Halaman Publik | ✅ | ✅ |
| `/dashboard` | Dashboard | ✅ | ✅ |
| `/pencatatans` | Pencatatan Meteran | ✅ | ✅ |
| `/pembayaran` | Pembayaran Tagihan | ✅ | ✅ |
| `/pembayaran-pemasangan` | Biaya Pemasangan | ✅ | ✅ |
| `/wargas` | Data Warga | ✅ | ❌ |
| `/akuns` | Manajemen Akun | ✅ | ❌ |
| `/rekap` | Rekap Laporan | ✅ | ❌ |
| `/rekap/excel` | Export Excel | ✅ | ❌ |
| `/profile` | Profil Akun | ✅ | ✅ |

### Logika Bisnis Utama (Untuk Referensi Penjelasan)

```
LOGIKA PEMAKAIAN:
  pemakaian = angka_meteran_sekarang - angka_meteran_bulan_lalu
  (jika belum pernah dicatat: angka_lalu = 0)

LOGIKA TAGIHAN:
  harga_air = pemakaian × tarif_per_meter
  tagihan_bulan_ini = harga_air + dana_meter
  total_tagihan = tagihan_bulan_ini + titip_lama

LOGIKA SALDO:
  sisa_saldo = total_tagihan - jumlah_dibayar
  titip_bulan_depan = sisa_saldo
  (sisa_saldo > 0 = hutang; sisa_saldo < 0 = titip/kredit)

VALIDASI PENCATATAN:
  - Satu warga hanya boleh dicatat SATU KALI per bulan
  - Angka meteran tidak boleh lebih kecil dari bulan sebelumnya
  - Bulan harus dalam format YYYY-MM

TARIF:
  - Setiap wilayah (sragan/luar_sragan) memiliki tarif sendiri
  - Tarif berlaku mulai bulan yang ditentukan
  - Sistem mengambil tarif terbaru yang berlaku untuk bulan bersangkutan
```

### Daftar Istilah

| Istilah | Definisi |
|---|---|
| **Pengelola** | Pengguna dengan akses penuh ke seluruh sistem |
| **Petugas** | Pengguna dengan akses pencatatan dan pembayaran saja |
| **Meteran Awal** | Angka meteran pada akhir bulan sebelumnya |
| **Meteran Akhir** | Angka meteran yang dibaca bulan ini |
| **Pemakaian** | Selisih meteran akhir dan awal, dalam satuan m³ |
| **Dana Meter** | Biaya tetap bulanan untuk pemeliharaan meteran |
| **Tagihan** | Total biaya air bulan ini (harga air + dana meter) |
| **Titip Lama** | Saldo dari bulan sebelumnya (positif = hutang, negatif = kredit) |
| **Total Tagihan** | Tagihan bulan ini + titip lama |
| **Terbayar** | Jumlah uang yang sudah diterima dari warga bulan ini |
| **Hutang/Titip** | Sisa setelah dikurangi pembayaran (merah = hutang, hijau = titip) |
| **Biaya Pemasangan** | Biaya satu kali saat warga baru mendaftar sebagai pelanggan |
| **Luar Sragan** | Kategori warga yang berdomisili di luar Dusun Sragan |

---

*Dokumen ini merupakan konsep panduan versi 1.0. Dikembangkan berdasarkan sistem Tirta Anugerah yang dibangun menggunakan Laravel dengan basis data MySQL.*
