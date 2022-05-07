<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Mitra;
use Carbon\Carbon;

class MitraController extends Controller
{
    public function showDataMitra(){
        $mitras = Mitra::all();

        if(count($mitras)>0){
            return response([
                'message' => 'Retrieve All Mitra Success',
                'data' => $mitras
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 404);
    }

    public function showDataMitrabyId($id){
        $mitra = Mitra::find($id);

        if(!is_null($mitra)){
            return response([
                'message' => 'Retrieve Mitra Success',
                'data' => $mitra
            ], 200);
        }
    }

    public function addDataMitra(Request $request){
        $addData = $request->all();
        
        $addData['status_aktif'] = 1;

        $validate = Validator::make($addData, [
            'id_mitra' ,
            'nama_mitra'=>'required',
            'no_ktp_mitra'=>'required',
            'alamat_mitra'=>'required',
            'no_telepon_mitra'=>'required|numeric|digits_between:1,13|regex:/^((08))/',
            'status_aktif'
        ],[],[
            'nama_mitra'=>'Nama Mitra',
            'no_ktp_mitra'=>'Nomor KTP Mitra',
            'alamat_mitra'=>'Alamat Mitra',
            'no_telepon_mitra'=>'Nomor Telepon Mitra',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $mitra = Mitra::create($addData);
        return response([
            'message' => 'Add Mitra Success',
            'data' => $mitra
        ], 200);
    }

    public function deleteDataMitra(Request $request, $id){
        $mitra = Mitra::find($id);

        if(is_null($mitra)){
            return response([
                'message' => 'Mitra Not Found',
                'data' => null
            ], 404); 
        }

        $mitra->status_aktif = 0;

        if($mitra->save()){
            return response([
                'message' => 'Delete Mitra Success',
                'data' => $mitra
            ],200);
        }

        return response([
            'message' => 'Delete Mitra Failed',
            'data' => null
        ], 400); 
    }

    public function updateDataMitra(Request $request, $id){
        $mitra = Mitra::find($id);
        if(is_null($mitra)){
            return response([
                'message'=>'Mitra Not Found',
                'data'=>null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'id_mitra',
            'nama_mitra'=>'required',
            'no_ktp_mitra'=>'required',
            'alamat_mitra'=>'required',
            'no_telepon_mitra'=>'required|numeric|digits_between:1,13|regex:/^((08))/',
            'status_aktif'
        ],[],[
            'nama_mitra'=>'Nama Mitra',
            'no_ktp_mitra'=>'Nomor KTP Mitra',
            'alamat_mitra'=>'Alamat Mitra',
            'no_telepon_mitra'=>'Nomor Telepon Mitra',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $mitra->nama_mitra = $updateData['nama_mitra'];
        $mitra->no_ktp_mitra = $updateData['no_ktp_mitra'];
        $mitra->alamat_mitra = $updateData['alamat_mitra'];
        $mitra->no_telepon_mitra = $updateData['no_telepon_mitra'];
        $mitra->status_aktif = $updateData['status_aktif'];
        
        if($mitra->save()){
            return response([
                'message' => 'Update Mitra Success',
                'data' => $mitra
            ], 200);
        }

        return response([
            'message' => 'Update Mitra Failed',
            'data'=> null
        ],400);
        
    }
}
