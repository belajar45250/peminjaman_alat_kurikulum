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

    {{-- Kolom Kiri: Pengaturan Umum & Denda --}}
    <div class="lg:col-span-3 space-y-6">

        {{-- Pengaturan Umum & Denda --}}
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
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Nama Sekolah
                        </label>
                        <div class="relative">
                            <input type="text" name="nama_sekolah"
                                   value="{{ \App\Models\Pengaturan::ambil('nama_sekolah') }}"
                                   placeholder="Nama sekolah untuk header laporan"
                                   class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                          font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                          placeholder-ghost transition-colors duration-200 focus:border-ink">
                            <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-rule/50"></div>

                {{-- Aturan Peminjaman --}}
                <div>
                    <p class="font-sans text-[0.48rem] font-semibold tracking-[0.3em] uppercase text-ghost mb-5">Aturan Peminjaman</p>
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Batas Waktu Peminjaman
                        </label>
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

                <div class="h-px bg-rule/50"></div>

                {{-- Pengaturan Denda --}}
                <div>
                    <p class="font-sans text-[0.48rem] font-semibold tracking-[0.3em] uppercase text-ghost mb-5">Pengaturan Denda</p>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                                Denda Rusak Berat
                            </label>
                            <div class="flex items-end gap-0">
                                <input type="number" id="inputRusak" name="persentase_denda_rusak"
                                       value="{{ \App\Models\Pengaturan::ambil('persentase_denda_rusak', 30) }}"
                                       min="0" max="100" step="0.5"
                                       class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1
                                              font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                                <span class="pb-2.5 pt-1 font-sans text-[0.78rem] text-ghost border-b border-rule pl-2">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                                Denda Hilang
                            </label>
                            <div class="flex items-end gap-0">
                                <input type="number" id="inputHilang" name="persentase_denda_hilang"
                                       value="{{ \App\Models\Pengaturan::ambil('persentase_denda_hilang', 100) }}"
                                       min="0" max="100" step="0.5"
                                       class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1
                                              font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                                <span class="pb-2.5 pt-1 font-sans text-[0.78rem] text-ghost border-b border-rule pl-2">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Simulasi --}}
                    <div class="mt-5 border-l-2 border-rule bg-cream/50 px-4 py-3">
                        <p class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost mb-2">Simulasi (harga alat Rp 500.000)</p>
                        <div class="flex gap-6">
                            <p class="font-sans text-[0.7rem] text-dim">
                                Rusak: <span class="font-semibold text-ink" id="previewRusak">Rp 0</span>
                            </p>
                            <p class="font-sans text-[0.7rem] text-dim">
                                Hilang: <span class="font-semibold text-ink" id="previewHilang">Rp 0</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="flex items-center gap-2 bg-espresso text-paper px-6 py-3
                               font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                               hover:bg-ink transition-colors active:scale-[0.99]">
                        <i class="fas fa-check text-[0.5rem]"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Ganti Password --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-6 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Keamanan</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Ganti Password Saya</h2>
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
    </div>

    {{-- Kolom Kanan: Manajemen Admin --}}
    <div class="lg:col-span-2">
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4 flex items-center justify-between">
                <div>
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Pengguna</p>
                    <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Manajemen Admin</h2>
                </div>
                <button onclick="document.getElementById('modalTambahUser').classList.remove('hidden')"
                        class="flex items-center gap-1.5 border border-rule text-label px-3 py-2
                               font-sans text-[0.52rem] font-semibold tracking-[0.18em] uppercase
                               hover:bg-espresso hover:text-paper hover:border-espresso transition-all">
                    <i class="fas fa-plus text-[0.45rem]"></i> Tambah
                </button>
            </div>
            <ul class="divide-y divide-rule/40">
                @foreach($users as $user)
                <li class="px-5 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-cream border border-rule flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-ghost text-[0.5rem]"></i>
                        </div>
                        <div>
                            <p class="font-sans text-[0.78rem] font-semibold text-ink leading-tight">{{ $user->name }}</p>
                            <p class="font-sans text-[0.58rem] tracking-wide text-ghost mt-0.5">@{{ $user->username }}</p>
                        </div>
                    </div>
                    <div>
                        @if($user->id === auth()->id())
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-espresso/10 text-ink border-rule">Saya</span>
                        @else
                            <form method="POST" action="{{ route('admin.pengaturan.hapus-user', $user->id) }}"
                                  onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-red-900 hover:text-paper hover:border-red-900 transition-all">
                                    <i class="fas fa-trash text-[0.45rem]"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
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
        const r = parseFloat(document.getElementById('inputRusak').value)  || 0;
        const l = parseFloat(document.getElementById('inputHilang').value) || 0;
        document.getElementById('previewRusak').textContent  = 'Rp ' + Math.round(h * r / 100).toLocaleString('id-ID');
        document.getElementById('previewHilang').textContent = 'Rp ' + Math.round(h * l / 100).toLocaleString('id-ID');
    }
    document.getElementById('inputRusak').addEventListener('input', updatePreview);
    document.getElementById('inputHilang').addEventListener('input', updatePreview);
    updatePreview();

    // Tutup modal klik di luar
    document.getElementById('modalTambahUser').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
</script>
@endsection