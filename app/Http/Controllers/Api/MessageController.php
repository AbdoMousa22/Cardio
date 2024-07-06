<?php

namespace App\Http\Controllers\Api;

use App\Helpers\apiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function insert(Request $request){

        $validator= Validator::make($request->all(),
         [

             'message'=>['required'],
         ]);
         if($validator->fails()){

             return apiResponse::sendResponse(422,'Contact validation fails',$validator->messages()->all());
         }
         else{

            $id=Auth::user()->id;
            $name=Auth::user()->name;
            $email=Auth::user()->email;

            $message=  Message::create([
                   "doctor_id"=>$id,
                   "name"=>$name,
                   "email"=>$email,
                   "message"=>$request->message,
               ]);


               return apiResponse::sendResponse(201,"Message has been sent Successfully",$message);

        }
    }

    public function delete($id){

       $delete_message= Message::where("id",$id)->delete();
       if($delete_message)
       return apiResponse::sendResponse(200,'Message Deleted Successfully',[]);
       return apiResponse::sendResponse(200,'Message Dose not exist',[]);

    }

    public function view(){

        $message= Message::all();
        if($message)
        return ApiResponse::sendResponse(200,"These Are All Message",MessageResource::collection($message));
        return ApiResponse::sendResponse(200,"These Are no Message",[]);

    }

    
}
