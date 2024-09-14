<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctorStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctorName', 'unit', 'absentStatus'
    ];
}
