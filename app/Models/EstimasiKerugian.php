<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimasiKerugian extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_jaring',
        'target',
        'aktual',
        'produksi_hari'
    ];
}
