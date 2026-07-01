<?php

namespace App\Models\Bioflok;

use Illuminate\Database\Eloquent\Model;

class MonitoringProduksi extends Model
{
    protected $connection = 'mysql_bioflok';
    protected $table = 'monitoring_produksi';

    protected $fillable = [
        'kdmp_id', 'user_id', 'tanggal', 'status_lokasi',
        'volume_panen_kg', 'tujuan_pasar', 'dokumentasi',
        'nilai_produksi', 'biaya_pakan', 'biaya_bibit',
        'biaya_lainnya', 'biaya_operasional',
        'jumlah_pembudidaya_aktif', 'survival_rate', 'fcr',
        'jumlah_kolam_aktif', 'jumlah_kolam_total',
        'kendala', 'tindak_lanjut', 'catatan'
    ];

    protected $casts = [
        'volume_panen_kg' => 'decimal:2',
        'nilai_produksi' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function kdmp()
    {
        return $this->belongsTo(Kdmp::class, 'kdmp_id');
    }
}
