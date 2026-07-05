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
            'tanggal_pelaksanaan' => 'required|date',
            'uraian_pekerjaan' => 'required|string',
            'volume_pekerjaan' => 'required|string',
            'persentase' => 'required|numeric|min:0|max:100',
            'progres_harian' => 'required|numeric|min:0|max:100',
            'kendala' => 'nullable|string',
            'solusi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'proyek_id.required' => 'Proyek wajib dipilih.',
            'proyek_id.exists' => 'Proyek tidak valid.',
            'tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan wajib diisi.',
            'tanggal_pelaksanaan.date' => 'Format tanggal pelaksanaan tidak valid.',
            'uraian_pekerjaan.required' => 'Uraian pekerjaan wajib diisi.',
            'volume_pekerjaan.required' => 'Volume pekerjaan wajib diisi.',
            'persentase.required' => 'Bobot pekerjaan (akumulatif) wajib diisi.',
            'persentase.numeric' => 'Bobot pekerjaan harus berupa angka.',
            'persentase.min' => 'Bobot pekerjaan minimal 0%.',
            'persentase.max' => 'Bobot pekerjaan maksimal 100%.',
            'progres_harian.required' => 'Persentase progres harian wajib diisi.',
            'progres_harian.numeric' => 'Persentase progres harian harus berupa angka.',
            'progres_harian.min' => 'Persentase progres harian minimal 0%.',
            'progres_harian.max' => 'Persentase progres harian maksimal 100%.',
        ];
    }
}
