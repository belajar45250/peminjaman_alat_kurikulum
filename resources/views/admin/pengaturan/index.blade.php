{{-- resources/views/admin/pengaturan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pengaturan Sistem')

@section('content')

{{-- Toast Notification --}}
<div id="toast" class="fixed top-5 right-5 z-[999] hidden">
    <div id="toastInner" class="flex items-center gap-3 px-5 py-3 border font-sans text-[0.68rem] font-semibold tracking-[0.15em] uppercase shadow-lg transition-all">
        <i id="toastIcon" class="text-[0.6rem]"></i>
        <span id="toastMsg"></span>
    </div>
</div>

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

                <div>
                    <p class="font-sans text-[0.48rem] font-semibold tracking-[0.3em] uppercase text-ghost mb-5">Aturan Peminjaman</p>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Batas Waktu Peminjaman</label>
                            <div class="flex items-end gap-0 max-w-[160px]">
                                <input type="number" name="batas_jam_pinjam"
                                   value="{{ \App\Models\Pengaturan::ambil('batas_jam_pinjam', 8) }}"
                                   min="1" max="72"
                                   class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                                <span class="pb-2.5 pt-1 font-sans text-[0.78rem] text-ghost border-b border-rule pl-2">Jam</span>
                            </div>
                            <p class="mt-1.5 font-sans text-[0.58rem] tracking-wide text-ghost">Waktu maksimal sebelum dianggap terlambat.</p>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-rule/50"></div>

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
                <button onclick="document.getElementById('modalTambahJam').classList.remove('hidden')"
                        class="border border-rule text-label px-3 py-2 font-sans text-[0.52rem] font-semibold uppercase hover:bg-espresso hover:text-paper transition-all">
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
                    <tbody id="tbodyJam">
                        @forelse($jamPelajaran as $jam)
                        <tr class="group/jam hover:bg-cream/40 transition-colors" data-ke="{{ $jam['ke'] }}">
                            <td class="py-3 px-5 border-b border-rule/40 font-serif text-ink">{{ $jam['ke'] }}</td>
                            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.82rem] font-semibold">{{ $jam['mulai'] }}</td>
                            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.82rem] font-semibold">{{ $jam['selesai'] }}</td>
                            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.72rem] text-ghost">
                                {{ \Carbon\Carbon::createFromFormat('H:i', $jam['mulai'])->diffInMinutes(\Carbon\Carbon::createFromFormat('H:i', $jam['selesai'])) }} Menit
                            </td>
                            <td class="py-3 px-5 border-b border-rule/40">
                                <div class="flex gap-2 opacity-0 group-hover/jam:opacity-100 transition-opacity">
                                    <button onclick="bukaEditJam({{ $jam['ke'] }}, '{{ $jam['mulai'] }}', '{{ $jam['selesai'] }}')"
                                            class="w-6 h-6 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper">
                                        <i class="fas fa-pen text-[0.4rem]"></i>
                                    </button>
                                    <button onclick="hapusJam({{ $jam['ke'] }})"
                                            class="w-6 h-6 border border-rule flex items-center justify-center text-ghost hover:bg-red-900 hover:text-paper">
                                        <i class="fas fa-trash text-[0.4rem]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyJam"><td colspan="5" class="py-8 text-center text-ghost text-[0.62rem] uppercase">Belum ada jam pelajaran</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3. Manajemen Admin --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Keamanan</p>
                    <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Manajemen Admin</h2>
                </div>
                <button onclick="document.getElementById('modalTambahUser').classList.remove('hidden')"
                        class="border border-rule text-label px-3 py-2 font-sans text-[0.52rem] font-semibold uppercase hover:bg-espresso hover:text-paper transition-all">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
            <ul id="listUsers" class="divide-y divide-rule/40">
                @foreach($users as $user)
                <li class="px-6 py-3.5 flex items-center justify-between" data-user-id="{{ $user->id }}">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 bg-cream border border-rule flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-ghost text-[0.6rem]"></i>
                        </div>
                        <div>
                            <p class="font-sans text-[0.78rem] font-semibold text-ink leading-tight">{{ $user->name }}</p>
                            <p class="font-sans text-[0.6rem] text-ghost">@ {{ $user->username }}</p>
                        </div>
                    </div>
                    @if($user->id !== auth()->id())
                    <button onclick="hapusUser({{ $user->id }}, this)"
                            class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-red-900 hover:text-paper transition-colors">
                        <i class="fas fa-trash-alt text-[0.55rem]"></i>
                    </button>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>

    </div>{{-- END KOLOM KIRI --}}

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
            <div class="px-6 py-6">
                @php $logoPath = \App\Models\Pengaturan::ambil('logo_sekolah'); @endphp

                @if($logoPath)
                <div class="mb-5 flex items-center gap-5">
                    <div class="w-24 h-24 border border-rule bg-cream/50 flex items-center justify-center overflow-hidden">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($logoPath) }}" alt="Logo" class="max-w-full max-h-full object-contain p-2">
                    </div>
                    <div>
                        <p class="font-sans text-[0.65rem] text-ink font-medium mb-1">Logo aktif</p>
                        <p class="font-sans text-[0.58rem] tracking-wide text-ghost mb-3">{{ basename($logoPath) }}</p>
                        <form method="POST" action="{{ route('admin.pengaturan.hapus-logo') }}" onsubmit="return confirm('Hapus logo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="flex items-center gap-1.5 border border-red-200 text-red-700 px-3 py-1.5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash text-[0.45rem]"></i> Hapus Logo
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="mb-5 flex items-center gap-4 border border-dashed border-rule bg-cream/30 px-5 py-6">
                    <div class="w-14 h-14 bg-sand border border-rule flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-image text-ghost text-lg"></i>
                    </div>
                    <div>
                        <p class="font-sans text-[0.65rem] text-dim font-medium mb-0.5">Belum ada logo</p>
                        <p class="font-sans text-[0.58rem] tracking-wide text-ghost">Upload logo sekolah untuk ditampilkan di header dan QR Code.</p>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.pengaturan.upload-logo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2.5">
                            {{ $logoPath ? 'Ganti Logo' : 'Upload Logo' }}
                        </label>
                        <input type="file" name="logo" accept="image/*"
                            class="w-full font-sans text-[0.78rem] text-dim file:mr-4 file:py-2 file:px-4 file:border file:border-rule file:bg-cream file:text-label file:font-sans file:text-[0.55rem] file:tracking-[0.15em] file:uppercase file:cursor-pointer hover:file:bg-sand file:transition-colors">
                        <p class="mt-1.5 font-sans text-[0.58rem] tracking-wide text-ghost">Format: JPG, PNG, WEBP, SVG. Maks 2MB. Disarankan ukuran persegi (1:1).</p>
                    </div>
                    <button type="submit" class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-ink transition-colors">
                        <i class="fas fa-upload text-[0.5rem]"></i>
                        {{ $logoPath ? 'Ganti Logo' : 'Upload Logo' }}
                    </button>
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
                        <input type="password" name="password_lama" placeholder="••••••••"
                               class="peer w-full border-b {{ $errors->has('password_lama') ? 'border-red-600':'border-rule' }} bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                    @error('password_lama') <p class="mt-1.5 font-sans text-[0.6rem] text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password_baru" placeholder="Min. 6 karakter"
                               class="peer w-full border-b {{ $errors->has('password_baru') ? 'border-red-600':'border-rule' }} bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                    @error('password_baru') <p class="mt-1.5 font-sans text-[0.6rem] text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru"
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="flex items-center gap-2 border border-espresso text-espresso px-6 py-3 font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase hover:bg-espresso hover:text-paper transition-all active:scale-[0.99]">
                        <i class="fas fa-key text-[0.5rem]"></i> Ubah Password
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. Daftar Kelas --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Akademik</p>
                    <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Daftar Kelas</h2>
                </div>
                <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')"
                        class="border border-rule text-label px-3 py-2 font-sans text-[0.52rem] font-semibold uppercase hover:bg-espresso hover:text-paper transition-all">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
            <div id="daftarKelasContainer" class="px-6 py-5 space-y-4">
                @foreach($daftarKelas as $tingkat => $group)
                <div>
                    <p class="text-[0.5rem] font-bold uppercase text-ghost mb-2">Tingkat {{ $tingkat + 1 }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($group as $kelas)
                        <div class="flex items-center gap-1.5 border border-rule bg-cream/50 px-3 py-1.5 group/kelas">
                            <span class="font-sans text-[0.72rem] text-dim">{{ $kelas }}</span>
                            <button onclick="hapusKelas('{{ $kelas }}', this)"
                                    class="text-ghost hover:text-red-700 transition-colors opacity-0 group-hover/kelas:opacity-100">
                                <i class="fas fa-xmark text-[0.5rem]"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 4. Maintenance Data --}}
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

    </div>{{-- END KOLOM KANAN --}}

</div>{{-- END GRID --}}


{{-- ══════════════════════════════ --}}
{{-- MODALS                        --}}
{{-- ══════════════════════════════ --}}

{{-- ══ MODAL TAMBAH JAM ══ --}}
<div id="modalTambahJam" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-paper border border-rule w-full max-w-sm mx-4">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Jadwal</p>
                <h3 class="font-serif text-ink text-lg font-normal mt-0.5">Tambah Jam Pelajaran</h3>
            </div>
            <button onclick="tutupModal('modalTambahJam')" class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form id="formTambahJam" class="px-6 py-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Jam Mulai</label>
                    <input type="time" name="mulai" required class="w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Jam Selesai</label>
                    <input type="time" name="selesai" required class="w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
            </div>
            <p class="font-sans text-[0.58rem] tracking-wide text-ghost">Jam akan diurutkan otomatis dan diberi nomor ulang.</p>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button" onclick="tutupModal('modalTambahJam')" class="border border-rule text-label px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-sand transition-colors">
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
            <button onclick="tutupModal('modalEditJam')" class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form id="formEditJam" class="px-6 py-6 space-y-5">
            @csrf
            <input type="hidden" name="ke" id="editJamKe">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Jam Mulai</label>
                    <input type="time" name="mulai" id="editJamMulai" required class="w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Jam Selesai</label>
                    <input type="time" name="selesai" id="editJamSelesai" required class="w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button" onclick="tutupModal('modalEditJam')" class="border border-rule text-label px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-sand transition-colors">
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
            <button onclick="tutupModal('modalTambahKelas')" class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form id="formTambahKelas" class="px-6 py-6 space-y-5">
            @csrf
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Nama Kelas</label>
                <div class="relative">
                    <input type="text" name="nama_kelas" placeholder="Contoh: X RPL 3" required
                           class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>
            </div>
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Masukkan ke Tingkat</label>
                <select name="tingkat" id="selectTingkatKelas"
                        class="w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors" required>
                    @foreach($daftarKelas as $i => $group)
                        <option value="{{ $i }}">Tingkat {{ $i + 1 }} ({{ count($group) }} kelas)</option>
                    @endforeach
                    <option value="{{ count($daftarKelas) }}">+ Buat Tingkat Baru</option>
                </select>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button" onclick="tutupModal('modalTambahKelas')" class="border border-rule text-label px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-sand transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL TAMBAH USER ══ --}}
<div id="modalTambahUser" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-paper border border-rule w-full max-w-md mx-4">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Pengguna</p>
                <h3 class="font-serif text-ink text-lg font-normal mt-0.5">Tambah Akun Admin</h3>
            </div>
            <button onclick="tutupModal('modalTambahUser')" class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form id="formTambahUser" class="px-6 py-6 space-y-5">
            @csrf
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Nama Lengkap</label>
                <div class="relative">
                    <input type="text" name="name" placeholder="Nama admin" required
                           class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>
            </div>
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Username</label>
                <div class="flex items-end gap-0">
                    <span class="pb-2.5 pt-1 font-sans text-[0.82rem] text-ghost border-b border-rule pr-2">@</span>
                    <div class="relative flex-1">
                        <input type="text" name="username" placeholder="username" required
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>
                <p id="errorUsername" class="hidden mt-1.5 font-sans text-[0.6rem] text-red-700"></p>
            </div>
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Password</label>
                <div class="relative">
                    <input type="password" name="password" placeholder="Min. 6 karakter huruf + angka" required
                           class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] text-ink outline-none placeholder-ghost transition-colors focus:border-ink">
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-check text-[0.45rem]"></i> Simpan
                </button>
                <button type="button" onclick="tutupModal('modalTambahUser')" class="flex items-center gap-2 border border-rule text-label px-5 py-2.5 font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase hover:bg-sand transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
    || '{{ csrf_token() }}';

// ══════════════════════════════════════
// TOAST
// ══════════════════════════════════════
let toastTimer;
function showToast(msg, ok = true) {
    const toast = document.getElementById('toast');
    const inner = document.getElementById('toastInner');
    const icon  = document.getElementById('toastIcon');
    const text  = document.getElementById('toastMsg');

    text.textContent = msg;
    icon.className = ok
        ? 'fas fa-check text-[0.6rem]'
        : 'fas fa-xmark text-[0.6rem]';
    inner.className = ok
        ? 'flex items-center gap-3 px-5 py-3 border border-green-300 bg-green-50 text-green-800 font-sans text-[0.68rem] font-semibold tracking-[0.15em] uppercase shadow-lg'
        : 'flex items-center gap-3 px-5 py-3 border border-red-300 bg-red-50 text-red-800 font-sans text-[0.68rem] font-semibold tracking-[0.15em] uppercase shadow-lg';

    toast.classList.remove('hidden');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.add('hidden'), 3000);
}

// ══════════════════════════════════════
// MODAL HELPERS
// ══════════════════════════════════════
function tutupModal(id) {
    document.getElementById(id).classList.add('hidden');
}

['modalTambahUser','modalTambahKelas','modalTambahJam','modalEditJam'].forEach(id => {
    const el = document.getElementById(id);
    el?.addEventListener('click', e => { if (e.target === el) tutupModal(id); });
});

// ══════════════════════════════════════
// AJAX HELPER
// ══════════════════════════════════════
async function ajaxPost(url, formData) {
    formData.append('_token', CSRF);
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
    });
    const json = await res.json();
    return { ok: res.ok, data: json };
}

// ══════════════════════════════════════
// JAM PELAJARAN — RENDER
// ══════════════════════════════════════
function diffMenit(mulai, selesai) {
    const [hm, mm] = mulai.split(':').map(Number);
    const [hs, ms] = selesai.split(':').map(Number);
    return (hs * 60 + ms) - (hm * 60 + mm);
}

function renderJam(jamList) {
    const tbody = document.getElementById('tbodyJam');
    if (!jamList.length) {
        tbody.innerHTML = `<tr id="emptyJam"><td colspan="5" class="py-8 text-center text-ghost text-[0.62rem] uppercase">Belum ada jam pelajaran</td></tr>`;
        return;
    }
    tbody.innerHTML = jamList.map(jam => `
        <tr class="group/jam hover:bg-cream/40 transition-colors" data-ke="${jam.ke}">
            <td class="py-3 px-5 border-b border-rule/40 font-serif text-ink">${jam.ke}</td>
            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.82rem] font-semibold">${jam.mulai}</td>
            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.82rem] font-semibold">${jam.selesai}</td>
            <td class="py-3 px-5 border-b border-rule/40 font-sans text-[0.72rem] text-ghost">${diffMenit(jam.mulai, jam.selesai)} Menit</td>
            <td class="py-3 px-5 border-b border-rule/40">
                <div class="flex gap-2 opacity-0 group-hover/jam:opacity-100 transition-opacity">
                    <button onclick="bukaEditJam(${jam.ke}, '${jam.mulai}', '${jam.selesai}')" class="w-6 h-6 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper"><i class="fas fa-pen text-[0.4rem]"></i></button>
                    <button onclick="hapusJam(${jam.ke})" class="w-6 h-6 border border-rule flex items-center justify-center text-ghost hover:bg-red-900 hover:text-paper"><i class="fas fa-trash text-[0.4rem]"></i></button>
                </div>
            </td>
        </tr>`).join('');
}

// ══ TAMBAH JAM ══
document.getElementById('formTambahJam').addEventListener('submit', async function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const { ok, data } = await ajaxPost('{{ route("admin.pengaturan.tambah-jam") }}', fd);
    if (ok && data.success) {
        renderJam(data.jamPelajaran);
        this.reset();
        tutupModal('modalTambahJam');
        showToast(data.message);
    } else {
        showToast(data.message || 'Terjadi kesalahan.', false);
    }
});

// ══ HAPUS JAM ══
async function hapusJam(ke) {
    if (!confirm('Hapus jam ke-' + ke + '?')) return;
    const fd = new FormData();
    fd.append('ke', ke);
    fd.append('_method', 'POST');
    const { ok, data } = await ajaxPost('{{ route("admin.pengaturan.hapus-jam") }}', fd);
    if (ok && data.success) {
        renderJam(data.jamPelajaran);
        showToast(data.message);
    } else {
        showToast(data.message || 'Gagal menghapus.', false);
    }
}

// ══ EDIT JAM ══
function bukaEditJam(ke, mulai, selesai) {
    document.getElementById('editJamKe').value = ke;
    document.getElementById('editJamMulai').value = mulai;
    document.getElementById('editJamSelesai').value = selesai;
    document.getElementById('editJamLabel').textContent = ke;
    document.getElementById('modalEditJam').classList.remove('hidden');
}

document.getElementById('formEditJam').addEventListener('submit', async function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const { ok, data } = await ajaxPost('{{ route("admin.pengaturan.update-jam") }}', fd);
    if (ok && data.success) {
        renderJam(data.jamPelajaran);
        tutupModal('modalEditJam');
        showToast(data.message);
    } else {
        showToast(data.message || 'Terjadi kesalahan.', false);
    }
});

// ══════════════════════════════════════
// KELAS — RENDER
// ══════════════════════════════════════
function renderKelas(daftarKelas) {
    const container = document.getElementById('daftarKelasContainer');
    const select    = document.getElementById('selectTingkatKelas');

    // Render list kelas
    if (!daftarKelas.length) {
        container.innerHTML = '<p class="font-sans text-[0.62rem] text-ghost uppercase">Belum ada kelas.</p>';
    } else {
        container.innerHTML = daftarKelas.map((group, i) => `
            <div>
                <p class="text-[0.5rem] font-bold uppercase text-ghost mb-2">Tingkat ${i + 1}</p>
                <div class="flex flex-wrap gap-2">
                    ${group.map(kelas => `
                        <div class="flex items-center gap-1.5 border border-rule bg-cream/50 px-3 py-1.5 group/kelas">
                            <span class="font-sans text-[0.72rem] text-dim">${kelas}</span>
                            <button onclick="hapusKelas('${kelas}', this)" class="text-ghost hover:text-red-700 transition-colors opacity-0 group-hover/kelas:opacity-100">
                                <i class="fas fa-xmark text-[0.5rem]"></i>
                            </button>
                        </div>`).join('')}
                </div>
            </div>`).join('');
    }

    // Update options di select modal
    select.innerHTML = daftarKelas.map((group, i) =>
        `<option value="${i}">Tingkat ${i + 1} (${group.length} kelas)</option>`
    ).join('') + `<option value="${daftarKelas.length}">+ Buat Tingkat Baru</option>`;
}

// ══ TAMBAH KELAS ══
document.getElementById('formTambahKelas').addEventListener('submit', async function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const { ok, data } = await ajaxPost('{{ route("admin.pengaturan.tambah-kelas") }}', fd);
    if (ok && data.success) {
        renderKelas(data.daftarKelas);
        this.reset();
        tutupModal('modalTambahKelas');
        showToast(data.message);
    } else {
        showToast(data.message || 'Terjadi kesalahan.', false);
    }
});

// ══ HAPUS KELAS ══
async function hapusKelas(nama, btn) {
    if (!confirm('Hapus kelas ' + nama + '?')) return;
    const fd = new FormData();
    fd.append('nama_kelas', nama);
    const { ok, data } = await ajaxPost('{{ route("admin.pengaturan.hapus-kelas") }}', fd);
    if (ok && data.success) {
        renderKelas(data.daftarKelas);
        showToast(data.message);
    } else {
        showToast(data.message || 'Gagal menghapus.', false);
    }
}

// ══════════════════════════════════════
// MANAJEMEN USER
// ══════════════════════════════════════

// ══ TAMBAH USER ══
document.getElementById('formTambahUser').addEventListener('submit', async function(e) {
    e.preventDefault();
    const errEl = document.getElementById('errorUsername');
    errEl.classList.add('hidden');

    const fd = new FormData(this);
    const { ok, data } = await ajaxPost('{{ route("admin.pengaturan.tambah-user") }}', fd);

    if (ok && data.success) {
        // Append user baru ke list
        const li = document.createElement('li');
        li.className = 'px-6 py-3.5 flex items-center justify-between';
        li.dataset.userId = data.user.id;
        li.innerHTML = `
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 bg-cream border border-rule flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-ghost text-[0.6rem]"></i>
                </div>
                <div>
                    <p class="font-sans text-[0.78rem] font-semibold text-ink leading-tight">${data.user.name}</p>
                    <p class="font-sans text-[0.6rem] text-ghost">@ ${data.user.username}</p>
                </div>
            </div>
            <button onclick="hapusUser(${data.user.id}, this)" class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-red-900 hover:text-paper transition-colors">
                <i class="fas fa-trash-alt text-[0.55rem]"></i>
            </button>`;
        document.getElementById('listUsers').appendChild(li);
        this.reset();
        tutupModal('modalTambahUser');
        showToast(data.message);
    } else {
        // Tampilkan error validasi jika ada
        const msg = data.errors?.username?.[0] || data.message || 'Terjadi kesalahan.';
        errEl.textContent = msg;
        errEl.classList.remove('hidden');
        showToast(msg, false);
    }
});

// ══ HAPUS USER ══
async function hapusUser(id, btn) {
    if (!confirm('Hapus akun ini?')) return;
    const fd = new FormData();
    fd.append('_method', 'DELETE');
    const { ok, data } = await ajaxPost(`{{ url('admin/pengaturan/hapus-user') }}/${id}`, fd);
    if (ok && data.success) {
        const li = document.querySelector(`[data-user-id="${id}"]`);
        li?.remove();
        showToast(data.message);
    } else {
        showToast(data.message || 'Gagal menghapus.', false);
    }
}

// ══════════════════════════════════════
// SIMULASI DENDA
// ══════════════════════════════════════
function updatePreview() {
    const h = 500000;
    const r = parseFloat(document.getElementById('inputRusak').value) || 0;
    const l = parseFloat(document.getElementById('inputHilang').value) || 0;
    document.getElementById('previewRusak').textContent = 'Rp ' + Math.round(h * r / 100).toLocaleString('id-ID');
    document.getElementById('previewHilang').textContent = 'Rp ' + Math.round(h * l / 100).toLocaleString('id-ID');
}
document.getElementById('inputRusak')?.addEventListener('input', updatePreview);
document.getElementById('inputHilang')?.addEventListener('input', updatePreview);
updatePreview();
</script>
@endsection