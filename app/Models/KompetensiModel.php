<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KompetensiModel extends Model
{
    use HasFactory;

    protected $table = 'kompetensi';
    protected $primaryKey = 'id_kompetensi';
    protected $fillable = ['id_mahasiswa', 'id_bidang'];
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(BidangModel::class, 'id_bidang', 'id_bidang');
    }
}
