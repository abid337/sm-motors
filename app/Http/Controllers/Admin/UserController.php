<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('items')
            ->latest()
            ->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete admin user!');
        }
        $user->items()->delete();
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted!');
    }
}