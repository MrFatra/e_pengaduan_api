<?php

namespace App\Models;

use App\Models\Complaint;
// use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class People extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = ['nik', 'nama', 'username', 'password', 'telp'];

    protected $primaryKey = 'nik';

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
