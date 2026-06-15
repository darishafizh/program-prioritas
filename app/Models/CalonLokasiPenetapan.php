<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonLokasiPenetapan extends Model 
{ 
    protected $connection = 'mysql_knmp'; 
    protected $table = 'calon_lokasi_penetapan'; 
    protected $guarded = []; 
    
    public function calonLokasi() { return $this->belongsTo(CalonLokasi::class); } 
}
