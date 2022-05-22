<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Validator;
use App\Models\Customer;
use App\Models\TransaksiPenyewaan;
use Carbon\Carbon;

class CustomerController extends Controller
{

    public function showDataCustomer(){
        $customers = Customer::all();

        if(count($customers)>0){
            return response([
                'message' => 'Retrieve All Customer Success',
                'data' => $customers
            ],200);
        }

        return response([
            'message'=>'Customer Not Found',
            'data'=>null
        ], 404);
    }

    public function showDataCustomerbyId($id){
        $customer = Customer::find($id);

        if(!is_null($customer)){
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customer
            ], 200);
        }
    }

    public function showDataTransaksiPenyewaanbyIdCustomer($id){
        $transaksis = TransaksiPenyewaan::selectRaw('*')->whereRaw("id_customer='$id'")->get();

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

    public function showDataTransaksiPenyewaanbyIdCustomerMobile($id){
        $transaksis = TransaksiPenyewaan::selectRaw( 'transaksi_penyewaans.no_transaksi,
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
        drivers.id_driver,
        drivers.foto_driver,
        drivers.nama_driver,
        drivers.jenis_kelamin_driver,
        drivers.tarif_driver_harian,
        drivers.no_telepon_driver,
        pegawais.nama_pegawai,
        transaksi_penyewaans.no_transaksi,
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
    ->leftJoin('pegawais','transaksi_penyewaans.id_pegawai','=','pegawais.id_pegawai')->whereRaw("transaksi_penyewaans.id_customer='$id'")->get();

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

    public function addDataCustomer(Request $request){
        
        $addData = $request->all();
        
        //CUSyymmdd-XXX (XXX = AUTO INCREMENT)
        $date = Carbon::now()->format('ymd');
        $count = count(Customer::all())+1;
        
        if($count<9){
            $formattedNum = Str::padLeft($count, 3,'00');
        }else if($count>9 && $count<100){
            $formattedNum = Str::padLeft($count, 3,'0');
        }

        $addData['id_customer'] = 'CUS'.$date.'-'.$formattedNum;

        $validate = Validator::make($addData, [
        'id_customer',
        'nama_customer'=>'required',
        'alamat_customer'=> 'required',
        'tgl_lahir_customer' =>'required|date_format:Y-m-d',
        'jenis_kelamin_customer' => 'required',
        'no_telepon_customer' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
        'no_kartu_identitas_customer' => 'required' ,
        'kartu_identitas_customer' => 'required|image',
        'no_sim_customer',
        'sim_customer' => 'image',
        'email' => 'required|email:rfc,dns|unique:customers|unique:drivers,email|unique:pegawais,email',
        'password',
        'tipe_sewa_customer'
        ],[],[
            'nama_customer'=>'Nama Customer',
            'alamat_customer'=> 'Alamat Customer',
            'tgl_lahir_customer' =>'Tanggal Lahir Customer',
            'jenis_kelamin_customer' => 'Jenis Kelamin Customer',
            'no_telepon_customer' => 'Nomor Telepon Customer',
            'no_kartu_identitas_customer' => 'Nomor Kartu Identitas' ,
            'kartu_identitas_customer' => 'Foto Kartu Identitas',
            'no_sim_customer',
            'sim_customer',
            'email' => 'Email Customer',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $addData['password'] = Carbon::parse($request->tgl_lahir_customer)->format('d/m/Y');
        $addData['password'] = bcrypt($addData['password']);

        $image1 = $request->file('kartu_identitas_customer');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/kartu_identitas_customer',$image1, $fileName.'.'.$image1->getClientOriginalExtension()));
        $addData['kartu_identitas_customer'] = $fileName.'.'.$image1->getClientOriginalExtension();

        $image2 = $request->file('sim_customer');
        if(is_null($image2)){
            $addData['sim_customer'] = null;
        }else{
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/sim_customer',$image2, $fileName.'.'.$image2->getClientOriginalExtension()));
            $addData['sim_customer'] = $fileName.'.'.$image2->getClientOriginalExtension();
        }
        

        $customer = Customer::create($addData);
        
        return response([
            'message' => 'Registrasi Customer Success',
            'data' => $customer
        ], 200); 
        
    }

    public function verifikasiDataCustomer($id){
        $customer = Customer::find($id);

        if(is_null($customer)){
            return response([
                'message'=>'Customer Not Found',
                'data' => null
            ],404);
        }

        if(is_null($customer->sim_customer)){
            //Sewa mobil saja
            $customer->tipe_sewa_customer = 0;
        }else{
            //Sewa mobil dan driver
            $customer->tipe_sewa_customer = 1;
        }

        if($customer->save()){
            return response([
                'message'=>'Verification Success',
                'data'=>$customer
            ],200);
        }

        return response([
            'message'=>'Verification Failed',
            'data'=>null
        ],400);
    }

    public function updateDataCustomer(Request $request, $id){
        $customer = Customer::find($id);
        if(is_null($customer)){
            return response([
                'message'=>'Customer Not Found',
                'data' =>null
            ],404);
        }

        $updateData = $request->all();

        if(isset($request->kartu_identitas_customer)){
            $image1 = $request->file('kartu_identitas_customer');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/kartu_identitas_customer',$image1, $fileName.'.'.$image1->getClientOriginalExtension()));
            $customer->kartu_identitas_customer = $fileName.'.'.$image1->getClientOriginalExtension();
        }

        if(isset($request->sim_customer)){
            $image2 = $request->file('sim_customer');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/sim_customer',$image2, $fileName.'.'.$image2->getClientOriginalExtension()));
            $customer->sim_customer = $fileName.'.'.$image2->getClientOriginalExtension();
        }

        $validate = Validator::make($updateData, [
        'id_customer',
        'nama_customer'=>'required',
        'alamat_customer'=> 'required',
        'tgl_lahir_customer' =>'required|date_format:Y-m-d',
        'jenis_kelamin_customer' => 'required',
        'no_telepon_customer' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
        'no_kartu_identitas_customer' => 'required' ,
        'kartu_identitas_customer' => 'image',
        'no_sim_customer',
        'sim_customer' => 'image',
        'email' => 'required|unique:drivers,email|unique:pegawais,email',
        'password',
        'tipe_sewa_customer'
        ],[],[
            'nama_customer'=>'Nama Customer',
            'alamat_customer'=> 'Alamat Customer',
            'tgl_lahir_customer' =>'Tanggal Lahir Customer',
            'jenis_kelamin_customer' => 'Jenis Kelamin Customer',
            'no_telepon_customer' => 'Nomor Telepon Customer',
            'no_kartu_identitas_customer' => 'Nomor Kartu Identitas' ,
            'kartu_identitas_customer' => 'Foto Kartu Identitas',
            'no_sim_customer',
            'sim_customer',
            'email' => 'Email Customer',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        if($request->tgl_lahir_customer!=$customer->tgl_lahir_customer){
            $updateData['password'] = Carbon::parse($request->tgl_lahir_customer)->format('d/m/Y');
            $updateData['password'] = bcrypt($updateData['password']);
            $customer->password = $updateData['password'];
        }
        // $updateData['password'] = bcrypt($updateData['password']);

        $customer->nama_customer = $updateData['nama_customer'];
        $customer->alamat_customer = $updateData['alamat_customer'];
        $customer->tgl_lahir_customer = $updateData['tgl_lahir_customer'];
        $customer->jenis_kelamin_customer = $updateData['jenis_kelamin_customer'];
        $customer->no_telepon_customer = $updateData['no_telepon_customer'];
        $customer->no_kartu_identitas_customer = $updateData['no_kartu_identitas_customer'];
        $customer->no_sim_customer = $updateData['no_sim_customer'];
        $customer->email = $updateData['email'];
        // $customer->password = $updateData['password'];

        if($customer->save()){
            return response([
                'message' => 'Update Data Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data'=> null
        ],400);
    }

    public function updateDataCustomerMobile(Request $request, $id){
        $customer = Customer::find($id);
        if(is_null($customer)){
            return response([
                'message'=>'Customer Not Found',
                'data' =>null
            ],404);
        }

        $updateData = $request->all();


        $validate = Validator::make($updateData, [
        'id_customer',
        'nama_customer'=>'required',
        'alamat_customer'=> 'required',
        'tgl_lahir_customer' =>'required|date_format:Y-m-d',
        'jenis_kelamin_customer' => 'required',
        'no_telepon_customer' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
        'no_kartu_identitas_customer' => 'required' ,
        'kartu_identitas_customer',
        'no_sim_customer',
        'sim_customer',
        'email' => 'required|unique:drivers,email|unique:pegawais,email',
        'password',
        'tipe_sewa_customer'
        ],[],[
            'nama_customer'=>'Nama Customer',
            'alamat_customer'=> 'Alamat Customer',
            'tgl_lahir_customer' =>'Tanggal Lahir Customer',
            'jenis_kelamin_customer' => 'Jenis Kelamin Customer',
            'no_telepon_customer' => 'Nomor Telepon Customer',
            'no_kartu_identitas_customer' => 'Nomor Kartu Identitas' ,
            'kartu_identitas_customer' => 'Foto Kartu Identitas',
            'no_sim_customer',
            'sim_customer',
            'email' => 'Email Customer',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        if($request->tgl_lahir_customer!=$customer->tgl_lahir_customer){
            $updateData['password'] = Carbon::parse($request->tgl_lahir_customer)->format('d/m/Y');
            $updateData['password'] = bcrypt($updateData['password']);
            $customer->password = $updateData['password'];
        }
        // $updateData['password'] = bcrypt($updateData['password']);

        $customer->nama_customer = $updateData['nama_customer'];
        $customer->alamat_customer = $updateData['alamat_customer'];
        $customer->tgl_lahir_customer = $updateData['tgl_lahir_customer'];
        $customer->jenis_kelamin_customer = $updateData['jenis_kelamin_customer'];
        $customer->no_telepon_customer = $updateData['no_telepon_customer'];
        // $customer->password = $updateData['password'];

        if($customer->save()){
            return response([
                'message' => 'Update Data Customer Success',
                'user' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data'=> null
        ],400);
    }
    
}
