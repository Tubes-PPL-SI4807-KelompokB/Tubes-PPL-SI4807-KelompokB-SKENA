@extends('layouts.app')
@section('title', 'Edit Profil — SKENA')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Pengaturan Profil</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Perbarui informasi profil dan identitas akun Anda di SKENA</p>
        </div>
        <a href="{{ route('profile.index') }}" class="text-xs font-bold text-gray-500 hover:text-brand-blue transition-colors">
            ← Kembali ke Profil
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
        <p class="text-xs font-bold text-red-700 mb-2">Mohon perbaiki data berikut:</p>
        <ul class="text-xs text-red-600 space-y-1">
            @foreach($errors->all() as $error)
                <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- 1. Data Personal --}}
            <div>
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide mb-4">Informasi Akun</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email <span class="text-gray-400 font-normal">(tidak dapat diubah)</span></label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kota Domisili</label>
                        <input type="text" name="city" value="{{ old('city', $user->profile?->city) }}" placeholder="Contoh: Bandung, Jakarta Selatan"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                </div>
            </div>

            {{-- 2. Detail Profesional --}}
            <div class="border-t border-gray-100 pt-6">
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide mb-4">Bio & Jasa</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Headline Profil</label>
                        <input type="text" name="headline" value="{{ old('headline', $user->profile?->headline) }}"
                               placeholder="Contoh: Creative Graphic Designer & Brand Specialist"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tentang Saya (Bio)</label>
                        <textarea name="bio" rows="4" placeholder="Ceritakan pengalaman, gaya desain, atau karya favorit Anda..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">{{ old('bio', $user->profile?->bio) }}</textarea>
                    </div>

                    @if($user->isFreelancer())
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tarif Jasa per Jam (Estimasi Rp)</label>
                        <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $user->profile?->hourly_rate) }}" placeholder="100000"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    @endif
                </div>
            </div>

            {{-- 3. Data Mahasiswa (Freelancer Only) --}}
            @if($user->isFreelancer())
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2.5 h-2.5 rounded-full" style="background:#3f749c"></span>
                    <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">Data Kemahasiswaan & KTM</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Universitas / Institut</label>
                        <input type="text" name="university" value="{{ old('university', $user->university) }}" placeholder="Telkom University"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" name="nim" value="{{ old('nim', $user->nim) }}" placeholder="12022XXXXXXX"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Fakultas</label>
                        <input type="text" name="faculty" value="{{ old('faculty', $user->faculty) }}" placeholder="Fakultas Industri Kreatif"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Program Studi</label>
                        <input type="text" name="major" value="{{ old('major', $user->major) }}" placeholder="Desain Komunikasi Visual"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Upload Ulang Foto KTM (Opsional)</label>
                        <input type="file" name="ktm" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:text-white file:bg-blue-500 hover:file:bg-blue-600 cursor-pointer">
                        <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, atau PDF (maksimal 4MB). Jika diunggah, admin akan meninjau ulang KTM Anda.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Submit Buttons --}}
            <div class="border-t border-gray-100 pt-6 flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-3 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-[0.98] shadow-sm"
                        style="background:#3f749c">
                    Simpan Perubahan
                </button>
                <a href="{{ route('profile.index') }}"
                   class="px-5 py-3 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:border-gray-300 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
