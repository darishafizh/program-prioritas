<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Model;

class CalonLokasiPengajuan extends Model 
{ 
    protected $connection = 'mysql_knmp'; 
    protected $table = 'calon_lokasi_pengajuan'; 
    protected $guarded = []; 
    
    public function calonLokasi() { return $this->belongsTo(CalonLokasi::class); } 
}
