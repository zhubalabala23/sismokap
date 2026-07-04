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
        'persentase',
        'keterangan',
    ];

    protected $casts = [
        'persentase' => 'decimal:2',
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
