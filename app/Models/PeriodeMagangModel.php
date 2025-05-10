<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeMagangModel extends Model
{
    use HasFactory;

    protected $table = 'periode_magang';
    protected $primaryKey = 'id_periode';
    protected $fillable = ['id_lowongan', 'nama', 'tanggal_mulai', 'tanggal_selesai'];
    public function lowongan_magang(): BelongsTo
    {
        return $this->belongsTo(LowonganMagangModel::class, 'id_lowongan', 'id_lowongan');
    }
    public function magang(): HasMany
    {
        return $this->hasMany(MagangModel::class, 'id_periode', 'id_periode');
    }
}
