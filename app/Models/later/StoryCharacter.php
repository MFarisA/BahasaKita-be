<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryCharacter extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'name',
        'name_local',
        'description',
        'role',
        'type'
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
