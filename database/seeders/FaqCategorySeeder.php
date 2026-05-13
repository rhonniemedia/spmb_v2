<?php

namespace Database\Seeders;

use App\Models\FaqCategories;
use Illuminate\Database\Seeder;

class FaqCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pendaftaran', 'slug' => 'pendaftaran', 'icon' => 'fa-user-plus', 'sort_order' => 1],
            ['name' => 'Biodata', 'slug' => 'biodata', 'icon' => 'fa-id-card', 'sort_order' => 2],
            ['name' => 'Seleksi', 'slug' => 'seleksi', 'icon' => 'fa-ranking-star', 'sort_order' => 3],
            ['name' => 'Daftar Ulang', 'slug' => 'daftarulang', 'icon' => 'fa-rotate-right', 'sort_order' => 4],
            ['name' => 'Pembayaran', 'slug' => 'pembayaran', 'icon' => 'fa-credit-card', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            FaqCategories::create($cat);
        }
    }
}
