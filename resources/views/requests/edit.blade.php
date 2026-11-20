
@extends('layouts.app')

@section('title', 'Edit Request Jasa | SKENA')

@push('styles')
<style>
    .request-edit {
        padding: 28px 0 56px;
        color: #0F172A;
    }

    .edit-heading {
        margin-bottom: 26px;
    }

    .edit-heading .kicker {
        color: #3F749C;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
    }

    .edit-heading h1 {
        margin-top: 10px;
        font-size: 30px;
        font-weight: 700;
    }

    .edit-heading p {
        margin-top: 8px;
        color: #64748B;
        font-size: 14px;
    }

    .edit-card {
        max-width: 850px;
        padding: 30px;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(63,116,156,.10);
    }

    .edit-service {
        padding: 18px;
        margin-bottom: 24px;
        background: #F8F9FB;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
    }

    .edit-service small {
        color: #64748B;
        font-size: 12px;
    }

    .edit-service strong {
        display: block;
        margin-top: 5px;
        font-size: 17px;
    }

    .edit-field {
        display: flex;
        flex-direction: column;
        gap: 9px;
        margin-bottom: 22px;
    }

    .edit-field label {
        font-size: 14px;
        font-weight: 600;
    }

    .edit-field input,
    .edit-field textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 14px 18px;
        border: 1px solid #E2E8F0;
        border-radius: 15px;
        background: #fff;
        color: #0F172A;
        font: inherit;
        font-size: 14px;
        outline: none;
    }

    .edit-field input {
        min-height: 48px;
    }

    .edit-field textarea {
        min-height: 120px;
        resize: vertical;
        line-height: 1.6;
    }

    .edit-field input:focus,
    .edit-field textarea:focus {
        border-color: #3F749C;
        box-shadow: 0 0 0 3px #E0ECF6;
    }

    .edit-field small {
        color: #64748B;
        font-size: 12px;
    }

    .edit-error {
        color: #DC2626;
        font-size: 12px;
    }

    .edit-actions {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .edit-back {
        min-height: 46px;
        padding: 0 24px;
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

    .edit-submit {
        min-height: 46px;
        padding: 0 25px;
        border: none;
        border-radius: 999px;
        background: #3F749C;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .edit-submit:hover {
        background: #346284;
    }

    @media(max-width: 600px) {
        .edit-card {
            padding: 22px 18px;
        }

        .edit-heading h1 {
            font-size: 25px;
        }

        .edit-actions {
            flex-direction: column-reverse;
        }

        .edit-actions a,
        .edit-actions button {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<section class="request-edit">

    <div class="edit-heading">
        <div class="kicker">PBI-10 · REQUEST JASA</div>
        <h1>Edit Request Jasa</h1>
        <p>Perbarui kebutuhan dan informasi request jasa milikmu.</p>
    </div>

    <div class="edit-card">

        <div class="edit-service">
            <small>Jasa yang dipilih</small>
            <strong>
                {{ $item->service?->judul ?? 'Jasa tidak ditemukan' }}
            </strong>
        </div>

        <form
            method="POST"
            action="{{ route('requests.update', $item->id) }}"
        >
            @csrf
            @method('PUT')

            <div class="edit-field">
                <label for="kebutuhan">Kebutuhan Jasa</label>

                <textarea
                    id="kebutuhan"
                    name="kebutuhan"
                    required
                    maxlength="5000"
                >{{ old('kebutuhan', $item->kebutuhan) }}</textarea>

                @error('kebutuhan')
                    <span class="edit-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="edit-field">
                <label for="tanggal">Tanggal Dibutuhkan</label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal', $item->tanggal?->format('Y-m-d')) }}"
                    min="{{ now()->toDateString() }}"
                    required
                >

                <small>Tanggal ketika hasil jasa dibutuhkan.</small>

                @error('tanggal')
                    <span class="edit-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="edit-field">
                <label for="catatan">Catatan (Opsional)</label>

                <input
                    type="text"
                    id="catatan"
                    name="catatan"
                    value="{{ old('catatan', $item->catatan) }}"
                    maxlength="2000"
                    placeholder="Tambahkan catatan jika ada..."
                >

                @error('catatan')
                    <span class="edit-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="edit-actions">
                <a
                    href="{{ route('requests.index') }}"
                    class="edit-back"
                >
                    Kembali
                </a>

                <button type="submit" class="edit-submit">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</section>

@endsection
