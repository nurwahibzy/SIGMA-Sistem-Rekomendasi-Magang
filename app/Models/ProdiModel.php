<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdiModel extends Model
{
    use HasFactory;
    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';
    protected $fillable = [ 'nama_prodi', 'nama_jurusan'];

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(MahasiswaModel::class, 'id_prodi', 'id_prodi');
    }
}
