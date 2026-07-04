<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama personel wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'kontak.required' => 'Kontak wajib diisi.',
        ];
    }
}
