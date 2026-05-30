<?php

namespace Database\Factories;

use App\Models\AdmissionPath;
use App\Models\Concentration;
use App\Models\PersonalData;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationDataFactory extends Factory
{
    public function definition(): array
    {
        $r1 = fake()->randomFloat(2, 75, 100);
        $r2 = fake()->randomFloat(2, 75, 100);
        $r3 = fake()->randomFloat(2, 75, 100);
        $r4 = fake()->randomFloat(2, 75, 100);
        $r5 = fake()->randomFloat(2, 75, 100);

        return [
            // MAGIC LARAVEL: Ini otomatis membuat 1 baris di tabel personal_data 
            // lalu mengambil ID-nya untuk dimasukkan ke tabel registration_data
            'personal_data_id' => PersonalData::factory(),

            'admission_path_id' => AdmissionPath::inRandomOrder()->first()->id ?? null,
            'registration_number' => 'REG-' . date('Y') . '-' . fake()->unique()->numerify('####'),

            'choice_1' => Concentration::inRandomOrder()->first()->id ?? null,
            'choice_2' => Concentration::inRandomOrder()->first()->id ?? null,
            'choice_3' => Concentration::inRandomOrder()->first()->id ?? null,

            // Kolom nilai rapor harus sesuai dengan migration Anda
            'report_sem_1' => $r1,
            'report_sem_2' => $r2,
            'report_sem_3' => $r3,
            'report_sem_4' => $r4,
            'report_sem_5' => $r5,
            'report_average' => ($r1 + $r2 + $r3 + $r4 + $r5) / 5,

            'tka_math' => fake()->randomFloat(2, 40, 100),
            'tka_indonesian' => fake()->randomFloat(2, 40, 100),
            'tka_average' => fake()->randomFloat(2, 40, 100),

            'verification_status' => fake()->randomElement(['pending', 'verified']),
            'submitted_at' => now(),
        ];
    }
}
