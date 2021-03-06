<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login','Api\AuthController@login');
Route::group(['middleware' => 'auth:pegawai-api'], function(){
    Route::get('pegawai','Api\PegawaiController@showDataPegawai');
    Route::post('pegawai','Api\PegawaiController@addDataPegawai');
    Route::get('pegawai/{id}','Api\PegawaiController@showDataPegawaibyId');
    Route::post('pegawai/{id}','Api\PegawaiController@updateDataPegawai');
    Route::get('pegawai/{id}/delete','Api\PegawaiController@deleteDataPegawai');
    
    Route::get('customer','Api\CustomerController@showDataCustomer');
    Route::get('customer/{id}','Api\CustomerController@showDataCustomerbyId');
    Route::get('customer/{id}/verifikasi','Api\CustomerController@verifikasiDataCustomer');

    Route::get('driver','Api\DriverController@showDataDriver');
    Route::get('average-ratingdriver','Api\DriverController@getRerataDriver');
    Route::get('driver/{id}','Api\DriverController@showDataDriverbyId');
    Route::post('driver','Api\DriverController@addDataDriver');
    Route::post('driver/{id}','Api\DriverController@updateDataDriver');
    Route::get('driver/{id}/delete','Api\DriverController@deleteDataDriver');

    Route::get('mitra','Api\MitraController@showDataMitra');
    Route::get('mitra/{id}','Api\MitraController@showDataMitrabyId');
    Route::post('mitra','Api\MitraController@addDataMitra');
    Route::post('mitra/{id}','Api\MitraController@updateDataMitra');
    Route::get('mitra/{id}/delete','Api\MitraController@deleteDataMitra');
    
    Route::get('mobil','Api\MobilController@showDataMobil');
    Route::get('mobil/{id}/id','Api\MobilController@showDataMobilbyId');
    Route::get('mobil/habiskontrak','Api\MobilController@showDataMobilHabisKontrak');
    Route::post('mobil','Api\MobilController@addDataMobil');
    Route::post('mobil/{id}','Api\MobilController@updateDataMobil');
    Route::get('mobil/{id}/delete','Api\MobilController@deleteDataMobil');
    
    Route::get('promo','Api\PromoController@showDataPromo');
    Route::get('promo/{id}','Api\PromoController@showDataPromobyId');
    Route::post('promo','Api\PromoController@addDataPromo');
    Route::post('promo/{id}','Api\PromoController@updateDataPromo');
    Route::get('promo/{id}/delete','Api\PromoController@deleteDataPromo');

    Route::get('jadwalpegawai','Api\JadwalPegawaiController@showJadwalPegawai');
    Route::get('jadwalpegawai/{id}','Api\JadwalPegawaiController@showJadwalPegawaibyId');
    Route::post('jadwalpegawai','Api\JadwalPegawaiController@addJadwalPegawai');
    Route::post('jadwalpegawai/{id}','Api\JadwalPegawaiController@updateJadwalPegawai');
    Route::delete('jadwalpegawai/{id}','Api\JadwalPegawaiController@deleteJadwalPegawai');

    Route::get('detailjadwal','Api\DetailJadwalController@showJadwalPegawai');
    Route::get('detailjadwal/{id}','Api\DetailJadwalController@showJadwalPegawaibyId');
    Route::post('detailjadwal','Api\DetailJadwalController@addJadwalPegawai');
    Route::post('detailjadwal/{id}','Api\DetailJadwalController@updateJadwalPegawai');
    Route::delete('detailjadwal/{id}','Api\DetailJadwalController@deleteJadwalPegawai');

    Route::get('transaksipenyewaan','Api\TransaksiPenyewaanController@showDataTransaksiPenyewaan');
    Route::get('average-ratingajr','Api\TransaksiPenyewaanController@averageRatingAJR');
    Route::get('transaksipenyewaan/{id}/transaksi','Api\TransaksiPenyewaanController@showDataTransaksiPenyewaanbyId');
    Route::get('transaksipenyewaan/{id}/customer','Api\TransaksiPenyewaanController@showDataTransaksiPenyewaanbyIdCustomer');
    Route::post('transaksipenyewaan/{id}/verifikasi','Api\TransaksiPenyewaanController@verifikasiDataTransaksiPenyewaan');
    Route::post('transaksipenyewaan/{id}/update','Api\TransaksiPenyewaanController@updateDataTransaksiPenyewaan');
    Route::post('transaksipenyewaan/{id}/pengembalian','Api\TransaksiPenyewaanController@hitungPembayaranAkhir');
    Route::get('transaksipenyewaan/{id}/pembatalan','Api\TransaksiPenyewaanController@cancelDataTransaksiPenyewaan');

});
Route::get('laporan/{month}/{year}/penyewaan-mobil', 'Api\LaporanController@getLaporanPenyewaanMobil');
Route::get('laporan/{month}/{year}/detail-transaksi', 'Api\LaporanController@getLaporanDetailPendapatanTransaksi');
Route::get('laporan/{month}/{year}/top-driver', 'Api\LaporanController@getLaporanDriverTransaksiTerbanyak');
Route::get('laporan/{month}/{year}/top-average-rating', 'Api\LaporanController@getLaporanPerformaDriver');
Route::get('laporan/{month}/{year}/top-customer', 'Api\LaporanController@getLaporanCustomerTransaksiTerbanyak');


Route::post('customer','Api\CustomerController@addDataCustomer');

Route::group(['middleware' => 'auth:customer-api'], function(){
    Route::get('customer-profile/{id}','Api\CustomerController@showDataCustomerbyId');
    Route::post('customer/{id}','Api\CustomerController@updateDataCustomer');
    Route::post('customer-mobile/{id}','Api\CustomerController@updateDataCustomerMobile');
    Route::get('mobil-list','Api\MobilController@showDataMobil');
    Route::get('mobil/available','Api\MobilController@showDataMobilAvailable');
    Route::get('driver-list','Api\DriverController@showDataDriver');
    Route::get('promo-list','Api\PromoController@showDataPromo');
    Route::get('promo-available','Api\PromoController@showDataPromoAvailable');
    Route::get('transaksipenyewaan/{id}','Api\TransaksiPenyewaanController@showDataTransaksiPenyewaanbyId');
    Route::get('transaksipenyewaan-customer/{id}','Api\CustomerController@showDataTransaksiPenyewaanbyIdCustomer');
    Route::get('transaksipenyewaan-customer-mobile/{id}','Api\CustomerController@showDataTransaksiPenyewaanbyIdCustomerMobile');
    Route::post('transaksipenyewaan','Api\TransaksiPenyewaanController@addDataTransaksiPenyewaan');
    // Route::get('transaksipenyewaan/{id}/pembatalan','Api\TransaksiPenyewaanController@cancelDataTransaksiPenyewaan');
    Route::post('transaksipenyewaan/{id}/pembayaran','Api\TransaksiPenyewaanController@pembayaranTransaksiPenyewaan');
    Route::post('transaksipenyewaan/{id}/total','Api\TransaksiPenyewaanController@hitungPembayaranAkhir');
    Route::post('transaksipenyewaan/{id}/rating','Api\TransaksiPenyewaanController@tambahRating');
    // Route::get('transaksipenyewaan/{id}/cetak-nota','Api\TransaksiPenyewaanController@cetakNotaPembayaran');
});
Route::get('transaksipenyewaan/{id}/cetak-nota','Api\TransaksiPenyewaanController@cetakNotaPembayaran');

Route::group(['middleware' => 'auth:driver-api'], function(){
    Route::get('driver/{id}/history','Api\DriverController@showDataTransaksiPenyewaanbyIdDriverMobile');
    Route::get('driver/{id}/average','Api\DriverController@getRerataDriverbyId');
    Route::post('driver-profile/{id}/update','Api\DriverController@updateDataDriverMobile');
    Route::post('driver-profile/{id}/update-status','Api\DriverController@updateStatusKetersediaanDriver');
});

    Route::get('role','Api\RoleController@showDataRole');
    Route::get('role/{id}','Api\RoleController@showDataRolebyId');


