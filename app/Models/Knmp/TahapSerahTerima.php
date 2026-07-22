<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Model;

use App\Models\Knmp\Knmp;

class TahapSerahTerima extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'tahap_serah_terima';
    protected $fillable = ['knmp_id', 'nomor_kontrak', 'tanggal_serah', 'catatan'];

    public function knmp()
    {
        return $this->belongsTo(Knmp::class, 'knmp_id');
    }
}
