<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $admin->createToken($request->email)->plainTextToken;

        return response([
            'admin' => $admin,
            'token' => $token
        ], 201);
    }

    public function logout(){

        if(auth('admin')->user()){
          
          auth('admin')->user()->tokens()->delete();
          return response([
          'message' => 'Successfully Logged Out as Admin!!'
        ]);

        }
        else
        {
          return response([
          'message' => 'Not Possible to Log Out as Admin!!'
        ]);
        }
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if(!$admin || !Hash::check($request->password, $admin->password)){
            return response([
                'message' => 'The provided credentials are incorrect as Admin.'
           ], 401);
        }

        $token = $admin->createToken($request->email)->plainTextToken;

        return response([
            'admin' => $admin,
            'token' => $token
        ], 200);

    }//end
    
}
