<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengalamanModel extends Model
{
    use HasFactory;
    protected $table = 'pengalaman';
    protected $primaryKey = 'id_pengalaman';
    protected $fillable = ['id_mahasiswa', 'deskripsi'];
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
