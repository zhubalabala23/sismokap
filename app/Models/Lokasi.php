<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    /** @use HasFactory<\Database\Factories\LokasiFactory> */
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'proyek_id',
        'kabupaten_kota',
        'provinsi',
        'latitude',
        'longitude',
        'keterangan_lokasi',
    ];

    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class, 'lokasi_id');
    }

    public function proyekAssociated(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
