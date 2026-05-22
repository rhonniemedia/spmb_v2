<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdmissionPathSeeder::class,
            AnnouncementSeeder::class,
            FaqCategorySeeder::class,
            FaqSeeder::class,
            ConcentrationSeeder::class,
            SchoolSeeder::class,
            SpmbStepSeeder::class,
            RequirementSeeder::class,
            // Seeder lainnya...
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
