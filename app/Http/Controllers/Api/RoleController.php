<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function showDataRole(){
        $roles = Role::all();

        if(count($roles)>0){
            return response([
                'message' => 'Retrieve All Role Success',
                'data' => $roles
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ], 404);
    }
    
    public function showDataRolebyId($id){
        $role = Role::find($id);

        if(!is_null($role)){
            return response([
                'message' => 'Retrieve Role Success',
                'data' => $role
            ], 200);
        }
    }
}
