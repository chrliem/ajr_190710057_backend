<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
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

        $image = $request->file('foto_mobil');
        $fileName = Carbon::now()->toDateString().uniqid();
        (Storage::putFileAs('public/foto_mobil',$image, $fileName.'.'.$image->getClientOriginalExtension()));
        $addData['foto_mobil'] = $fileName.'.'.$image->getClientOriginalExtension();

        $validate = Validator::make($addData, [
            'id_mobil',
            'id_mitra',
            'no_plat' => 'required',
            'nama_mobil' => 'required',
            'foto_mobil' => 'required',
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
            'tgl_mulai_kontrak'=> 'date_format:Y-m-d',
            'tgl_habis_kontrak'=> 'date_format:Y-m-d'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

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
            $updateData['foto_mobil'] = $fileName.'.'.$image->getClientOriginalExtension();
        }

        $validate = Validator::make($updateData, [
            'id_mobil',
            'id_mitra',
            'no_plat' => 'required',
            'nama_mobil' => 'required',
            'foto_mobil' => 'required',
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
            'tgl_mulai_kontrak'=> 'date_format:Y-m-d',
            'tgl_habis_kontrak'=> 'date_format:Y-m-d'
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
        $mobils = Mobil::selectRaw("mobils.no_plat, mobils.nama_mobil, mitras.nama_mitra, DATEDIFF($currDate, mobils.tgl_habis_kontrak) as sisa_durasi_kontrak")
            ->join('mitras','mitras.id_mitra','=','mobils.id_mitra')
            ->whereRaw("DATEDIFF($currDate, mobils.tgl_habis_kontrak)<30")
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
}
