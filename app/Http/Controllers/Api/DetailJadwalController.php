<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\DetailJadwal;
use Carbon\Carbon;

class DetailJadwalController extends Controller
{
    public function showJadwalPegawai(){
        $jadwals = DetailJadwal::all();

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
        $jadwal = DetailJadwal::find($id);

        if(!is_null($jadwal)){
            return response([
                'message' => 'Retrieve Jadwal Pegawai Success',
                'data' => $jadwal
            ], 200);
        }
    }

    public function addJadwalPegawai(Request $request){
        $addData = $request->all();
        
        $countShift = DetailJadwal::selectRaw('COUNT(id_jadwal) as jumlah_shift')->whereRaw('id_pegawai = ?',$request->id_pegawai)
            ->get()
            ->first()
            ->jumlah_shift;
        
        if($countShift>5){
            return response([
                'message' => 'Pegawai Has More Than 6 Shifts',
            ]);
        }

        $validate = Validator::make($addData, [
            'id',
            'id_jadwal'=>'required',
            'id_pegawai'=>'required'
        ]);

        $jadwal = DetailJadwal::create($addData);
        return response([
            'message' => 'Add Jadwal Pegawai Success',
            'data' => $jadwal
        ], 200);
    }

    public function deleteJadwalPegawai(Request $request, $id){
        $jadwal = DetailJadwal::find($id);

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
        $jadwal = DetailJadwal::find($id);
        if(is_null($jadwal)){
            return response([
                'message'=>'Jadwal Not Found',
                'data'=>null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'id',
            'id_jadwal'=>'required',
            'id_pegawai'=>'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $jadwal->id_jadwal=$updateData['id_jadwal'];
        $jadwal->id_pegawai=$updateData['id_pegawai'];
        
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

