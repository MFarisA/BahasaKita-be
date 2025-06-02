<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\SubUnit;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseSubmissionController extends Controller
{
    public function submit(Request $request, $exercise_id)
    {
        $data = $request->validate([
            'submitted_answer' => 'required|array|min:1',
        ]);

        $exercise = Exercise::findOrFail($exercise_id);
        $submittedIndex = $data['submitted_answer'][0] ?? null;

        $isCorrect = false;

        if ($exercise->type === 'multiple_choice') {
            $choices = $exercise->content['choices'] ?? [];

            if (!is_numeric($submittedIndex) || !isset($choices[(int) $submittedIndex])) {
                return response()->json([
                    'message' => 'Submitted answer is not a valid option.',
                ], 422);
            }

            $correctIndex = (int) ($exercise->answer['correct_index'] ?? -1);
            $isCorrect = ((int) $submittedIndex === $correctIndex);
        }

        $submission = ExerciseSubmission::create([
            'user_id' => Auth::id(),
            'exercise_id' => $exercise->id,
            'submitted_answer' => $data['submitted_answer'],
            'is_correct' => $isCorrect,
        ]);

        if ($isCorrect) {
            $profile = Auth::user()->profile;
            $profile->increment('xp', $exercise->xp ?? 15);
        }

        return response()->json([
            'message' => $isCorrect ? 'Your answer is correct.' : 'Your answer is wrong.',
            'is_correct' => $isCorrect,
            'submission' => $submission,
        ], 201);
    }


    public function showExercises($languageId, $courseId, $unitId, $subUnitId, Request $request)
    {
        $userId = $request->user()->id;

        $subUnit = SubUnit::where('id', $subUnitId)
            ->where('unit_id', $unitId)
            ->firstOrFail();

        $previousSubUnit = SubUnit::where('unit_id', $unitId)
            ->where('order', '<', $subUnit->order)
            ->orderByDesc('order')
            ->first();

        if ($previousSubUnit) {
            $previousExercises = $previousSubUnit->exercises;

            foreach ($previousExercises as $exercise) {
                $submission = ExerciseSubmission::where('user_id', $userId)
                    ->where('exercise_id', $exercise->id)
                    ->where('is_correct', true) 
                    ->first();

                if (!$submission) {
                    return response()->json([
                        'message' => 'Selesaikan level sebelumnya terlebih dahulu.'
                    ], 403);
                }
            }
        }

        $exercises = $subUnit->exercises;

        return response()->json($exercises);
    }

    public function showUnit($languageId, $courseId, $unitId, Request $request)
    {
        $userId = $request->user()->id;

        // Ambil Unit sekarang
        $unit = Unit::where('id', $unitId)
            ->where('course_id', $courseId)
            ->firstOrFail();

        // Cari Unit sebelumnya berdasarkan urutan
        $previousUnit = Unit::where('course_id', $courseId)
            ->where('order', '<', $unit->order)
            ->orderByDesc('order')
            ->first();

        if ($previousUnit) {
            foreach ($previousUnit->subunits as $subunit) {
                foreach ($subunit->exercises as $exercise) {
                    $submission = ExerciseSubmission::where('user_id', $userId)
                        ->where('exercise_id', $exercise->id)
                        ->where('is_correct', true)
                        ->first();

                    if (!$submission) {
                        return response()->json([
                            'message' => 'Selesaikan chapter (unit) sebelumnya terlebih dahulu.'
                        ], 403);
                    }
                }
            }
        }

        return response()->json([
            'unit' => $unit->load('subunits')
        ]);
    }

    public function checkAnswer(Request $request, $exercise_id)
    {
        $data = $request->validate([
            'submitted_answer' => 'required|array|min:1',
        ]);

        $exercise = Exercise::findOrFail($exercise_id);
        $submittedIndex = $data['submitted_answer'][0] ?? null;

        if ($exercise->type !== 'multiple_choice') {
            return response()->json([
                'message' => 'Unsupported exercise type.',
            ], 422);
        }

        $choices = $exercise->content['choices'] ?? [];
        if (!is_numeric($submittedIndex) || !isset($choices[(int) $submittedIndex])) {
            return response()->json([
                'message' => 'Submitted answer is not a valid option.',
            ], 422);
        }

        $correctIndex = (int) ($exercise->answer['correct_index'] ?? -1);
        $isCorrect = ((int) $submittedIndex === $correctIndex);

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_index' => $correctIndex,
            'message' => $isCorrect ? 'Jawaban Anda benar.' : 'Jawaban Anda salah.',
        ]);
    }
}
