<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Model;

class CalonLokasiDetail extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'calon_lokasi_detail';
    protected $guarded = [];

    public function calonLokasi()
    {
        return $this->belongsTo(CalonLokasi::class, 'calon_lokasi_id');
    }
}
