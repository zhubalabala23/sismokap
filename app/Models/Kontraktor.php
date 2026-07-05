<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Kontraktor extends Model
{
    /** @use HasFactory<\Database\Factories\KontraktorFactory> */
    use HasFactory;

    protected $table = 'kontraktor';

    protected $fillable = [
        'nama_kontraktor',
        'kontak',
        'alamat',
        'proyek_id',
        'nama_penanggung_jawab',
        'no_telp',
        'email',
        'no_kontrak',
        'masa_berlaku_kontrak',
    ];

    protected $casts = [
        'masa_berlaku_kontrak' => 'date',
    ];

    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class, 'kontraktor_id');
    }

    public function proyekAssociated(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
