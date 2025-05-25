<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CulturalFactMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'cultural_fact_id',
        'type',
        'filename',
        'original_name',
        'path',
        'url',
        'mime_type',
        'size',
        'description',
        'order'
    ];

    protected $casts = [
        'size' => 'integer',
        'order' => 'integer'
    ];

    public function culturalFact(): BelongsTo
    {
        return $this->belongsTo(CulturalFact::class);
    }
}
