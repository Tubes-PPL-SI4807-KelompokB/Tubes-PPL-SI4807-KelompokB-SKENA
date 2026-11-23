
@extends('layouts.app')

@section('title', 'Ajukan Request Jasa | SKENA')

@push('styles')
<style>
    .request-create {
        padding: 28px 0 56px;
        color: #0F172A;
    }

    .request-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 9px;
        color: #64748B;
        font-size: 13px;
        margin-bottom: 24px;
    }

    .request-breadcrumb a {
        color: #3F749C;
        text-decoration: none;
    }

    .request-breadcrumb a:hover {
        text-decoration: underline;
    }

    .request-create-heading {
        margin-bottom: 28px;
    }

    .request-create-heading .kicker {
        color: #3F749C;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
    }

    .request-create-heading h1 {
        margin-top: 10px;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 700;
    }

    .request-create-heading p {
        margin-top: 8px;
        color: #64748B;
        font-size: 14px;
    }

    .request-form-card {
        max-width: 900px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(63,116,156,.10);
    }

    .request-service-info {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 24px;
        background: #F8F9FB;
        border-bottom: 1px solid #E2E8F0;
    }

    .request-service-icon {
        width: 100px;
        height: 100px;
        flex: none;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: #E3ECF4;
        color: #3F749C;
        font-size: 32px;
        font-weight: 700;
    }

    .request-service-description {
        flex: 1;
        min-width: 0;
    }

    .request-service-description h2 {
        margin-top: 8px;
        font-size: 18px;
        line-height: 1.35;
        font-weight: 600;
    }

    .request-service-description p {
        margin-top: 5px;
        color: #64748B;
        font-size: 13px;
    }

    .request-service-price {
        text-align: right;
        white-space: nowrap;
    }

    .request-service-price small {
        display: block;
        color: #64748B;
        font-size: 12px;
    }

    .request-service-price strong {
        display: block;
        margin-top: 5px;
        font-size: 22px;
        font-weight: 700;
    }

    .request-form {
        display: flex;
        flex-direction: column;
        gap: 22px;
        padding: 32px;
    }

    .request-field {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .request-field label {
        font-size: 14px;
        font-weight: 600;
    }

    .request-field input,
    .request-field textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #E2E8F0;
        border-radius: 15px;
        background: #fff;
        padding: 14px 18px;
        color: #0F172A;
        font: inherit;
        font-size: 14px;
        outline: none;
    }

    .request-field input {
        min-height: 48px;
    }

    .request-field textarea {
        min-height: 125px;
        resize: vertical;
        line-height: 1.6;
    }

    .request-field input:focus,
    .request-field textarea:focus {
        border-color: #3F749C;
        box-shadow: 0 0 0 3px #E0ECF6;
    }

    .request-field .hint {
        color: #64748B;
        font-size: 12px;
        line-height: 1.5;
    }

    .request-field .error {
        color: #DC2626;
        font-size: 12px;
    }

    .request-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .request-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 4px;
    }

    .request-cancel-btn {
        min-height: 48px;
        padding: 0 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #E2E8F0;
        border-radius: 999px;
        color: #0F172A;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .request-submit-btn {
        min-height: 48px;
        padding: 0 28px;
        border: none;
        border-radius: 999px;
        background: #3F749C;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .request-submit-btn:hover {
        background: #346284;
    }

    @media (max-width: 640px) {
        .request-create-heading h1 {
            font-size: 26px;
        }

        .request-service-info {
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 18px;
        }

        .request-service-icon {
            width: 65px;
            height: 65px;
        }

        .request-service-price {
            width: 100%;
            text-align: left;
        }

        .request-form {
            padding: 22px 18px;
        }

        .request-form-row {
            grid-template-columns: 1fr;
        }

        .request-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .request-form-actions a,
        .request-form-actions button {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<section class="request-create">

    {{-- BREADCRUMB --}}
    <div class="request-breadcrumb">
        <a href="{{ route('home') }}">Jelajahi Jasa</a>
        <span>/</span>
        <span>{{ $service->judul }}</span>
        <span>/</span>
        <strong>Ajukan Request</strong>
    </div>

    {{-- JUDUL HALAMAN --}}
    <div class="request-create-heading">
        <div class="kicker">PBI-10 · SPRINT 1</div>
        <h1>Ajukan Request Jasa</h1>
        <p>
            Jelaskan kebutuhanmu. Penyedia jasa akan menerima atau menolak request ini.
        </p>
    </div>

    {{-- INFORMASI JASA DAN FORM --}}
    <div class="request-form-card">

        {{-- INFORMASI JASA --}}
        <div class="request-service-info">

            <div class="request-service-icon">
                {{ strtoupper(mb_substr($service->judul, 0, 1)) }}
            </div>

            <div class="request-service-description">
                <span class="cat">Jasa Pilihan</span>

                <h2>{{ $service->judul }}</h2>

                <p>
                    {{ $service->provider?->name ?? 'Penyedia jasa SKENA' }}
                </p>

                @if($service->deskripsi)
                    <p>{{ $service->deskripsi }}</p>
                @endif

                <p>Estimasi pengerjaan: {{ $service->durasi_label }}</p>
            </div>

            <div class="request-service-price">
                <small>Harga jasa</small>

                <strong>
                    Rp{{ number_format((float) $service->harga, 0, ',', '.') }}
                </strong>
            </div>

        </div>

        {{-- FORM REQUEST --}}
        <form
            class="request-form"
            method="POST"
            action="{{ route('requests.store') }}"
        >
            @csrf

            <input
                type="hidden"
                name="service_id"
                value="{{ $service->id }}"
            >

            {{-- KEBUTUHAN --}}
            <div class="request-field">
                <label for="kebutuhan">Kebutuhan Jasa</label>

                <textarea
                    id="kebutuhan"
                    name="kebutuhan"
                    rows="4"
                    maxlength="5000"
                    required
                    placeholder="Jelaskan kebutuhan jasa yang kamu inginkan..."
                >{{ old('kebutuhan') }}</textarea>

                <span class="hint">
                    Jelaskan secara detail agar penyedia memahami kebutuhanmu.
                </span>

                @error('kebutuhan')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            {{-- TANGGAL DAN CATATAN --}}
            <div class="request-form-row">

                <div class="request-field">
                    <label for="tanggal">Tanggal Dibutuhkan</label>

                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        value="{{ old('tanggal') }}"
                        min="{{ now()->toDateString() }}"
                        required
                    >

                    <span class="hint">
                        Tanggal ketika hasil jasa dibutuhkan.
                    </span>

                    @error('tanggal')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="request-field">
                    <label for="catatan">
                        Catatan
                        <span style="font-weight:400;color:#64748B">(Opsional)</span>
                    </label>

                    <input
                        type="text"
                        id="catatan"
                        name="catatan"
                        value="{{ old('catatan') }}"
                        maxlength="2000"
                        placeholder="Tambahkan catatan jika ada..."
                    >

                    <span class="hint">
                        Informasi tambahan untuk penyedia jasa.
                    </span>

                    @error('catatan')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            {{-- TOMBOL --}}
            <div class="request-form-actions">

                <a
                    href="{{ route('home') }}"
                    class="request-cancel-btn"
                >
                    Batal
                </a>

                <button type="submit" class="request-submit-btn">
                    Kirim Request
                </button>

            </div>

        </form>

    </div>

</section>

@endsection
