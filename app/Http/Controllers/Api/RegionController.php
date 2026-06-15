<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region\Province;
use App\Models\Region\Regency;
use App\Models\Region\District;
use App\Models\Region\Village;

class RegionController extends Controller
{
    public function provinces()
    {
        return response()->json(Province::orderBy('name')->get());
    }

    public function regencies($provinceId)
    {
        return response()->json(Regency::where('province_id', $provinceId)->orderBy('name')->get());
    }

    public function districts($regencyId)
    {
        return response()->json(District::where('regency_id', $regencyId)->orderBy('name')->get());
    }

    public function villages($districtId)
    {
        $villages = Village::where('district_id', $districtId)->orderBy('name')->get();

        // Jika data desa belum ada di database lokal, lakukan penarikan On-The-Fly (Lazy Loading)
        if ($villages->isEmpty()) {
            $response = \Illuminate\Support\Facades\Http::get("https://wilayah.id/api/villages/{$districtId}.json");
            
            if ($response->successful()) {
                $data = $response->json('data');
                foreach ($data as $item) {
                    Village::updateOrCreate(
                        ['id' => $item['code']],
                        [
                            'district_id' => $districtId,
                            'name' => $item['name']
                        ]
                    );
                }
                // Ambil ulang dari database agar urut
                $villages = Village::where('district_id', $districtId)->orderBy('name')->get();
            }
        }

        return response()->json($villages);
    }
}
