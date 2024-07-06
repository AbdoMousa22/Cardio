<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable=[
        "doctor_id","diagnoses_id","name","gender","age","phone","address",
        "Diabetes","hypertension","pregnant"
    ];

    public function doctor(){
        return  $this->belongsTo(Doctor::class);
      }

    public function diagnoses(){
        return  $this->belongsTo(Diagnosis::class);
    }


}
