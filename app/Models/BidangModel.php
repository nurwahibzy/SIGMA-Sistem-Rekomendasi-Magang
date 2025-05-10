<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BidangModel extends Model
{
    use HasFactory;
    protected $table = 'bidang';
    protected $primaryKey = 'id_bidang';
    protected $fillable = [ 'nama'];

    public function lowongan_magang(): HasMany
    {
        return $this->hasMany(LowonganMagangModel::class, 'id_bidang', 'id_bidang');
    }
    public function kompetensi(): HasMany
    {
        return $this->hasMany(KompetensiModel::class, 'id_bidang', 'id_bidang');
    }
    public function keahlian_mahasiswa(): HasMany
    {
        return $this->hasMany(KeahlianMahasiswaModel::class, 'id_bidang', 'id_bidang');
    }
    public function keahlian_dosen(): HasMany
    {
        return $this->hasMany(KeahlianDosenModel::class, 'id_bidang', 'id_bidang');
    }
}
