<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseSubmission extends Model
{
    protected $fillable = ['user_id', 'exercise_id', 'submitted_answer', 'is_correct'];

    protected $casts = [
        'submitted_answer' => 'array',
        'is_correct' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
