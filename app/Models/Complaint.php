<?php

namespace App\Models;

use App\Models\People;
use App\Models\Response;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Complaint extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = ['tgl_pengaduan', 'nik', 'isi_laporan', 'foto'];

    protected $guarded = ['id_pengaduan'];

    public function people()
    {
        return $this->belongsTo(People::class);
    }

    public function response()
    {
        return $this->hasOne(Response::class);
    }
}
