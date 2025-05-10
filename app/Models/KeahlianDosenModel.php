<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeahlianDosenModel extends Model
{
    use HasFactory;
    protected $table = 'keahlian_dosen';
    protected $primaryKey = 'id_keahlian_dosen';
    protected $fillable = ['id_dosen', 'id_bidang', 'keahlian'];
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenModel::class, 'id_dosen', 'id_dosen');
    }
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(BidangModel::class, 'id_bidang', 'id_bidang');
    }
}
