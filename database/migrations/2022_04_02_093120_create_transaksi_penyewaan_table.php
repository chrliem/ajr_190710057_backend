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
            $table->string('id_customer')->nullable();
            $table->unsignedBigInteger('id_promo')->nullable();
            $table->unsignedBigInteger('id_mobil')->nullable();
            $table->string('id_driver')->nullable();
            $table->string('id_pegawai')->nullable();
            $table->datetime('tgl_transaksi')->nullable();
            $table->datetime('tgl_mulai_sewa')->nullable();
            $table->datetime('tgl_selesai_sewa')->nullable();
            $table->datetime('tgl_pengembalian')->nullable();
            $table->float('total_biaya_ekstensi',24,2)->nullable();
            $table->float('total_biaya_driver',24,2)->nullable();
            $table->float('total_biaya_mobil',24,2)->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->string('status_transaksi')->nullable();
            $table->integer('rating_driver')->nullable();
            $table->integer('rating_ajr')->nullable();
            $table->float('grand_total_pembayaran',24,2)->nullable();
            $table->timestamps();
            $table->foreign('id_customer')->references('id_customer')->on('customers');
            $table->foreign('id_promo')->references('id_promo')->on('promos');
            $table->foreign('id_mobil')->references('id_mobil')->on('mobils');
            $table->foreign('id_driver')->references('id_driver')->on('drivers');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais');
        });
    }
//sudah selesa blm bayar
//belum verifikasi, sedang berjalan, selesai, batal
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
