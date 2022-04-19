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
        Schema::create('transaksi_penyewaans', function (Blueprint $table) {
            $table->string('no_transaksi')->primary();
            $table->string('id_customer');
            $table->string('kode_promo');
            $table->unsignedBigInteger('id_mobil');
            $table->string('id_driver');
            $table->unsignedBigInteger('id_pegawai');
            $table->datetime('tgl_transaksi');
            $table->datetime('tgl_mulai_sewa');
            $table->datetime('tgl_selesai_sewa');
            $table->float('total_biaya_ekstensi',24,2);
            $table->float('total_biaya_driver',24,2);
            $table->float('total_biaya_mobil',24,2);
            $table->string('metode_pembayaran');
            $table->boolean('status_pembayaran');
            $table->boolean('status_transaksi');
            $table->integer('rating_driver');
            $table->integer('rating_ajr');
            $table->float('grand_total_pembayaran',24,2);
            $table->timestamps();
            $table->foreign('id_customer')->references('id_customer')->on('customers');
            $table->foreign('kode_promo')->references('kode_promo')->on('promos');
            $table->foreign('id_mobil')->references('id_mobil')->on('mobils');
            $table->foreign('id_driver')->references('id_driver')->on('drivers');
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
        Schema::dropIfExists('transaksi_penyewaan');
    }
};
