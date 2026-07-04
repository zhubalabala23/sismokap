<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgressHarianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyek_id' => 'required|exists:proyek,id',
            'tanggal' => 'required|date',
            'persentase' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'proyek_id.required' => 'Proyek wajib dipilih.',
            'proyek_id.exists' => 'Proyek tidak valid.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'persentase.required' => 'Persentase progress wajib diisi.',
            'persentase.numeric' => 'Persentase progress harus berupa angka.',
            'persentase.min' => 'Persentase progress minimal 0%.',
            'persentase.max' => 'Persentase progress maksimal 100%.',
        ];
    }
}
