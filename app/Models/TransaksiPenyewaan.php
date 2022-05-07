<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenyewaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_transaksi';
    protected $keyType = 'string';
    protected $tempEkstensi;

    protected $fillable = [
        'no_transaksi',
        'id_customer',
        'id_promo',
        'id_mobil',
        'id_driver',
        'id_pegawai',
        'tgl_transaksi',
        'tgl_mulai_sewa',
        'tgl_selesai_sewa',
        'tgl_pengembalian',
        'total_biaya_ekstensi',
        'total_biaya_driver',
        'total_biaya_mobil',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'status_transaksi',
        'rating_driver',
        'rating_ajr',
        'grand_total_pembayaran',
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
