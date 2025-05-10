<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianModel extends Model
{
    use HasFactory;

    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';
    protected $fillable = ['id_magang', 'fasilitas', 'tugas', 'kedisiplinan'];
    public function magang(): BelongsTo
    {
        return $this->belongsTo(MagangModel::class, 'id_magang', 'id_magang');
    }
}
