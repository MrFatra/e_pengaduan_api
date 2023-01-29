<?php

namespace App\Models;

use App\Models\Officer;
use App\Models\Complaint;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Response extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['id_pengaduan', 'tgl_tanggapan', 'tanggapan', 'id_petugas'];

    protected $guarded = ['id_tanggapan'];

    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
