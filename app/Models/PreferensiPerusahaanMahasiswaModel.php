<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferensiPerusahaanMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'preferensi_perusahaan_mahasiswa';
    protected $primaryKey = 'id_preferensi_perusahaan';
    protected $fillable = ['id_mahasiswa', 'id_jenis'];
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function jenis_perusahaan(): BelongsTo
    {
        return $this->belongsTo(JenisPerusahaanModel::class, 'id_jenis', 'id_jenis');
    }
}
