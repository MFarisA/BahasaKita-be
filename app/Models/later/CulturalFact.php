<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CulturalFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'region',
        'language_context',
        'related_stories',
        'popularity',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'related_stories' => 'array',
        'is_active' => 'boolean',
        'popularity' => 'integer'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function media(): HasMany
    {
        return $this->hasMany(CulturalFactMedia::class);
    }

    public function relatedStories()
    {
        if (!$this->related_stories) {
            return collect();
        }

        return Story::whereIn('id', $this->related_stories)->get();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }
}
