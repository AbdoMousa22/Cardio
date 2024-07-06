<?php

namespace App\Http\Controllers\Api;
use Carbon\Carbon;

use App\Helpers\apiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Diagnosis;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function insert(Request $request){

        $validator= Validator::make($request->all(),
        [
            'name'=>['required'],
            'gender'=>['required'],
            'age'=>['required'],
            'Diabetes'=>['required'],
            'hypertension'=>['required'],
            'pregnant'=>['required'],

        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Insert validation fails',$validator->messages()->all());
        }
        else{
            if (Patient::where('name', $request->name)->exists()) {
                return apiResponse::sendResponse(201,"'The Patient already exists",[]);

            } else {

                // $diagnosis_name=Diagnosis::select('id', 'name')->get();

                $doctor_id=Auth::user()->id;

            $patient =  Patient::create([
                    'doctor_id'=>$doctor_id,
                    'diagnoses_id'=>$request->input('diagnoses_id'),
                    'name'=>$request->input('name'),
                    'gender'=>$request->input('gender'),
                    'age'=>$request->input('age'),
                    'Diabetes'=>$request->input('Diabetes'),
                    'hypertension'=>$request->input('hypertension'),
                    'pregnant'=>$request->input('pregnant'),
                    'phone'=>$request->input('phone'),
                    'address'=>$request->input('address'),

                ]);
                return apiResponse::sendResponse(201,"Patient Added Successfully",new PatientResource($patient));


            }


        }

    }

    public function update(Request $request ,$patient_id){

        $validator= Validator::make($request->all(),
        [
            'name'=>['required'],
            'gender'=>['required'],
            'age'=>['required'],
            'diagnoses_id'=>['required'],
            'Diabetes'=>['required'],
            'hypertension'=>['required'],

        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Update validation fails',$validator->messages()->all());
        }
        else{

            if (Patient::where('id', $patient_id)->exists()) {

                  Patient::where('id',$patient_id)->update([
                    'diagnoses_id'=>$request->input('diagnoses_id'),
                    'name'=>$request->input('name'),
                    'gender'=>$request->input('gender'),
                    'age'=>$request->input('age'),
                    'phone'=>$request->input('phone'),
                    'address'=>$request->input('address'),
                    'Diabetes'=>$request->input('Diabetes'),
                    'hypertension'=>$request->input('hypertension'),


                    ]);

                    $patient=Patient::where('id',$patient_id)->first();

                    return apiResponse::sendResponse(201,"Patient Updated Successfully", new PatientResource($patient));

            }
            else
                return apiResponse::sendResponse(201,"Patient Dosen't exist",[]);


        }

    }

    public function delete($patient_id){
        if (Patient::where('id', $patient_id)->exists()) {


                    $patient=Patient::findOrFail($patient_id);
                    $patient_delete=$patient->delete();
                    if($patient_delete) return apiResponse::sendResponse(200,"Patient Deleted successfully",[]);
        }

         return apiResponse::sendResponse(200,"Patient Dosen't Exist",[]);




    }

    public function view(){
       $patient=Patient::all();


        if($patient)

        return apiResponse::sendResponse(200," Here You Are All Patient",PatientResource::collection($patient));
        return apiResponse::sendResponse(200," There are no Patient",[]);



    }

    public function day(){
        $last24Hours = Carbon::now()->subDay();
        $patientsLast24Hours = Patient::where('created_at', '>=', $last24Hours)->get();
        if($patientsLast24Hours)
            return apiResponse::sendResponse(200," Here You Are All Patient Add last 24 Hours",PatientResource::collection($patientsLast24Hours));
            return apiResponse::sendResponse(200,"There Are no Patient Add last 24 Hours",[]);

    }

    public function week(){
        $lastWeek = Carbon::now()->subWeek();
        $postsLastWeek = Patient::whereBetween('created_at', [$lastWeek, Carbon::now()])->get();
        if($postsLastWeek)
        return apiResponse::sendResponse(200," Here You Are All Patient Add last week",PatientResource::collection($postsLastWeek));
        return apiResponse::sendResponse(200,"There Are no Patient Add last week",[]);
    }

    public function month(){
        $patientsThisMonth = Patient::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->get();
                                        if($patientsThisMonth)
                                        return apiResponse::sendResponse(200," Here You Are All Patient Add last Month",PatientResource::collection($patientsThisMonth));
                                        return apiResponse::sendResponse(200,"There Are no Patient Add last Month",[]);
    }



}
