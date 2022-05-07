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
        Schema::create('drivers', function (Blueprint $table) {
            $table->string('id_driver')->primary();
            $table->string('nama_driver');
            $table->string('alamat_driver');
            $table->string('tgl_lahir_driver');
            $table->string('jenis_kelamin_driver');
            $table->string('no_telepon_driver');
            $table->string('email');
            $table->string('password');
            $table->string('foto_driver');
            $table->string('no_sim_driver');
            $table->string('sim_driver');
            $table->string('surat_bebas_napza');
            $table->string('surat_kesehatan_jiwa');
            $table->string('surat_kesehatan_jasmani');
            $table->string('skck');
            $table->float('tarif_driver_harian',24,2);
            $table->boolean('kemampuan_bahasa_asing');
            $table->boolean('status_ketersediaan_driver');
            $table->boolean('status_aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver');
    }
};
