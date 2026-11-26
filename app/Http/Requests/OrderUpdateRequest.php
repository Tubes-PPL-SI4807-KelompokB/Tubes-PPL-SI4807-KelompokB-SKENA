<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PBI-11 p11-2 — ubah catatan & kode voucher pesanan (otorisasi dicek di Order::isEditableBy).
 */
class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'kode_voucher' => strtoupper(trim((string) $this->input('kode_voucher'))) ?: null,
            'catatan'      => trim((string) $this->input('catatan')) ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'catatan'      => ['nullable', 'string', 'max:1000'],
            'kode_voucher' => ['nullable', 'string', 'max:30', 'regex:/^[A-Z0-9_-]+$/'],
            'aksi'         => ['nullable', 'in:terapkan,simpan'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.max'        => 'Catatan maksimal 1.000 karakter. Ringkas catatanmu lalu simpan lagi.',
            'kode_voucher.max'   => 'Kode voucher maksimal 30 karakter. Periksa lagi kodenya.',
            'kode_voucher.regex' => 'Kode voucher hanya berisi huruf, angka, - atau _. Periksa lagi kodenya.',
        ];
    }
}
