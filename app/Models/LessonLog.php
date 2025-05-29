<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonLog extends Model
{
    // notification
    protected $fillable = ['user_id', 'duration', 'logged_at'];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
