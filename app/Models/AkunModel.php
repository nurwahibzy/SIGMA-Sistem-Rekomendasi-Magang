<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AkunModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'akun';
    protected $primaryKey = 'id_akun';
    protected $fillable = ['id_user', 'id_level', 'username', 'password', 'status', 'foto_path'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'id_level', 'id_level');
    }

    public function getRoleName(): string
    {
        return $this->level->role;
    }

    public function getRole()
    {
        return $this->level->kode;
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(AdminModel::class, 'id_akun', 'id_akun');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_akun', 'id_akun');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenModel::class, 'id_akun', 'id_akun');
    }
}
