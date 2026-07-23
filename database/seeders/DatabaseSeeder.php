<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CurriculumSeeder::class,
            CulturalContentSeeder::class,
            ExerciseSubmissionSeeder::class,
        ]);
    }
}
