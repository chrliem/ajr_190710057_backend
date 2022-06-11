<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Mobil;
use Carbon\Carbon;


class MobilController extends Controller
{

    public function showDataMobil(){
        $mobils = Mobil::all();

        if(count($mobils)>0){
            return response([
                'message'=>'Retrieve All Mobil Success',
                'data'=>$mobils
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 400);
    }
    
    public function showDataMobilbyId($id){
        $mobil = Mobil::find($id);

        if(!is_null($mobil)){
            return response([
                'message'=>'Retrieve Mobil Success',
                'data'=>$mobil
            ], 200);
        }

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ], 404);
    }

    public function addDataMobil(Request $request){
        $addData = $request->all();        
        
        $addData['status_ketersediaan_mobil'] = 1;
        
        if(is_null($addData['id_mitra'])){
            $addData['kategori_aset'] = 'Perusahaan';
        }else{
            $addData['kategori_aset'] = 'Mitra';
        }

        

        $validate = Validator::make($addData, [
            'id_mobil',
            'id_mitra',
            'no_plat' => 'required',
            'nama_mobil' => 'required',
            'foto_mobil' => 'required|image',
            'tipe_mobil' => 'required',
            'jenis_transmisi' => 'required',
            'jenis_bahan_bakar' => 'required',
            'volume_bahan_bakar' => 'required',
            'warna_mobil' => 'required',
            'kapasitas_penumpang' => 'required',
            'fasilitas_mobil' => 'required',
            'no_stnk' => 'required',
            'tgl_servis_terakhir' => 'required|date_format:Y-m-d',
            'kategori_aset',
            'status_ketersediaan_mobil',
            'tarif_mobil_harian'=> 'required',
            'tgl_mulai_kontrak',
            'tgl_habis_kontrak'
        ],[],[
            'no_plat' => 'Nomor Plat Mobil',
            'nama_mobil' => 'Nama Mobil',
            'foto_mobil' => 'Foto Mobil',
            'tipe_mobil' => 'Tipe Mobil',
            'jenis_transmisi' => 'Jenis Transmisi',
            'jenis_bahan_bakar' => 'Jenis Bahan Bakar',
            'volume_bahan_bakar' => 'Volume Bahan Bakar',
            'warna_mobil' => 'Warna Mobil',
            'kapasitas_penumpang' => 'Kapasitas Penumpang',
            'fasilitas_mobil' => 'Fasilitas Mobil',
            'no_stnk' => 'Nomor STNK',
            'tgl_servis_terakhir' => 'Tanggal Servis Terakhir',
            'tarif_mobil_harian'=> 'Tarif Mobil Harian',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $image = $request->file('foto_mobil');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/foto_mobil',$image, $fileName.'.'.$image->getClientOriginalExtension()));
        $addData['foto_mobil'] = $fileName.'.'.$image->getClientOriginalExtension();

        $mobil = Mobil::create($addData);
        return response([
            'message' => 'Add Mobil Success',
            'data' => $mobil
        ], 200); 
    }
    
    public function deleteDataMobil(Request $request, $id){
        $mobil = Mobil::find($id);

        if(is_null($mobil)){
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404); 
        }

        $mobil->status_ketersediaan_mobil = 0;

        if($mobil->save()){
            return response([
                'message' => 'Delete Mobil Success',
                'data' => $mobil
            ],200);
        }

        return response([
            'message' => 'Delete Mobil Failed',
            'data' => null
        ], 400); 
    }

    public function updateDataMobil(Request $request, $id){
        $mobil = Mobil::find($id);
        if(is_null($mobil)){
            return response([
                'message'=>'Mobil Not Found',
                'data'=>null
            ], 404);
        }

        $updateData = $request->all();
        
        if(is_null($updateData['id_mitra'])){
            $updateData['kategori_aset'] = 'Perusahaan';
        }else{
            $updateData['kategori_aset'] = 'Mitra';
        }

        if(isset($request->foto_mobil)){
            $image = $request->file('foto_mobil');
            $fileName = Carbon::now()->toDateString().uniqid();
            (Storage::putFileAs('public/foto_mobil',$image, $fileName.'.'.$image->getClientOriginalExtension()));
            $mobil->foto_mobil = $fileName.'.'.$image->getClientOriginalExtension();
        }

        $validate = Validator::make($updateData, [
            'id_mobil',
            'id_mitra',
            'no_plat' => 'required',
            'nama_mobil' => 'required',
            'foto_mobil' => 'image',
            'tipe_mobil' => 'required',
            'jenis_transmisi' => 'required',
            'jenis_bahan_bakar' => 'required',
            'volume_bahan_bakar' => 'required',
            'warna_mobil' => 'required',
            'kapasitas_penumpang' => 'required',
            'fasilitas_mobil' => 'required',
            'no_stnk' => 'required',
            'tgl_servis_terakhir' => 'required|date_format:Y-m-d',
            'kategori_aset' => 'required',
            'status_ketersediaan_mobil',
            'tarif_mobil_harian'=> 'required',
            'tgl_mulai_kontrak',
            'tgl_habis_kontrak' 
        ],[],[
            'no_plat' => 'Nomor Plat Mobil',
            'nama_mobil' => 'Nama Mobil',
            'foto_mobil' => 'Foto Mobil',
            'tipe_mobil' => 'Tipe Mobil',
            'jenis_transmisi' => 'Jenis Transmisi',
            'jenis_bahan_bakar' => 'Jenis Bahan Bakar',
            'volume_bahan_bakar' => 'Volume Bahan Bakar',
            'warna_mobil' => 'Warna Mobil',
            'kapasitas_penumpang' => 'Kapasitas Penumpang',
            'fasilitas_mobil' => 'Fasilitas Mobil',
            'no_stnk' => 'Nomor STNK',
            'tgl_servis_terakhir' => 'Tanggal Servis Terakhir',
            'tarif_mobil_harian'=> 'Tarif Mobil Harian',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $mobil->id_mitra = $updateData['id_mitra'];
        $mobil->no_plat = $updateData['no_plat'];
        $mobil->nama_mobil = $updateData['nama_mobil'];
        $mobil->tipe_mobil = $updateData['tipe_mobil'];
        $mobil->jenis_transmisi = $updateData['jenis_transmisi'];
        $mobil->jenis_bahan_bakar = $updateData['jenis_bahan_bakar'];
        $mobil->volume_bahan_bakar = $updateData['volume_bahan_bakar'];
        $mobil->warna_mobil = $updateData['warna_mobil'];
        $mobil->kapasitas_penumpang = $updateData['kapasitas_penumpang'];
        $mobil->fasilitas_mobil = $updateData['fasilitas_mobil'];
        $mobil->no_stnk = $updateData['no_stnk'];
        $mobil->tgl_servis_terakhir = $updateData['tgl_servis_terakhir'];
        $mobil->kategori_aset=$updateData['kategori_aset'];
        $mobil->tgl_mulai_kontrak=$updateData['tgl_mulai_kontrak'];
        $mobil->tgl_habis_kontrak=$updateData['tgl_habis_kontrak'];
        $mobil->status_ketersediaan_mobil=$updateData['status_ketersediaan_mobil'];
        $mobil->tarif_mobil_harian=$updateData['tarif_mobil_harian'];

        if($mobil->save()){
            return response([
                'message' => 'Update Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Update Mobil Failed',
            'data'=> null
        ],400);
        
    }
 
    public function showDataMobilHabisKontrak(){
        $currDate = Carbon::now()->format('ymd');
        $mobils = Mobil::selectRaw("mobils.no_plat, mobils.nama_mobil, mitras.nama_mitra, DATEDIFF(mobils.tgl_habis_kontrak, $currDate) as sisa_durasi_kontrak")
            ->join('mitras','mitras.id_mitra','=','mobils.id_mitra')
            ->whereRaw("DATEDIFF(mobils.tgl_habis_kontrak, $currDate)<30")
            ->get();
        
        if(count($mobils)>0){
            return response([
                'message'=>'Retrieve Mobil Habis Kontrak Success',
                'data'=>$mobils
            ], 200);
        }

        return response([
            'message'=>'Mobil Habis Kontrak Not Found',
            'data'=>null
        ], 404);
    }

    public function showDataMobilAvailable(){
        $mobils = Mobil::where('status_ketersediaan_mobil','=',1)->get();

        if(count($mobils)>0){
            return response([
                'message'=>'Retrieve Mobil Success',
                'data'=>$mobils
            ], 200);
        }

        return response([
            'message'=>'No Mobil Available',
            'data'=>null
        ], 404);
    }
}
