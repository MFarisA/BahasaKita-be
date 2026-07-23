<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Course;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\Exercise;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------
        // 1. BAHASA JAWA
        // ----------------------------------------------------
        $jawa = Language::updateOrCreate(['name' => 'Bahasa Jawa']);

        // Course 1: Pemula
        $jawaPemula = Course::updateOrCreate([
            'language_id' => $jawa->id,
            'title' => 'Bahasa Jawa untuk Pemula',
        ]);

        // Units for Pemula
        $jawaUnit1 = Unit::updateOrCreate([
            'course_id' => $jawaPemula->id,
            'order' => 1,
        ], [
            'title' => 'Level 1: Dasar & Salam',
        ]);

        $jawaUnit2 = Unit::updateOrCreate([
            'course_id' => $jawaPemula->id,
            'order' => 2,
        ], [
            'title' => 'Level 2: Angka & Kosa Kata',
        ]);

        // SubUnits for Jawa Unit 1
        $jawaSub1_1 = SubUnit::updateOrCreate([
            'unit_id' => $jawaUnit1->id,
            'order' => 1,
        ], [
            'title' => 'Salam & Menyapa (Atur Pambagya)',
        ]);

        $jawaSub1_2 = SubUnit::updateOrCreate([
            'unit_id' => $jawaUnit1->id,
            'order' => 2,
        ], [
            'title' => 'Perkenalan Diri (Tetepungan)',
        ]);

        // SubUnits for Jawa Unit 2
        $jawaSub2_1 = SubUnit::updateOrCreate([
            'unit_id' => $jawaUnit2->id,
            'order' => 1,
        ], [
            'title' => 'Angka & Etung-etungan',
        ]);

        // Exercises for Jawa SubUnit 1.1
        $this->createExercise($jawaSub1_1->id, 'Apa bahasa Jawa dari "Selamat Pagi"?', [
            'Sugeng enjang',
            'Sugeng siang',
            'Sugeng sonten',
            'Sugeng dalu',
        ], 0, 10);

        $this->createExercise($jawaSub1_1->id, 'Apa arti dari "Sugeng rawuh"?', [
            'Selamat tinggal',
            'Selamat datang',
            'Selamat malam',
            'Selamat makan',
        ], 1, 10);

        $this->createExercise($jawaSub1_1->id, 'Bagaimana mengucapkan "Terima kasih" dalam bahasa Jawa Ngoko?', [
            'Matur nuwun',
            'Matur suwun',
            'Kulo nuwun',
            'Sami-sami',
        ], 0, 15);

        // Exercises for Jawa SubUnit 1.2
        $this->createExercise($jawaSub1_2->id, 'Kata "Saya" dalam bahasa Jawa Krama adalah...', [
            'Aku',
            'Kula',
            'Sampeyan',
            'Panjenengan',
        ], 1, 10);

        $this->createExercise($jawaSub1_2->id, 'Kalimat "Nama saya Budi" dalam bahasa Jawa yang benar adalah...', [
            'Jenengku Budi',
            'Asma kula Budi',
            'Kula Budi jeneng',
            'Budi jenenge kula',
        ], 1, 15);

        // Exercises for Jawa SubUnit 2.1
        $this->createExercise($jawaSub2_1->id, 'Angka "Satu" dalam bahasa Jawa Krama adalah...', [
            'Siji',
            'Setunggal',
            'Kalih',
            'Tiga',
        ], 1, 10);

        $this->createExercise($jawaSub2_1->id, 'Berapakah "Lima" ditambah "Loro" dalam bahasa Jawa?', [
            'Pitu',
            'Wolu',
            'Sanga',
            'Sewelas',
        ], 0, 15);


        // Course 2: Menengah (Jawa)
        $jawaMenengah = Course::updateOrCreate([
            'language_id' => $jawa->id,
            'title' => 'Bahasa Jawa Menengah',
        ]);

        $jawaIntUnit1 = Unit::updateOrCreate([
            'course_id' => $jawaMenengah->id,
            'order' => 1,
        ], [
            'title' => 'Level 1: Undha-Usuk Basa',
        ]);

        $jawaIntSub1 = SubUnit::updateOrCreate([
            'unit_id' => $jawaIntUnit1->id,
            'order' => 1,
        ], [
            'title' => 'Tembung Ngoko & Krama Inggil',
        ]);

        $this->createExercise($jawaIntSub1->id, 'Tembung Krama Inggil saka tembung "Mangan" yaiku...', [
            'Ndedhar',
            'Dahar',
            'Mangan',
            'Neda',
        ], 1, 20);

        $this->createExercise($jawaIntSub1->id, 'Tembung Krama Inggil saka "Turu" yaiku...', [
            'Sarean',
            'Turu',
            'Tilem',
            'Mulek',
        ], 0, 20);

        // ----------------------------------------------------
        // 2. BAHASA SUNDA
        // ----------------------------------------------------
        $sunda = Language::updateOrCreate(['name' => 'Bahasa Sunda']);

        $sundaPemula = Course::updateOrCreate([
            'language_id' => $sunda->id,
            'title' => 'Bahasa Sunda untuk Pemula',
        ]);

        $sundaUnit1 = Unit::updateOrCreate([
            'course_id' => $sundaPemula->id,
            'order' => 1,
        ], [
            'title' => 'Level 1: Wilujeng Sumping & Salam',
        ]);

        $sundaSub1_1 = SubUnit::updateOrCreate([
            'unit_id' => $sundaUnit1->id,
            'order' => 1,
        ], [
            'title' => 'Salam & Menyapa Sadidinten',
        ]);

        $sundaSub1_2 = SubUnit::updateOrCreate([
            'unit_id' => $sundaUnit1->id,
            'order' => 2,
        ], [
            'title' => 'Wasta & Diri',
        ]);

        $this->createExercise($sundaSub1_1->id, 'Apa arti dari "Wilujeng enjing"?', [
            'Selamat pagi',
            'Selamat siang',
            'Selamat sore',
            'Selamat malam',
        ], 0, 10);

        $this->createExercise($sundaSub1_1->id, 'Bagaimana cara mengucapkan "Terima kasih" dalam bahasa Sunda?', [
            'Hatur nuhun',
            'Sami-sami',
            'Wilujeng',
            'Punten',
        ], 0, 10);

        $this->createExercise($sundaSub1_1->id, 'Kata "Permisi" dalam bahasa Sunda adalah...', [
            'Punten',
            'Mangga',
            'Rampes',
            'Nuhun',
        ], 0, 10);

        $this->createExercise($sundaSub1_2->id, 'Kata "Nama" dalam bahasa Sunda lemes (halus) adalah...', [
            'Nami',
            'Wasta',
            'Ngaran',
            'Kadieu',
        ], 1, 15);

        // Course 2: Menengah (Sunda)
        $sundaMenengah = Course::updateOrCreate([
            'language_id' => $sunda->id,
            'title' => 'Bahasa Sunda Menengah',
        ]);

        $sundaIntUnit1 = Unit::updateOrCreate([
            'course_id' => $sundaMenengah->id,
            'order' => 1,
        ], [
            'title' => 'Level 1: Tatakrama Basa Sunda',
        ]);

        $sundaIntSub1 = SubUnit::updateOrCreate([
            'unit_id' => $sundaIntUnit1->id,
            'order' => 1,
        ], [
            'title' => 'Basa Lemes & Loma',
        ]);

        $this->createExercise($sundaIntSub1->id, 'Basa lemes pikeun sewarang (diri sendiri) tina kata "Makan" nyaeta...', [
            'Neda',
            'Tuang',
            'Dahar',
            'Nyatu',
        ], 0, 20);

        // ----------------------------------------------------
        // 3. BAHASA BALI
        // ----------------------------------------------------
        $bali = Language::updateOrCreate(['name' => 'Bahasa Bali']);

        $baliPemula = Course::updateOrCreate([
            'language_id' => $bali->id,
            'title' => 'Bahasa Bali untuk Pemula',
        ]);

        $baliUnit1 = Unit::updateOrCreate([
            'course_id' => $baliPemula->id,
            'order' => 1,
        ], [
            'title' => 'Level 1: Rahajeng & Tata Krama Bali',
        ]);

        $baliSub1 = SubUnit::updateOrCreate([
            'unit_id' => $baliUnit1->id,
            'order' => 1,
        ], [
            'title' => 'Salam & Menyapa Bahasa Bali',
        ]);

        $this->createExercise($baliSub1->id, 'Salam khas Bali "Om Swastyastu" bermakna...', [
            'Semoga dalam keadaan selamat dan bahagia atas karunia Hyang Widhi',
            'Selamat pagi dan selamat datang',
            'Terima kasih banyak',
            'Sampai jumpa kembali',
        ], 0, 10);

        $this->createExercise($baliSub1->id, 'Apa bahasa Bali dari "Selamat Pagi"?', [
            'Rahajeng semeng',
            'Rahajeng tengai',
            'Rahajeng wengi',
            'Suksma',
        ], 0, 10);

        $this->createExercise($baliSub1->id, 'Jawaban untuk ucapan "Matur Suksma" adalah...', [
            'Sami-sami',
            'Mewali',
            'Rahajeng',
            'Bisa',
        ], 1, 15);

        // ----------------------------------------------------
        // 4. BAHASA BATAK
        // ----------------------------------------------------
        $batak = Language::updateOrCreate(['name' => 'Bahasa Batak']);

        $batakPemula = Course::updateOrCreate([
            'language_id' => $batak->id,
            'title' => 'Bahasa Batak Toba untuk Pemula',
        ]);

        $batakUnit1 = Unit::updateOrCreate([
            'course_id' => $batakPemula->id,
            'order' => 1,
        ], [
            'title' => 'Level 1: Horas & Perkenalan',
        ]);

        $batakSub1 = SubUnit::updateOrCreate([
            'unit_id' => $batakUnit1->id,
            'order' => 1,
        ], [
            'title' => 'Salam Horas',
        ]);

        $this->createExercise($batakSub1->id, 'Kata salam khas masyarakat Batak yang bermakna selamat/sehat adalah...', [
            'Horas',
            'Mejuah-juah',
            'Njuah-juah',
            'Tabe',
        ], 0, 10);

        $this->createExercise($batakSub1->id, 'Kata "Terima kasih" dalam bahasa Batak Toba adalah...', [
            'Mauliate',
            'Horas',
            'Marsega',
            'Tobang',
        ], 0, 10);
    }

    /**
     * Helper method to create exercise dynamically
     */
    private function createExercise(int $subUnitId, string $question, array $choices, int $correctIndex, int $xp = 10): void
    {
        $choicesFormatted = array_map(function ($choice) {
            return [
                'text' => $choice,
                'image' => null,
            ];
        }, $choices);

        Exercise::create([
            'sub_unit_id' => $subUnitId,
            'type' => 'multiple_choice',
            'gambar' => null,
            'xp' => $xp,
            'content' => [
                'question' => $question,
                'choices' => $choicesFormatted,
            ],
            'answer' => [
                'correct_index' => $correctIndex,
            ],
        ]);
    }
}
