<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = $request->user()->load('profile');

        return response()->json([
            'user'    => $user,
            'profile' => $user->profile,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024|min:10',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->profile && $user->profile->photo && Storage::disk('public')->exists($user->profile->photo)) {
                Storage::disk('public')->delete($user->profile->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile/image', $filename, 'public');

            $photoData = ['photo' => $path];

            if (!$user->profile) {
                $user->profile()->create($photoData);
            } else {
                $user->profile()->update($photoData);
            }
        }

        if (isset($data['name'])) {
            $user->update(['name' => $data['name']]);
        }

        $user->load('profile');

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }
}
