<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\SubUnit;
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
        $submittedKey = $data['submitted_answer'][0] ?? null;

        $isCorrect = false;

        if ($exercise->type === 'multiple_choice') {
            $content = $exercise->content; // example: ["a" => "cat", "b" => "dog", "question" => "..."]
            $validKeys = array_keys($content);
            $validKeys = array_filter($validKeys, fn($key) => $key !== 'question');

            if (!in_array($submittedKey, $validKeys)) {
                return response()->json([
                    'message' => 'Submitted answer is not a valid option.',
                ], 422);
            }

            // Compare submitted key with the correct answer key
            $correctKey = array_key_first($exercise->answer); // assuming {"a": "cat"}
            $isCorrect = $submittedKey === $correctKey;
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

        // Ambil SubUnit sebelumnya berdasarkan order
        $previousSubUnit = SubUnit::where('unit_id', $unitId)
            ->where('order', '<', $subUnit->order)
            ->orderByDesc('order')
            ->first();

        // Jika ada subunit sebelumnya, cek apakah sudah diselesaikan
        if ($previousSubUnit) {
            $previousExercises = $previousSubUnit->exercises;

            foreach ($previousExercises as $exercise) {
                $submission = ExerciseSubmission::where('user_id', $userId)
                    ->where('exercise_id', $exercise->id)
                    ->where('is_correct', true) // atau tergantung kriteria "selesai"
                    ->first();

                if (!$submission) {
                    return response()->json([
                        'message' => 'Selesaikan level sebelumnya terlebih dahulu.'
                    ], 403);
                }
            }
        }

        // Kalau sudah lolos, ambil exercise di subunit sekarang
        $exercises = $subUnit->exercises;

        return response()->json($exercises);
    }


    // // Function 1: validate that the current exercise has been submitted.
    // protected function ensureExerciseSubmitted($exercise_id)
    // {
    //     $submission = ExerciseSubmission::where('user_id', Auth::id())
    //         ->where('exercise_id', $exercise_id)
    //         ->first();

    //     return $submission !== null;
    // }

    // // Function 2: validate that the user has completed previous units and subunits.
    // protected function validatePreviousCompletion(Exercise $currentExercise)
    // {
    //     $submittedExerciseIds = ExerciseSubmission::where('user_id', Auth::id())
    //         ->pluck('exercise_id')
    //         ->toArray();

    //     // Check incomplete exercises for previous units.
    //     $incompleteUnitExercises = Exercise::where('unit_id', '<', $currentExercise->unit_id)
    //         ->whereNotIn('id', $submittedExerciseIds)
    //         ->exists();

    //     if ($incompleteUnitExercises) {
    //         return response()->json([
    //             'message' => 'Please complete all exercises in the previous units first.',
    //         ], 403);
    //     }

    //     // Check incomplete exercises for previous subunits within the same unit.
    //     $incompleteSubunitExercises = Exercise::where('unit_id', $currentExercise->unit_id)
    //         ->where('subunit_id', '<', $currentExercise->subunit_id)
    //         ->whereNotIn('id', $submittedExerciseIds)
    //         ->exists();

    //     if ($incompleteSubunitExercises) {
    //         return response()->json([
    //             'message' => 'Please complete all exercises in the previous subunits first.',
    //         ], 403);
    //     }

    //     return true;
    // }

    // // Combined endpoint that uses both functions.
    // public function nextQuestion()
    // {
    //     // Retrieve the user's last submitted exercise.
    //     $lastSubmission = ExerciseSubmission::where('user_id', Auth::id())
    //         ->orderBy('exercise_id', 'desc')
    //         ->first();

    //     // If no submissions exist, instruct the user to start with the first exercise.
    //     if (!$lastSubmission) {
    //         return response()->json([
    //             'message' => 'You have not started any exercise yet. Please begin with the first exercise.'
    //         ], 403);
    //     }

    //     // Get the current exercise based on the last submission.
    //     $currentExercise = Exercise::findOrFail($lastSubmission->exercise_id);

    //     // Validate completion of previous units and subunits for the current exercise.
    //     $completionCheck = $this->validatePreviousCompletion($currentExercise);
    //     if ($completionCheck !== true) {
    //         return $completionCheck;
    //     }

    //     // Get the next exercise. (This example uses ordering by id.)
    //     $nextExercise = Exercise::where('id', '>', $currentExercise->id)
    //         ->orderBy('id')
    //         ->first();

    //     if (!$nextExercise) {
    //         return response()->json([
    //             'message' => 'No more exercises available.'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'exercise' => $nextExercise,
    //     ], 200);
    // }
}
