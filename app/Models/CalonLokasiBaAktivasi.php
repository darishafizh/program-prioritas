<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonLokasiBaAktivasi extends Model 
{ 
    protected $connection = 'mysql_knmp'; 
    protected $table = 'calon_lokasi_ba_aktivasi'; 
    protected $guarded = []; 
    
    public function calonLokasi() { return $this->belongsTo(CalonLokasi::class); } 
}
