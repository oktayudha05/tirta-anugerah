<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Pencatatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PencatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));
        $search = $request->input('search');

        $wargas = Warga::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_meteran', 'like', "%{$search}%");
            })
            ->orderBy('rt')
            ->orderBy('rw')
            ->orderBy('nama')
            ->get();

        // Attach recording details for the selected month to each warga
        foreach ($wargas as $warga) {
            $warga->pencatatan_sekarang = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', $bulan)
                ->first();

            // Fetch the latest recording before this month
            $warga->pencatatan_lalu = Pencatatan::where('warga_id', $warga->id)
                ->where('bulan', '<', $bulan)
                ->orderBy('bulan', 'desc')
                ->first();
        }

        return view('pencatatans.index', compact('wargas', 'bulan', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'warga_id' => ['required', 'exists:wargas,id'],
            'bulan' => ['required', 'date_format:Y-m'],
            'angka_meteran' => ['required', 'integer', 'min:0'],
        ]);

        $wargaId = $request->warga_id;
        $bulan = $request->bulan;
        $angkaMeteran = $request->angka_meteran;

        $exists = Pencatatan::where('warga_id', $wargaId)
            ->where('bulan', $bulan)
            ->exists();

        if ($exists) {
            return back()->withErrors(['pencatatan' => 'Data pencatatan warga ini untuk bulan ' . $bulan . ' sudah diinput.']);
        }

        $pencatatanLalu = Pencatatan::where('warga_id', $wargaId)
            ->where('bulan', '<', $bulan)
            ->orderBy('bulan', 'desc')
            ->first();

        $angkaLalu = $pencatatanLalu ? $pencatatanLalu->angka_meteran : 0;
        
        if ($angkaMeteran < $angkaLalu) {
            return back()->withErrors([
                'angka_meteran' => "Angka meteran baru ($angkaMeteran) tidak boleh lebih kecil dari angka meteran sebelumnya ($angkaLalu)."
            ])->withInput();
        }

        $pemakaian = $angkaMeteran - $angkaLalu;
        
        // ✅ PERBAIKAN: Hitung tagihan dan titip langsung saat input
        $warga = Warga::find($wargaId);
        
        // Gunakan model Pembayaran (atau Keuangan jika nama model lo adalah Keuangan)
        $tarif = \App\Models\Pembayaran::getTarifAktif($warga->dusun, $bulan); 
        
        $hargaMeter = $tarif ? $tarif->harga_per_meter : 0;
        $danaMeter = $tarif ? $tarif->dana_meter : 0;
        $tagihanBulanIni = ($pemakaian * $hargaMeter) + $danaMeter;
        
        $saldoAwal = $pencatatanLalu ? $pencatatanLalu->titip : 0;
        $totalHarusDibayar = $tagihanBulanIni + $saldoAwal;

        Pencatatan::create([
            'warga_id' => $wargaId,
            'bulan' => $bulan,
            'angka_meteran' => $angkaMeteran,
            'pemakaian' => $pemakaian,
            'user_id' => Auth::id(),
            'dibayar' => 0,
            'titip' => $totalHarusDibayar, // ✅ INI KUNCINYA! Langsung simpan total tunggakan
        ]);

        return redirect()->route('pencatatans.index', ['bulan' => $bulan])
            ->with('success', 'Pencatatan meteran berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pencatatan = Pencatatan::with('warga')->findOrFail($id);
        $bulan = $pencatatan->bulan;
        return view('pencatatans.edit', compact('pencatatan', 'bulan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pencatatan = Pencatatan::with('warga')->findOrFail($id);

        // Opsi A: Blokir edit jika tagihan sudah dibayar
        if ($pencatatan->dibayar > 0) {
            return back()->withErrors([
                'angka_meteran' => 'Data ini sudah dibayar dan tidak bisa diubah. Hubungi pengelola jika ada kesalahan.'
            ]);
        }

        $request->validate([
            'angka_meteran' => ['required', 'integer', 'min:0'],
        ]);

        $wargaId   = $pencatatan->warga_id;
        $bulan     = $pencatatan->bulan;
        $angkaBaru = $request->angka_meteran;

        // Ambil pencatatan bulan sebelumnya (bukan data yang sedang diedit)
        $pencatatanLalu = Pencatatan::where('warga_id', $wargaId)
            ->where('bulan', '<', $bulan)
            ->orderBy('bulan', 'desc')
            ->first();

        $angkaLalu = $pencatatanLalu ? $pencatatanLalu->angka_meteran : 0;

        // Validasi: angka baru tidak boleh lebih kecil dari angka bulan lalu
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
            'user_id'       => Auth::id(),
        ]);

        return redirect()->route('pencatatans.index', ['bulan' => $bulan])
            ->with('success', 'Pencatatan meteran berhasil diperbarui.');
    }
}
