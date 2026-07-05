<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKontraktorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kontraktor' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'proyek_id' => 'nullable|exists:proyek,id',
            'nama_penanggung_jawab' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_kontrak' => 'nullable|string|max:255',
            'masa_berlaku_kontrak' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kontraktor.required' => 'Nama perusahaan wajib diisi.',
            'alamat.required' => 'Alamat perusahaan wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'masa_berlaku_kontrak.date' => 'Format tanggal masa berlaku kontrak tidak valid.',
        ];
    }
}
