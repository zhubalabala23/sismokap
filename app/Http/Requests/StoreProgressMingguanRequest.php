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
            'progress_sebelumnya' => 'required|numeric|min:0|max:100',
            'progress_berjalan' => 'required|numeric|min:0|max:100',
            'target_mingguan' => 'required|numeric|min:0|max:100',
            'selisih_capaian' => 'required|numeric|min:-100|max:100',
            'kendala' => 'nullable|string',
            'rencana_berikutnya' => 'nullable|string',
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
            'persentase.required' => 'Progress kumulatif wajib diisi.',
            'persentase.numeric' => 'Progress kumulatif harus berupa angka.',
            'persentase.min' => 'Progress kumulatif minimal 0%.',
            'persentase.max' => 'Progress kumulatif maksimal 100%.',
            'progress_sebelumnya.required' => 'Progress minggu sebelumnya wajib diisi.',
            'progress_sebelumnya.numeric' => 'Progress minggu sebelumnya harus berupa angka.',
            'progress_berjalan.required' => 'Progress minggu berjalan wajib diisi.',
            'progress_berjalan.numeric' => 'Progress minggu berjalan harus berupa angka.',
            'target_mingguan.required' => 'Target mingguan wajib diisi.',
            'target_mingguan.numeric' => 'Target mingguan harus berupa angka.',
            'selisih_capaian.required' => 'Selisih capaian wajib diisi.',
            'selisih_capaian.numeric' => 'Selisih capaian harus berupa angka.',
        ];
    }
}
