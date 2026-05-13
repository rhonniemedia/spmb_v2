<?php

namespace Database\Seeders;

use App\Models\Concentration;
use Illuminate\Database\Seeder;

class ConcentrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $concentrations = [
            [
                'name' => 'Desain Pemodelan dan Informasi Bangunan',
                'alias' => 'DPIB',
                'code' => '01',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Elektronika Industri',
                'alias' => 'TEI',
                'code' => '02',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Komputer dan Jaringan',
                'alias' => 'TKJ',
                'code' => '03',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Instalasi Tenaga Listrik',
                'alias' => 'TITL',
                'code' => '05',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Pembangkit Tenaga Listrik',
                'alias' => 'TPTL',
                'code' => '04',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Pemesinan',
                'alias' => 'TM',
                'code' => '06',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Pengelasan',
                'alias' => 'TL',
                'code' => '07',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Sepeda Motor',
                'alias' => 'TSM',
                'code' => '09',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Kendaraan Ringan',
                'alias' => 'TKR',
                'code' => '08',
                'status' => 'active',
            ],
            [
                'name' => 'Teknik Konstruksi dan Perumahan',
                'alias' => 'TKP',
                'code' => '10',
                'status' => 'active',
            ],
        ];

        foreach ($concentrations as $data) {
            // Menggunakan updateOrCreate berdasarkan 'code' agar tidak duplikat saat seeder dijalankan ulang
            Concentration::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
