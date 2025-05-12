<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();

    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('contact', 'like', "%$search%");
        });
    }

    $users = $query->orderBy('name')->get();

    return view('admin.users.index', compact('users'));
}

public function edit(User $user)
{
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'contact' => 'nullable|string|max:20',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:user,admin',
    ]);

    // Only allow updates to editable fields
    $user->update($validated);

    return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
}


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }
}


