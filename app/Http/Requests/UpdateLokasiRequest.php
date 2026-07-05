<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLokasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyek_id' => 'nullable|exists:proyek,id',
            'nama_lokasi' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'alamat' => 'required|string',
            'keterangan_lokasi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi.',
            'kabupaten_kota.required' => 'Kabupaten/Kota wajib diisi.',
            'provinsi.required' => 'Provinsi wajib diisi.',
            'alamat.required' => 'Alamat lokasi wajib diisi.',
            'latitude.numeric' => 'Format Latitude harus berupa angka.',
            'longitude.numeric' => 'Format Longitude harus berupa angka.',
        ];
    }
}
