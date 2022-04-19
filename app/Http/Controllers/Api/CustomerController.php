<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Validator;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerController extends Controller
{

    public function showDataCustomer(){
        $customers = Customer::all();

        if(count($customers)>0){
            return response([
                'message' => 'Retrieve All Customer Success',
                'data' => $customers
            ],200);
        }

        return response([
            'message'=>'Customer Not Found',
            'data'=>null
        ], 404);
    }

    public function showDataCustomerbyId($id){
        $customer = Customer::find($id);

        if(!is_null($customer)){
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customer
            ], 200);
        }
    }

    public function addDataCustomer(Request $request){
        
        $addData = $request->all();
        
        //CUSyymmdd-XXX (XXX = AUTO INCREMENT)
        $date = Carbon::now()->format('Ymd');
        $count = count(Customer::all())+1;
        
        if($count<9){
            $formattedNum = Str::padLeft($count, 3,'00');
        }else if($count>9 && $count<100){
            $formattedNum = Str::padLeft($count, 3,'0');
        }

        $kartuIdentitas = base64_decode($request['kartu_identitas_customer']);
        $sim = base64_decode($request['sim_customer']);
        $imageName = uniqid() . '.png';
        $filePath = 'images/' . $imageName;

        if(Storage::disk('public')->put($filePath, $kartuIdentitas)){
            $request['kartu_identitas_customer'] = Storage::disk('public')->url($filePath);
        }else{
            return response(['message' => 'Upload Kartu Identitas Failed. '],409);
        }

        if(Storage::disk('public')->put($filePath, $sim)){
            $request['sim_customer'] = Storage::disk('public')->url($filePath);
        }else{
            return response(['message' => 'Upload SIM Failed. '],409);
        }

        $addData['id_customer'] = 'CUS'.$date.'-'.$formattedNum;

        $validate = Validator::make($addData, [
        'id_customer',
        'nama_customer'=>'required',
        'alamat_customer'=> 'required',
        'tgl_lahir_customer' =>'required|date_format:Y-m-d',
        'jenis_kelamin_customer' => 'required',
        'no_telepon_customer' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
        'no_kartu_identitas_customer' => 'required' ,
        'kartu_identitas_customer' => 'required',
        'no_sim_customer',
        'sim_customer',
        'email_customer' => 'required|email:rfc,dns|unique:customers',
        'password_customer' => 'required',
        'tipe_sewa_customer'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        $addData['password_customer'] = bcrypt($request->password_customer);

        $customer = Customer::create($addData);
        
        return response([
            'message' => 'Add Customer Success',
            'data' => $customer
        ], 200); 
        
    }

    public function verifikasiDataCustomer($id){
        $customer = Customer::find($id);

        if(is_null($customer)){
            return response([
                'message'=>'Customer Not Found',
                'data' => null
            ],404);
        }

        if(is_null($customer->sim_customer)){
            //Sewa mobil saja
            $customer->tipe_sewa_customer = 0;
        }else{
            //Sewa mobil dan driver
            $customer->tipe_sewa_customer = 1;
        }

        if($customer->save()){
            return response([
                'message'=>'Verification Success',
                'data'=>$customer
            ],200);
        }

        return response([
            'message'=>'Verification Failed',
            'data'=>null
        ],400);
    }

    public function updateDataCustomer(Request $request, $id){
        $customer = Customer::find($id);
        if(is_null($customer)){
            return response([
                'message'=>'Customer Not Found',
                'data' =>null
            ],404);
        }

        $updateData = $request->all();

        $kartuIdentitas = base64_decode($request['kartu_identitas_customer']);
        $sim = base64_decode($request['sim_customer']);
        $imageName = uniqid() . '.png';
        $filePath = 'images/' . $imageName;

        if(Storage::disk('public')->put($filePath, $kartuIdentitas)){
            $request['kartu_identitas_customer'] = Storage::disk('public')->url($filePath);
        }else{
            return response(['message' => 'Upload Kartu Identitas Failed. '],409);
        }

        if(Storage::disk('public')->put($filePath, $sim)){
            $request['sim_customer'] = Storage::disk('public')->url($filePath);
        }else{
            return response(['message' => 'Upload SIM Failed. '],409);
        }

        $validate = Validator::make($updateData, [
        'id_customer',
        'nama_customer'=>'required',
        'alamat_customer'=> 'required',
        'tgl_lahir_customer' =>'required|date_format:Y-m-d',
        'jenis_kelamin_customer' => 'required',
        'no_telepon_customer' => 'required|numeric|digits_between:1,13|regex:/^((08))/',
        'no_kartu_identitas_customer' => 'required' ,
        'kartu_identitas_customer' => 'required',
        'no_sim_customer',
        'sim_customer',
        'email_customer' => 'required',
        'password_customer' => 'required',
        'tipe_sewa_customer'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);
        
        $updateData['password_customer'] = bcrypt($request->password_customer); 
        
        $customer->nama_customer = $updateData['nama_customer'];
        $customer->alamat_customer = $updateData['alamat_customer'];
        $customer->tgl_lahir_customer = $updateData['tgl_lahir_customer'];
        $customer->jenis_kelamin_customer = $updateData['jenis_kelamin_customer'];
        $customer->no_telepon_customer = $updateData['no_telepon_customer'];
        $customer->no_kartu_identitas_customer = $updateData['no_kartu_identitas_customer'];
        $customer->kartu_identitas_customer= $updateData['kartu_identitas_customer'];
        $customer->no_sim_customer = $updateData['no_sim_customer'];
        $customer->sim_customer= $updateData['sim_customer'];
        $customer->email_customer = $updateData['email_customer'];
        $customer->password_customer = $updateData['password_customer'];

        if($customer->save()){
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data'=> null
        ],400);
    }
    
}
