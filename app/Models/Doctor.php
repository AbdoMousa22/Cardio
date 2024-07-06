<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $fillable=[
        'name','email','password','phone','google_id'
    ];

    public function patient(){
           return $this->hasMany(Patient::class);
        }

        public function message()
        {
            return $this->hasOne(Message::class);
        }




}
