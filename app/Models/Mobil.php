<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_mobil';

    protected $fillable = [
        'id_mobil',
        'id_mitra',
        'no_plat',
        'nama_mobil',
        'foto_mobil',
        'tipe_mobil',
        'jenis_transmisi',
        'jenis_bahan_bakar',
        'volume_bahan_bakar',
        'warna_mobil',
        'kapasitas_penumpang',
        'fasilitas_mobil',
        'no_stnk',
        'tgl_servis_terakhir',
        'kategori_aset',
        'status_ketersediaan_mobil',
        'tarif_mobil_harian',
        'tgl_mulai_kontrak',
        'tgl_habis_kontrak'
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
