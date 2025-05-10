<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferensiLokasiMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'preferensi_lokasi_mahasiswa';
    protected $primaryKey = 'id_preferensi_lokasi';
    protected $fillable = ['id_mahasiswa', 'provinsi', 'daerah', 'latitude', 'longitude'];
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
