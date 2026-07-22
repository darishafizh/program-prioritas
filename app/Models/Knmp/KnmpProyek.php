<?php

namespace App\Models\Knmp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnmpProyek extends Model
{
    use HasFactory;

    protected $connection = 'mysql_knmp';
    protected $table = 'knmp_proyek';

    protected $fillable = [
        'id_user', 'nama_lokasi', 'status_wilayah', 'koordinat', 'tahap_aktif',
        'dokumen_proposal', 'skala_proposal',
        'dokumen_verif_admin', 'skala_verif_admin',
        'dokumen_ba_aktivasi',
        'dokumen_verif_teknis', 'skala_verif_teknis',
        'dokumen_ba_calon', 'skala_ba_calon',
        'dokumen_penetapan',
        'konsultan_perencana', 'dokumen_ded', 'skala_ded',
        'kode_tender', 'nama_konstruksi', 'dokumen_lelang',
        'konstruktor', 'progres_fisik', 'dokumen_bast'
    ];
}
