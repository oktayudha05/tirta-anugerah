<x-app-layout>
    <div class="space-y-6">
        {{-- Header & Filter --}}
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#36656B]">Pembayaran Pemasangan</h1>
                    <p class="text-xs text-gray-400">Tagihan & cicilan biaya pemasangan instalasi air</p>
                </div>
            </div>

            <form method="GET" action="{{ route('pembayaran-pemasangan.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                {{-- Filter Status --}}
                <select name="status" onchange="this.form.submit()"
                    class="w-full sm:w-auto px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B] transition-all">
                    <option value="" {{ $status === null || $status === '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="belum_lunas" {{ $status === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="lunas" {{ $status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>

                {{-- Search --}}
                <div class="relative flex items-center w-full sm:w-64">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau no. meteran..."
                        class="w-full px-4 py-2 pr-10 text-xs bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150">
                    <button type="submit" class="absolute right-3 text-gray-400 hover:text-[#36656B]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    @if(!empty($search))
                        <a href="{{ route('pembayaran-pemasangan.index', ['status' => $status]) }}" class="absolute right-8 text-[10px] text-red-500 hover:underline mr-1">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-[#75B06F]/20 text-[#36656B] text-sm font-semibold px-4 py-3 rounded-xl border border-[#75B06F]/30 relative">
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-lg leading-none hover:text-red-600">&times;</button>
            </div>
        @endif

        {{-- Panel Setting Biaya (hanya pengelola) --}}
        @if(Auth::user()->role === 'pengelola')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form Set Biaya --}}
            <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm h-fit">
                <h3 class="text-md font-bold text-[#36656B] mb-4">Pengaturan Biaya Pemasangan</h3>

                <form action="{{ route('pembayaran-pemasangan.update-biaya') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Biaya Pemasangan (Rp)</label>
                        <input type="number" name="biaya" required min="0"
                            placeholder="Contoh: 2000000"
                            value="{{ $biayaAktif ? $biayaAktif->biaya : '' }}"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#36656B]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Mulai Berlaku Pada Periode</label>
                        <input type="month" name="berlaku_mulai" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#36656B]">
                        <span class="text-[10px] text-gray-400 mt-1 block">* Biaya ini akan diterapkan untuk warga yang didaftarkan sejak periode ini.</span>
                    </div>

                    <button type="submit" class="w-full bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition duration-150 shadow-sm">
                        Simpan Biaya Pemasangan
                    </button>
                </form>

                @if($biayaAktif)
                <div class="mt-4 pt-4 border-t border-[#DAD887]/30">
                    <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1">Biaya Aktif Saat Ini</p>
                    <p class="text-lg font-bold text-[#36656B] font-mono">Rp {{ number_format($biayaAktif->biaya, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-400">Berlaku mulai: {{ $biayaAktif->berlaku_mulai }}</p>
                </div>
                @endif
            </div>

            {{-- Riwayat Biaya --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm overflow-hidden flex flex-col h-[320px]">
                <h3 class="text-md font-bold text-[#36656B] mb-4">Riwayat Pengaturan Biaya Pemasangan</h3>
                <div class="overflow-y-auto flex-1 rounded-xl border border-[#DAD887]/30">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-[#36656B] text-white text-[10px] uppercase tracking-wider sticky top-0">
                            <tr>
                                <th class="px-4 py-3">Biaya Pemasangan</th>
                                <th class="px-4 py-3 text-center">Berlaku Mulai</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#DAD887]/30">
                            @forelse($riwayatBiaya as $rb)
                                <tr class="hover:bg-[#F0F8A4]/10 transition-colors">
                                    <td class="px-4 py-3 font-mono font-semibold text-gray-900">Rp {{ number_format($rb->biaya, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-[#36656B]">{{ $rb->berlaku_mulai }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($biayaAktif && $rb->id === $biayaAktif->id)
                                            <span class="inline-block px-2 py-0.5 rounded-full bg-[#75B06F]/20 text-[#36656B] font-semibold text-[10px]">Aktif</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded-full bg-gray-100 text-gray-400 font-semibold text-[10px]">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-400">Belum ada riwayat biaya pemasangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Tabel Desktop --}}
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-[#36656B]">Daftar Tagihan Pemasangan</h2>
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    @php
                        $totalBelumLunas = $tagihanPemasangans->where('status', 'belum_lunas')->count();
                        $totalLunas = $tagihanPemasangans->where('status', 'lunas')->count();
                    @endphp
                    <span class="bg-red-50 text-red-600 border border-red-200 rounded-lg px-2 py-1">Belum Lunas: <strong>{{ $totalBelumLunas }}</strong></span>
                    <span class="bg-[#75B06F]/10 text-[#36656B] border border-[#75B06F]/30 rounded-lg px-2 py-1">Lunas: <strong>{{ $totalLunas }}</strong></span>
                </div>
            </div>

            <div class="hidden md:block bg-white rounded-2xl border border-[#DAD887]/50 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#36656B] text-white text-[10px] uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Warga / Dusun</th>
                                <th class="px-4 py-3 text-right">Total Biaya</th>
                                <th class="px-4 py-3 text-right">Sudah Dibayar</th>
                                <th class="px-4 py-3 text-right">Sisa Tagihan</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#DAD887]/30">
                            @forelse($tagihanPemasangans as $tagihan)
                                @php
                                    $warga = $tagihan->warga;
                                    $sisa  = $tagihan->total_biaya - $tagihan->total_dibayar;
                                @endphp
                                <tr class="hover:bg-[#F0F8A4]/20 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">{{ $warga?->nama ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-500 flex items-center gap-1 mt-0.5">
                                            <span class="px-1 py-0.5 rounded bg-gray-100 text-gray-600 font-mono">
                                                {{ ($warga?->dusun === 'sragan') ? 'Sragan' : 'Luar' }}
                                            </span>
                                            @if($warga?->dusun === 'sragan')
                                                <span>RT{{ sprintf('%02d', $warga->rt) }}/RW{{ sprintf('%02d', $warga->rw) }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-right font-mono font-semibold text-[#36656B] text-xs">
                                        Rp {{ number_format($tagihan->total_biaya, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-mono text-[#75B06F] font-semibold text-xs">
                                        Rp {{ number_format($tagihan->total_dibayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-mono font-bold text-xs {{ $sisa > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                        Rp {{ number_format($sisa, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        @if($tagihan->status === 'lunas')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#75B06F]/20 text-[#36656B] text-[10px] font-bold uppercase tracking-wider">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Belum Lunas
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        @if($tagihan->status !== 'lunas')
                                            <button onclick="document.getElementById('modal-desktop-{{ $tagihan->id }}').showModal()"
                                                class="inline-flex items-center gap-1 bg-[#36656B] hover:bg-[#2a4f54] text-white text-[10px] font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Bayar
                                            </button>
                                        @else
                                            <span class="text-[10px] text-gray-300 italic">—</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Modal Pembayaran Desktop --}}
                                @if($tagihan->status !== 'lunas')
                                <dialog id="modal-desktop-{{ $tagihan->id }}" class="rounded-2xl p-0 shadow-2xl backdrop:bg-black/50 w-full max-w-md">
                                    <div class="bg-white p-6">
                                        <h3 class="text-lg font-bold text-[#36656B] mb-4">Input Pembayaran Pemasangan</h3>

                                        <div class="bg-[#F0F8A4]/30 rounded-xl p-4 mb-6 space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Warga:</span>
                                                <span class="font-semibold">{{ $warga?->nama }}</span>
                                            </div>
                                            <div class="border-t border-[#DAD887]/50 pt-2 mt-2 space-y-1.5 text-xs text-gray-600">
                                                <div class="flex justify-between">
                                                    <span>Total Biaya Pemasangan:</span>
                                                    <span class="font-mono font-semibold text-[#36656B]">Rp {{ number_format($tagihan->total_biaya, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Sudah Dibayar:</span>
                                                    <span class="font-mono text-[#75B06F] font-semibold">Rp {{ number_format($tagihan->total_dibayar, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between font-bold text-base border-t border-[#DAD887] pt-2">
                                                    <span class="text-red-600">Sisa Tagihan:</span>
                                                    <span class="font-mono text-red-600">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="{{ route('pembayaran-pemasangan.bayar', $tagihan->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="search" value="{{ $search }}">
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jumlah Dibayarkan (Rp)</label>
                                                <input type="number" name="jumlah" min="1" max="{{ $sisa }}" required
                                                    placeholder="Masukkan jumlah pembayaran"
                                                    class="w-full px-4 py-3 bg-white border border-[#DAD887] text-gray-800 rounded-xl text-lg font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-[#36656B] transition-all">
                                                <p class="text-[10px] text-gray-400 mt-1">*Pembayaran ini akan mengurangi sisa tagihan pemasangan. Maksimal: Rp {{ number_format($sisa, 0, ',', '.') }}.</p>
                                            </div>
                                            <div class="flex gap-3">
                                                <button type="button" onclick="document.getElementById('modal-desktop-{{ $tagihan->id }}').close()"
                                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="flex-1 bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm">
                                                    Simpan Pembayaran
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </dialog>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                            </svg>
                                            <span>Belum ada tagihan pemasangan.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Card List --}}
            <div class="md:hidden space-y-3 mt-4">
                @forelse($tagihanPemasangans as $tagihan)
                    @php
                        $warga = $tagihan->warga;
                        $sisa  = $tagihan->total_biaya - $tagihan->total_dibayar;
                    @endphp
                    <div class="bg-[#F0F8A4]/10 rounded-2xl border border-[#DAD887]/50 shadow-sm overflow-hidden">

                        {{-- Header: Nama + Status --}}
                        <div class="flex items-start justify-between gap-2 p-4 pb-2">
                            <div class="min-w-0 flex-1">
                                <span class="font-semibold text-gray-900 text-base block truncate">{{ $warga?->nama ?? '-' }}</span>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-mono">
                                        {{ ($warga?->dusun === 'sragan') ? 'Sragan' : 'Luar' }}
                                    </span>
                                    @if($warga?->dusun === 'sragan')
                                        <span class="text-[10px] text-gray-400">RT{{ sprintf('%02d', $warga->rt) }}/RW{{ sprintf('%02d', $warga->rw) }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- Status Badge --}}
                            @if($tagihan->status === 'lunas')
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-[#75B06F]/20 text-[#36656B] text-[10px] font-bold uppercase">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Lunas
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold uppercase">
                                    Belum Lunas
                                </span>
                            @endif
                        </div>

                        {{-- Total Biaya Pemasangan --}}
                        <div class="px-4 py-2">
                            <div class="flex items-baseline justify-between">
                                <span class="text-[10px] text-gray-400 font-semibold uppercase">Total Biaya</span>
                                <span class="font-mono text-lg font-bold text-[#36656B]">Rp {{ number_format($tagihan->total_biaya, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Progress Bayar --}}
                        <div class="mx-4 mt-2 mb-3 p-2.5 rounded-xl bg-[#F0F8A4]/20 border border-[#DAD887]/30">
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-gray-500 block text-[10px] uppercase font-semibold">Sudah Dibayar</span>
                                    <span class="font-mono font-semibold text-[#75B06F]">Rp {{ number_format($tagihan->total_dibayar, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-gray-500 block text-[10px] uppercase font-semibold">Sisa</span>
                                    <span class="font-mono font-bold {{ $sisa > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                        Rp {{ number_format($sisa, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            {{-- Progress Bar --}}
                            @if($tagihan->total_biaya > 0)
                            <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                                @php $pct = min(100, round(($tagihan->total_dibayar / $tagihan->total_biaya) * 100)); @endphp
                                <div class="h-1.5 rounded-full {{ $tagihan->status === 'lunas' ? 'bg-[#75B06F]' : 'bg-[#36656B]' }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 text-right mt-0.5">{{ $pct }}% terbayar</p>
                            @endif
                        </div>

                        {{-- Action --}}
                        <div class="p-4 pt-0">
                            @if($tagihan->status !== 'lunas')
                                <button onclick="document.getElementById('modal-mobile-{{ $tagihan->id }}').showModal()"
                                    class="w-full inline-flex items-center justify-center gap-1.5 bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Bayar / Cicil
                                </button>
                            @else
                                <div class="w-full text-center text-xs text-[#75B06F] font-semibold py-2">
                                    ✓ Tagihan Lunas
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Modal Pembayaran Mobile --}}
                    @if($tagihan->status !== 'lunas')
                    <dialog id="modal-mobile-{{ $tagihan->id }}" class="rounded-2xl p-0 shadow-2xl backdrop:bg-black/50 w-full max-w-md">
                        <div class="bg-white p-6">
                            <h3 class="text-lg font-bold text-[#36656B] mb-4">Input Pembayaran Pemasangan</h3>

                            <div class="bg-[#F0F8A4]/30 rounded-xl p-4 mb-6 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Warga:</span>
                                    <span class="font-semibold">{{ $warga?->nama }}</span>
                                </div>
                                <div class="border-t border-[#DAD887]/40 pt-2 mt-2 space-y-1.5 text-xs text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Total Biaya Pemasangan:</span>
                                        <span class="font-mono font-semibold text-[#36656B]">Rp {{ number_format($tagihan->total_biaya, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Sudah Dibayar:</span>
                                        <span class="font-mono text-[#75B06F] font-semibold">Rp {{ number_format($tagihan->total_dibayar, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-base border-t border-[#DAD887] pt-2">
                                        <span class="text-red-600">Sisa Tagihan:</span>
                                        <span class="font-mono text-red-600">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('pembayaran-pemasangan.bayar', $tagihan->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="search" value="{{ $search }}">
                                <input type="hidden" name="status" value="{{ $status }}">
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jumlah Dibayarkan (Rp)</label>
                                    <input type="number" name="jumlah" min="1" max="{{ $sisa }}" required
                                        placeholder="Masukkan jumlah pembayaran"
                                        class="w-full px-4 py-3 bg-white border border-[#DAD887] text-gray-800 rounded-xl text-lg font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-[#36656B] transition-all">
                                    <p class="text-[10px] text-gray-400 mt-1">*Pembayaran ini akan mengurangi sisa tagihan pemasangan. Maksimal: Rp {{ number_format($sisa, 0, ',', '.') }}.</p>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" onclick="document.getElementById('modal-mobile-{{ $tagihan->id }}').close()"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="flex-1 bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm">
                                        Simpan Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                    @endif
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed rounded-xl">
                        Belum ada tagihan pemasangan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
