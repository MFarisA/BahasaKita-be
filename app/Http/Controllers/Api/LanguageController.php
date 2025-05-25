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
    // List all languages
    public function index()
    {
        return response()->json(Language::all(), 200);
    }

    // Create new language
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $language = Language::create([
            'name' => $request->name,
        ]);

        return response()->json($language, 201);
    }

    // Show language with nested relations: courses -> units -> lessons -> exercises
    public function show($id)
    {
        $language = Language::with('courses.units.lessons.exercises')->findOrFail($id);
        return response()->json($language, 200);
    }

    // Get all courses for a language, with unit count
    public function courses($id)
    {
        $language = Language::findOrFail($id);
        $courses = $language->courses()->withCount('units')->get();
        return response()->json($courses, 200);
    }

    // Get units for a specific course
    public function courseUnits($id)
    {
        $course = Course::with('units')->findOrFail($id);
        return response()->json($course->units, 200);
    }

    // Get lessons for a specific unit
    public function unitLessons($id)
    {
        $unit = Unit::with('lessons')->findOrFail($id);
        return response()->json($unit->lessons, 200);
    }

    // Get exercises for a specific lesson
    public function lessonExercises($id)
    {
        $lesson = Lesson::with('exercises')->findOrFail($id);
        return response()->json($lesson->exercises, 200);
    }

    // Update language info
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $language->update([
            'name' => $request->name,
        ]);

        return response()->json($language, 200);
    }

    // Delete language
    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return response()->json(['message' => 'Language deleted'], 200);
    }
}
