<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:indoor,outdoor'],
            'price_per_hour' => ['required', 'numeric', 'min:10000', 'max:10000000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
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
            'name.required' => 'Nama lapangan wajib diisi.',
            'name.max' => 'Nama lapangan maksimal 255 karakter.',
            'type.required' => 'Tipe lapangan wajib dipilih.',
            'type.in' => 'Tipe lapangan harus indoor atau outdoor.',
            'price_per_hour.required' => 'Harga per jam wajib diisi.',
            'price_per_hour.numeric' => 'Harga harus berupa angka.',
            'price_per_hour.min' => 'Harga minimal Rp 10.000 per jam.',
            'price_per_hour.max' => 'Harga maksimal Rp 10.000.000 per jam.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
