<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'type',
        'filename',
        'original_name',
        'path',
        'url',
        'mime_type',
        'size',
        'description',
        'order',
        'is_primary'
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer',
        'is_primary' => 'boolean'
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeAudio($query)
    {
        return $query->where('type', 'audio');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
