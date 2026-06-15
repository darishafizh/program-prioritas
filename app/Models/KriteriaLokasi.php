<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaLokasi extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'kriteria_lokasi';

    protected $fillable = [
        'nama_kriteria',
        'bobot',
        'keterangan'
    ];
}
