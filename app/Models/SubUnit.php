<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubUnit extends Model
{
    protected $fillable = [
        'unit_id',
        'title',
        'order',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
