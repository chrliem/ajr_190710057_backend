<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Driver;
use App\Models\TransaksiPenyewaan;
use Carbon\Carbon;

class DriverController extends Controller
{
    public function showDataDriver(){
        $drivers = Driver::all();

        if(count($drivers)>0){
            return response([
                'message'=>'Retrieve All Driver Success',
                'data'=>$drivers
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 400);
    }

    public function showDataDriverbyId($id){
        $driver = Driver::find($id);

        if(!is_null($driver)){
            return response([
                'message'=>'Retrieve Driver Success',
                'data'=>$driver
            ], 200);
        }

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404);
    }

    public function addDataDriver(Request $request){
        $addData = $request->all();

        //DRV-DDMMYYXXX (XXX = AUTO INCREMENT)
        $date = Carbon::now()->format('dmy');
        $count = count(Driver::all())+1;
        
        if($count<9){
            $formattedNum = Str::padLeft($count, 3,'00');
        }else if($count>9 && $count<100){
            $formattedNum = Str::padLeft($count, 3,'0');
        }
        
        $addData['id_driver'] = 'DRV-'.$date.$formattedNum;
        $addData['status_ketersediaan_driver'] = 1;
        $addData['status_aktif'] = 1;

        $validate = Validator::make($addData, [
            'id_driver',
            'nama_driver'=>'required',
            'alamat_driver'=>'required',
            'tgl_lahir_driver'=>'required',
            'jenis_kelamin_driver'=>'required',
            'no_telepon_driver' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
            'email'=>'required|email:rfc,dns|unique:drivers|unique:customers,email|unique:pegawais,email',
            'password',
            'foto_driver'=> 'required|image|mimes:jpeg,jpg,png',
            'no_sim_driver'=> 'required',
            'sim_driver'=> 'required|image|mimes:jpeg,jpg,png',
            'surat_bebas_napza'=> 'required|image|mimes:jpeg,jpg,png',
            'surat_kesehatan_jiwa'=> 'required|image|mimes:jpeg,jpg,png',
            'surat_kesehatan_jasmani'=> 'required|image|mimes:jpeg,jpg,png',
            'skck'=> 'required|image|mimes:jpeg,jpg,png',
            'tarif_driver_harian'=> 'required',
            'kemampuan_bahasa_asing'=> 'required',
            'status_ketersediaan_driver',
            'status_aktif'
        ], [], [
            'nama_driver'=>'Nama Driver',
            'alamat_driver'=>'Alamat Driver',
            'tgl_lahir_driver'=>'Tanggal Lahir Driver',
            'jenis_kelamin_driver'=>'Jenis Kelamin Driver',
            'no_telepon_driver' => 'Nomor Telepon Driver',
            'email'=>'Email Driver',
            'foto_driver'=> 'Foto Driver',
            'no_sim_driver'=> 'Nomor SIM Driver',
            'sim_driver'=> 'Foto SIM Driver',
            'surat_bebas_napza'=> 'Foto Surat Bebas Napza',
            'surat_kesehatan_jiwa'=> 'Foto Surat Kesehatan Jiwa',
            'surat_kesehatan_jasmani'=> 'Foto Surat Kesehatan Jasmani',
            'skck'=> 'Foto SKCK',
            'tarif_driver_harian'=> 'Tarif Harian Driver',
            'kemampuan_bahasa_asing'=> 'Kemampuan Bahasa Asing',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $addData['password'] = Carbon::parse($request->tgl_lahir_pegawai)->format('d/m/Y');
        $addData['password'] = bcrypt($addData['password']);

        $image1 = $request->file('foto_driver');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/foto_driver',$image1, $fileName.'.'.$image1->getClientOriginalExtension()));
        $addData['foto_driver'] = $fileName.'.'.$image1->getClientOriginalExtension();
        
        $image2 = $request->file('sim_driver');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/sim_driver',$image2, $fileName.'.'.$image2->getClientOriginalExtension()));
        $addData['sim_driver'] = $fileName.'.'.$image2->getClientOriginalExtension();

        $image3 = $request->file('surat_bebas_napza');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/surat_bebas_napza',$image3, $fileName.'.'.$image3->getClientOriginalExtension()));
        $addData['surat_bebas_napza'] = $fileName.'.'.$image3->getClientOriginalExtension();

        $image4 = $request->file('surat_kesehatan_jiwa');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/surat_kesehatan_jiwa',$image4, $fileName.'.'.$image4->getClientOriginalExtension()));
        $addData['surat_kesehatan_jiwa'] = $fileName.'.'.$image4->getClientOriginalExtension();

        $image5 = $request->file('surat_kesehatan_jasmani');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/surat_kesehatan_jasmani',$image5, $fileName.'.'.$image5->getClientOriginalExtension()));
        $addData['surat_kesehatan_jasmani'] = $fileName.'.'.$image5->getClientOriginalExtension();

        $image6 = $request->file('skck');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/skck',$image6, $fileName.'.'.$image6->getClientOriginalExtension()));
        $addData['skck'] = $fileName.'.'.$image6->getClientOriginalExtension();
        
        $driver = Driver::create($addData);
        return response([
            'message' => 'Add Driver Success',
            'data' => $driver
        ], 200); 
    }

    public function deleteDataDriver(Request $request, $id){
        $driver = Driver::find($id);

        if(is_null($driver)){
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404); 
        }

        $driver->status_aktif = 0;
        $driver->status_ketersediaan_driver = 0;

        if($driver->save()){
            return response([
                'message' => 'Delete Driver Success',
                'data' => $driver
            ],200);
        }

        return response([
            'message' => 'Delete Driver Failed',
            'data' => null
        ], 400); 
    }

    public function updateDataDriver(Request $request, $id){
        $driver = Driver::find($id);
        if(is_null($driver)){
            return response([
                'message'=>'Driver Not Found',
                'data' =>null
            ],404);
        }

        $updateData = $request->all();

        if(isset($request->foto_driver)){
            $image = $request->file('foto_driver');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/foto_driver',$image, $fileName.'.'.$image->getClientOriginalExtension()));
            $driver->foto_driver = $fileName.'.'.$image->getClientOriginalExtension();
        }
        
        if(isset($request->sim_driver)){
            $image1 = $request->file('sim_driver');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/sim_driver',$image1, $fileName.'.'.$image1->getClientOriginalExtension()));
            $driver->sim_driver = $fileName.'.'.$image1->getClientOriginalExtension();    
        }
        
        if(isset($request->surat_bebas_napza)){
            $image2 = $request->file('surat_bebas_napza');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/surat_bebas_napza',$image2, $fileName.'.'.$image2->getClientOriginalExtension()));
            $driver->surat_bebas_napza = $fileName.'.'.$image2->getClientOriginalExtension();
        }

        if(isset($request->surat_kesehatan_jiwa)){
            $image3 = $request->file('surat_kesehatan_jiwa');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/surat_kesehatan_jiwa',$image3, $fileName.'.'.$image3->getClientOriginalExtension()));
            $driver->surat_kesehatan_jiwa = $fileName.'.'.$image3->getClientOriginalExtension();
        }

        if(isset($request->surat_kesehatan_jasmani)){
            $image4 = $request->file('surat_kesehatan_jasmani');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/surat_kesehatan_jasmani',$image4, $fileName.'.'.$image4->getClientOriginalExtension()));
            $driver->surat_kesehatan_jasmani = $fileName.'.'.$image4->getClientOriginalExtension();
        }

        if(isset($request->skck)){
            $image5 = $request->file('skck');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/skck',$image5, $fileName.'.'.$image5->getClientOriginalExtension()));
            $driver->skck = $fileName.'.'.$image5->getClientOriginalExtension();
        }      

        $validate = Validator::make($updateData, [
            'id_driver',
            'nama_driver'=>'required',
            'alamat_driver'=>'required',
            'tgl_lahir_driver'=>'required',
            'jenis_kelamin_driver'=>'required',
            'no_telepon_driver' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
            'email'=>'required|email:rfc,dns|unique:customers,email|unique:pegawais,email',
            'password'=> 'required',
            'foto_driver' => 'image',
            'no_sim_driver'=> 'required',
            'sim_driver'=>'image',
            'surat_bebas_napza'=> 'image',
            'surat_kesehatan_jiwa'=> 'image',
            'surat_kesehatan_jasmani' => 'image',
            'skck' => 'image',
            'tarif_driver_harian'=> 'required',
            'kemampuan_bahasa_asing'=> 'required',
            'status_ketersediaan_driver',
            'status_aktif'
        ], [], [
            'nama_driver'=>'Nama Driver',
            'alamat_driver'=>'Alamat Driver',
            'tgl_lahir_driver'=>'Tanggal Lahir Driver',
            'jenis_kelamin_driver'=>'Jenis Kelamin Driver',
            'no_telepon_driver' => 'Nomor Telepon Driver',
            'email'=>'Email Driver',
            'foto_driver'=> 'Foto Driver',
            'no_sim_driver'=> 'Nomor SIM Driver',
            'sim_driver'=> 'Foto SIM Driver',
            'surat_bebas_napza'=> 'Foto Surat Bebas Napza',
            'surat_kesehatan_jiwa'=> 'Foto Surat Kesehatan Jiwa',
            'surat_kesehatan_jasmani'=> 'Foto Surat Kesehatan Jasmani',
            'skck'=> 'Foto SKCK',
            'tarif_driver_harian'=> 'Tarif Harian Driver',
            'kemampuan_bahasa_asing'=> 'Kemampuan Bahasa Asing',
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);
        if($request->tgl_lahir_driver!=$pegawai->tgl_lahir_driver){
            $updateData['password'] = Carbon::parse($request->tgl_lahir_driver)->format('d/m/Y');
            $updateData['password'] = bcrypt($updateData['password']);
            $driver->password = $updateData['password'];
        }

        $driver->nama_driver = $updateData['nama_driver'];
        $driver->alamat_driver = $updateData['alamat_driver'];
        $driver->tgl_lahir_driver = $updateData['tgl_lahir_driver'];
        $driver->jenis_kelamin_driver = $updateData['jenis_kelamin_driver'];
        $driver->no_telepon_driver = $updateData['no_telepon_driver'];
        $driver->email = $updateData['email'];
        $driver->password = $updateData['password'];
        // $driver->foto_driver = $updateData['foto_driver'];
        $driver->no_sim_driver = $updateData['no_sim_driver'];
        // $driver->sim_driver = $updateData['sim_driver'];
        // $driver->surat_bebas_napza = $updateData['surat_bebas_napza'];
        // $driver->surat_kesehatan_jiwa = $updateData['surat_kesehatan_jiwa'];
        // $driver->surat_kesehatan_jasmani = $updateData['surat_kesehatan_jasmani'];
        // $driver->skck = $updateData['skck'];
        $driver->tarif_driver_harian = $updateData['tarif_driver_harian'];
        $driver->kemampuan_bahasa_asing = $updateData['kemampuan_bahasa_asing'];
        $driver->status_aktif = $updateData['status_aktif'];      
    
        if($driver->save()){
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Update Driver Failed',
            'data'=> null
        ],400);
    }

    public function getRerataDriver(){
        $drivers = TransaksiPenyewaan::selectRaw("id_driver, AVG(rating_driver) as rerata_rating")->groupBy('id_driver')->get();

        if(count($drivers)>0){
            return response([
                'message'=>'Retrieve Rerata Driver',
                'data'=>$drivers
            ], 200);
        }

        return response([
            'message'=>'Rerata Driver Not Found',
            'data'=>null
        ], 404);
    }
}
