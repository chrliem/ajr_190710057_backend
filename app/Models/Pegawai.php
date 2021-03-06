<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
// use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;


class Pegawai extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $guard = 'pegawai';
    protected $keyType = 'string';

    protected $primaryKey = 'id_pegawai';
    
    public function jadwalPegawais(){
        return $this->belongToMany(JadwalPegawai::class, 'detail_jadwal','id_pegawai','id_pegawai');
    }

    protected $fillable = [
        'id_pegawai',
        'id_role',
        'nama_pegawai',
        'tgl_lahir_pegawai',
        'jenis_kelamin_pegawai',
        'alamat_pegawai',
        'no_telepon_pegawai',
        'foto_pegawai',
        'email',
        'password',
        'status_aktif'
    ];

    public function getCreatedAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->$attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAttribute(){
        if(!is_null($this->attributes['update_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

}
