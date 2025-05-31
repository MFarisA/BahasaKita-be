<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['language_id', 'title'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
