<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register Page
    public function registerForm()
    {
        return view('user.auth.register');
    }

    // Register Submit
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone'    => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'whatsapp' => $request->whatsapp,
            'role'     => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Welcome ' . $user->name . '!');
    }

    // Login Page
    public function loginForm()
    {
        return view('user.auth.login');
    }

    // Login Submit
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            if (auth()->user()->isAdmin()) {
                Auth::logout();
                return back()->with('error', 'Use admin panel to login!');
            }
            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Invalid email or password!');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login');
    }
}