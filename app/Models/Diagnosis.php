<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;
    protected $fillable=[
        'name','reasons','symptoms',"treatments"
    ];


    public function patient(){
        return  $this->hasMany(Patient::class);
    }

    public function medicine(){
        return $this->belongsToMany(Medicine::class,"diagnoses_medicines");
    }



}
