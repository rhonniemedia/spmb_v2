<?php

namespace Database\Seeders;

use App\Models\RegistrationData;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
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

        // RegistrationData::factory(5)->create();

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
