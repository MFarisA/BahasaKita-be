<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'language_code',
        'title',
        'content',
        'moral_lesson'
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
