<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressHarian extends Model
{
    use HasFactory;

    protected $table = 'progress_harian';

    protected $fillable = [
        'proyek_id',
        'tanggal',
        'persentase',
        'keterangan',
        'input_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'persentase' => 'decimal:2',
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}
