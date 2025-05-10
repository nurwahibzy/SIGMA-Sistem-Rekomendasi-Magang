<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluasiMagangModel extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_magang';
    protected $primaryKey = 'id_evaluasi';
    protected $fillable = ['id_magang', 'feedback'];
    public function magang(): BelongsTo
    {
        return $this->belongsTo(MagangModel::class, 'id_magang', 'id_magang');
    }
}
