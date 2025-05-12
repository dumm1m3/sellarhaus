<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'users' => $request->users(),
        ]);
    }

    public function index()
{
    $user = Auth::user();
    $balance = $user->balance;

    // ✅ Always return the profile view for any role
    return view('user.profile', compact('user'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'road' => 'nullable|string|max:100',
            'address_note' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        if ($request->hasFile('verification_document')) {
            $path = $request->file('verification_document')->store('verification_document', 'public');
            $user->ver_doc = $path;
        }


        $user->update($validated);

        return redirect()->route('user.profile')->with('status', 'Profile updated successfully.');
    }



    public function updateStatOnLoad()
    {
        $user = Auth::user();
        $balance = $user->balance;
        $current_level = $user->account_status;
    
        if ($balance < 30) {
            $user->account_status = 'inactive';
        } elseif ($balance < 500 && in_array($current_level, ['VIP2', 'VIP3', 'VIP4', 'VIP5'])) {
            $user->account_status = 'VIP1';
        } elseif ($balance < 3000 && in_array($current_level, ['VIP3', 'VIP4', 'VIP5'])) {
            $user->account_status = 'VIP2';
        } elseif ($balance < 10000 && in_array($current_level, ['VIP4', 'VIP5'])) {
            $user->account_status = 'VIP3';
        } elseif ($balance < 30000 && $current_level === 'VIP5') {
            $user->account_status = 'VIP4';
        } elseif ($balance >= 30000) {
            $user->account_status = 'VIP5';
        } else {
            $user->account_status = 'inactive';
        }
    
        $user->save();
    
        return response()->json(['status' => 'updated', 'new_level' => $user->account_status]);
    }




    public function updateStat(Request $request){
        $user = Auth::user();
        $balance = $user->balance;
        $level = $request->input("membership_level");
        $current_level = Auth::user()->account_status;

        if($balance<30){
            $user->account_status = 'inactive';
        }
        elseif($balance>=30 && $balance<500 && $level === 'VIP1'){
            $user->account_status = 'VIP1';
        }
        elseif($balance>=500 && $balance<3000 && $level === 'VIP2'){
            $user->account_status = 'VIP2';
        }
        elseif($balance>=3000 && $balance<10000 && $level === 'VIP3'){
            $user->account_status = 'VIP3';
        }
        elseif($balance>=10000 && $balance<30000 && $level === 'VIP4'){
            $user->account_status = 'VIP4';
        }
        elseif($balance>=30000 && $level === 'VIP5'){
            $user->account_status = 'VIP5';
        }
        $user->save();
        // Render the profile view with the user data
        return redirect()->route('user.profile')->with('status', 'Congratulations! Status Unlocked!');
}

public function insertVerificationData(Request $request)
{
    $user = Auth::user(); // ✅ get the user model instance, not just the ID

    if ($request->hasFile('verification_document')) {
        $path = $request->file('verification_document')->store('verification_document', 'public');
        $user->ver_doc = $path; // ✅ store file path
    }

    $user->save(); // ✅ save user model with updated data

    return redirect()->route('user.profile')->with('status', 'Verification Request Submitted!');
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('login');
    }
}
