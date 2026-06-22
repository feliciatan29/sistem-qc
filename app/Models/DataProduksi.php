<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProduksi extends Model
{
    use HasFactory;

    protected $table = 'tbl_dataproduksi';

    protected $fillable = [
        'jenis_jaring',
        'bulan_produksi',
        'jumlah_pesanan',
        'status'
    ];
}
