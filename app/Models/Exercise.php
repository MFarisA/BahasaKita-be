<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['lesson_id', 'type', 'gambar', 'content', 'answer'];

    protected $casts = [
        'content' => 'array',
        'answer' => 'array',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(ExerciseSubmission::class);
    }
}
