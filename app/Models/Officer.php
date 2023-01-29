<?php

namespace App\Models;

use App\Models\Response;
// use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Officer extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = ['nama_petugas', 'username', 'password', 'telp', 'level'];

    protected $guarded = ['id_petugas'];
    protected $primaryKey = 'id_petugas';

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
