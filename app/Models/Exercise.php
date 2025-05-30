<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['sub_unit_id', 'type', 'gambar', 'xp', 'content', 'answer'];

    protected $casts = [
        'content' => 'array',
        'answer' => 'array',
    ];

    public function subunit()
    {
        return $this->belongsTo(SubUnit::class, 'sub_unit_id');
    }


    public function submissions()
    {
        return $this->hasMany(ExerciseSubmission::class);
    }
}
