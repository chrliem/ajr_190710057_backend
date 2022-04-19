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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id_customer')->primary();
            $table->string('nama_customer');
            $table->string('alamat_customer');
            $table->date('tgl_lahir_customer');
            $table->string('jenis_kelamin_customer');
            $table->string('no_telepon_customer');
            $table->string('no_kartu_identitas_customer');
            $table->string('kartu_identitas_customer');
            $table->string('no_sim_customer')->nullable();
            $table->string('sim_customer')->nullable();
            $table->string('email_customer');
            $table->string('password_customer');
            $table->boolean('tipe_sewa_customer')->nullable(); //1 = sewa driver dan mobil, 0 = sewa mobil
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
        Schema::dropIfExists('customer');
    }
};
