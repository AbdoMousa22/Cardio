<?php

namespace App\Http\Controllers\Api;

use App\Helpers\apiResponse as ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{

    public function register(Request $request){

       $validator= Validator::make($request->all(),
        [
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique :'. Doctor::class],
            'password'=>['required',Rules\Password::defaults()],
            'phone'=>['nullable','numeric','digits:11'],
        ]);
        if($validator->fails()){

            return ApiResponse::sendResponse(422,'Register validation fails',$validator->messages()->all());
        }
        else{

            $doctor = Doctor::create([
                   "name"=>$request->name,
                   "email"=>$request->email,
                   "password"=>Hash::make($request->password),
                   "phone"=>$request->phone,
               ]);

               $data['token']=$doctor->createToken('DOCTOR_API')->plainTextToken;
               $data['name']=$doctor->name;
               $data['email']=$doctor->email;
               return apiResponse::sendResponse(201,"Account Created Successfully",$data);

        }


    }

    public function login(Request $request){

       $validator= Validator::make($request->all(),
        [
            'email'=>['required','email'],
            'password'=>['required'],
        ]);
        if($validator->fails()){

            return ApiResponse::sendResponse(422,'Login validation fails',$validator->messages()->all());
        }

        $doctor = Doctor::where('email', $request->email)->first();

        if (! $doctor || ! Hash::check($request->password, $doctor->password)) {
            return apiResponse::sendResponse(401," login failed",[]);

        }
        else{
            $data['token'] = $doctor->createToken('Doctor_Tokne')->plainTextToken;
            $data['name']  =  $doctor->name;
            $data['email']  =  $doctor->email;

            return apiResponse::sendResponse(201,' YOU LOGIN SUCCESSFULLY',$data);

        }


    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return apiResponse::sendResponse(200,"YOU LGOED OUT SUCCESSFULLY",null);
    }

    public function delete(Request $request){

        $request->user()->currentAccessToken()->delete();
        $user_id= $request->user()->id;
        $doctor_delete= Doctor::where('id',$user_id)->delete();

        if($doctor_delete)
        return apiResponse::sendResponse(200,"YOU DELETE ACCOUNT SUCCESSFULLY",null);
        return apiResponse::sendResponse(200," no",null);
    }
    public function update(Request $request ){

        $validator= Validator::make($request->all(),
        [
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique :'. Doctor::class],
            'password'=>['required','confirmed',Rules\Password::defaults()],
            'phone'=>['nullable','numeric','digits:11'],

        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Update validation fails',$validator->messages()->all());
        }
        else{

            $id= Auth::user()->id;

            if (Doctor::where('id', $id)->exists()) {

                Doctor::where('id',$id)->update([
                    "name"=>$request->name,
                    "email"=>$request->email,
                    "password"=>Hash::make($request->password),
                    "phone"=>$request->phone,


                    ]);
                    $doctor=   Doctor::findOrFail($id);
                    return apiResponse::sendResponse(201,"Doctor Updated Successfully", new DoctorResource($doctor));

            }
            else
                return apiResponse::sendResponse(201,"Doctor Dosen't exist",[]);


        }

    }
    public function view(){

        $doctor= Doctor::all();
        if($doctor)
        return ApiResponse::sendResponse(200,"These Are All Doctors",DoctorResource::collection($doctor));
        return ApiResponse::sendResponse(200,"These Are no Doctors",[]);

    }


}
