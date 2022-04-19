<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_customer';
    protected $keyType = 'string';

    protected $fillable = [
        'id_customer',
        'nama_customer',
        'alamat_customer',
        'tgl_lahir_customer',
        'jenis_kelamin_customer',
        'no_telepon_customer',
        'no_kartu_identitas_customer',
        'kartu_identitas_customer',
        'no_sim_customer',
        'sim_customer',
        'email_customer',
        'password_customer',
        'tipe_sewa_customer'
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
