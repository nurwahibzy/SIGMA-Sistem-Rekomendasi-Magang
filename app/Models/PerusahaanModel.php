<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerusahaanModel extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $primaryKey = 'id_perusahaan';
    protected $fillable = ['id_jenis', 'nama', 'telepon', 'deskripsi', 'foto_path', 'provinsi', 'daerah', 'latitude', 'longitude'];
    public function jenis_perusahaan(): BelongsTo
    {
        return $this->belongsTo(JenisPerusahaanModel::class, 'id_jenis', 'id_jenis');
    }

    public function lowongan_magang(): HasMany
    {
        return $this->hasMany(LowonganMagangModel::class, 'id_perusahaan', 'id_perusahaan');
    }
}
