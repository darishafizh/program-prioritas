<?php

namespace App\Models\Bioflok;

use Illuminate\Database\Eloquent\Model;

class Kdmp extends Model
{
    protected $connection = 'mysql_bioflok';
    protected $table = 'kdmp';

    protected $fillable = [
        'provinsi', 'kabupaten', 'desa', 'nama_kdkmp', 'komoditas',
        'ketua_anggota', 'no_hp', 'nama_penyuluh', 'no_hp_penyuluh',
        'long', 'lat'
    ];

    public function monitoringProduksi()
    {
        return $this->hasMany(MonitoringProduksi::class, 'kdmp_id');
    }
}
