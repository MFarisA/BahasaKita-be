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

        // Validate the input; "name" is for the User model only
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024|min:10',
        ]);

        // Process photo upload separately so that we don't include "name" for the profile
        if ($request->hasFile('photo')) {
            // Check if a previous photo exists on the profile
            if ($user->profile && $user->profile->photo && Storage::disk('public')->exists($user->profile->photo)) {
                Storage::disk('public')->delete($user->profile->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile/image', $filename, 'public');

            // Prepare photo data only
            $photoData = ['photo' => $path];

            // Create or update the profile with photo column only.
            if (!$user->profile) {
                $user->profile()->create($photoData);
            } else {
                $user->profile()->update($photoData);
            }
        }

        // Update the user's name if provided
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
