<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    public function getLeaderboard(Request $request)
    {
        $leaderboard = User::select('users.*')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->where('users.email', '!=', 'adminSuper@x.com')
            ->orderBy('profiles.xp', 'desc')
            ->orderBy('profiles.level', 'desc')
            ->with('profile')
            ->get();


        $rankedLeaderboard = $leaderboard->values()->map(function ($user, $index) {
            $user->rank = $index + 1;
            return $user;
        });

        return response()->json($rankedLeaderboard);
    }
}
