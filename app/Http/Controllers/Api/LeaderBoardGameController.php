<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LessonProgress;
use App\Models\User;
use Carbon\Carbon;
use DB;

class LeaderBoardGameController extends Controller
{
    // Global leaderboard: total score dari semua waktu
    public function globalLeaderboard()
    {
        $users = User::select('users.id', 'users.name')
            ->join('lesson_progresses', 'users.id', '=', 'lesson_progresses.user_id')
            ->selectRaw('users.id, users.name, SUM(lesson_progresses.score) as total_score')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_score')
            ->limit(100)
            ->get();

        return response()->json([
            'status' => 'success',
            'type' => 'global',
            'data' => $users
        ]);
    }

    // Weekly leaderboard: hanya progress dalam 7 hari terakhir
    public function weeklyLeaderboard()
    {
        $startOfWeek = Carbon::now()->subDays(7);

        $users = User::select('users.id', 'users.name')
            ->join('lesson_progresses', 'users.id', '=', 'lesson_progresses.user_id')
            ->where('lesson_progresses.created_at', '>=', $startOfWeek)
            ->selectRaw('users.id, users.name, SUM(lesson_progresses.score) as total_score')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_score')
            ->limit(100)
            ->get();

        return response()->json([
            'status' => 'success',
            'type' => 'weekly',
            'data' => $users
        ]);
    }

    // Melihat progres pribadi user yang sedang login
    public function myProgress(Request $request)
    {
        $user = $request->user();

        $totalScore = LessonProgress::where('user_id', $user->id)
            ->sum('score');

        $weeklyScore = LessonProgress::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->sum('score');

        return response()->json([
            'status' => 'success',
            'user' => $user->only(['id', 'name']),
            'global_score' => $totalScore,
            'weekly_score' => $weeklyScore,
        ]);
    }
}
