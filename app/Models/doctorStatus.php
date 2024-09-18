<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctorStatus extends Model
{
    use HasFactory;

    protected $table = 'dokter_slot';

    public function doctor() {
        return $this->belongsTo(doctor::class, 'kddokter', 'kode');
    }
}
