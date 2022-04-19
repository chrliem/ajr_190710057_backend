<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Pegawai;
use Carbon\Carbon;

class PegawaiController extends Controller
{
    public function showDataPegawai(){
        $pegawais = Pegawai::all();

        if(count($pegawais) > 0){
            return response([
                'message' => 'Retrieve All Pegawai Success',
                'data' => $pegawais
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showDataPegawaibyId($id){
        $pegawai = Pegawai::find($id);

        if(!is_null($pegawai)){
            return response([
                'message' => 'Retreive Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404);
    }

    public function addDataPegawai(Request $request){
        
        $addData = $request->all(); 
        
        $addData['status_aktif'] = 1;
        $addData['password_pegawai'] = Carbon::parse($request->tgl_lahir_pegawai)->format('d/m/Y');

        $validate = Validator::make($addData, [
            'id_pegawai',
            'id_role' => 'required',
            'nama_pegawai' => 'required',
            'tgl_lahir_pegawai' => 'required|date_format:Y-m-d',
            'jenis_kelamin_pegawai' => 'required',
            'alamat_pegawai' => 'required',
            'no_telepon_pegawai' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
            'foto_pegawai' => 'required|image|mimes:jpeg,jpg,png',
            'email_pegawai' => 'required|email:rfc,dns|unique:pegawais', 
            'password_pegawai',
            'status_aktif' => 'required'
        ]); 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $image = $request->file('foto_pegawai');
        $fileName = Carbon::now()->toDateString().uniqid();
        Storage::putFileAs('public/foto_pegawai',$image, $fileName.'.'.$image->getClientOriginalExtension());
        $addData['foto_pegawai'] = $fileName.'.'.$image->getClientOriginalExtension();

        $pegawai = Pegawai::create($addData);
        return response([
            'message' => 'Add Pegawai Success',
            'data' => $pegawai
        ], 200); 
    }

    public function deleteDataPegawai(Request $request, $id){
        $pegawai = Pegawai::find($id);

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404); 
        }

        $pegawai->status_aktif = 0;

        if($pegawai->save()){
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $pegawai
            ],200);
        }

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null
        ], 400); 
    }

    public function updateDataPegawai(Request $request, $id){
        $pegawai = Pegawai::find($id);

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all() ;
        
        if(isset($request->foto_pegawai)){
            $image = $request->file('foto_pegawai');
            $fileName = Carbon::now()->toDateString().uniqid();
            Storage::putFileAs('public/foto_pegawai',$image, $fileName.'.'.$image->getClientOriginalExtension());
            $updateData['foto_pegawai'] = $fileName.'.'.$image->getClientOriginalExtension();
        }
            
        $validate = Validator::make($updateData, [
            'id_pegawai',
            'id_role' => 'required',
            'nama_pegawai' => 'required',
            'tgl_lahir_pegawai' => 'required|date_format:Y-m-d',
            'jenis_kelamin_pegawai' => 'required',
            'alamat_pegawai' => 'required',
            'no_telepon_pegawai' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
            'foto_pegawai => required|image|mime:jpg, jpeg, png',
            'email_pegawai' => 'required|email:rfc,dns', 
            'password_pegawai' => 'required',
            'status_aktif'
        ]);
            
        if($validate->fails())
            return response(['message' => $validate->errors()],400);
  
        $pegawai->id_role = $updateData['id_role'];
        $pegawai->nama_pegawai = $updateData['nama_pegawai'];
        $pegawai->tgl_lahir_pegawai = $updateData['tgl_lahir_pegawai'];
        $pegawai->jenis_kelamin_pegawai = $updateData['jenis_kelamin_pegawai'];
        $pegawai->alamat_pegawai = $updateData['alamat_pegawai'];
        $pegawai->no_telepon_pegawai = $updateData['no_telepon_pegawai'];
        $pegawai->foto_pegawai = $updateData['foto_pegawai'];
        $pegawai->email_pegawai = $updateData['email_pegawai'];
        $pegawai->password_pegawai = $updateData['password_pegawai'];
        $pegawai->status_aktif = $updateData['status_aktif'];

        if($pegawai->save()){
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Update Pegawai Failed',
            'data'=> null
        ],400);
        
    }
}


