<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\TransaksiPenyewaan;
use App\Models\Driver;
use DB;

class LaporanController extends Controller
{
    public function getLaporanPenyewaanMobil($month, $year){
        DB::enableQueryLog();
        $report = TransaksiPenyewaan::selectRaw(
            'mobils.tipe_mobil,
            mobils.nama_mobil,
            COUNT(transaksi_penyewaans.id_mobil) as jumlah_peminjaman,
            SUM(transaksi_penyewaans.total_biaya_mobil) as total_pendapatan'
        )->join('mobils','transaksi_penyewaans.id_mobil','=','mobils.id_mobil')
        ->whereRaw("MONTH(transaksi_penyewaans.tgl_transaksi)=$month AND YEAR(transaksi_penyewaans.tgl_transaksi)=$year")
        ->groupBy('mobils.tipe_mobil')
        ->groupBy('mobils.nama_mobil')
        ->orderBy('total_pendapatan')
        ->get();
        $queries = DB::getQueryLog(); //get query
        if(count($report)>0){
            return response([
                'messsage' => 'Retrieve Report Success',
                'data' => $report
            ], 200);
        }

        return response([
            'message'=>'Empty',
            'data'=> $queries
        ], 404);
    }

    public function getLaporanDetailPendapatanTransaksi($month, $year){
        DB::enableQueryLog();
        $report = TransaksiPenyewaan::selectRaw(
            "customers.nama_customer,
            mobils.nama_mobil,
            IF(transaksi_penyewaans.total_biaya_driver > 0,'Peminjaman Mobil dan Driver','Peminjaman Mobil') as jenis_transaksi,
            COUNT(transaksi_penyewaans.id_customer) as jumlah_transaksi,
            SUM(transaksi_penyewaans.grand_total_pembayaran) as pendapatan"
        )->join('customers','transaksi_penyewaans.id_customer','=','customers.id_customer')
        ->join('mobils','transaksi_penyewaans.id_mobil','=','mobils.id_mobil')
        ->whereRaw("MONTH(transaksi_penyewaans.tgl_transaksi)=$month AND YEAR(transaksi_penyewaans.tgl_transaksi)=$year")
        ->groupBy('customers.nama_customer')
        ->groupBy('mobils.nama_mobil')
        ->groupBy('jenis_transaksi')
        ->get();

        $queries = DB::getQueryLog(); //get query
        if(count($report)>0){
            return response([
                'messsage' => 'Retrieve Report Success',
                'data' => $report
            ], 200);
        }

        return response([
            'message'=>'Empty',
            'data'=> $queries
        ], 404);
    }

    public function getLaporanDriverTransaksiTerbanyak($month, $year){
        DB::enableQueryLog();
        $report = TransaksiPenyewaan::selectRaw(
            "drivers.id_driver,
            drivers.nama_driver,
            count(transaksi_penyewaans.id_driver) as jumlah_transaksi"
        )->join('drivers','transaksi_penyewaans.id_driver','=','drivers.id_driver')
        ->whereRaw("MONTH(transaksi_penyewaans.tgl_transaksi)=$month AND YEAR(transaksi_penyewaans.tgl_transaksi)=$year")
        ->groupBy('drivers.id_driver')
        ->groupBy('drivers.nama_driver')
        ->orderBy('jumlah_transaksi','DESC')
        ->limit(5)
        ->get();

        $queries = DB::getQueryLog(); //get query
        if(count($report)>0){
            return response([
                'messsage' => 'Retrieve Report Success',
                'data' => $report
            ], 200);
        }

        return response([
            'message'=>'Empty',
            'data'=> $queries
        ], 404);
    }

    public function getLaporanPerformaDriver($month, $year){
        DB::enableQueryLog();
        $report = TransaksiPenyewaan::selectRaw(
            "drivers.id_driver,
            drivers.nama_driver,
            count(transaksi_penyewaans.id_driver) as jumlah_transaksi,
            avg(transaksi_penyewaans.rating_driver) as rerata_rating"
        )->join('drivers','transaksi_penyewaans.id_driver','=','drivers.id_driver')
        ->whereRaw("MONTH(transaksi_penyewaans.tgl_transaksi)=$month AND YEAR(transaksi_penyewaans.tgl_transaksi)=$year")
        ->groupBy('drivers.id_driver')
        ->groupBy('drivers.nama_driver')
        ->orderBy('jumlah_transaksi','DESC')
        ->limit(5)
        ->get();

        $queries = DB::getQueryLog(); //get query
        if(count($report)>0){
            return response([
                'messsage' => 'Retrieve Report Success',
                'data' => $report
            ], 200);
        }

        return response([
            'message'=>'Empty',
            'data'=> $queries
        ], 404);
    }

    public function getLaporanCustomerTransaksiTerbanyak($month, $year){
        DB::enableQueryLog();
        $report = TransaksiPenyewaan::selectRaw(
            "customers.nama_customer,
            count(transaksi_penyewaans.id_customer) as jumlah_transaksi"
        )->join('customers','transaksi_penyewaans.id_customer','=','customers.id_customer')
        ->whereRaw("MONTH(transaksi_penyewaans.tgl_transaksi)=$month AND YEAR(transaksi_penyewaans.tgl_transaksi)=$year")
        ->groupBy('customers.nama_customer')
        ->orderBy('jumlah_transaksi','DESC')
        ->limit(5)
        ->get();

        $queries = DB::getQueryLog(); //get query
        if(count($report)>0){
            return response([
                'messsage' => 'Retrieve Report Success',
                'data' => $report
            ], 200);
        }

        return response([
            'message'=>'Empty',
            'data'=> $queries
        ], 404);
    }
}
