<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\TransaksiPenyewaan;
use Illuminate\Support\Str;

use App\Models\Driver;
use App\Models\Mobil;
use App\Models\Promo;
use App\Models\Customer;
use Carbon\Carbon;
use PDF;

class TransaksiPenyewaanController extends Controller
{
    public function showDataTransaksiPenyewaan(){
        $transaksis = TransaksiPenyewaan::all();

        if(count($transaksis)>0){
            return response([
                'messsage' => 'Retrieve All Transaksi Success',
                'data' => $transaksis
            ], 200);
        }

        return response([
            'message'=>'Empty',
            'data'=> null
        ], 404);
    }

    public function showDataTransaksiPenyewaanbyId($id){
        $transaksi = TransaksiPenyewaan::selectRaw(
            'transaksi_penyewaans.no_transaksi,
            customers.nama_customer,
            promos.kode_promo,
            promos.potongan_promo,
            mobils.nama_mobil,
            mobils.foto_mobil,
            mobils.no_plat,
            mobils.tipe_mobil,
            mobils.warna_mobil,
            mobils.jenis_transmisi,
            mobils.jenis_bahan_bakar,
            mobils.volume_bahan_bakar,
            mobils.kapasitas_penumpang,
            mobils.fasilitas_mobil,
            mobils.tarif_mobil_harian,
            drivers.foto_driver,
            drivers.nama_driver,
            drivers.jenis_kelamin_driver,
            drivers.tarif_driver_harian,
            drivers.no_telepon_driver,
            pegawais.nama_pegawai,
            transaksi_penyewaans.tgl_transaksi,
            transaksi_penyewaans.tgl_mulai_sewa,
            transaksi_penyewaans.tgl_selesai_sewa,
            DATEDIFF(transaksi_penyewaans.tgl_selesai_sewa, transaksi_penyewaans.tgl_mulai_sewa) as durasi_penyewaan,
            transaksi_penyewaans.tgl_pengembalian,
            transaksi_penyewaans.total_biaya_ekstensi,
            transaksi_penyewaans.total_biaya_driver,
            transaksi_penyewaans.total_biaya_mobil,
            transaksi_penyewaans.metode_pembayaran,
            transaksi_penyewaans.bukti_pembayaran,
            transaksi_penyewaans.status_pembayaran,
            transaksi_penyewaans.status_transaksi,
            transaksi_penyewaans.rating_driver,
            transaksi_penyewaans.rating_ajr,
            transaksi_penyewaans.grand_total_pembayaran'
        )->leftJoin('customers','transaksi_penyewaans.id_customer','=','customers.id_customer')
        ->leftJoin('promos','transaksi_penyewaans.id_promo','=','promos.id_promo')
        ->leftJoin('mobils','transaksi_penyewaans.id_mobil','=','mobils.id_mobil')
        ->leftJoin('drivers','transaksi_penyewaans.id_driver','=','drivers.id_driver')
        ->leftJoin('pegawais','transaksi_penyewaans.id_pegawai','=','pegawais.id_pegawai')
        ->where('transaksi_penyewaans.no_transaksi',$id)
        ->get();

        if(!is_null($transaksi)){
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksi
            ], 200);
        }
    }

    // public function showDataTransaksiPenyewaanbyIdCustomer($id_customer){
    //     $transaksis = TransaksiPenyewaan::selectRaw('*')->whereRaw("id_customer='$id_customer'");

    //     if(count($transaksis)>0){
    //         return response([
    //             'messsage' => 'Retrieve All Transaksi Success',
    //             'data' => $transaksis
    //         ], 200);
    //     }

    //     return response([
    //         'message'=>'Empty',
    //         'data'=> null
    //     ], 404);
    // }

    public function addDataTransaksiPenyewaan(Request $request){
        $addData = $request->all();

       //Cek jika sedang ada transaksi berjalan
       $checkNoData = TransaksiPenyewaan::selectRaw('*')->whereRaw("id_customer='$request->id_customer'")->count();
       if($checkNoData!=null){
            $checkIfFinished = TransaksiPenyewaan::selectRaw('status_transaksi')->whereRaw("id_customer='$request->id_customer' &&status_transaksi='Selesai'")
                        ->count();
            $checkIfCancelled = TransaksiPenyewaan::selectRaw('status_transaksi')->whereRaw("id_customer='$request->id_customer' &&status_transaksi='Batal Transaksi'")
            ->count();
            $checkIfRejected = TransaksiPenyewaan::selectRaw('status_transaksi')->whereRaw("id_customer='$request->id_customer' &&status_transaksi='Verifikasi Ditolak'")
            ->count();
        if($checkIfFinished==null){
            if($checkIfCancelled==null){
                if($checkIfRejected==null){
                    return response([
                        'message' => 'Transaksi penyewaan sebelumnya belum selesai'
                    ]);
                }
                
            }
        }
       }
       

        $validate = Validator::make($addData, [
            'id_mobil'=>'required',
            'tgl_mulai_sewa'=>'required',
            'tgl_selesai_sewa'=>'required',
        ],[],[
            'id_mobil'=>'Pilihan Mobil',
            'tgl_mulai_sewa'=>'Tanggal Mulai Sewa',
            'tgl_selesai_sewa'=>'Tanggal Selesai Sewa'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        //Generate Nomor Transaksi
         //TRNYYMMDD01-XXX (auto incr) Jjika sewa mobil dan driver
        //TRNYYMMDD00-XXX (auto incr) Jjika sewa mobil dan driver
        $date = Carbon::now()->format('ymd');
        $count = count(TransaksiPenyewaan::all())+1;
        
        if($count<9){
            $formattedNum = Str::padLeft($count, 3,'00');
        }else if($count>9 && $count<100){
            $formattedNum = Str::padLeft($count, 3,'0');
        }
        
        if($request->id_driver==null){
            $jenisTransaksi = '00';
        }else{
            $jenisTransaksi = '01';
        }
        $addData['no_transaksi'] = 'TRN-'.$date.$jenisTransaksi.'-'.$formattedNum;

        //Hitung durasi sewa
        $durasiSewa = Carbon::parse($request->tgl_selesai_sewa)->diffInDays(Carbon::parse($request->tgl_mulai_sewa));
        $addData['tgl_transaksi'] = Carbon::now()->format('Y-m-d G:i:s');
        //Hitung total biaya driver
        if(is_null($request->id_driver)){
            $addData['total_biaya_driver'] = 0;
            $addData['id_driver'] = null;
        }else{
            $driver = Driver::find($request->id_driver);
            if(is_null($driver)){
                $addData['total_biaya_driver'] = 0;
                $addData['id_driver'] = null;
            }else{
                $addData['total_biaya_driver'] = $driver->tarif_driver_harian*$durasiSewa;
            }
            
         }

        //Hitung total biaya mobil
        if($request->id_mobil!=null){
            $mobil = Mobil::find($request->id_mobil);
            $addData['total_biaya_mobil'] = $mobil->tarif_mobil_harian*$durasiSewa;
        }
        $addData['grand_total_pembayaran'] = $addData['total_biaya_driver'] + $addData['total_biaya_mobil'];
        $addData['status_transaksi'] = 'Menunggu Verifikasi';
        $addData['status_pembayaran'] = 'Belum Lunas';
        $addData['total_biaya_ekstensi'] = 0;
        $addData['metode_pembayaran'] = null;

        //Cek syarat promo kalau jadi nanti disini

        $transaksi = TransaksiPenyewaan::create($addData);
        return response([
            'message'=> 'Berhasil Tambah Transaksi',
            'data' => $transaksi
        ], 200);
    }

    public function cancelDataTransaksiPenyewaan(Request $request, $id){
        $transaksi = TransaksiPenyewaan::find($id);
        

        if(is_null($transaksi)){
            return response([
                'message'=>'Transaksi Not Found',
                'data' =>null
            ],404);
        }

        if($transaksi->status_transaksi=='Sedang Berjalan'){
            return response([
                'message' => 'Transaksi Tidak Dapat Dibatalkan',
            ]);
        }
        $transaksi->status_transaksi = 'Batal Transaksi';
        if($transaksi->id_driver!=null){
            $mobil = Mobil::find($transaksi->id_mobil);
            $mobil->status_ketersediaan_mobil=1;
            $mobil->save();
            $driver = Driver::find($transaksi->id_driver);
            $driver->status_ketersediaan_driver=1;
            $mobil->save();
        }else{
            $mobil = Mobil::find($transaksi->id_mobil);
            $mobil->status_ketersediaan_mobil=1;
            $mobil->save();
        }
        if($transaksi->save()){
            return response([
                'message' => 'Pembatalan Berhasil',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Batal Transaksi Failed',
            'data'=> null
        ],400);

    }


    //Customer Service
    public function verifikasiDataTransaksiPenyewaan(Request $request, $id){
        $transaksi = TransaksiPenyewaan::find($id);
        if(is_null($transaksi)){
            return response([
                'message'=>'Transaksi Not Found',
                'data' =>null
            ],404);
        }

        $updateData = $request->all();
        if($updateData['status_transaksi']=='Sedang Berjalan' && $transaksi->id_driver==null){
            $mobil = Mobil::find($transaksi->id_mobil);
            $mobil->status_ketersediaan_mobil = 0;
            $mobil->save();
        }else if($updateData['status_transaksi']=='Sedang Berjalan'){
            $mobil = Mobil::find($transaksi->id_mobil);
            $driver = Driver::find($transaksi->id_driver);
            $mobil->status_ketersediaan_mobil = 0;
            $driver->status_ketersediaan_driver = 0;
            $mobil->save();
            $driver->save();
        }
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        $transaksi->status_transaksi = $updateData['status_transaksi'];
        $transaksi->status_pembayaran = $updateData['status_pembayaran'];

        if($transaksi->save()){
            return response([
                'message' => 'Verification Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Verification Failed',
            'data'=> null
        ],400);

    }

    public function updateDataTransaksiPenyewaan(Request $request, $id){
        $transaksi = TransaksiPenyewaan::find($id);
        if(is_null($transaksi)){
            return response([
                'message'=>'Transaksi Not Found',
                'data' =>null
            ],404);
        }
        
        $updateData = $request->all();
        
        if($transaksi->id_driver!=null){
            if($transaksi->id_driver!=$request->id_driver || $transaksi->id_mobil!=$request->id_mobil){
                $driver = Driver::find($request->id_driver);
                $mobil = Mobil::find($request->id_mobil);
                if($driver->status_ketersediaan_driver==0 && $mobil->status_ketersediaan_mobil==0){
                    return response([
                        'message'=>'Driver dan Mobil Tidak Tersedia'
                    ], 400);
                }else if($driver->status_ketersediaan_driver==0){
                    return response([
                        'message'=>'Driver Tidak Tersedia'
                    ], 400);
                }else if($mobil->status_ketersediaan_mobil==0){
                    return response([
                        'message'=>'Mobil Tidak Tersedia'
                    ], 400);
                }else{
                    $driver->status_ketersediaan_driver=1;
                    $driver->save();
                    $transaksi->id_driver = $updateData['id_driver'];
                    $mobil = Mobil::find($transaksi->id_mobil);
                    $mobil->status_ketersediaan_mobil=1;
                    $transaksi->id_mobil = $updateData['id_mobil'];
                    $mobil->save();
                }
        }
        
        }else

        // if($transaksi->id_driver!=null&&$transaksi->id_driver!=$request->id_driver){
        //     $driver = Driver::find($request->id_driver);
        //     if($driver->status_ketersediaan_driver==0){
        //         return response([
        //             'message'=>'Driver Tidak Tersedia'
        //         ], 400);
        //     }
        // }else
        if($transaksi->id_driver==null && $transaksi->id_mobil!= $request->id_mobil){
            $mobil = Mobil::find($request->id_mobil);
            if($mobil->status_ketersediaan_mobil==0){
                return response([
                    'message'=>'Mobil Tidak Tersedia'
                ], 400);
            }
        }else{
            $mobil = Mobil::find($transaksi->id_mobil);
            $mobil->status_ketersediaan_mobil=1;
            $mobil->save();

            // if($transaksi->id_driver!=null){
            //     $driver = Driver::find($transaksi->id_driver);
            //     $driver->status_ketersediaan_driver=1;
            //     $driver->save();
            //     $transaksi->id_driver = $updateData['id_driver'];
            // }
        }
        
        // if($transaksi->id_driver!=null && $transaksi->id_driver!= $request->id_driver){
        //     $driver = Driver::find($transaksi->id_driver);
        //     $driver->status_ketersediaan_driver=1;
        //     $driver->save();
        //     $transaksi->id_driver = $updateData['id_driver'];
        // }
        // if($transaksi->id_mobil!= $request->id_mobil){
        //     $mobil = Mobil::find($transaksi->id_mobil);
        //     $mobil->status_ketersediaan_mobil=1;
        //     $mobil->save();
        // }
        $transaksi->grand_total_pembayaran = 0;
        $transaksi->id_mobil = $updateData['id_mobil'];
        $transaksi->tgl_mulai_sewa = $updateData['tgl_mulai_sewa'];
        $transaksi->tgl_selesai_sewa = $updateData['tgl_selesai_sewa'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        $transaksi->id_promo = $updateData['id_promo'];
        $transaksi->save();
        

        $durasiSewa = Carbon::parse($transaksi->tgl_selesai_sewa)->diffInDays(Carbon::parse($transaksi->tgl_mulai_sewa));
        
        if(is_null($transaksi->id_driver)){
            $transaksi->total_biaya_driver = 0;
            $transaksi->id_driver= null;
        }else{
            $driver = Driver::find($transaksi->id_driver);
            if(is_null($driver)){
                $transaksi->total_biaya_driver = 0;
                $transaksi->id_driver= null;
            }else{
                $transaksi->total_biaya_driver = $driver->tarif_driver_harian*$durasiSewa;
            }
            
         }

        //Hitung total biaya mobil
        if($transaksi->id_mobil!=null){
            $mobil = Mobil::find($transaksi->id_mobil);
            $transaksi->total_biaya_mobil = $mobil->tarif_mobil_harian*$durasiSewa;
        }
        $transaksi->save();
        $transaksi->grand_total_pembayaran  = $transaksi->total_biaya_driver + $transaksi->total_biaya_mobil;
        
        
        if($transaksi->save()){
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi Failed',
            'data'=> null
        ],400);
    }

    //Customer
    public function pembayaranTransaksiPenyewaan(Request $request,$id){
        $transaksi = TransaksiPenyewaan::find($id);
        if(is_null($transaksi)){
            return response([
                'message'=>'Transaksi Not Found',
                'data' =>null
            ],404);
        }

        if($transaksi->tgl_pengembalian==null){
            return response([
                'message' => 'Pengembalian belum dilakukan'
            ]);
        }

        $updateData = $request->all();

        if(isset($request->bukti_pembayaran)){
            $image = $request->file('bukti_pembayaran');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/bukti_pembayaran',$image, $fileName.'.'.$image->getClientOriginalExtension()));
            $transaksi->bukti_pembayaran = $fileName.'.'.$image->getClientOriginalExtension();
        }

        $validate = Validator::make($updateData,[
            'bukti_pembayaran'=>'image',
            'metode_pembayaran'=>'required'
        ],[],[
            'bukti_pembayaran'=>'Bukti Pembayaran',
            'metode_pembayaran'=>'Metode Pembayaran'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        // $transaksi->bukti_pembayaran = $updateData['bukti_pembayaran'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];

        if($transaksi->save()){
            return response([
                'message' => 'Add Pembayaran Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Add Pembayaran Failed',
            'data'=> null
        ],400);

    }

    public function hitungPembayaranAkhir(Request $request, $id){
        $transaksi = TransaksiPenyewaan::find($id);
        if(is_null($transaksi)){
            return response([
                'message'=>'Transaksi Not Found',
                'data' =>null
            ],404);
        }
        $updateData = $request->all();

        $validate = Validator::make($updateData,[
            'tgl_pengembalian'=>'required',
        ],[],[
            'tgl_pengembalian'=>'Tanggal Pengembalian'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $checkTime  = Carbon::parse($updateData['tgl_pengembalian'])->diffInHours(Carbon::parse($transaksi->tgl_selesai_sewa));
        if($checkTime>3 && Carbon::parse($updateData['tgl_pengembalian'])>Carbon::parse($transaksi->tgl_selesai_sewa)){
        
            if($transaksi->id_driver!=null){
                $driver = Driver::find($transaksi->id_driver);
                $mobil = Mobil::find($transaksi->id_mobil);
                $transaksi->total_biaya_ekstensi  = $driver->tarif_driver_harian + $mobil->tarif_mobil_harian;
                if($transaksi->id_promo!=null){
                    $promo = Promo::find($transaksi->id_promo);
                    $diskon = $promo->potongan_promo * $transaksi->grand_total_pembayaran;
                    $transaksi->grand_total_pembayaran  = ($transaksi->grand_total_pembayaran - $diskon) + $transaksi->total_biaya_ekstensi;
                }else{
                    $transaksi->grand_total_pembayaran = $transaksi->grand_total_pembayaran + $transaksi->total_biaya_ekstensi ;
                }
                
                $mobil->status_ketersediaan_mobil = 1;
                $mobil->save();
                $driver->status_ketersediaan_driver = 1;
                $driver->save();

            }else{
                $mobil = Mobil::find($transaksi->id_mobil);
                $transaksi->total_biaya_ekstensi  = $mobil->tarif_mobil_harian;
                if($transaksi->id_promo!=null){
                    $promo = Promo::find($transaksi->id_promo);
                    $diskon = $promo->potongan_promo * $transaksi->grand_total_pembayaran;
                    $transaksi->grand_total_pembayaran  = ($transaksi->grand_total_pembayaran - $diskon) + $transaksi->total_biaya_ekstensi;
                }else{
                    $transaksi->grand_total_pembayaran = $transaksi->grand_total_pembayaran + $transaksi->total_biaya_ekstensi ;
                }
                // $transaksi->grand_total_pembayaran = $transaksi->grand_total_pembayaran + $transaksi->total_biaya_ekstensi ;
                $mobil->status_ketersediaan_mobil = 1;
                $mobil->save();
            }
        }else{
            if($transaksi->id_driver!=null){
                $driver = Driver::find($transaksi->id_driver);
                $mobil = Mobil::find($transaksi->id_mobil);
                $transaksi->total_biaya_ekstensi  = 0;
                $transaksi->grand_total_pembayaran = $transaksi->grand_total_pembayaran + $transaksi->total_biaya_ekstensi ;
                $mobil->status_ketersediaan_mobil = 1;
                $mobil->save();
                $driver->status_ketersediaan_driver = 1;
                $driver->save();

            }else{
                $mobil = Mobil::find($transaksi->id_mobil);
                $transaksi->total_biaya_ekstensi = 0;
                $transaksi->grand_total_pembayaran = $transaksi->grand_total_pembayaran + $transaksi->total_biaya_ekstensi ;
                $mobil->status_ketersediaan_mobil = 1;
                $mobil->save();
            }
        }

        // $transaksi->grand_total_pembayaran = $transaksi->grand_total_pembayaran + $updateData['total_biaya_ekstensi'];

        // if($transaksi->id_promo!=null){
        //     $promo = Promo::find($transaksi->id_promo);
        //     $diskon = $promo->potongan_promo * $transaksi->grand_total_pembayaran;
        //     $transaksi->grand_total_pembayaran  = ($transaksi->grand_total_pembayaran - $diskon) + $transaksi->total_biaya_ekstensi;
        // }
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        $transaksi->tgl_pengembalian = $updateData['tgl_pengembalian'];
        // $transaksi->grand_total_pembayaran = $updateData['grand_total_pembayaran'];

        if($transaksi->save()){
            return response([
                'message' => 'Succesful',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Failed',
            'data'=> null
        ],400);
    }

    public function tambahRating(Request $request, $id){
        $transaksi = TransaksiPenyewaan::find($id);

        if(is_null($transaksi)){
            return response([
                'message'=>'Transaksi Not Found',
                'data' =>null
            ],404);
        }
        

        if($transaksi->id_driver==null){

            $updateData = $request->all();
            $validate = Validator::make($updateData,[
                'rating_ajr'=>'required',
            ],[],[
                'rating_ajr'=>'Rating AJR'
            ]);

            if($validate->fails())
            return response(['message'=> $validate->errors()],400);

            $transaksi->rating_ajr = $updateData['rating_ajr'];

            if($transaksi->save()){
                return response([
                    'message' => 'Succesful',
                    'data' => $transaksi
                ], 200);
            }
    
            return response([
                'message' => 'Failed',
                'data'=> null
            ],400);

        }else if($transaksi->id_driver!=null){

            $updateData = $request->all();
            $validate = Validator::make($updateData,[
                'rating_ajr'=>'required',
                'rating_driver'=>'required'
            ],[],[
                'rating_ajr'=>'Rating AJR',
                'rating_driver'=>'Rating Driver'
            ]);

            if($validate->fails())
            return response(['message'=> $validate->errors()],400);

            $transaksi->rating_ajr = $updateData['rating_ajr'];
            $transaksi->rating_driver = $updateData['rating_driver'];

            if($transaksi->save()){
                return response([
                    'message' => 'Succesful',
                    'data' => $transaksi
                ], 200);
            }
    
            return response([
                'message' => 'Failed',
                'data'=> null
            ],400);
        }
    }

    public function cetakNotaPembayaran($id){
        $transaksi = TransaksiPenyewaan::selectRaw(
            'transaksi_penyewaans.no_transaksi,
            customers.nama_customer,
            promos.kode_promo,
            promos.potongan_promo,
            mobils.nama_mobil,
            mobils.foto_mobil,
            mobils.no_plat,
            mobils.tipe_mobil,
            mobils.warna_mobil,
            mobils.jenis_transmisi,
            mobils.jenis_bahan_bakar,
            mobils.volume_bahan_bakar,
            mobils.kapasitas_penumpang,
            mobils.fasilitas_mobil,
            mobils.tarif_mobil_harian,
            drivers.foto_driver,
            drivers.nama_driver,
            drivers.jenis_kelamin_driver,
            drivers.tarif_driver_harian,
            drivers.no_telepon_driver,
            pegawais.nama_pegawai,
            transaksi_penyewaans.tgl_transaksi,
            transaksi_penyewaans.tgl_mulai_sewa,
            transaksi_penyewaans.tgl_selesai_sewa,
            DATEDIFF(transaksi_penyewaans.tgl_selesai_sewa, transaksi_penyewaans.tgl_mulai_sewa) as durasi_penyewaan,
            transaksi_penyewaans.tgl_pengembalian,
            transaksi_penyewaans.total_biaya_ekstensi,
            transaksi_penyewaans.total_biaya_driver,
            transaksi_penyewaans.total_biaya_mobil,
            transaksi_penyewaans.metode_pembayaran,
            transaksi_penyewaans.bukti_pembayaran,
            transaksi_penyewaans.status_pembayaran,
            transaksi_penyewaans.status_transaksi,
            transaksi_penyewaans.rating_driver,
            transaksi_penyewaans.rating_ajr,
            transaksi_penyewaans.grand_total_pembayaran'
        )->leftJoin('customers','transaksi_penyewaans.id_customer','=','customers.id_customer')
        ->leftJoin('promos','transaksi_penyewaans.id_promo','=','promos.id_promo')
        ->leftJoin('mobils','transaksi_penyewaans.id_mobil','=','mobils.id_mobil')
        ->leftJoin('drivers','transaksi_penyewaans.id_driver','=','drivers.id_driver')
        ->leftJoin('pegawais','transaksi_penyewaans.id_pegawai','=','pegawais.id_pegawai')
        ->where('transaksi_penyewaans.no_transaksi',$id)
        ->get();
        // $transaksi = mb_convert_encoding($transaksi, "UTF-8", "UTF-8");
        $pdf = PDF::loadView('notaPembayaran',compact('transaksi'));
        Storage::put('public/Nota Pembayaran.pdf', $pdf->output());

        // $content = $pdf->download('notaPembayaran.pdf')->getOriginalContent();
        // if(Storage::exists('notaPembayaran.pdf')){
        //     Storage::putFileAs('public', $content);
        // }else{
        //     Storage::putFileAs('public', $content);
        // }
        // $pdf->setOption('encoding','UTF-8');
        
        // return response([
            // 'message' => 'Succesful',
            // 'data' =>  $pdf->download("$transaksi->no_transaksi.'.pdf'")
            // 'data' => 
            //  return $pdf->stream("coba.pdf");
        // ], 200);
        // return $pdf->download("notaPembayaran.pdf");
        $path = storage_path().'\app\public\Nota Pembayaran.pdf';
        return response()->download($path);
        // return response([
        //     'message' => 'Successful',
        //     'data'=>mb_convert_encoding($pdf->output(), 'UTF-8', 'UTF-8')
        //     // 'data'=> $pdf->stream()
        // ], 200);
    }

    public function averageRatingAJR(){
        $avgRatingAJR = TransaksiPenyewaan::avg('rating_ajr');
        $avgRatingAJR = round($avgRatingAJR,2);
        return response([
            'data'=>$avgRatingAJR
        ]);
    }
}
