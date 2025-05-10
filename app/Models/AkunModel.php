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
    protected $fillable = ['id_level', 'username', 'password', 'status', 'foto_path'];
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
}
