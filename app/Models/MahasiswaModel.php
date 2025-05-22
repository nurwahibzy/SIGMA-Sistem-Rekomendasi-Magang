<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MahasiswaModel extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = ['id_akun', 'id_prodi', 'nama', 'alamat', 'telepon', 'tanggal_lahir', 'email'];
    public function akun(): BelongsTo
    {
        return $this->belongsTo(AkunModel::class, 'id_akun', 'id_akun');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProdiModel::class, 'id_prodi', 'id_prodi');
    }
    public function preferensi_lokasi_mahasiswa(): BelongsTo
    {
        return $this->belongsTo(PreferensiLokasiMahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    public function preferensi_perusahaan_mahasiswa(): HasMany
    {
        return $this->hasMany(PreferensiPerusahaanMahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function pengalaman(): HasMany
    {
        return $this->hasMany(PengalamanModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function keahlian_mahasiswa(): HasMany
    {
        return $this->hasMany(KeahlianMahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    public function magang(): HasMany
    {
        return $this->hasMany(MagangModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
