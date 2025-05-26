<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Language;
use App\Models\Unit;
use App\Models\Lesson;

class ExerciseController extends Controller
{
    // Get all courses for a language, with unit count
    public function courses($id)
    {
        $language = Language::findOrFail($id);
        $courses = $language->courses()->withCount('units')->get();
        return response()->json($courses, 200);
    }

    // Get units for a specific course
    public function courseUnits($id)
    {
        $course = Course::with('units')->findOrFail($id);
        return response()->json($course->units, 200);
    }

    // Get lessons for a specific unit
    public function unitLessons($id)
    {
        $unit = Unit::with('lessons')->findOrFail($id);
        return response()->json($unit->lessons, 200);
    }

    // Get exercises for a specific lessonn
    public function lessonExercises($id)
    {
        $lesson = Lesson::with('exercises')->findOrFail($id);
        return response()->json($lesson->exercises, 200);
    }
    // Submit jawaban exercise
    public function submit(Request $request, $exercise_id)
    {
        $request->validate([
            'submitted_answer' => 'required|array',
        ]);

        $exercise = Exercise::findOrFail($exercise_id);

        // Contoh penilaian sederhana (hanya untuk tipe multiple_choice)
        $isCorrect = null;
        if ($exercise->type === 'multiple_choice') {
            $isCorrect = strtolower(trim($request->submitted_answer['answer'] ?? '')) === strtolower(trim($exercise->correct_answer ?? ''));
        }

        $submission = ExerciseSubmission::create([
            'user_id' => Auth::id(),
            'exercise_id' => $exercise->id,
            'submitted_answer' => $request->submitted_answer,
            'is_correct' => $isCorrect,
        ]);

        return response()->json([
            'message' => 'Jawaban berhasil disimpan.',
            'is_correct' => $isCorrect,
            'submission' => $submission,
        ], 201);
    }

    // Lihat semua jawaban yang dikumpulkan user
    public function userSubmissions()
    {
        $submissions = ExerciseSubmission::with('exercise.lesson.unit.course.language')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($submissions);
    }

    // Lihat detail submission tertentu
    public function show($id)
    {
        $submission = ExerciseSubmission::with('exercise')->where('user_id', Auth::id())->findOrFail($id);
        return response()->json($submission);
    }
}
