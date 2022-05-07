<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Driver extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $guard = 'driver';
    protected $primaryKey = 'id_driver';
    protected $keyType = 'string';

    protected $fillable = [
        'id_driver',
        'nama_driver',
        'alamat_driver',
        'tgl_lahir_driver',
        'jenis_kelamin_driver',
        'no_telepon_driver',
        'email',
        'password',
        'foto_driver',
        'no_sim_driver',
        'sim_driver',
        'surat_bebas_napza',
        'surat_kesehatan_jiwa',
        'surat_kesehatan_jasmani',
        'skck',
        'tarif_driver_harian',
        'kemampuan_bahasa_asing',
        'status_ketersediaan_driver',
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
