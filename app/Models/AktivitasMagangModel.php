<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasMagangModel extends Model
{
    use HasFactory;
    
    protected $table = 'aktivitas_magang';
    protected $primaryKey = 'id_aktivitas';
    protected $fillable = ['id_magang', 'tanggal', 'keterangan', 'foto_path'];
    public function magang(): BelongsTo
    {
        return $this->belongsTo(MagangModel::class, 'id_magang', 'id_magang');
    }
}
