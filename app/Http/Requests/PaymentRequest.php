<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('order_id')) {
            $this->merge(['order_id' => (int) $this->input('order_id')]);
        }
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'proof'    => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048', // Maksimal 2 MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'ID pesanan wajib disertakan.',
            'order_id.exists'   => 'Pesanan tidak ditemukan.',
            'proof.required'    => 'File bukti pembayaran wajib diunggah.',
            'proof.file'        => 'File tidak valid.',
            'proof.mimes'       => 'Format file harus JPG, PNG, atau PDF.',
            'proof.max'         => 'Ukuran file maksimal 2 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'order_id' => 'pesanan',
            'proof'    => 'bukti pembayaran',
        ];
    }
}
