<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokumentasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyek_id' => 'required|exists:proyek,id',
            'file' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'proyek_id.required' => 'Proyek wajib dipilih.',
            'proyek_id.exists' => 'Proyek tidak valid.',
            'file.required' => 'File foto wajib diunggah.',
            'file.image' => 'File harus berupa gambar.',
            'file.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'file.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ];
    }
}
