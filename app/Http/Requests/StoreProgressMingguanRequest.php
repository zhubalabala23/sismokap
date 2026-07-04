<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgressMingguanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyek_id' => 'required|exists:proyek,id',
            'minggu_ke' => 'required|integer|min:1|max:53',
            'tahun' => 'required|integer|min:2020|max:2100',
            'persentase' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'proyek_id.required' => 'Proyek wajib dipilih.',
            'proyek_id.exists' => 'Proyek tidak valid.',
            'minggu_ke.required' => 'Minggu ke- wajib diisi.',
            'minggu_ke.integer' => 'Minggu ke- harus berupa angka bulat.',
            'minggu_ke.min' => 'Minggu ke- minimal 1.',
            'minggu_ke.max' => 'Minggu ke- maksimal 53.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka bulat.',
            'tahun.min' => 'Tahun minimal 2020.',
            'tahun.max' => 'Tahun maksimal 2100.',
            'persentase.required' => 'Persentase progress wajib diisi.',
            'persentase.numeric' => 'Persentase progress harus berupa angka.',
            'persentase.min' => 'Persentase progress minimal 0%.',
            'persentase.max' => 'Persentase progress maksimal 100%.',
        ];
    }
}
