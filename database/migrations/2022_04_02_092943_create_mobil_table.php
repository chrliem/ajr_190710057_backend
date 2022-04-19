<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobils', function (Blueprint $table) {
            $table->id('id_mobil');
            $table->unsignedBigInteger('id_mitra')->nullable();
            $table->string('no_plat');
            $table->string('nama_mobil');
            $table->string('foto_mobil');
            $table->string('tipe_mobil');
            $table->string('jenis_transmisi');
            $table->string('jenis_bahan_bakar');
            $table->string('volume_bahan_bakar');
            $table->string('warna_mobil');
            $table->string('kapasitas_penumpang');
            $table->string('fasilitas_mobil');
            $table->string('no_stnk');
            $table->date('tgl_servis_terakhir');
            $table->string('kategori_aset');
            $table->boolean('status_ketersediaan_mobil');
            $table->float('tarif_mobil_harian',24,2);
            $table->date('tgl_mulai_kontrak');
            $table->date('tgl_habis_kontrak');
            $table->timestamps();
            $table->foreign('id_mitra')->references('id_mitra')->on('mitras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobil');
    }
};
