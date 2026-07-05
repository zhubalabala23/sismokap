<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    /** @use HasFactory<\Database\Factories\PersonelFactory> */
    use HasFactory;

    protected $table = 'personel';

    protected $fillable = [
        'nrp_nip',
        'nama',
        'pangkat_golongan',
        'jabatan',
        'no_hp',
        'email',
        'unit_kerja',
        'hak_akses',
        'password',
    ];
}
