<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LecturerProfileController extends Controller
{
    public function show()
    {
        $profile = auth()->user()->lecturerProfile;
        return response()->json($profile);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required',
            'account_number' => 'required',
            'branch' => 'required',
            'phone_number' => 'required',
        ]);

        $profile = auth()->user()->lecturerProfile;

        if (!$profile) {
            $profile = new LecturerProfile($validated);
            $profile->user_id = auth()->id();
            $profile->save();
        } else {
            $profile->update($validated);
        }

        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile]);
    }
}
