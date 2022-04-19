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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->unsignedBigInteger('id_role');
            $table->string('nama_pegawai');
            $table->date('tgl_lahir_pegawai');
            $table->string('jenis_kelamin_pegawai');
            $table->string('alamat_pegawai');
            $table->string('no_telepon_pegawai');
            $table->string('foto_pegawai');
            $table->string('email_pegawai');
            $table->string('password_pegawai');
            $table->boolean('status_aktif');
            $table->timestamps();
            $table->foreign('id_role')->references('id_role')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
};
