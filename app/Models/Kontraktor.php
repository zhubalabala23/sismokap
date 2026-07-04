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
    ];

    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class, 'kontraktor_id');
    }
}
