<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mitra extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mitra';

    public function mobils()
    {
        return $this->hasMany(Mobil::class, 'id_mitra', 'id_mitra');
    }

    protected $fillable = [
        'id_mitra',
        'nama_mitra',
        'no_ktp_mitra',
        'alamat_mitra',
        'no_telepon_mitra',
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
