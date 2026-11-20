<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'duration_hours' => 'required|integer|min:1|max:8',
            'payment_method' => 'required|in:qris,cash',
            'notes' => 'nullable|string|max:500',
            'addons' => 'nullable|array',
            'addons.*' => 'nullable|integer|min:0|max:99',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'field_id.required' => 'Lapangan wajib dipilih.',
            'field_id.exists' => 'Lapangan tidak ditemukan.',
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'start_time.date_format' => 'Format jam mulai tidak valid (HH:MM).',
            'duration_hours.required' => 'Durasi sewa wajib dipilih.',
            'duration_hours.min' => 'Durasi minimal adalah 1 jam.',
            'duration_hours.max' => 'Durasi maksimal adalah 8 jam.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'addons.array' => 'Item tambahan tidak valid.',
            'addons.*.integer' => 'Jumlah item tambahan harus berupa angka.',
            'addons.*.min' => 'Jumlah item tambahan tidak boleh negatif.',
            'addons.*.max' => 'Jumlah item tambahan maksimal 99.',
        ];
    }
}
