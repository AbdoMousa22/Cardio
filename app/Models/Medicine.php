<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable=[
        'name','image','not_for_diabetes','not_for_hypertension','not_for_pregnant','brand_name',
        'active_ingredient','dosage','dosage_form','administration_route',
        'side_effects','alternatives','manufacturer',
        'interaction','contraindications','instructions'
    ];

    public function diagnosis(){
        return $this->belongsToMany(Diagnosis::class,"diagnoses_medicines");
    }


}
