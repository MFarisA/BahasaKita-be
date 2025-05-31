<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CulturalContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'language',
        'title',
        'excerpt',
        'image_url',
        'full_content',
        'text',
        'translation',
        'explanation',
        'category',
        'fact',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeStories($query)
    {
        return $query->where('type', 'story');
    }

    public function scopeProverbs($query)
    {
        return $query->where('type', 'proverb');
    }

    public function scopeTrivia($query)
    {
        return $query->where('type', 'trivia');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }
}
