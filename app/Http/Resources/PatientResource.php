<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'name'=>$this->name,
            'gender'=>$this->gender,
            'age'=>$this->age,
            'diagnoses'=>$this->diagnoses,
            'Diabetes'=>$this->Diabetes,
            'pregnant'=>$this->pregnant,
            'hypertension'=>$this->hypertension,
            'phone'=>$this->phone,
            'address'=>$this->address,

        ];
    }
}
