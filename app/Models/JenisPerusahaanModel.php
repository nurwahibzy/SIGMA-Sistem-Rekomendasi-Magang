<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPerusahaanModel extends Model
{
    use HasFactory;
    protected $table = 'jenis_perusahaan';
    protected $primaryKey = 'id_jenis';
    protected $fillable = ['jenis'];

    public function perusahaan(): HasMany
    {
        return $this->hasMany(PerusahaanModel::class, 'id_jenis', 'id_jenis');
    }

    public function preferensi_perusahaan_mahasiswa(): HasMany
    {
        return $this->hasMany(PreferensiPerusahaanMahasiswaModel::class, 'id_jenis', 'id_jenis');
    }
}
