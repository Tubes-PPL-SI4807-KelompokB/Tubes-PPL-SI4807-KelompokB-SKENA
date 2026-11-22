<?php

namespace App\Http\Requests;

use App\Models\Voucher;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Validasi tambah/ubah voucher – PBI-09. */
class VoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code'      => strtoupper(trim((string) $this->input('code'))),
            // "5.000" / "5,000" → 5000
            'value'     => preg_replace('/\D/', '', (string) $this->input('value')),
            'quota'     => preg_replace('/\D/', '', (string) $this->input('quota')),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        $voucher = $this->route('voucher');   // id (string) saat ubah, null saat tambah
        $used    = $voucher ? (int) Voucher::whereKey($voucher)->value('used_count') : 0;

        return [
            'code'       => ['required', 'string', 'max:30', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('vouchers', 'code')->ignore($voucher)],
            'type'       => ['required', Rule::in([Voucher::TYPE_PERCENT, Voucher::TYPE_NOMINAL])],
            'value'      => ['required', 'integer', 'min:1', $this->input('type') === Voucher::TYPE_PERCENT ? 'max:100' : 'max:100000000'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'quota'      => ['required', 'integer', 'min:' . max(1, $used), 'max:1000000'],
            'is_active'  => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'            => 'Kode voucher wajib diisi.',
            'code.regex'               => 'Kode hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'code.max'                 => 'Kode voucher maksimal 30 karakter.',
            'code.unique'              => 'Kode voucher sudah digunakan. Coba kode lain.',
            'type.required'            => 'Pilih jenis potongan.',
            'type.in'                  => 'Jenis potongan tidak valid.',
            'value.required'           => 'Nilai potongan wajib diisi.',
            'value.min'                => 'Nilai potongan minimal 1.',
            'value.max'                => $this->input('type') === Voucher::TYPE_PERCENT
                                            ? 'Potongan persen maksimal 100%.'
                                            : 'Nilai potongan terlalu besar.',
            'start_date.required'      => 'Tanggal mulai wajib diisi.',
            'end_date.required'        => 'Tanggal berakhir wajib diisi.',
            'end_date.after_or_equal'  => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
            'quota.required'           => 'Kuota wajib diisi.',
            'quota.min'                => 'Kuota minimal 1 dan tidak boleh kurang dari jumlah yang sudah dipakai.',
            'quota.max'                => 'Kuota terlalu besar.',
        ];
    }

    public function attributes(): array
    {
        return ['code' => 'kode voucher', 'value' => 'nilai potongan', 'quota' => 'kuota'];
    }

    /** Saat kode bentrok, sertakan saran kode (keadaan "Galat · Kode Duplikat" di desain). */
    protected function failedValidation(Validator $validator): void
    {
        if (isset($validator->failed()['code']['Unique'])) {
            session()->flash('code_suggestions', Voucher::suggestCodes((string) $this->input('code')));
        }

        parent::failedValidation($validator);
    }
}
