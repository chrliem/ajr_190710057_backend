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
        Schema::create('detail_jadwals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal')->nullable();
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->timestamps();
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_pegawais');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_jadwal');
    }
};
