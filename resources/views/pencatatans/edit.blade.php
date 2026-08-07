<x-app-layout>

    <!-- Header Tile -->
    <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#36656B]">Edit Pencatatan Meteran</h1>
                <p class="text-xs text-gray-400">Perbarui angka meteran — pemakaian & tagihan akan dihitung ulang otomatis</p>
            </div>
        </div>
        <a href="{{ route('pencatatans.index', ['bulan' => $bulan]) }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#36656B] font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 text-red-700 text-sm font-medium px-4 py-3 rounded-xl border border-red-200 shadow-sm">
            @foreach($errors->all() as $error)
                <p class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <!-- Info Warga + Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kiri: Info Warga & Data Lama -->
        <div class="lg:col-span-1 space-y-4">

            <!-- Card Info Warga -->
            <div class="bg-white rounded-2xl p-5 border border-[#DAD887]/50 shadow-sm">
                <h2 class="text-sm font-bold text-[#36656B] mb-3 uppercase tracking-wide">Info Warga</h2>
                <div class="space-y-2">
                    <div>
                        <span class="text-xs text-gray-400 block">Nama</span>
                        <span class="font-semibold text-gray-800">{{ $pencatatan->warga->nama }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">No. Meteran</span>
                        <span class="font-mono text-sm text-gray-700">{{ $pencatatan->warga->nomor_meteran }}</span>
                    </div>
                    @if($pencatatan->warga->dusun === 'sragan')
                    <div>
                        <span class="text-xs text-gray-400 block">RT / RW</span>
                        <span class="text-sm text-gray-700">
                            RT {{ sprintf('%02d', $pencatatan->warga->rt) }} /
                            RW {{ sprintf('%02d', $pencatatan->warga->rw) }}
                        </span>
                    </div>
                    @endif
                    <div>
                        <span class="text-xs text-gray-400 block">Periode</span>
                        <span class="text-sm font-semibold text-[#36656B]">{{ $bulan }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Data Saat Ini -->
            <div class="bg-[#F0F8A4]/30 rounded-2xl p-5 border border-[#DAD887]/50 shadow-sm">
                <h2 class="text-sm font-bold text-[#36656B] mb-3 uppercase tracking-wide">Data Saat Ini</h2>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">Angka Meteran</span>
                        <span class="font-mono font-bold text-[#36656B]">{{ number_format($pencatatan->angka_meteran) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">Pemakaian</span>
                        <span class="font-mono font-semibold text-gray-700">{{ number_format($pencatatan->pemakaian) }} m³</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">Status Bayar</span>
                        @if($pencatatan->dibayar > 0)
                            <span class="inline-flex items-center gap-1 bg-[#75B06F]/25 text-[#36656B] text-xs font-semibold px-2 py-0.5 rounded-lg border border-[#75B06F]/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#75B06F]"></span>
                                Sudah Dibayar
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-semibold px-2 py-0.5 rounded-lg border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Belum Dibayar
                            </span>
                        @endif
                    </div>
                </div>

                @if($pencatatan->dibayar > 0)
                    <div class="mt-3 bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                        ⚠️ Tagihan ini sudah dibayar. Data tidak bisa diubah. Hubungi pengelola jika ada kesalahan.
                    </div>
                @endif
            </div>
        </div>

        <!-- Kanan: Form Edit -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
                <h2 class="text-base font-bold text-gray-800 mb-5">Ubah Angka Meteran</h2>

                @if($pencatatan->dibayar > 0)
                    <!-- Tampilkan pesan jika sudah dibayar, form dinonaktifkan -->
                    <div class="bg-gray-50 rounded-xl p-6 text-center text-gray-500 text-sm border border-dashed border-gray-200">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <p class="font-semibold">Data Terkunci</p>
                        <p class="text-xs mt-1">Pencatatan ini tidak bisa diubah karena tagihan sudah dibayar.</p>
                    </div>
                @else
                    <form action="{{ route('pencatatans.update', $pencatatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Angka Meteran Baru -->
                        <div class="mb-5">
                            <label for="angka_meteran" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Angka Meteran Baru
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="angka_meteran"
                                   name="angka_meteran"
                                   value="{{ old('angka_meteran', $pencatatan->angka_meteran) }}"
                                   min="0"
                                   required
                                   class="w-full px-4 py-3 bg-[#F0F8A4]/20 border border-[#DAD887] rounded-xl text-gray-800 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150
                                          @error('angka_meteran') border-red-400 bg-red-50 @enderror">
                            <p class="text-xs text-gray-400 mt-1.5">
                                Nilai sebelumnya: <strong class="font-mono text-gray-600">{{ number_format($pencatatan->angka_meteran) }}</strong>
                                &nbsp;|&nbsp; Pemakaian akan dihitung ulang otomatis setelah disimpan.
                            </p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    class="flex-1 flex items-center justify-center gap-2 bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-sm transition-all duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('pencatatans.index', ['bulan' => $bulan]) }}"
                               class="flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-3 rounded-xl transition-all duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
