{{-- resources/views/admin/pengaturan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pengaturan Sistem')

@section('content')

<div class="mb-8">
    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Sistem</p>
    <h1 class="font-serif text-ink text-3xl font-normal leading-none">Pengaturan</h1>
    <div class="mt-3 h-px w-10 bg-rule"></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- ========================================== --}}
    {{-- KOLOM KIRI (Lebar: col-span-3)             --}}
    {{-- ========================================== --}}
    <div class="lg:col-span-3 space-y-6">

        {{-- 1. Pengaturan Umum & Denda --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Konfigurasi</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Pengaturan Sistem</h2>
            </div>
            <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="px-6 py-6 space-y-8">
                @csrf

                {{-- Identitas Sekolah --}}
                <div>
                    <p class="font-sans text-[0.48rem] font-semibold tracking-[0.3em] uppercase text-ghost mb-5">Identitas Sekolah</p>
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Nama Sekolah</label>
                        <div class="relative">
                            <input type="text" name="nama_sekolah" value="{{ \App\Models\Pengaturan::ambil('nama_sekolah') }}"
                                placeholder="Nama sekolah untuk header laporan"
                                class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none transition-colors focus:border-ink">
                            <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-rule/50"></div>

                {{-- Aturan Peminjaman --}}
                <div>
                    <p class="font-sans text-[0.48rem] font-semibold tracking-[0.3em] uppercase text-ghost mb-5">Aturan Peminjaman</p>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Batas Waktu Peminjaman</label>
                            <div class="flex items-end gap-0 max-w-[160px]">
                                <input type="number" name="batas_jam_pinjam"
                                   value="{{ \App\Models\Pengaturan::ambil('batas_jam_pinjam', 8) }}"
                                   min="1" max="72"
                                   class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1
                                          font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                                <span class="pb-2.5 pt-1 font-sans text-[0.78rem] text-ghost border-b border-rule pl-2">Jam</span>
                            </div>
                            <p class="mt-1.5 font-sans text-[0.58rem] tracking-wide text-ghost">Waktu maksimal sebelum dianggap terlambat.</p>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-rule/50"></div>

                {{-- Pengaturan Denda --}}
                <div>
                    <p class="font-sans text-[0.48rem] font-semibold tracking-[0.3em] uppercase text-ghost mb-5">Pengaturan Denda</p>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Denda Rusak Berat</label>
                            <div class="flex items-end gap-0">
                                <input type="number" id="inputRusak" name="persentase_denda_rusak" value="{{ \App\Models\Pengaturan::ambil('persentase_denda_rusak', 30) }}"
                                       class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink">
                                <span class="pb-2.5 pt-1 font-sans text-[0.78rem] text-ghost border-b border-rule pl-2">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Denda Hilang</label>
                            <div class="flex items-end gap-0">
                                <input type="number" id="inputHilang" name="persentase_denda_hilang" value="{{ \App\Models\Pengaturan::ambil('persentase_denda_hilang', 100) }}"
                                       class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink">
                                <span class="pb-2.5 pt-1 font-sans text-[0.78rem] text-ghost border-b border-rule pl-2">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 border-l-2 border-rule bg-cream/50 px-4 py-3">
                        <p class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost mb-2">Simulasi (Harga Alat Rp 500.000)</p>
                        <div class="flex gap-6">
                            <p class="font-sans text-[0.7rem] text-dim">Rusak: <span class="font-semibold text-ink" id="previewRusak">Rp 0</span></p>
                            <p class="font-sans text-[0.7rem] text-dim">Hilang: <span class="font-semibold text-ink" id="previewHilang">Rp 0</span></p>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="flex items-center gap-2 bg-espresso text-paper px-6 py-3 font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase hover:bg-ink transition-colors">
                        <i class="fas fa-check text-[0.5rem]"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- 2. Jam Pelajaran --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Peminjaman</p>
                    <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Jam Pelajaran</h2>
                </div>
                <button onclick="document.getElementById('modalTambahJam').classList.remove('hidden')" class="border border-rule text-label px-3 py-2 font-sans text-[0.52rem] font-semibold uppercase hover:bg-espresso hover:text-paper transition-all">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-cream">
                            <th class="py-3 px-5 text-left font-sans text-[0.48rem] uppercase text-label border-b border-rule">Ke-</th>
                            <th class="py-3 px-5 text-left font-sans text-[0.48rem] uppercase text-label border-b border-rule">Mulai</th>
                            <th class="py-3 px-5 text-left font-sans text-[0.48rem] uppercase text-label border-b border-rule">Selesai</th>
                            <th class="py-3 px-5 text-left font-sans text-[0.48rem] uppercase text-label border-b border-rule">Durasi</th>
                            <th class="py-3 px-5 border-b border-rule"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jamPelajaran as $jam)
                        <tr class="group/jam hover:bg-cream/40 transition-colors">
                            <td class="py-3 px-5 border-b border-rule/40 font-serif text-ink">{{ $jam['ke'] }}</td>
                            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.82rem] font-semibold">{{ $jam['mulai'] }}</td>
                            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.82rem] font-semibold">{{ $jam['selesai'] }}</td>
                            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.72rem] text-ghost">
                                {{ \Carbon\Carbon::createFromFormat('H:i', $jam['mulai'])->diffInMinutes(\Carbon\Carbon::createFromFormat('H:i', $jam['selesai'])) }} Menit
                            </td>
                            <td class="py-3 px-5 border-b border-rule/40">
                                <div class="flex gap-2 opacity-0 group-hover/jam:opacity-100 transition-opacity">
                                    <button onclick="bukaEditJam({{ $jam['ke'] }}, '{{ $jam['mulai'] }}', '{{ $jam['selesai'] }}')" class="w-6 h-6 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper"><i class="fas fa-pen text-[0.4rem]"></i></button>
                                    <form method="POST" action="{{ route('admin.pengaturan.hapus-jam') }}">
                                        @csrf <input type="hidden" name="ke" value="{{ $jam['ke'] }}">
                                        <button type="submit" class="w-6 h-6 border border-rule flex items-center justify-center text-ghost hover:bg-red-900 hover:text-paper"><i class="fas fa-trash text-[0.4rem]"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-8 text-center text-ghost text-[0.62rem] uppercase">Belum ada jam pelajaran</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- KOLOM KANAN (Sempit: col-span-2)           --}}
    {{-- ========================================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- 1. Logo Sekolah --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Tampilan</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Logo Sekolah</h2>
            </div>
            <div class="px-6 py-6 text-center">
                @php $logoPath = \App\Models\Pengaturan::ambil('logo_sekolah'); @endphp
                @if($logoPath)
                    <div class="mb-5">
                        <img src="{{ asset('storage/' . $logoPath) }}" class="mx-auto" style="max-height: 100px; width: auto; object-fit: contain;">
                        <div>
                            <p class="font-sans text-[0.65rem] text-ink font-medium mb-1">Logo aktif</p>
                            <p class="font-sans text-[0.58rem] tracking-wide text-ghost mb-3">{{ basename($logoPath) }}</p>
                            <form method="POST" action="{{ route('admin.pengaturan.hapus-logo') }}"
                                onsubmit="return confirm('Hapus logo?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="flex items-center gap-1.5 border border-red-200 text-red-700 px-3 py-1.5
                                            font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase
                                            hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash text-[0.45rem]"></i> Hapus Logo
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="mb-5 py-6 border border-dashed border-rule bg-cream/30">
                        <i class="fas fa-image text-ghost text-2xl mb-2 block"></i>
                        <p class="text-[0.58rem] text-ghost uppercase tracking-wider">Belum ada logo</p>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.pengaturan.upload-logo') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="logo" class="w-full text-xs text-ghost mb-4 file:mr-4 file:py-2 file:px-4 file:bg-cream file:text-[0.55rem] file:uppercase">
                    <button type="submit" class="w-full bg-espresso text-paper py-2.5 font-sans text-[0.58rem] font-bold uppercase tracking-[0.2em] hover:bg-ink">Upload Logo</button>
                </form>
            </div>
        </div>

        {{-- 2. Ganti Password --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Keamanan</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Ganti Password</h2>
            </div>
            <form method="POST" action="{{ route('admin.pengaturan.ganti-password') }}" class="px-6 py-6 space-y-5">
                @csrf
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Password Lama</label>
                    <div class="relative">
                        <input type="password" name="password_lama"
                               placeholder="••••••••"
                               class="peer w-full border-b {{ $errors->has('password_lama') ? 'border-red-600':'border-rule' }} bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                    @error('password_lama') <p class="mt-1.5 font-sans text-[0.6rem] text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password_baru"
                               placeholder="Min. 6 karakter"
                               class="peer w-full border-b {{ $errors->has('password_baru') ? 'border-red-600':'border-rule' }} bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                    @error('password_baru') <p class="mt-1.5 font-sans text-[0.6rem] text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password_baru_confirmation"
                               placeholder="Ulangi password baru"
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit"
                        class="flex items-center gap-2 border border-espresso text-espresso px-6 py-3
                               font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                               hover:bg-espresso hover:text-paper transition-all active:scale-[0.99]">
                        <i class="fas fa-key text-[0.5rem]"></i> Ubah Password
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. Daftar Kelas --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
                <h2 class="font-serif text-ink text-lg font-normal">Daftar Kelas</h2>
                <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')" class="text-label hover:text-espresso"><i class="fas fa-plus text-xs"></i></button>
            </div>
            <div class="px-6 py-5 space-y-4">
                @foreach($daftarKelas as $tingkat => $group)
                <div>
                    <p class="text-[0.5rem] font-bold uppercase text-ghost mb-2">Tingkat {{ $tingkat + 1 }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($group as $kelas)
                        <div class="flex items-center gap-1.5 border border-rule bg-cream/50 px-3 py-1.5 group/kelas">
                            <span class="font-sans text-[0.72rem] text-dim">{{ $kelas }}</span>
                            <form method="POST" action="{{ route('admin.pengaturan.hapus-kelas') }}"
                                onsubmit="return confirm('Hapus kelas {{ $kelas }}?')">
                                @csrf
                                <input type="hidden" name="nama_kelas" value="{{ $kelas }}">
                                <button type="submit"
                                        class="text-ghost hover:text-red-700 transition-colors opacity-0 group-hover/kelas:opacity-100">
                                    <i class="fas fa-xmark text-[0.5rem]"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 4. Manajemen Admin --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
                <h2 class="font-serif text-ink text-lg font-normal">Manajemen Admin</h2>
                <button onclick="document.getElementById('modalTambahUser').classList.remove('hidden')" class="text-label hover:text-espresso"><i class="fas fa-plus text-xs"></i></button>
            </div>
            <ul class="divide-y divide-rule/40">
                @foreach($users as $user)
                <li class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-ink leading-tight">{{ $user->name }}</p>
                        <p class="text-[0.6rem] text-ghost">@ {{ $user->username }}</p>
                    </div>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.pengaturan.hapus-user', $user->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-ghost hover:text-red-700"><i class="fas fa-trash-alt text-[0.7rem]"></i></button>
                    </form>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="bg-paper border border-rule">
    <div class="border-b border-rule px-6 py-4">
        <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Sistem</p>
        <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Maintenance Data</h2>
    </div>
    <div class="px-6 py-6">
        <p class="text-[0.65rem] text-ghost mb-4">Bersihkan data lama (>1 tahun) dan simpan ke file JSON.</p>
        <form method="POST" action="{{ route('admin.pengaturan.maintenance') }}" onsubmit="return confirm('Jalankan maintenance?')">
            @csrf
            <button type="submit" class="w-full bg-espresso text-paper py-2.5 text-[0.58rem] font-bold uppercase tracking-widest hover:bg-ink transition-colors">
                <i class="fas fa-database mr-2"></i> Jalankan Sekarang
            </button>
        </form>
    </div>
</div>
</div>

{{-- MODALS --}}


{{-- ══ MODAL TAMBAH JAM ══ --}}
<div id="modalTambahJam" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-paper border border-rule w-full max-w-sm mx-4">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Jadwal</p>
                <h3 class="font-serif text-ink text-lg font-normal mt-0.5">Tambah Jam Pelajaran</h3>
            </div>
            <button onclick="document.getElementById('modalTambahJam').classList.add('hidden')"
                    class="w-7 h-7 border border-rule flex items-center justify-center text-ghost
                           hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.pengaturan.tambah-jam') }}" class="px-6 py-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Jam Mulai
                    </label>
                    <input type="time" name="mulai" required
                           class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Jam Selesai
                    </label>
                    <input type="time" name="selesai" required
                           class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
            </div>
            <p class="font-sans text-[0.58rem] tracking-wide text-ghost">
                Jam akan diurutkan otomatis dan diberi nomor ulang.
            </p>
            <div class="pt-2 flex gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5
                           font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                           hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button"
                        onclick="document.getElementById('modalTambahJam').classList.add('hidden')"
                        class="border border-rule text-label px-5 py-2.5
                               font-sans text-[0.58fransk] font-semibold tracking-[0.22em] uppercase
                               hover:bg-sand transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══ MODAL EDIT JAM ══ --}}
<div id="modalEditJam" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-paper border border-rule w-full max-w-sm mx-4">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Jadwal</p>
                <h3 class="font-serif text-ink text-lg font-normal mt-0.5">Edit Jam ke-<span id="editJamLabel"></span></h3>
            </div>
            <button onclick="document.getElementById('modalEditJam').classList.add('hidden')"
                    class="w-7 h-7 border border-rule flex items-center justify-center text-ghost
                           hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.pengaturan.update-jam') }}" class="px-6 py-6 space-y-5">
            @csrf
            <input type="hidden" name="ke" id="editJamKe">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Jam Mulai
                    </label>
                    <input type="time" name="mulai" id="editJamMulai" required
                           class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Jam Selesai
                    </label>
                    <input type="time" name="selesai" id="editJamSelesai" required
                           class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5
                           font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                           hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button"
                        onclick="document.getElementById('modalEditJam').classList.add('hidden')"
                        class="border border-rule text-label px-5 py-2.5
                               font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                               hover:bg-sand transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL TAMBAH KELAS ══ --}}
<div id="modalTambahKelas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-paper border border-rule w-full max-w-sm mx-4">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Kelas</p>
                <h3 class="font-serif text-ink text-lg font-normal mt-0.5">Tambah Kelas</h3>
            </div>
            <button onclick="document.getElementById('modalTambahKelas').classList.add('hidden')"
                    class="w-7 h-7 border border-rule flex items-center justify-center text-ghost
                           hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.pengaturan.tambah-kelas') }}" class="px-6 py-6 space-y-5">
            @csrf
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                    Nama Kelas
                </label>
                <div class="relative">
                    <input type="text" name="nama_kelas" placeholder="Contoh: X RPL 3" required
                           class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none placeholder-ghost
                                  transition-colors focus:border-ink">
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>
            </div>
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                    Masukkan ke Tingkat
                </label>
                <select name="tingkat"
                        class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                               font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors"
                        required>
                    @foreach($daftarKelas as $i => $group)
                        <option value="{{ $i }}">Tingkat {{ $i + 1 }} ({{ count($group) }} kelas)</option>
                    @endforeach
                    <option value="{{ count($daftarKelas) }}">+ Buat Tingkat Baru</option>
                </select>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5
                           font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                           hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button"
                        onclick="document.getElementById('modalTambahKelas').classList.add('hidden')"
                        class="border border-rule text-label px-5 py-2.5
                               font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                               hover:bg-sand transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>


{{-- Modal Tambah User --}}
<div id="modalTambahUser" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-paper border border-rule w-full max-w-md mx-4">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Pengguna</p>
                <h3 class="font-serif text-ink text-lg font-normal mt-0.5">Tambah Akun Admin</h3>
            </div>
            <button onclick="document.getElementById('modalTambahUser').classList.add('hidden')"
                    class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.pengaturan.tambah-user') }}" class="px-6 py-6 space-y-5">
            @csrf
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Nama Lengkap</label>
                <div class="relative">
                    <input type="text" name="name" placeholder="Nama admin" required
                           class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>
            </div>
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Username</label>
                <div class="flex items-end gap-0">
                    <span class="pb-2.5 pt-1 font-sans text-[0.82rem] text-ghost border-b border-rule pr-2">@</span>
                    <div class="relative flex-1">
                        <input type="text" name="username" placeholder="username" required
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>
            </div>
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Password</label>
                <div class="relative">
                    <input type="password" name="password" placeholder="Min. 6 karakter huruf + angka" required
                           class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5
                           font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                           hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button"
                        onclick="document.getElementById('modalTambahUser').classList.add('hidden')"
                        class="flex items-center gap-2 border border-rule text-label px-5 py-2.5
                               font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                               hover:bg-sand transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updatePreview() {
        const h = 500000;
        const r = parseFloat(document.getElementById('inputRusak').value) || 0;
        const l = parseFloat(document.getElementById('inputHilang').value) || 0;
        document.getElementById('previewRusak').textContent = 'Rp ' + Math.round(h * r / 100).toLocaleString('id-ID');
        document.getElementById('previewHilang').textContent = 'Rp ' + Math.round(h * l / 100).toLocaleString('id-ID');
    }
    document.getElementById('inputRusak').addEventListener('input', updatePreview);
    document.getElementById('inputHilang').addEventListener('input', updatePreview);
    updatePreview();

    function bukaEditJam(ke, mulai, selesai) {
        document.getElementById('editJamKe').value = ke;
        document.getElementById('editJamMulai').value = mulai;
        document.getElementById('editJamSelesai').value = selesai;
        document.getElementById('editJamLabel').textContent = ke;
        document.getElementById('modalEditJam').classList.remove('hidden');
    }

    ['modalTambahUser','modalTambahKelas','modalTambahJam','modalEditJam'].forEach(id => {
        const el = document.getElementById(id);
        el?.addEventListener('click', e => { if (e.target === el) el.classList.add('hidden'); });
    });
</script>
<script>
    // ── Modal helpers ──
    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', e => {
            if (e.target === el) el.classList.add('hidden');
        });
    });

    // ── Edit Jam ──
    function bukaEditJam(ke, mulai, selesai) {
        document.getElementById('editJamKe').value      = ke;
        document.getElementById('editJamMulai').value   = mulai;
        document.getElementById('editJamSelesai').value = selesai;
        document.getElementById('editJamLabel').textContent = ke;
        document.getElementById('modalEditJam').classList.remove('hidden');
    }

    // ── Tutup modal klik di luar ──
    ['modalTambahUser','modalTambahKelas','modalTambahJam','modalEditJam'].forEach(id => {
        const el = document.getElementById(id);
        el?.addEventListener('click', e => { if (e.target === el) el.classList.add('hidden'); });
    });

    // ── Preview simulasi denda (sudah ada) ──
    function updatePreview() {
        const h = 500000;
        const r = parseFloat(document.getElementById('inputRusak').value)  || 0;
        const l = parseFloat(document.getElementById('inputHilang').value) || 0;
        document.getElementById('previewRusak').textContent  = 'Rp ' + Math.round(h * r / 100).toLocaleString('id-ID');
        document.getElementById('previewHilang').textContent = 'Rp ' + Math.round(h * l / 100).toLocaleString('id-ID');
    }
    document.getElementById('inputRusak')?.addEventListener('input', updatePreview);
    document.getElementById('inputHilang')?.addEventListener('input', updatePreview);
    updatePreview();
</script>
@endsection