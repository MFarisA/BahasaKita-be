<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Course;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\Exercise;
use App\Models\CulturalContent;
use App\Models\User; // Diperlukan jika ExerciseSubmissionSeeder diaktifkan
use App\Models\Profile; // Diperlukan jika ExerciseSubmissionSeeder terkait XP
use App\Models\ExerciseSubmission; // Opsional

class KontenSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Memulai proses seeding...');

        // --- Language Seeder ---
        $this->command->info('Seeding Languages...');
        $languagesData = [
            ['name' => 'Bahasa Jawa'],
            ['name' => 'Bahasa Sunda'],
            ['name' => 'Bahasa Bali'],
            ['name' => 'Bahasa Minangkabau'],
            ['name' => 'Bahasa Batak Toba'],
            ['name' => 'Bahasa Bugis'],
        ];
        foreach ($languagesData as $lang) {
            Language::firstOrCreate(['name' => $lang['name']]);
        }
        $this->command->info(count($languagesData) . ' bahasa telah diproses.');

        // --- Course Seeder ---
        $this->command->info('Seeding Courses...');
        $jawa = Language::where('name', 'Bahasa Jawa')->first();
        $sunda = Language::where('name', 'Bahasa Sunda')->first();
        $bali = Language::where('name', 'Bahasa Bali')->first();

        $coursesCreated = 0;
        if ($jawa) {
            Course::firstOrCreate(['language_id' => $jawa->id, 'title' => 'Dasar-Dasar Krama Alus (Bahasa Jawa)']);
            Course::firstOrCreate(['language_id' => $jawa->id, 'title' => 'Percakapan Sehari-hari Ngoko (Bahasa Jawa)']);
            $coursesCreated += 2;
        }
        if ($sunda) {
            Course::firstOrCreate(['language_id' => $sunda->id, 'title' => 'Mengenal Aksara Sunda Baku']);
            Course::firstOrCreate(['language_id' => $sunda->id, 'title' => 'Ungkapan Umum Bahasa Sunda Loma']);
            $coursesCreated += 2;
        }
        if ($bali) {
            Course::firstOrCreate(['language_id' => $bali->id, 'title' => 'Sor Singgih Basa Bali']);
            $coursesCreated++;
        }
        $this->command->info($coursesCreated . ' kursus awal telah diproses.');

        // --- Unit Seeder ---
        $this->command->info('Seeding Units...');
        $kursusJawaDasar = Course::where('title', 'Dasar-Dasar Krama Alus (Bahasa Jawa)')->first();
        $kursusSundaAksara = Course::where('title', 'Mengenal Aksara Sunda Baku')->first();

        $unitsCreated = 0;
        if ($kursusJawaDasar) {
            Unit::firstOrCreate(['course_id' => $kursusJawaDasar->id, 'title' => 'Unit 1: Perkenalan dan Salam (Krama Alus)', 'order' => 1]);
            Unit::firstOrCreate(['course_id' => $kursusJawaDasar->id, 'title' => 'Unit 2: Keluarga dan Kerabat (Krama Alus)', 'order' => 2]);
            Unit::firstOrCreate(['course_id' => $kursusJawaDasar->id, 'title' => 'Unit 3: Kata Ganti Orang (Krama Alus)', 'order' => 3]);
            $unitsCreated += 3;
        }
        if ($kursusSundaAksara) {
            Unit::firstOrCreate(['course_id' => $kursusSundaAksara->id, 'title' => 'Unit 1: Ngalagena (Konsonan Dasar)', 'order' => 1]);
            Unit::firstOrCreate(['course_id' => $kursusSundaAksara->id, 'title' => 'Unit 2: Swaralagena (Vokal)', 'order' => 2]);
            Unit::firstOrCreate(['course_id' => $kursusSundaAksara->id, 'title' => 'Unit 3: Rarangken (Tanda Vokalisasi)', 'order' => 3]);
            $unitsCreated += 3;
        }
        $this->command->info($unitsCreated . ' unit awal telah diproses.');

        // --- SubUnit Seeder ---
        $this->command->info('Seeding SubUnits...');
        $unitJawaSalam = Unit::where('title', 'Unit 1: Perkenalan dan Salam (Krama Alus)')->first();
        $unitSundaNgalagena = Unit::where('title', 'Unit 1: Ngalagena (Konsonan Dasar)')->first();

        $subUnitsCreated = 0;
        if ($unitJawaSalam) {
            SubUnit::firstOrCreate(['unit_id' => $unitJawaSalam->id, 'title' => 'Sub-Unit 1.1: Mengucapkan Salam Pembuka', 'order' => 1]);
            SubUnit::firstOrCreate(['unit_id' => $unitJawaSalam->id, 'title' => 'Sub-Unit 1.2: Memperkenalkan Diri Sendiri', 'order' => 2]);
            SubUnit::firstOrCreate(['unit_id' => $unitJawaSalam->id, 'title' => 'Sub-Unit 1.3: Menanyakan Kabar', 'order' => 3]);
            $subUnitsCreated += 3;
        }
        if ($unitSundaNgalagena) {
            SubUnit::firstOrCreate(['unit_id' => $unitSundaNgalagena->id, 'title' => 'Sub-Unit 1.1: Huruf Ka, Ga, Nga', 'order' => 1]);
            SubUnit::firstOrCreate(['unit_id' => $unitSundaNgalagena->id, 'title' => 'Sub-Unit 1.2: Huruf Ca, Ja, Nya', 'order' => 2]);
            $subUnitsCreated += 2;
        }
        $this->command->info($subUnitsCreated . ' sub-unit awal telah diproses.');

        // --- Exercise Seeder ---
        $this->command->info('Seeding Exercises...');
        $subUnitJawaSalamPembuka = SubUnit::where('title', 'Sub-Unit 1.1: Mengucapkan Salam Pembuka')->first();
        $subUnitSundaKaGaNga = SubUnit::where('title', 'Sub-Unit 1.1: Huruf Ka, Ga, Nga')->first();

        $exercisesCreated = 0;
        if ($subUnitJawaSalamPembuka) {
            Exercise::firstOrCreate(
                ['sub_unit_id' => $subUnitJawaSalamPembuka->id, 'content' => json_encode(['question' => 'Bagaimana mengucapkan "Selamat Pagi" dalam Krama Alus?', 'options' => ['A' => 'Sugeng sonten', 'B' => 'Sugeng enjing', 'C' => 'Sugeng siyang', 'D' => 'Sugeng ndalu']])],
                [
                    'type' => 'multiple_choice',
                    'xp' => 10, // Pastikan tipe data 'xp' di migrasi adalah integer
                    'answer' => json_encode(['correct_option' => 'B']),
                ]
            );
            Exercise::firstOrCreate(
                ['sub_unit_id' => $subUnitJawaSalamPembuka->id, 'content' => json_encode(['question' => 'Arti dari "Sugeng rawuh" adalah...', 'options' => ['A' => 'Selamat jalan', 'B' => 'Selamat tidur', 'C' => 'Selamat datang', 'D' => 'Selamat makan']])],
                [
                    'type' => 'multiple_choice',
                    'xp' => 10,
                    'answer' => json_encode(['correct_option' => 'C']),
                ]
            );
            $exercisesCreated += 2;
        }

        if ($subUnitSundaKaGaNga) {
            Exercise::firstOrCreate(
                ['sub_unit_id' => $subUnitSundaKaGaNga->id, 'content' => json_encode(['question' => 'Aksara Sunda untuk "Ka" adalah...', 'options' => ['A' => 'ᮊ', 'B' => 'ᮌ', 'C' => 'ᮍ', 'D' => 'ᮎ']])],
                [
                    'type' => 'multiple_choice',
                    'xp' => 15,
                    'answer' => json_encode(['correct_option' => 'A']),
                ]
            );
            $exercisesCreated++;
        }
        $this->command->info($exercisesCreated . ' latihan awal telah diproses.');

        // --- CulturalContent Seeder ---
        $this->command->info('Seeding Cultural Contents...');
        CulturalContent::firstOrCreate(
            ['type' => 'story', 'language' => 'Bahasa Jawa', 'title' => 'Legenda Rawa Pening'],
            [
                'excerpt' => 'Kisah tentang Baru Klinting, seekor naga yang menjelma menjadi bocah sakti.',
                'image_url' => 'images/cultural/rawa_pening.jpg',
                'full_content' => 'Alkisah, di sebuah desa di lereng Gunung Telomoyo...',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
        CulturalContent::firstOrCreate(
            ['type' => 'proverb', 'language' => 'Bahasa Sunda', 'text' => 'Cikaracak ninggang batu laun-laun jadi legok'],
            [
                'translation' => 'Air menetes di batu, lama-lama akan berlubang juga.',
                'explanation' => 'Pepatah ini mengajarkan tentang ketekunan dan kesabaran. Usaha yang terus menerus meskipun kecil, pada akhirnya akan membuahkan hasil.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
        CulturalContent::firstOrCreate(
            ['type' => 'trivia', 'language' => 'BahasaKita', 'category' => 'Alat Musik Tradisional'],
            [
                'fact' => 'Gamelan adalah ansambel musik yang menonjolkan metalofon, gambang, gendang, dan gong. Istilah gamelan merujuk pada instrumennya / alatnya, yang mana merupakan satu kesatuan utuh yang diwujudkan dan dibunyikan bersama.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
        $this->command->info('3 konten budaya awal telah diproses.');

        // --- ExerciseSubmission Seeder (Opsional, jika ada user yang bisa di-assign) ---
        // $this->command->info('Seeding Exercise Submissions (Opsional)...');
        // $userContoh = User::where('email', 'pengguna@contoh.com')->first(); // Ganti dengan email user yang sudah ada
        // $exerciseContoh = Exercise::first(); // Ambil exercise pertama sebagai contoh

        // if ($userContoh && $exerciseContoh) {
        //     $exerciseContent = json_decode($exerciseContoh->content, true);
        //     $correctAnswer = json_decode($exerciseContoh->answer, true);
        //     $selectedOption = $correctAnswer['correct_option'] ?? array_key_first($exerciseContent['options']); // Default ke opsi pertama jika tidak ada correct_option

        //     ExerciseSubmission::firstOrCreate(
        //         ['user_id' => $userContoh->id, 'exercise_id' => $exerciseContoh->id],
        //         [
        //             'submitted_answer' => json_encode(['selected_option' => $selectedOption]),
        //             'is_correct' => (isset($correctAnswer['correct_option']) && $selectedOption == $correctAnswer['correct_option']),
        //         ]
        //     );
        //     $this->command->info('1 data pengumpulan latihan awal telah diproses.');
        // } else {
        //     $this->command->warn('User atau Exercise contoh tidak ditemukan untuk ExerciseSubmissionSeeder.');
        // }

        $this->command->info('Proses seeding selesai.');
    }
}
