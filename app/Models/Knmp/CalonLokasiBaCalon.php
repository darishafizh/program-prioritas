<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Model;

class CalonLokasiBaCalon extends Model 
{ 
    protected $connection = 'mysql_knmp'; 
    protected $table = 'calon_lokasi_ba_calon'; 
    protected $guarded = []; 
    
    public function calonLokasi() { return $this->belongsTo(CalonLokasi::class); } 
}
