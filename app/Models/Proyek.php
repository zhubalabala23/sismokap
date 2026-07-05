<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    /** @use HasFactory<\Database\Factories\ProyekFactory> */
    use HasFactory;

    protected $table = 'proyek';

    protected $fillable = [
        'kode_proyek',
        'nama_proyek',
        'lokasi_id',
        'kontraktor_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'target_progress',
        'status',
        'jenis_pekerjaan',
        'nilai_kontrak',
        'keterangan',
        'tahapan_pekerjaan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'target_progress' => 'decimal:2',
        'nilai_kontrak' => 'decimal:2',
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function kontraktor(): BelongsTo
    {
        return $this->belongsTo(Kontraktor::class, 'kontraktor_id');
    }

    public function progressHarian(): HasMany
    {
        return $this->hasMany(ProgressHarian::class, 'proyek_id');
    }

    public function progressMingguan(): HasMany
    {
        return $this->hasMany(ProgressMingguan::class, 'proyek_id');
    }

    public function dokumentasi(): HasMany
    {
        return $this->hasMany(Dokumentasi::class, 'proyek_id');
    }

    public function getActualProgressAttribute()
    {
        if ($this->relationLoaded('progressHarian')) {
            $latestProgress = $this->progressHarian->sortByDesc(function ($ph) {
                $tanggalStr = $ph->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $ph->tanggal_pelaksanaan->format('Y-m-d') : (string)$ph->tanggal_pelaksanaan;
                return $tanggalStr . '_' . str_pad($ph->id, 10, '0', STR_PAD_LEFT);
            })->first();
            return $latestProgress ? $latestProgress->persentase : 0;
        }

        // Ambil persentase dari progress harian terbaru berdasarkan tanggal
        $latestProgress = $this->progressHarian()->orderBy('tanggal_pelaksanaan', 'desc')->orderBy('id', 'desc')->first();
        return $latestProgress ? $latestProgress->persentase : 0;
    }

    public function updateStatusBasedOnProgress($persentase, $tanggal)
    {
        $target = $this->target_progress;
        $tanggalSelesai = $this->tanggal_selesai;

        $tanggal = \Carbon\Carbon::parse($tanggal);
        $tanggalSelesai = \Carbon\Carbon::parse($tanggalSelesai);

        if ($persentase >= $target && $tanggal->greaterThanOrEqualTo($tanggalSelesai)) {
            $status = 'selesai';
        } elseif ($tanggal->greaterThan($tanggalSelesai) && $persentase < $target) {
            $status = 'terlambat';
        } else {
            $status = 'berjalan';
        }

        $this->status = $status;
        $this->save();
    }
}
