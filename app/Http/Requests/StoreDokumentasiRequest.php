<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokumentasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyek_id' => 'required|exists:proyek,id',
            'file' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi,mkv|max:5120',
            'jenis_dokumentasi' => 'required|string|max:100',
            'tanggal_upload' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'proyek_id.required' => 'Proyek wajib dipilih.',
            'proyek_id.exists' => 'Proyek tidak valid.',
            'file.required' => 'File foto wajib diunggah.',
            'file.image' => 'File harus berupa gambar.',
            'file.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'file.max' => 'Ukuran gambar maksimal adalah 2MB.',
            'video.file' => 'File video tidak valid.',
            'video.mimes' => 'Format video harus MP4, MOV, AVI, atau MKV.',
            'video.max' => 'Ukuran video maksimal adalah 5MB.',
            'jenis_dokumentasi.required' => 'Jenis dokumentasi wajib diisi.',
            'tanggal_upload.required' => 'Tanggal dokumentasi wajib diisi.',
            'tanggal_upload.date' => 'Format tanggal tidak valid.',
        ];
    }
}
