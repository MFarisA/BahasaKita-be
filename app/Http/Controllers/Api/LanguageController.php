<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Course;
use App\Models\Unit;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        return response()->json(Language::all(), 200);
    }

    public function show($id)
    {
        $language = Language::with('courses.units')->findOrFail($id);
        return response()->json($language, 200);
    }
}
