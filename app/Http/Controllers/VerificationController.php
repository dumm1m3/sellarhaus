<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;



class VerificationController extends Controller{

    public function index()
    {
        $users = User::whereNull('verification')
        ->whereNotNull('ver_doc')
        ->get();
    
        return view('admin.userverification.index', compact('users'));
    }

    public function verifyUser(Request $request)
    {
        $userID = $request->input('user_id');
    
        // Find the user by ID
        $user = User::findOrFail($userID);
    
        // Update the verification column (you can change 'verified' to a boolean or timestamp if preferred)
        $user->verification = 1;
        $user->save();
    
        return redirect()->back();
    }
}
