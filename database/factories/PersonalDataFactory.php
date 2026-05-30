<?php

namespace Database\Factories;

use App\Models\AdmissionPath;
use App\Models\Concentration;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class PersonalDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genderCode = fake()->randomElement(['L', 'P']);
        $fakerGender = $genderCode === 'L' ? 'male' : 'female';

        return [
            // Identitas Inti
            'full_name' => fake()->name($fakerGender),
            'nick_name' => fake()->firstName($fakerGender),
            'gender' => $genderCode,

            // MAGIC LARAVEL: Cukup gunakan nama virtual/aslinya. 
            // Mutator setNisnAttribute dan setPhoneNumberAttribute di Model akan otomatis 
            // mengenkripsi dan menghash nilai ini sebelum masuk ke database.
            'nisn' => fake()->unique()->numerify('##########'),
            'nik' => fake()->unique()->numerify('16##############'), // 16 Digit NIK
            'phone_number' => fake()->numerify('08##########'),

            // Alamat (opsional, karena nullable di database)
            'address' => fake()->streetAddress(),
            'village' => fake()->citySuffix(),
            'district' => fake()->city(),

            // Pendidikan Sebelumnya
            'previous_school' => fake()->randomElement(['SMPN ', 'SMP IT ', 'MTsN ', 'MTs ']) . fake()->numberBetween(1, 10) . ' ' . fake()->city(),

            'profile_status' => 'final',
        ];
    }
}
