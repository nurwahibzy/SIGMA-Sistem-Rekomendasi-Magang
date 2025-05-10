<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeahlianMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'keahlian_mahasiswa';
    protected $primaryKey = 'id_keahlian_mahasiswa';
    protected $fillable = ['id_mahasiswa', 'id_bidang', 'keahlian'];
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(BidangModel::class, 'id_bidang', 'id_bidang');
    }
}
