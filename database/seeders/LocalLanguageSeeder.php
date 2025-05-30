<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Course;
use App\Models\Unit;

class LocalLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Languages
        $jawa = Language::create([
            'name' => 'Bahasa Jawa'
        ]);

        $sunda = Language::create([
            'name' => 'Bahasa Sunda'
        ]);

        // Create Courses for Jawa
        $jawaBeginnerCourse = Course::create([
            'language_id' => $jawa->id,
            'title' => 'Bahasa Jawa untuk Pemula'
        ]);

        $jawaIntermediateCourse = Course::create([
            'language_id' => $jawa->id,
            'title' => 'Bahasa Jawa Menengah'
        ]);

        // Create Courses for Sunda
        $sundaBeginnerCourse = Course::create([
            'language_id' => $sunda->id,
            'title' => 'Bahasa Sunda untuk Pemula'
        ]);

        $sundaIntermediateCourse = Course::create([
            'language_id' => $sunda->id,
            'title' => 'Bahasa Sunda Menengah'
        ]);

        // Create Units for Jawa Beginner Course
        $jawaUnit1 = Unit::create([
            'course_id' => $jawaBeginnerCourse->id,
            'title' => 'Level 1',
            'order' => 1
        ]);

        $jawaUnit2 = Unit::create([
            'course_id' => $jawaBeginnerCourse->id,
            'title' => 'Level 2',
            'order' => 2
        ]);

        $jawaUnit3 = Unit::create([
            'course_id' => $jawaBeginnerCourse->id,
            'title' => 'Level 3',
            'order' => 3
        ]);

        // Create Units for Jawa Intermediate Course
        $jawaIntUnit1 = Unit::create([
            'course_id' => $jawaIntermediateCourse->id,
            'title' => 'Level 1',
            'order' => 1
        ]);

        // Create Units for Sunda Beginner Course
        $sundaUnit1 = Unit::create([
            'course_id' => $sundaBeginnerCourse->id,
            'title' => 'Level 1',
            'order' => 1
        ]);

        $sundaUnit2 = Unit::create([
            'course_id' => $sundaBeginnerCourse->id,
            'title' => 'Level 2',
            'order' => 2
        ]);

        $sundaUnit3 = Unit::create([
            'course_id' => $sundaBeginnerCourse->id,
            'title' => 'Level 3',
            'order' => 3
        ]);

        // Create Units for Sunda Intermediate Course  
        $sundaIntUnit1 = Unit::create([
            'course_id' => $sundaIntermediateCourse->id,
            'title' => 'Level 1',
            'order' => 1
        ]);
    }
}
