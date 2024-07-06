<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosesMedicine extends Model
{
    use HasFactory;
    public function diagnosis()
{
    return $this->belongsTo(Diagnosis::class);
}

public function medicine()
{
    return $this->belongsTo(Medicine::class);
}
}
