<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MagangModel extends Model
{
    use HasFactory;

    protected $table = 'magang';
    protected $primaryKey = 'id_magang';
    protected $fillable = ['id_mahasiswa', 'id_dosen', 'id_periode', 'status', 'status_penilaian', 'tanggal_pengajuan'];
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenModel::class, 'id_dosen', 'id_dosen');
    }
    public function periode_magang(): BelongsTo
    {
        return $this->belongsTo(PeriodeMagangModel::class, 'id_periode', 'id_periode');
    }
    public function aktivitas_magang(): HasMany
    {
        return $this->hasMany(AktivitasMagangModel::class, 'id_magang', 'id_magang');
    }
    public function evaluasi_magang(): HasMany
    {
        return $this->hasMany(EvaluasiMagangModel::class, 'id_magang', 'id_magang');
    }
    public function penilaian(): HasMany
    {
        return $this->hasMany(PenilaianModel::class, 'id_magang', 'id_magang');
    }
}
