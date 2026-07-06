<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressMingguan extends Model
{
    use HasFactory;

    protected $table = 'progress_mingguan';

    protected $fillable = [
        'proyek_id',
        'minggu_ke',
        'tahun',
        'persentase', // Progress Kumulatif
        'progress_sebelumnya',
        'progress_berjalan',
        'target_mingguan',
        'selisih_capaian',
        'kendala',
        'rencana_berikutnya',
        'keterangan',
    ];

    protected $casts = [
        'persentase' => 'decimal:2',
        'progress_sebelumnya' => 'decimal:2',
        'progress_berjalan' => 'decimal:2',
        'target_mingguan' => 'decimal:2',
        'selisih_capaian' => 'decimal:2',
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    protected static function booted()
    {
        static::saved(function ($progressMingguan) {
            Proyek::clearDashboardCache();
        });
        static::deleted(function ($progressMingguan) {
            Proyek::clearDashboardCache();
        });
    }
}
