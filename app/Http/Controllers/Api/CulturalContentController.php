<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CulturalContent;
use Illuminate\Http\Request;

class CulturalContentController extends Controller
{
    public function index(Request $request)
    {
        $language = $request->get('language', 'BahasaKita');
        
        $stories = CulturalContent::stories()
            ->active()
            ->byLanguage($language)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'excerpt' => $item->excerpt,
                    'imageUrl' => $item->image_url,
                    'language' => $item->language,
                    'fullContent' => $item->full_content,
                ];
            });

        $proverbs = CulturalContent::proverbs()
            ->active()
            ->byLanguage($language)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->text,
                    'translation' => $item->translation,
                    'explanation' => $item->explanation,
                ];
            });

        $trivia = CulturalContent::trivia()
            ->active()
            ->byLanguage($language)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'category' => $item->category,
                    'fact' => $item->fact,
                ];
            });

        return response()->json([
            'stories' => $stories,
            'proverbs' => $proverbs,
            'trivia' => $trivia,
        ]);
    }

    public function getByType(Request $request, $type)
    {
        $language = $request->get('language', 'BahasaKita');
        
        $query = CulturalContent::where('type', $type)
            ->active()
            ->byLanguage($language)
            ->orderBy('sort_order');

        $items = $query->get()->map(function ($item) use ($type) {
            switch ($type) {
                case 'story':
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'excerpt' => $item->excerpt,
                        'imageUrl' => $item->image_url,
                        'language' => $item->language,
                        'fullContent' => $item->full_content,
                    ];
                case 'proverb':
                    return [
                        'id' => $item->id,
                        'text' => $item->text,
                        'translation' => $item->translation,
                        'explanation' => $item->explanation,
                    ];
                case 'trivia':
                    return [
                        'id' => $item->id,
                        'category' => $item->category,
                        'fact' => $item->fact,
                    ];
                default:
                    return $item;
            }
        });

        return response()->json($items);
    }
}