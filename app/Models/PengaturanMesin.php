<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanMesin extends Model
{
    use HasFactory;
    protected $table = 'tbl_pengaturan';

    protected $fillable = [
        'kode_mesin',
        'jenis_jaring',
        'ukuran_jaring',
        'MD_jaring',
        'RPM_jaring',
        'status'
    ];
}
