<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProyekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $proyekId = $this->route('proyek');
        // Handle both object and ID route bindings
        if (is_object($proyekId)) {
            $proyekId = $proyekId->id;
        }

        return [
            'kode_proyek' => 'required|string|unique:proyek,kode_proyek,' . $proyekId,
            'nama_proyek' => 'required|string|max:255',
            'lokasi_id' => 'required|exists:lokasi,id',
            'kontraktor_id' => 'required|exists:kontraktor,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_progress' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:berjalan,selesai,terlambat',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_proyek.required' => 'Kode proyek wajib diisi.',
            'kode_proyek.unique' => 'Kode proyek sudah terdaftar.',
            'nama_proyek.required' => 'Nama proyek wajib diisi.',
            'lokasi_id.required' => 'Lokasi wajib dipilih.',
            'lokasi_id.exists' => 'Lokasi yang dipilih tidak valid.',
            'kontraktor_id.required' => 'Kontraktor wajib dipilih.',
            'kontraktor_id.exists' => 'Kontraktor yang dipilih tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'target_progress.required' => 'Target progress wajib diisi.',
            'target_progress.numeric' => 'Target progress harus berupa angka.',
            'target_progress.min' => 'Target progress minimal 0.',
            'target_progress.max' => 'Target progress maksimal 100.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ];
    }
}
