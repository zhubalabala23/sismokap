<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi';

    protected $fillable = [
        'proyek_id',
        'file_path',
        'video_path',
        'jenis_dokumentasi',
        'tanggal_upload',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    protected $appends = ['file_url', 'video_url'];

    public function getFileUrlAttribute(): string
    {
        if (!$this->file_path) {
            return 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=500';
        }

        if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
            return $this->file_path;
        }

        $disk = config('filesystems.default');
        if ($disk === 'supabase') {
            return \Illuminate\Support\Facades\Storage::disk('supabase')->url($this->file_path);
        }

        return asset('storage/' . $this->file_path);
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (!$this->video_path) {
            return null;
        }

        if (filter_var($this->video_path, FILTER_VALIDATE_URL)) {
            return $this->video_path;
        }

        $disk = config('filesystems.default');
        if ($disk === 'supabase') {
            return \Illuminate\Support\Facades\Storage::disk('supabase')->url($this->video_path);
        }

        return asset('storage/' . $this->video_path);
    }

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
