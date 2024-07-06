<?php

namespace App\Http\Controllers\Api;

use App\Helpers\apiResponse;
use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosesMedicineController extends Controller
{
    public function insert(Request $request)
    {
        $request->validate([
            'diagnosis_id' => 'required|exists:diagnoses,id',
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        $diagnosis = Diagnosis::find($request->diagnosis_id);
        $diagnosis->medicine()->attach($request->medicine_id);

                return apiResponse::sendResponse(201," Created Successfully",[]);
    }

    public function view()
    {
        // Fetch all diagnosis-medicine relationships
        $relationships = Diagnosis::with('medicine')->get();
        return apiResponse::sendResponse(200," Here You Are All Diagnosis",$relationships);

    }

    public function delete($diagnosis_id, $medicine_id)
    {
        $diagnosis = Diagnosis::find($diagnosis_id);
        $diagnosis->medicine()->detach($medicine_id);
        return apiResponse::sendResponse(200," Deleted Successfully",[]);
        
    }


}
