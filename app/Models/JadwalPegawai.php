<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPegawai extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_jadwal';

    public function pegawais(){
        return $this->belongToMany(Pegawai::class,'detail_jadwal','id_jadwal','id_jadwal');
    }

    protected $fillable = [
        'id_jadwal',
        'hari',
        'shift'
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
