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
            'nrp_nip' => 'nullable|string|max:255',
            'pangkat_golongan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'hak_akses' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama personel wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}
