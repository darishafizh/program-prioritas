<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Region\Province;
use App\Models\Region\Regency;
use App\Models\Region\District;
use App\Models\Region\Village;

class SyncRegionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'region:sync {--level=all : Limit sync to a specific level (provinces, regencies, districts, villages)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch region data from an API and sync to local database';

    // Base URL menggunakan API wilayah.id sesuai dokumentasi
    private $baseUrl = 'https://wilayah.id/api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $level = $this->option('level');

        $this->info("Memulai sinkronisasi data wilayah dari wilayah.id...");

        if (in_array($level, ['all', 'provinces'])) {
            $this->syncProvinces();
        }
        
        if (in_array($level, ['all', 'regencies'])) {
            $this->syncRegencies();
        }
        
        if (in_array($level, ['all', 'districts'])) {
            $this->syncDistricts();
        }
        
        if (in_array($level, ['all', 'villages'])) {
            $this->syncVillages();
        }

        $this->info("Sinkronisasi selesai!");
    }

    private function syncProvinces()
    {
        $this->info("Mengambil data Provinsi...");
        $response = Http::get("{$this->baseUrl}/provinces.json");
        
        if ($response->successful()) {
            $provinces = $response->json('data');
            $bar = $this->output->createProgressBar(count($provinces));
            $bar->start();

            foreach ($provinces as $prov) {
                Province::updateOrCreate(
                    ['id' => $prov['code']],
                    ['name' => $prov['name']]
                );
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
            $this->info("Sinkronisasi Provinsi selesai.");
        } else {
            $this->error("Gagal mengambil data Provinsi.");
        }
    }

    private function syncRegencies()
    {
        $provinces = Province::all();
        $this->info("Mengambil data Kabupaten/Kota...");
        $bar = $this->output->createProgressBar(count($provinces));
        $bar->start();
        
        foreach ($provinces as $prov) {
            $response = Http::get("{$this->baseUrl}/regencies/{$prov->id}.json");
            
            if ($response->successful()) {
                $regencies = $response->json('data');
                foreach ($regencies as $reg) {
                    Regency::updateOrCreate(
                        ['id' => $reg['code']],
                        [
                            'province_id' => $prov->id,
                            'name' => $reg['name']
                        ]
                    );
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        $this->info("Sinkronisasi Kabupaten/Kota selesai.");
    }

    private function syncDistricts()
    {
        $regencies = Regency::all();
        $this->info("Mengambil data Kecamatan... (Mungkin memakan waktu lama)");
        $bar = $this->output->createProgressBar(count($regencies));
        $bar->start();
        
        foreach ($regencies as $reg) {
            $response = Http::get("{$this->baseUrl}/districts/{$reg->id}.json");
            
            if ($response->successful()) {
                $districts = $response->json('data');
                foreach ($districts as $dist) {
                    District::updateOrCreate(
                        ['id' => $dist['code']],
                        [
                            'regency_id' => $reg->id,
                            'name' => $dist['name']
                        ]
                    );
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        $this->info("Sinkronisasi Kecamatan selesai.");
    }

    private function syncVillages()
    {
        $districts = District::all();
        $this->info("Mengambil data Desa/Kelurahan... (Mungkin memakan waktu SANGAT LAMA)");
        $bar = $this->output->createProgressBar(count($districts));
        $bar->start();
        
        foreach ($districts as $dist) {
            $response = Http::get("{$this->baseUrl}/villages/{$dist->id}.json");
            
            if ($response->successful()) {
                $villages = $response->json('data');
                foreach ($villages as $vill) {
                    Village::updateOrCreate(
                        ['id' => $vill['code']],
                        [
                            'district_id' => $dist->id,
                            'name' => $vill['name']
                        ]
                    );
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        $this->info("Sinkronisasi Desa/Kelurahan selesai.");
    }
}
