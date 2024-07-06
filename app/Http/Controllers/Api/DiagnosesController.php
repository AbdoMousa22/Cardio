<?php

namespace App\Http\Controllers\Api;

use App\Helpers\apiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnosesResource;
use App\Http\Resources\MedicineResource;
use App\Models\Diagnosis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DiagnosesController extends Controller
{
    public function insert(Request $request){

        $validator= Validator::make($request->all(),
        [
            'name'=>['required'],


        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Insert validation fails',$validator->messages()->all());
        }
        else{
            if (Diagnosis::where('name', $request->name)->exists()) {
                // The name already exists
                return apiResponse::sendResponse(201,"'The Diagnosis already exists",[]);

            } else {
                // The name does not exist, proceed with insertion

            $diagnosis =  Diagnosis::create([
                    'name'=>$request->input('name'),
                    'symptoms'=>$request->input('symptoms'),
                    'reasons'=>$request->input('reasons'),
                    'treatments'=>$request->input('treatments'),

                ]);
                return apiResponse::sendResponse(201,"Diagnosis Created Successfully",new DiagnosesResource($diagnosis));
            }


        }

    }

    public function update(Request $request ,$diagnosis_id){

        $validator= Validator::make($request->all(),
        [
            'name'=>['required'],


        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Update validation fails',$validator->messages()->all());
        }
        else{

            if (Diagnosis::where('id', $diagnosis_id)->exists()) {

                $diagnosis =  Diagnosis::where('id',$diagnosis_id)->update([
                        'name'=>$request->input('name'),
                        'symptoms'=>$request->input('symptoms'),
                        'reasons'=>$request->input('reasons'),


                    ]);
                    return apiResponse::sendResponse(201,"Diagnosis Updated Successfully",new DiagnosesResource($diagnosis));

            }
            else
                return apiResponse::sendResponse(201,"Diagnosis Dosen't exist",[]);


        }

    }

    public function delete($diagnosis_id){
        if (Diagnosis::where('id', $diagnosis_id)->exists()) {


                    $diagnosis=Diagnosis::findOrFail($diagnosis_id);
                    $diagnosis_delete=$diagnosis->delete();
                    if($diagnosis_delete) return apiResponse::sendResponse(200,"Diagnosis Deleted successfully",[]);
        }

         return apiResponse::sendResponse(200,"Diagnosis Dosen't Exist",[]);




    }

    public function view(){

        $diagnosis=Diagnosis::all();
        if($diagnosis)

        return apiResponse::sendResponse(200," Here You Are All Diagnosis",$diagnosis);
        return apiResponse::sendResponse(200," There are no Diagnosis",[]);



    }

    public function search(Request $request){
        $validator= Validator::make($request->all(),
        [
            'diagnosis'=>['required'],
            'diabetes'=>['required','boolean'],
            'hypertension'=>['required','boolean'],
            'pregnant'=>['required','boolean'],
        ]);
        if($validator->fails()){

            return ApiResponse::sendResponse(422,'Search validation fails',$validator->messages()->all());
        }

        $diagnosisName = $request->input('diagnosis');
        $hasDiabetes = $request->input('diabetes', false);
        $hasHypertension = $request->input('hypertension', false);
        $isPregnant = $request->input('pregnant', false);

        $diagnosis = Diagnosis::where('name',$diagnosisName)->first();


        if (!$diagnosis)
            return response()->json(['message' => 'Diagnosis not found'], 404);


        $medicines = $diagnosis->medicine()->get();

        // Filter medicines based on conditions
        $filteredMedicines = $medicines->filter(function ($medicine) use ($hasDiabetes, $hasHypertension, $isPregnant) {
            if ($hasDiabetes && $medicine->not_for_diabetes) {
                return false;
            }
            if ($hasHypertension && $medicine->not_for_hypertension) {
                return false;
            }
            if ($isPregnant && $medicine->not_for_pregnant) {
                return false;
            }
            return true;
        });

        return response()->json($filteredMedicines);


    }
}
