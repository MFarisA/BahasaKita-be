<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;

class ExerciseSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch demo users (excluding adminSuper)
        $users = User::where('email', '!=', 'adminSuper@x.com')->get();
        $exercises = Exercise::all();

        if ($users->isEmpty() || $exercises->isEmpty()) {
            return;
        }

        // Add some correct submissions for Budi and Siti to unlock levels
        foreach ($users as $user) {
            // Pick 2-4 exercises to submit per user
            $userExercises = $exercises->take($user->id % 4 + 1);

            foreach ($userExercises as $exercise) {
                $correctIndex = $exercise->answer['correct_index'] ?? 0;

                ExerciseSubmission::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'exercise_id' => $exercise->id,
                    ],
                    [
                        'submitted_answer' => [$correctIndex],
                        'is_correct' => true,
                    ]
                );
            }
        }
    }
}
