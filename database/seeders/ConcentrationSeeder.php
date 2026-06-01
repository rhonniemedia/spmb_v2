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
                'name' => "Desain Pemodelan dan\nInformasi Bangunan",
                'alias' => 'DPIB',
                'code' => '01',
                'icon' => 'fa-compass-drafting',
                'description' => 'Mempelajari perencanaan, gambar desain bangunan, visualisasi 3D, serta perhitungan anggaran biaya konstruksi menggunakan teknologi BIM modern.',
                'tags' => ['Architecture', 'BIM Modeler'],
                'color' => 'cyan', // Khas warna arsitektur/teknik
                'quota' => 36,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Elektronika\nIndustri",
                'alias' => 'TEI',
                'code' => '02',
                'icon' => 'fa-microchip',
                'description' => 'Menguasai sistem kontrol otomatis elektronik, pemrogaman PLC, robotika industri, serta maintenance perangkat kontrol elektronika pabrik.',
                'tags' => ['Automation', 'Robotics'],
                'color' => 'emerald', // Hijau elektronik / mapan
                'quota' => 36,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Komputer\ndan Jaringan",
                'alias' => 'TKJ',
                'code' => '03',
                'icon' => 'fa-network-wired',
                'description' => 'Menguasai infrastruktur jaringan komputer, instalasi server, administrasi sistem linux/mikrotik, serta dasar keamanan siber industri.',
                'tags' => ['Network', 'Security'],
                'color' => 'blue', // Biru Cisco / Jaringan
                'quota' => 72,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Pembangkit\nTenaga Listrik",
                'alias' => 'TPTL',
                'code' => '04',
                'icon' => 'fa-charging-station',
                'description' => 'Mempelajari pengoperasian dan pemeliharaan komponen pembangkit energi listrik, termasuk pengelolaan generator dan sistem energi terbarukan.',
                'tags' => ['Power Plant', 'Energy Tech'],
                'color' => 'amber', // Kuning/Amber Energi listrik tinggi
                'quota' => 36,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Instalasi\nTenaga Listrik",
                'alias' => 'TITL',
                'code' => '05',
                'icon' => 'fa-bolt',
                'description' => 'Mempelajari pemasangan, pengoperasian, dan perbaikan instalasi penerangan gedung, instalasi tenaga, hingga panel kontrol motor listrik industri.',
                'tags' => ['Electrical', 'Control Panel'],
                'color' => 'yellow', // Kuning petir/listrik
                'quota' => 108,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Pemesinan",
                'alias' => 'TM',
                'code' => '06',
                'icon' => 'fa-gears',
                'description' => 'Menguasai teknik pengerjaan komponen logam menggunakan mesin bubut, frais, perkakas industri, hingga pemrograman mesin CNC modern.',
                'tags' => ['Machining', 'CNC Tech'],
                'color' => 'indigo', // Indigo/pabrik berat
                'quota' => 72,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Pengelasan",
                'alias' => 'TL',
                'code' => '07',
                'icon' => 'fa-fire-burner',
                'description' => 'Mempelajari teknik penyambungan logam menggunakan berbagai metode las industri standar internasional (SMAW, GMAW, GTAW) untuk manufaktur.',
                'tags' => ['Welding', 'Fabrication'],
                'color' => 'orange', // Orange percikan api/las
                'quota' => 36,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Kendaraan\nRingan",
                'alias' => 'TKR',
                'code' => '08',
                'icon' => 'fa-car',
                'description' => 'Mempelajari perawatan berkala, perbaikan mesin, sistem sasis, pemindah tenaga, dan kelistrikan mobil berbasis sistem EFI elektronik terbaru.',
                'tags' => ['Otomotif', 'EV Tech'],
                'color' => 'rose', // Merah mobil balap/sporty
                'quota' => 72,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Sepeda Motor",
                'alias' => 'TSM',
                'code' => '09',
                'icon' => 'fa-motorcycle',
                'description' => 'Mempelajari perawatan, perbaikan mesin injeksi, sistem kelistrikan, sasis, dan komponen mekanis sepeda motor matic maupun sport terkini.',
                'tags' => ['Motorcycle', 'Injection Tech'],
                'color' => 'red', // Merah racing
                'quota' => 72,
                'status' => 'active',
            ],
            [
                'name' => "Teknik Konstruksi\ndan Perumahan",
                'alias' => 'TKP',
                'code' => '10',
                'icon' => 'fa-building',
                'description' => 'Mempelajari pelaksanaan konstruksi bangunan perumahan, teknik beton bertulang, pemasangan dinding lantai, serta manajemen kerja area konstruksi.',
                'tags' => ['Civil Eng', 'Construction'],
                'color' => 'sky', // Cerah seperti langit konstruksi lapangan
                'quota' => 36,
                'status' => 'active',
            ],
        ];

        foreach ($concentrations as $data) {
            // Menggunakan updateOrCreate berdasarkan 'code' agar tidak duplikat saat seeder dijalankan ulang
            Concentration::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
