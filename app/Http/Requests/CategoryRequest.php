<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi tambah/ubah kategori jasa – PBI-05 (FR-11). Hanya admin (dicek middleware role:admin).
 */
class CategoryRequest extends FormRequest
{
    /** Error ditampung di bag terpisah agar slide-over terbuka lagi setelah gagal simpan. */
    protected $errorBag = 'category';

    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_kategori' => trim((string) $this->input('nama_kategori')),
            'deskripsi'     => trim((string) $this->input('deskripsi')) ?: null,
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('category');

        return [
            'nama_kategori' => ['required', 'string', 'max:100', Rule::unique('service_categories', 'nama_kategori')->ignore($id)],
            'deskripsi'     => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique'   => 'Kategori dengan nama ini sudah ada. Pakai nama lain atau ubah kategori yang sudah ada.',
            'deskripsi.max'          => 'Deskripsi maksimal 500 karakter. Persingkat lalu simpan lagi.',
        ];
    }
}
