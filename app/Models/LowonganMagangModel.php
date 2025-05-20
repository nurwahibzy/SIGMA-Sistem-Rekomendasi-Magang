<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LowonganMagangModel extends Model
{
    use HasFactory;

    protected $table = 'lowongan_magang';
    protected $primaryKey = 'id_lowongan';
    protected $fillable = ['id_perusahaan', 'id_bidang', 'nama', 'persyaratan', 'foto_path', 'deskripsi'];
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(PerusahaanModel::class, 'id_perusahaan', 'id_perusahaan');
    }
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(BidangModel::class, 'id_bidang', 'id_bidang');
    }
    public function periode_magang(): HasMany
    {
        return $this->hasMany(PeriodeMagangModel::class, 'id_lowongan', 'id_lowongan');
    }
}
