<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PBI-11 p11-3 — alasan penolakan yang dikirim ke pencari jasa
 * (otorisasi penyedia dicek di OrderController::rejectRequest).
 */
class RejectServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_penolakan.required' => 'Tulis alasan penolakan agar pencari jasa tahu langkah berikutnya.',
            'alasan_penolakan.min'      => 'Alasan terlalu singkat. Tulis minimal 5 karakter.',
            'alasan_penolakan.max'      => 'Alasan maksimal 500 karakter. Ringkas alasanmu lalu kirim lagi.',
        ];
    }
}
