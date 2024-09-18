<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
    use HasFactory;

    protected $table = 'dokter_tmp';

    public function doctorStatus() {
        return $this->hasMany(doctorStatus::class, 'kddokter', 'kode');
    }
}
