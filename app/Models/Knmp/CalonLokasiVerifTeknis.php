<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Model;

class CalonLokasiVerifTeknis extends Model 
{ 
    protected $connection = 'mysql_knmp'; 
    protected $table = 'calon_lokasi_verif_teknis'; 
    protected $guarded = []; 
    
    public function calonLokasi() { return $this->belongsTo(CalonLokasi::class); } 
}
