<?php

namespace App\Http\Controllers\Api;

use App\Helpers\apiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineResource;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as File;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{
    public function insert(Request $request){

        $validator= Validator::make($request->all(),
        [
            'name'=>['required'],
            'not_for_diabetes'=>['required'],
            'not_for_hypertension'=>['required'],
            'not_for_pregnant'=>['required'],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'active_ingredient'=>['required'],
            // 'dosage'=>['required'],
            // 'dosage_form'=>['required'],
            // 'administration_route'=>['required'],
            // 'side_effects'=>['required'],
            // 'alternatives'=>['required'],
            // 'interaction'=>['required'],
            // 'contraindications'=>['required'],
            // 'instructions'=>['required'],
        ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Insert validation fails',$validator->messages()->all());
        }
        else{
            if (Medicine::where('name', $request->name)->exists()) {
                // The name already exists
                return apiResponse::sendResponse(201,"'The Medicine already exists",[]);

            } else {
              //  The name does not exist, proceed with insertion
              if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('images'), $imageName);
            } else {
                $imageName = null;
            }

            $Medicine =  Medicine::create([
                    'name'=>$request->input('name'),
                    'image'=>$imageName,
                    'not_for_diabetes'=>$request->input('not_for_diabetes'),
                    'not_for_hypertension'=>$request->input('not_for_hypertension'),
                    'not_for_pregnant'=>$request->input('not_for_pregnant'),
                    'brand_name'=>$request->input('brand_name'),
                    'active_ingredient'=>$request->input('active_ingredient'),
                    'dosage'=>$request->input('dosage'),
                    'dosage_form'=>$request->input('dosage_form'),
                    'administration_route'=>$request->input('administration_route'),
                    'side_effects'=>$request->input('side_effects'),
                    'alternatives'=>$request->input('alternatives'),
                    'manufacturer'=>$request->input('manufacturer'),
                    'interaction'=>$request->input('interaction'),
                    'contraindications'=>$request->input('contraindications'),
                    'instructions'=>$request->input('instructions'),

                ]);
                return apiResponse::sendResponse(201,"Medicine Created Successfully",$Medicine);
            }


        }

    }
    public function update(Request $request,$medicine_id){


        $validator= Validator::make($request->all(),
            [
                'name'=>['required'],
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'not_for_diabetes'=>['required'],
                'not_for_hypertension'=>['required'],
                'not_for_pregnant'=>['required'],
            //     'brand_name'=>['nullable'],
            //     'active_ingredient'=>['required'],
            //     'dosage'=>['required'],
            //     'dosage_form'=>['required'],
            //     'administration_route'=>['required'],
            //     'side_effects'=>['required'],
            //     'alternatives'=>['required'],
            //     'the manufacturer'=>['nullable'],
            //     'interaction'=>['required'],
            //     'contraindications'=>['required'],
            //     'instructions'=>['required'],
            ]);
        if($validator->fails()){

            return apiResponse::sendResponse(422,'Update validation fails',$validator->messages()->all());
        }
        else{
            if (Medicine::where('id', $medicine_id)->exists()) {
                $medicine = Medicine::findOrFail($medicine_id);

                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($medicine->image) {
                        File::delete(public_path('images').'/'.$medicine->image);
                    }

                    $image = $request->file('image');
                    $imageName = time().'.'.$image->extension();
                    $image->move(public_path('images'), $imageName);
                    $medicine->image = $imageName;
                }
               // The name already exists
                   Medicine::where('id', $medicine_id)->update([
                        'name'=>$request->input('name'),
                        'not_for_diabetes'=>$request->input('not_for_diabetes'),
                        'not_for_hypertension'=>$request->input('not_for_hypertension'),
                        'not_for_pregnant'=>$request->input('not_for_pregnant'),
                        'brand_name'=>$request->input('brand_name'),
                        'active_ingredient'=>$request->input('active_ingredient'),
                        'dosage'=>$request->input('dosage'),
                        'dosage_form'=>$request->input('dosage_form'),
                        'administration_route'=>$request->input('administration_route'),
                        'side_effects'=>$request->input('side_effects'),
                        'alternatives'=>$request->input('alternatives'),
                        'manufacturer'=>$request->input('manufacturer'),
                        'interaction'=>$request->input('interaction'),
                        'contraindications'=>$request->input('contraindications'),
                        'instructions'=>$request->input('instructions'),

                    ]);
                    $medicines=Medicine::where('id',$medicine_id)->first();

                  return apiResponse::sendResponse(201,"Medicine Updated Successfully",new MedicineResource($medicines));

            } else
              return apiResponse::sendResponse(201,"Medicine Dosen't exist",[]);





        }

    }
    public function delete($medicine_id){

        if (Medicine::where('id', $medicine_id)->exists()) {

            $medicine = Medicine::findOrFail($medicine_id);

            if ($medicine->image) {
                File::delete(public_path('images').'/'.$medicine->image);
            }
            $medicine_delete=$medicine->delete();
            if($medicine_delete)
            return apiResponse::sendResponse(200,"Medicine Deleted successfully",[]);

        }

         return apiResponse::sendResponse(200,"Medicine Dosen't Exist",[]);

    }
    public function view(){
        $medicine=Medicine::all();
        // return $diagnosis;

        if($medicine)

        return apiResponse::sendResponse(200," Here You Are All Medicine",$medicine);
        return apiResponse::sendResponse(200," There are no Medicine",[]);



    }


}
