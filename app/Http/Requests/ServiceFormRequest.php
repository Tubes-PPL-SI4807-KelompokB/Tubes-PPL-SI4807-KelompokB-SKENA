<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi tambah/ubah jasa – PBI-04 (FR-10).
 * Dinamai ServiceFormRequest agar tidak tertukar dengan model ServiceRequest (PBI-10).
 */
class ServiceFormRequest extends FormRequest
{
    /** Pilihan durasi sesuai desain PBI-04 (p04-2). */
    public const DURATIONS = ['1 hari', '2 hari', '3 hari', '5 hari', '7 hari', '1 jam/sesi'];

    public const MIN_PRICE = 5000;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        // "20.000" / "Rp20.000" → 20000
        $this->merge([
            'harga'  => preg_replace('/\D/', '', (string) $this->input('harga')),
            'status' => $this->boolean('aktif') ? 'aktif' : 'nonaktif',
        ]);
    }

    public function rules(): array
    {
        return [
            'judul'       => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'integer', 'exists:service_categories,id'],
            'deskripsi'   => ['required', 'string', 'max:2000'],
            'harga'       => ['required', 'integer', 'min:' . self::MIN_PRICE, 'max:100000000'],
            'durasi'      => ['required', 'string', 'in:' . implode(',', self::DURATIONS)],
            'status'      => ['required', 'in:aktif,nonaktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'       => 'Judul jasa wajib diisi.',
            'judul.max'            => 'Judul jasa maksimal 150 karakter. Persingkat judulnya lalu simpan lagi.',
            'category_id.required' => 'Pilih kategori jasa.',
            'category_id.exists'   => 'Kategori tidak ditemukan. Pilih kategori dari daftar.',
            'deskripsi.required'   => 'Ceritakan singkat apa yang kamu kerjakan di jasa ini.',
            'harga.required'       => 'Isi harga jasa dalam rupiah.',
            'harga.integer'        => 'Tulis harga dengan angka saja, misalnya 20000.',
            'harga.min'            => 'Harga minimal Rp5.000. Ubah nominalnya lalu simpan lagi.',
            'harga.max'            => 'Harga terlalu besar. Periksa lagi nominalnya.',
            'durasi.required'      => 'Pilih durasi pengerjaan.',
            'durasi.in'            => 'Pilih durasi pengerjaan dari daftar.',
        ];
    }
}
