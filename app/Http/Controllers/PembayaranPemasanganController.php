<?php

namespace App\Http\Controllers;

use App\Models\BiayaPemasangan;
use App\Models\PembayaranPemasangan;
use Illuminate\Http\Request;

class PembayaranPemasanganController extends Controller
{
    /**
     * Halaman utama pembayaran pemasangan.
     * Akses: pengelola & petugas
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = PembayaranPemasangan::with('warga')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('warga', function ($wq) use ($search) {
                    $wq->where('nama', 'like', "%{$search}%")
                       ->orWhere('nomor_meteran', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            });

        $tagihanPemasangans = $query->get()->sortBy(fn($t) => $t->warga?->nama);

        $riwayatBiaya = BiayaPemasangan::orderBy('berlaku_mulai', 'desc')->get();
        $biayaAktif   = BiayaPemasangan::getBiayaAktif();

        return view('pembayaran-pemasangans.index', compact(
            'tagihanPemasangans',
            'riwayatBiaya',
            'biayaAktif',
            'search',
            'status'
        ));
    }

    /**
     * Proses pembayaran cicilan/DP.
     * Akses: pengelola & petugas
     */
    public function bayar(Request $request, $id)
    {
        $tagihan = PembayaranPemasangan::findOrFail($id);

        $sisa = $tagihan->total_biaya - $tagihan->total_dibayar;

        $request->validate([
            'jumlah' => [
                'required',
                'numeric',
                'min:1',
                "max:{$sisa}",
            ],
        ], [
            'jumlah.max' => 'Jumlah pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($sisa, 0, ',', '.') . ').',
            'jumlah.min' => 'Jumlah pembayaran minimal Rp 1.',
        ]);

        $tagihan->total_dibayar += $request->jumlah;

        if ($tagihan->total_dibayar >= $tagihan->total_biaya) {
            $tagihan->status = 'lunas';
        }

        $tagihan->save();

        $sisaBaru = $tagihan->total_biaya - $tagihan->total_dibayar;

        $message = 'Pembayaran Rp ' . number_format($request->jumlah, 0, ',', '.') . ' berhasil dicatat.';
        if ($tagihan->status === 'lunas') {
            $message .= ' Tagihan pemasangan telah LUNAS!';
        } else {
            $message .= ' Sisa tagihan: Rp ' . number_format($sisaBaru, 0, ',', '.') . '.';
        }

        return redirect()->route('pembayaran-pemasangan.index', array_filter([
            'search' => $request->search,
            'status' => $request->status,
        ]))->with('success', $message);
    }

    /**
     * Set/update biaya pemasangan (pengelola only).
     */
    public function updateBiaya(Request $request)
    {
        $request->validate([
            'biaya'         => 'required|integer|min:0',
            'berlaku_mulai' => 'required|date_format:Y-m',
        ]);

        BiayaPemasangan::updateOrCreate(
            ['berlaku_mulai' => $request->berlaku_mulai],
            [
                'biaya'     => $request->biaya,
                'is_active' => true,
            ]
        );

        return redirect()->route('pembayaran-pemasangan.index')
            ->with('success', 'Biaya pemasangan Rp ' . number_format($request->biaya, 0, ',', '.') . ' berhasil disimpan, berlaku mulai ' . $request->berlaku_mulai . '.');
    }
}
