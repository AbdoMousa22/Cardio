<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
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
            'image'=>$this->image,
            'not_for_diabetes'=>$this->not_for_diabetes,
            'not_for_hypertension'=>$this->not_for_hypertension,
            'not_for_pregnant'=>$this->not_for_pregnant,
            'brand_name'=>$this->brand_name,
            'active_ingredient'=>$this->active_ingredient,
            'dosage'=>$this->dosage,
            'dosage_form'=>$this->dosage_form,
            'administration_route'=>$this->administration_route,
            'side_effects'=>$this->side_effects,
            'alternatives'=>$this->alternatives,
            'manufacturer'=>$this->manufacturer,
            'interaction'=>$this->interaction,
            'contraindications'=>$this->contraindications,
            'description'=>$this->description,

        ];
    }
}
