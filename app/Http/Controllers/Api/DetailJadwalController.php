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
        // $detailjadwals = DetailJadwal::all();
        $detailjadwals = DetailJadwal::selectRaw("detail_jadwals.id, pegawais.id_pegawai, jadwal_pegawais.id_jadwal, pegawais.nama_pegawai, jadwal_pegawais.hari, jadwal_pegawais.shift")
                    ->join('pegawais','pegawais.id_pegawai','=','detail_jadwals.id_pegawai')
                    ->join('jadwal_pegawais','jadwal_pegawais.id_jadwal','=','detail_jadwals.id_jadwal')
                    ->get();

        if(count($detailjadwals)>0){
            return response([
                'message' => 'Retrieve All Jadwal Pegawai Success',
                'data' => $detailjadwals
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 404);
    }

    public function showJadwalPegawaibyId($id){
        $detailjadwal = DetailJadwal::find($id);

        if(!is_null($detailjadwal)){
            return response([
                'message' => 'Retrieve Jadwal Pegawai Success',
                'data' => $jadwal
            ], 200);
        }
    }

    public function addJadwalPegawai(Request $request){
        $addData = $request->all();
        
        

        $validate = Validator::make($addData, [
            'id',
            'id_jadwal'=>'required',
            'id_pegawai'=>'required'
        ],[],['id_jadwal'=>'Pilihan Jadwal',
        'id_pegawai'=>'Pegawai']);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

            $countShift = DetailJadwal::selectRaw('COUNT(id_jadwal) as jumlah_shift')->whereRaw('id_pegawai = ?',$request->id_pegawai)
            ->get()
            ->first()
            ->jumlah_shift;

        $checkUnique = DetailJadwal::selectRaw('id_jadwal, id_pegawai')->whereRaw("id_pegawai =$request->id_pegawai&& id_jadwal=$request->id_jadwal")
            ->get()
            ->first();

        if($countShift>5){
            return response([
                'message' => 'Pegawai sudah memiliki 6 shift',
            ]);
        }

        if($checkUnique!=null){
            return response([
                'message' => 'Pegawai sudah mengisi shift ini'
            ]);
        }

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
        ],[],['id_jadwal'=>'Pilihan Jadwal',
        'id_pegawai'=>'Pegawai']);

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

