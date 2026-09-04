<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Only Admin can create users
     */
    public function create()
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Only Admin can create new users.');
        }
        return view('admin.users.create');
    }

    /**
     * Only Admin can store new users
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Only Admin can create new users.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Admin,Manager',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Admin can edit anyone. Manager can only edit own profile.
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        // Admin can edit anyone
        if ($currentUser->role === 'Admin') {
            return view('admin.users.edit', compact('user'));
        }

        // Non-admin can only edit own profile
        if ($currentUser->id !== $user->id) {
            abort(403, 'You can only edit your own profile.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Admin can update anyone. Others can only update own name/email.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'Admin') {
            // Admin can update everything
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|in:Admin,Manager',
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);
        } else {
            // Non-admin can only update own name/email (not role)
            if ($currentUser->id !== $user->id) {
                abort(403, 'You can only edit your own profile.');
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Only Admin can delete users
     */
    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Only Admin can delete users.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Show change password form (any logged-in user)
     */
    public function changePassword()
    {
        $user = Auth::user();
        return view('admin.users.change-password', compact('user'));
    }

    /**
     * Update password (any logged-in user)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
