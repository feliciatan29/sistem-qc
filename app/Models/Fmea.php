<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fmea extends Model
{
    use HasFactory;

    protected $table = 'tbl_fmea';

    protected $fillable = [
        'jenis_jaring',
        'kategori_defect',
        'severity',
        'occurrence',
        'detection',
    ];
}
