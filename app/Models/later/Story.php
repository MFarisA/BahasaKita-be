<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; 


class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_local',
        'content',
        'content_local',
        'language',
        'region',
        'category',
        'moral_lesson',
        'cultural_elements',
        'setting',
        'pronunciation_guide',
        'slug',
        'view_count',
        'popularity',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'cultural_elements' => 'array',
        'is_active' => 'boolean',
        'view_count' => 'integer',
        'popularity' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($story) {
            if (empty($story->slug)) {
                $story->slug = Str::slug($story->title . '-' . Str::random(6));
            }
        });
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(StoryCharacter::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(StoryMedia::class);
    }

    public function audio(): HasMany
    {
        return $this->hasMany(StoryMedia::class)->where('type', 'audio');
    }

    public function images(): HasMany
    {
        return $this->hasMany(StoryMedia::class)->where('type', 'image');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(StoryTranslation::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(StoryTag::class, 'story_tag');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorite_stories')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('popularity', 'desc');
    }

    // Accessors
    public function getLanguageNameAttribute(): string
    {
        $languages = [
            'javanese' => 'Bahasa Jawa',
            'sundanese' => 'Bahasa Sunda',
            'batak' => 'Bahasa Batak',
            'minangkabau' => 'Bahasa Minang',
            'balinese' => 'Bahasa Bali',
            'buginese' => 'Bahasa Bugis',
            'banjarese' => 'Bahasa Banjar',
            'acehnese' => 'Bahasa Aceh',
            'betawi' => 'Bahasa Betawi',
            'madurese' => 'Bahasa Madura'
        ];

        return $languages[$this->language] ?? ucfirst($this->language);
    }

    public function getPrimaryImageAttribute()
    {
        return $this->media()->where('type', 'image')->where('is_primary', true)->first()
            ?? $this->media()->where('type', 'image')->first();
    }
}
