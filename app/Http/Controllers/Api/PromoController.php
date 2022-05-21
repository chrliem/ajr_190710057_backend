<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Promo;
use Carbon\Carbon;

class PromoController extends Controller
{
    public function showDataPromo(){
        $promos = Promo::all();

        if(count($promos)>0){
            return response([
                'message' => 'Retrieve All Promo Success',
                'data' => $promos
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 404);
    }
    
    public function showDataPromobyId($id){
        $promo = Promo::find($id);

        if(!is_null($promo)){
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        }
    }

    public function addDataPromo(Request $request){
        $addData = $request->all();

        $addData['status_promo'] = 1;
        $validate = Validator::make($addData, [
            'kode_promo'=>'required',
            'jenis_promo'=>'required',
            'keterangan'=>'required',
            'potongan_promo'=>'required',
            'status_promo'
        ],[],[
            'kode_promo'=>'Kode Promo',
            'jenis_promo'=>'Jenis Promo',
            'keterangan'=>'Keterangan',
            'potongan_promo'=>'Potongan Promo',
            'status_promo'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $promo = Promo::create($addData);
        return response([
            'message' => 'Add Promo Success',
            'data' => $promo
        ], 200);
    }

    public function deleteDataPromo(Request $request, $id){
        $promo = Promo::find($id);

        if(is_null($promo)){
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404); 
        }

        $promo->status_promo = 0;

        if($promo->save()){
            return response([
                'message' => 'Delete Promo Success',
                'data' => $promo
            ],200);
        }

        return response([
            'message' => 'Delete Promo Failed',
            'data' => null
        ], 400); 
    }

    public function updateDataPromo(Request $request, $id){
        $promo = Promo::find($id);
        if(is_null($promo)){
            return response([
                'message'=>'Promo Not Found',
                'data'=>null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'kode_promo'=>'required',
            'jenis_promo'=>'required',
            'keterangan'=>'required',
            'potongan_promo'=>'required',
            'status_promo'
        ],[],[
            'kode_promo'=>'Kode Promo',
            'jenis_promo'=>'Jenis Promo',
            'keterangan'=>'Keterangan',
            'potongan_promo'=>'Potongan Promo',
            'status_promo'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $promo->kode_promo = $updateData['kode_promo'];
        $promo->jenis_promo = $updateData['jenis_promo'];
        $promo->keterangan = $updateData['keterangan'];
        $promo->potongan_promo = $updateData['potongan_promo'];
        $promo->status_promo = $updateData['status_promo'];
        
        if($promo->save()){
            return response([
                'message' => 'Update Promo Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Update Promo Failed',
            'data'=> null
        ],400);
        
    }

    public function showDataPromoAvailable(){
        $promos = Promo::where('status_promo','=',1)->get();

        if(count($promos)>0){
            return response([
                'message' => 'Retrieve All Promo Success',
                'data' => $promos
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 404);

    }
}
