<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Region\Provinsi;
use App\Models\Region\Kabupaten;
use App\Models\Region\Kecamatan;
use App\Models\Region\Desa;

class Knmp extends Model
{
    use HasFactory;

    protected $connection = 'mysql_knmp';
    protected $table = 'knmp';

    protected $fillable = [
        'batch_id', 'tahap_saat_ini', 'nama', 'provinsi', 'kabupaten',
        'kecamatan', 'desa', 'latitude', 'longitude', 'status'
    ];

    public function tahapUsulan()
    {
        return $this->hasOne(TahapUsulan::class, 'knmp_id');
    }

    public function tahapSurvey()
    {
        return $this->hasOne(TahapSurvey::class, 'knmp_id');
    }

    public function tahapDed()
    {
        return $this->hasOne(TahapDed::class, 'knmp_id');
    }

    public function tahapLelang()
    {
        return $this->hasOne(TahapLelang::class, 'knmp_id');
    }

    public function konstruksiKnmp()
    {
        return $this->hasOne(KonstruksiKnmp::class, 'knmp_id');
    }

    public function tahapSerahTerima()
    {
        return $this->hasOne(TahapSerahTerima::class, 'knmp_id');
    }
}
