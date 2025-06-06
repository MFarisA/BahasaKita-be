<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Language;
use App\Models\SubUnit;
use App\Models\Unit;

class GetDataController extends Controller
{
    public function getExercises($languageId, $courseId, $unitId, $subUnitId)
    {
        $exercises = Exercise::with('subunit.unit.course.language')
            ->where('sub_unit_id', $subUnitId)
            ->whereHas('subunit', function ($query) use ($unitId, $courseId, $languageId) {
                $query->where('unit_id', $unitId)
                    ->whereHas('unit', function ($q) use ($courseId, $languageId) {
                        $q->where('course_id', $courseId)
                            ->whereHas('course', function ($q2) use ($languageId) {
                                $q2->where('language_id', $languageId);
                            });
                    });
            })->get();

        if ($exercises->isEmpty()) {
            return response()->json(['message' => 'No exercises found for given IDs'], 404);
        }

        return response()->json($exercises, 200);
    }

    public function getSubUnit($languageId, $courseId, $unitId)
    {
        $subUnits = SubUnit::with('unit.course.language')
            ->where('unit_id', $unitId)
            ->whereHas('unit', function ($query) use ($courseId, $languageId) {
                $query->where('course_id', $courseId)
                    ->whereHas('course', function ($q2) use ($languageId) {
                        $q2->where('language_id', $languageId);
                    });
            })->get();

        return response()->json($subUnits, 200);
    }

    public function getUnit($languageId, $courseId)
    {
        $units = Unit::with('course.language')
            ->where('course_id', $courseId)
            ->whereHas('course', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })->get();

        return response()->json($units, 200);
    }

    public function getCourse($languageId)
    {
        $language = Language::with('courses.language')->find($languageId);
        return response()->json($language ? $language->courses : [], 200);
    }
}
