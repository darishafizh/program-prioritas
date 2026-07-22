<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilKnmp extends Model
{
    use HasFactory;

    protected $connection = 'mysql_knmp';
    protected $table = 'profil_knmp';

    protected $fillable = [
        'knmp_id',
        'jml_kk',
        'jml_nelayan',
        'jml_kapal',
        'prod_total_desa',
        'ukuran_perahu_dominan',
        'alat_tangkap_dominan',
        'komoditas_utama',
        'pend_nelayan',
        'prod_per_trip_per_kapal',
        'jml_trip_per_bulan',
        'prod_kapal',
        'prod_total_kapal',
    ];

    public function knmp()
    {
        return $this->belongsTo(Knmp::class, 'knmp_id', 'id');
    }
}
