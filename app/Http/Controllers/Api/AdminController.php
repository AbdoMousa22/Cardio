<?php

namespace App\Http\Controllers\Api;

use App\Helpers\apiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('CheckAdmin')->except('register');

    // }

    public function register(Request $request)
    {

        $validator= Validator::make($request->all(),
        [
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique :'. Admin::class],
            'password'=>['required','confirmed',Rules\Password::defaults()],
        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Register validation fails',$validator->messages()->all());
        }
        else{

            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $data['token'] = $admin->createToken('admin-token')->plainTextToken;
            $data['name']  =  $admin->name;
            $data['email']  =  $admin->email;

            return apiResponse::sendResponse(201,"Account Created Successfully",$admin);

        }

    }

    public function login(Request $request)
    {

        $validator= Validator::make($request->all(),
         [
             'email'=>['required','email'],
             'password'=>['required'],
         ]);
         if($validator->fails()){

             return ApiResponse::sendResponse(422,'Login validation fails',$validator->messages()->all());
         }

         $admin = Admin::where('email', $request->email)->first();

         if (! $admin || ! Hash::check($request->password, $admin->password)) {
             return apiResponse::sendResponse(401," login failed",[]);

         }
         else{
             $data['token'] = $admin->createToken('admin-token')->plainTextToken;
             $data['name']  =  $admin->name;
             $data['email']  =  $admin->email;

             return apiResponse::sendResponse(201,' YOU LOGIN SUCCESSFULLY',$data);
         }

    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return apiResponse::sendResponse(200,"YOU LGOED OUT SUCCESSFULLY",null);
    }

    public function delete(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        $user_id= $request->user()->id;
        $admin_delete= Admin::where('id',$user_id)->delete();

        if($admin_delete)
        return apiResponse::sendResponse(200,"YOU DELETE ACCOUNT SUCCESSFULLY",null);
    }
    public function update(Request $request )
    {

        $validator= Validator::make($request->all(),
        [
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique :'. Admin::class],
            'password'=>['required','confirmed',Rules\Password::defaults()],

        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Update validation fails',$validator->messages()->all());
        }
        else{

            $id= Auth::user()->id;

            if (Admin::where('id', $id)->exists()) {

                Admin::where('id',$id)->update([
                    "name"=>$request->name,
                    "email"=>$request->email,
                    "password"=>Hash::make($request->password),



                    ]);
                    $admin=   Admin::findOrFail($id);
                    return apiResponse::sendResponse(201,"Admin Updated Successfully",$admin);

            }
            else
                return apiResponse::sendResponse(201,"Admin Dosen't exist",[]);


        }

    }
    public function view()
    {

        $admin= Admin::all();
        if($admin)
        return ApiResponse::sendResponse(200,"These Are All Admins",$admin);
        return ApiResponse::sendResponse(200,"These Are no Admin",[]);

    }




}


