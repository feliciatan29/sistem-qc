<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QlfSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'biaya_kerugian',
        'batas_toleransi',
        'hari_produksi'
    ];
}
