<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name"=>$request->name,
            "reasons"=>$request->reasons,
            "symptoms"=>$request->symptoms,
            "treatments"=>$request->treatments,
        ];
    }
}
