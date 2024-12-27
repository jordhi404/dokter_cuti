<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class libur extends Model
{
    use HasFactory;

    protected $connection = 'mysql_libur';
    protected $table = 'libur';
    protected $fillable = ['tanggal', 'libur'];
}
