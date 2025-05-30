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

        // Tambahkan XP ke profile jika benar
        if ($isCorrect) {
            $profile = Auth::user()->profile;
            $profile->increment('xp', $exercise->xp ?? 0);
        }

        return response()->json([
            'message' => 'Jawaban berhasil disimpan.',
            'is_correct' => $isCorrect,
            'submission' => $submission,
        ], 201);
    }
}
