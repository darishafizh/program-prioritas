<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Model;

use App\Models\Knmp\Knmp;

class TahapLelang extends Model
{
    protected $connection = 'mysql_knmp';
    protected $table = 'tahap_lelang';
    protected $fillable = ['knmp_id', 'tanggal_penetapan', 'catatan'];

    public function knmp()
    {
        return $this->belongsTo(Knmp::class, 'knmp_id');
    }
}
