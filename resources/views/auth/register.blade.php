@extends('layouts.app')
@section('title', 'Daftar Akun — SKENA')

@section('content')
<div class="min-h-screen py-10 px-4" style="background:#F8FAFC">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#2C99D6">
                    <span class="text-white font-black">S</span>
                </div>
                <span class="text-xl font-black text-gray-900">SKENA</span>
            </a>
            <h1 class="text-2xl font-black text-gray-900 mb-1">Buat Akun Baru</h1>
            <p class="text-sm text-gray-500">Bergabung dengan ribuan mahasiswa kreatif di Indonesia.</p>
        </div>

        {{-- Role Selector --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6" x-data="{ role: '{{ $selectedRole ?? 'freelancer' }}' }">
            <p class="text-sm font-bold text-gray-700 mb-3">Saya ingin bergabung sebagai:</p>
            <div class="grid grid-cols-2 gap-3 mb-6">
                {{-- Freelancer Option --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="role_selector" value="freelancer" x-model="role" class="sr-only peer">
                    <div class="border-2 rounded-xl p-4 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 border-gray-200 hover:border-gray-300">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:linear-gradient(135deg,#2C99D6,#0F2942)">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <p class="font-bold text-sm text-gray-900">Freelancer Mahasiswa</p>
                        <p class="text-xs text-gray-500 mt-1">Tawarkan jasa kreatif & dapatkan penghasilan</p>
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-blue-500 bg-blue-500 items-center justify-center peer-checked:flex hidden" style="background:#2C99D6">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </label>

                {{-- Client Option --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="role_selector" value="client" x-model="role" class="sr-only peer">
                    <div class="border-2 rounded-xl p-4 transition-all peer-checked:border-red-400 peer-checked:bg-red-50 border-gray-200 hover:border-gray-300">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:linear-gradient(135deg,#EB2E33,#8B0000)">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="font-bold text-sm text-gray-900">Klien / Pemberi Kerja</p>
                        <p class="text-xs text-gray-500 mt-1">Cari & rekrut talenta kreatif terbaik</p>
                    </div>
                </label>
            </div>

            {{-- Error Bag --}}
            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-xs font-bold text-red-700 mb-2">Mohon perbaiki data berikut:</p>
                <ul class="text-xs text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-red-500 inline-block"></span>{{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="role" :value="role">

                {{-- Data Pribadi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="Nama sesuai KTM/KTP" required
                               class="w-full border rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none focus:ring-2 @error('name') border-red-400 bg-red-50 focus:ring-red-200 @else border-gray-200 bg-white focus:border-blue-400 focus:ring-blue-100 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@kampus.ac.id" required
                               class="w-full border rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none focus:ring-2 @error('email') border-red-400 bg-red-50 focus:ring-red-200 @else border-gray-200 bg-white focus:border-blue-400 focus:ring-blue-100 @enderror">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor HP</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="password"
                                   placeholder="Min. 8 karakter" required
                                   class="w-full border rounded-xl px-4 py-2.5 pr-10 text-sm text-gray-900 placeholder-gray-400 outline-none focus:ring-2 @error('password') border-red-400 bg-red-50 focus:ring-red-200 @else border-gray-200 bg-white focus:border-blue-400 focus:ring-blue-100 @enderror">
                            <button type="button" @click="show = !show" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               placeholder="Ulangi password" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                </div>

                {{-- Data Mahasiswa (hanya jika role = freelancer) --}}
                <div x-show="role === 'freelancer'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="border border-blue-200 rounded-xl p-4 bg-blue-50 space-y-4">

                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-5 h-5 rounded flex items-center justify-center" style="background:#2C99D6">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-sm font-bold" style="color:#0F2942">Verifikasi Identitas Mahasiswa</p>
                    </div>
                    <p class="text-xs text-blue-700">Data ini diperlukan untuk mendapatkan badge <strong>Mahasiswa Terverifikasi</strong> yang meningkatkan kepercayaan klien terhadap profil Anda.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">NIM / Nomor Mahasiswa <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" value="{{ old('nim') }}"
                                   placeholder="12022XXXXXXX"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Universitas / Politeknik <span class="text-red-500">*</span></label>
                            <input type="text" name="university" value="{{ old('university') }}"
                                   placeholder="Telkom University"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Fakultas</label>
                            <input type="text" name="faculty" value="{{ old('faculty') }}"
                                   placeholder="Fakultas Industri Kreatif"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Program Studi</label>
                            <input type="text" name="major" value="{{ old('major') }}"
                                   placeholder="Desain Komunikasi Visual"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Foto / Scan KTM (opsional, max 4MB)</label>
                            <div class="border-2 border-dashed border-blue-200 rounded-xl px-4 py-5 text-center bg-white hover:border-blue-400 transition-colors cursor-pointer">
                                <input type="file" name="ktm" id="ktm" accept=".jpg,.jpeg,.png,.pdf" class="sr-only"
                                       onchange="document.getElementById('ktm-label').textContent = this.files[0]?.name || 'Belum ada file dipilih'">
                                <label for="ktm" class="cursor-pointer">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p id="ktm-label" class="text-xs text-gray-500">Klik untuk pilih file KTM (JPG, PNG, PDF)</p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-[0.98] shadow-sm"
                        style="background:#2C99D6">
                    Buat Akun SKENA
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-600 mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color:#2C99D6">Masuk di sini</a>
        </p>

        <p class="text-center text-xs text-gray-400 mt-3">
            Dengan mendaftar, Anda menyetujui
            <a href="#" class="underline">Syarat & Ketentuan</a> dan
            <a href="#" class="underline">Kebijakan Privasi</a> SKENA.
        </p>
    </div>
</div>
@endsection
