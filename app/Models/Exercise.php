<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['unit_id', 'type', 'gambar', 'xp', 'content', 'answer'];

    protected $casts = [
        'content' => 'array',
        'answer' => 'array',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function submissions()
    {
        return $this->hasMany(ExerciseSubmission::class);
    }
}
