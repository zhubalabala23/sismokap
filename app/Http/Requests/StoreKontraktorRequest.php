<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKontraktorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kontraktor' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'alamat' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kontraktor.required' => 'Nama kontraktor wajib diisi.',
            'kontak.required' => 'Kontak wajib diisi.',
            'alamat.required' => 'Alamat kontraktor wajib diisi.',
        ];
    }
}
