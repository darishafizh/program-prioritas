<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Model;

class TahapKonstruksi extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'tahap_konstruksi';
    protected $fillable = ['knmp_konstruksi_id', 'periode_mingguan', 'bobot_rencana_kumulatif', 'bobot_realisasi_kumulatif'];

    public function konstruksiKnmp()
    {
        return $this->belongsTo(KonstruksiKnmp::class, 'knmp_konstruksi_id');
    }
}
