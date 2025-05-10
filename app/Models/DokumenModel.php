<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenModel extends Model
{
    use HasFactory;

    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';
    protected $fillable = ['id_mahasiswa', 'nama', 'file_path'];
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
