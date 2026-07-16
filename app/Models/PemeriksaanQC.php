<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanQC extends Model
{
    use HasFactory;

    protected $table = 'tbl_pemeriksaanqc';

    protected $fillable = [
        'id_produksi',
        'jenis_jaring',
        'bulan_produksi',
        'jumlah_pesanan',
        'jumlah_cek',
        'baik',
        'rr',
        'pr',
        'rps',
        'super',
        'rj',
        'berbulu',
        'rusak_blok',
        'total_defect',
        'keterangan',
    ];

    public function produksi()
    {
        return $this->belongsTo(DataProduksi::class, 'id_produksi', 'id');
    }

    /**
     * Menghitung persentase reject sesuai rumus Excel: 
     * % Reject = ((Cek Pcs - Baik) / Cek Pcs) * 100
     */
    public function getPersentaseRejectAttribute()
    {
        if ($this->jumlah_cek > 0) {
            return (($this->jumlah_cek - $this->baik) / $this->jumlah_cek) * 100;
        }
        return 0;
    }
}
