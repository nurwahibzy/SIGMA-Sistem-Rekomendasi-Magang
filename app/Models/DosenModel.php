<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DosenModel extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    protected $fillable = ['id_akun', 'nip', 'nama', 'alamat', 'telepon', 'tanggal_lahir', 'email', 'gender'];
    public function akun(): BelongsTo
    {
        return $this->belongsTo(AkunModel::class, 'id_akun', 'id_akun');
    }
    public function keahlian_dosen(): HasMany
    {
        return $this->hasMany(KeahlianDosenModel::class, 'id_dosen', 'id_dosen');
    }
    public function magang(): HasMany
    {
        return $this->hasMany(MagangModel::class, 'id_dosen', 'id_dosen');
    }
}
