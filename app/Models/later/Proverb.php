<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proverb extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'text_local',
        'meaning',
        'context',
        'usage_example',
        'language',
        'region',
        'pronunciation_guide',
        'popularity',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'popularity' => 'integer'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

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
}
