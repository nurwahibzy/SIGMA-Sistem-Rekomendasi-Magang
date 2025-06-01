<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminModel extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['id_akun', 'nip', 'nama', 'alamat', 'telepon', 'tanggal_lahir', 'email', 'gender'];
    public function akun(): BelongsTo
    {
        return $this->belongsTo(AkunModel::class, 'id_akun', 'id_akun');
    }
}
