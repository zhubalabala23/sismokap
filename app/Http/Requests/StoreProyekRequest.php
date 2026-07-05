<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProyekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_proyek' => 'required|string|unique:proyek,kode_proyek',
            'nama_proyek' => 'required|string|max:255',
            'lokasi_nama' => 'required|string|max:255',
            'kontraktor_id' => 'required|exists:kontraktor,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_progress' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:perencanaan,berjalan,selesai,terlambat',
            'jenis_pekerjaan' => 'required|string|max:255',
            'nilai_kontrak' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'tahapan_pekerjaan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_proyek.required' => 'Kode proyek wajib diisi.',
            'kode_proyek.unique' => 'Kode proyek sudah terdaftar.',
            'nama_proyek.required' => 'Nama proyek wajib diisi.',
            'lokasi_nama.required' => 'Lokasi wajib diisi.',
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
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib diisi.',
            'nilai_kontrak.required' => 'Nilai kontrak wajib diisi.',
            'nilai_kontrak.numeric' => 'Nilai kontrak harus berupa angka.',
            'nilai_kontrak.min' => 'Nilai kontrak minimal 0.',
            'foto.image' => 'Foto harus berupa berkas gambar.',
            'foto.mimes' => 'Format foto harus berupa jpeg, png, jpg, atau webp.',
            'foto.max' => 'Ukuran foto tidak boleh melebihi 2MB.',
        ];
    }
}
