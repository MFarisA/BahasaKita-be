<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
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
}
