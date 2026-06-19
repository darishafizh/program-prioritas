<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonLokasi extends Model 
{ 
    protected $connection = 'mysql_knmp'; 
    protected $table = 'calon_lokasi'; 
    protected $guarded = []; 
    
    public function user() { return $this->belongsTo(\App\Models\User::class, 'user_id', 'id'); }
    public function detail() { return $this->hasOne(CalonLokasiDetail::class); }
    public function knmp() { return $this->belongsTo(Knmp::class); } 
    public function pengajuan() { return $this->hasOne(CalonLokasiPengajuan::class); } 
    public function verifAdmin() { return $this->hasOne(CalonLokasiVerifAdmin::class); } 
    public function baAktivasi() { return $this->hasOne(CalonLokasiBaAktivasi::class); } 
    public function verifTeknis() { return $this->hasOne(CalonLokasiVerifTeknis::class); } 
    public function baCalon() { return $this->hasOne(CalonLokasiBaCalon::class); } 
    public function penetapan() { return $this->hasOne(CalonLokasiPenetapan::class); } 
}
