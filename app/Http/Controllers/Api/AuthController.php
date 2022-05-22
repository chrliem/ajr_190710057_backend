<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\Driver;
use App\Models\Customer;       
use Validator;


class AuthController extends Controller
{
    public function login(Request $request){
        $loginData = $request->all();
        
        $validate = Validator::make($loginData, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
        return response([
            'message' => $validate->errors()
        ],400);

        $checkPegawai = Pegawai::where('email',$request->email)->where('status_aktif',1)->count();
        if($checkPegawai!=null){
            if(!Auth::guard('pegawai')->attempt($loginData))
            return response([
                'message' => 'Invalid Credentials Pegawai',
                'data' => $checkPegawai
            ],401); 

            $pegawai = Auth::guard('pegawai')->user();
            $token = $pegawai->createToken('Authentication Token')->accessToken; 

            return response([
                'message' => 'Authenticated as Pegawai',
                'user' => $pegawai,
                'token_type' => 'Bearer',
                'access_token' => $token
            ]); 
            }

            $checkCustomer = Customer::where('email',$request->email)->count();
            if($checkCustomer!=null){
                if(!Auth::guard('customer')->attempt($loginData))
                return response([
                    'message' => $loginData,
                    'data' => $loginData
                ],401); 

                $customer = Auth::guard('customer')->user();
                $token = $customer->createToken('Authentication Token')->accessToken; 

                return response([
                    'message' => 'Authenticated as Customer',
                    'user' => $customer,
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ]); 
            }

            $checkDriver = Driver::where('email',$request->email)->where('status_aktif',1)->count();
            if($checkDriver!=null){
                if(!Auth::guard('driver')->attempt($loginData))
                return response([
                    'message' => 'Invalid Credentials Driver',
                    'data' => $loginData
                ],401); 

                $driver = Auth::guard('driver')->user();
                $token = $driver->createToken('Authentication Token')->accessToken; 

                return response([
                    'message' => 'Authenticated as Driver',
                    'user' => $driver,
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ]); 
            
        }
        
    }

    public function logout(Request $request) {
        Auth::logout();
        return response([
            'message' => 'Berhasil Logout',
        ]); 
      }

}
