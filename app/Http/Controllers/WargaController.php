<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $wargas = Warga::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_meteran', 'like', "%{$search}%");
            })
            ->orderBy('dusun')
            ->orderBy('rt')
            ->orderBy('rw')
            ->orderBy('nama')
            ->get();

        return view('wargas.index', compact('wargas', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'dusun' => ['required', 'in:sragan,luar_sragan'],
            'rt' => ['nullable', 'integer', 'min:1'],
            'rw' => ['nullable', 'integer', 'min:1'],
            'nomor_meteran' => ['required', 'string', 'max:255'],
        ]);

        $warga = Warga::create($request->only('nama', 'dusun', 'rt', 'rw', 'nomor_meteran'));

        // Auto-buat tagihan pemasangan jika ada biaya pemasangan aktif
        $biaya = \App\Models\BiayaPemasangan::getBiayaAktif();
        if ($biaya) {
            \App\Models\PembayaranPemasangan::create([
                'warga_id'      => $warga->id,
                'total_biaya'   => $biaya->biaya,
                'total_dibayar' => 0,
                'status'        => 'belum_lunas',
            ]);
        }

        return redirect()->route('wargas.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a warga.
     */
    public function edit(Warga $warga)
    {
        return view('wargas.edit', compact('warga'));
    }

    /**
     * Update the specified warga in storage.
     */
    public function update(Request $request, Warga $warga)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'dusun' => ['required', 'in:sragan,luar_sragan'],
            'rt' => ['nullable', 'integer', 'min:1'],
            'rw' => ['nullable', 'integer', 'min:1'],
            'nomor_meteran' => ['required', 'string', 'max:255'],
        ]);

        $warga->update($request->only('nama', 'dusun', 'nomor_meteran'));

        if ($request->dusun === 'luan_sragan') {
            $data['rt'] = null;
            $data['rw'] = null;
        } else {
            $data['rt'] = $request->rt;
            $data['rw'] = $request->rw;
        }
        
        $warga->update($data);

        return redirect()->route('wargas.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();

        return redirect()->route('wargas.index')->with('success', 'Data warga berhasil dihapus.');
    }
}
