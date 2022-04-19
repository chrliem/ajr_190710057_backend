<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\JadwalPegawai;
use Carbon\Carbon;

class JadwalPegawaiController extends Controller
{
    public function showJadwalPegawai(){
        $jadwals = JadwalPegawai::all();

        if(count($jadwals)>0){
            return response([
                'message' => 'Retrieve All Jadwal Pegawai Success',
                'data' => $jadwals
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 404);
    }

    public function showJadwalPegawaibyId($id){
        $jadwal = JadwalPegawai::find($id);

        if(!is_null($jadwal)){
            return response([
                'message' => 'Retrieve Jadwal Pegawai Success',
                'data' => $jadwal
            ], 200);
        }
    }

    public function addJadwalPegawai(Request $request){
        $addData = $request->all();

        $validate = Validator::make($addData, [
            'id_jadwal',
            'hari'=>'required',
            'shift'=>'required'
        ]);

        $jadwal = JadwalPegawai::create($addData);
        return response([
            'message' => 'Add Jadwal Pegawai Success',
            'data' => $jadwal
        ], 200);
    }

    public function deleteJadwalPegawai(Request $request, $id){
        $jadwal = JadwalPegawai::find($id);

        if(is_null($jadwal)){
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404); 
        }

        if($jadwal->delete()){
            return response([
                'message' => 'Delete Jadwal Success',
                'data' => $jadwal
            ],200);
        }

        return response([
            'message' => 'Delete Jadwal Failed',
            'data' => null
        ], 400); 
    }

    public function updateJadwalPegawai(Request $request, $id){
        $jadwal = JadwalPegawai::find($id);
        if(is_null($jadwal)){
            return response([
                'message'=>'Jadwal Not Found',
                'data'=>null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'id_jadwal',
            'hari'=>'required',
            'shift'=>'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $jadwal->hari=$updateData['hari'];
        $jadwal->shift=$updateData['shift'];
        
        if($jadwal->save()){
            return response([
                'message' => 'Update Jadwal Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Update Jadwal Failed',
            'data'=> null
        ],400);
        
    }
}
