<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\City;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_items'      => Item::count(),
            'published_items'  => Item::where('status', 'published')->count(),
            'draft_items'      => Item::where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_cities'     => City::count(),
            'total_inquiries'  => Inquiry::count(),
        ];

        $recent_items = Item::with('category', 'city')
            ->latest()
            ->take(5)
            ->get();

        $recent_inquiries = Inquiry::with('item')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_items', 'recent_inquiries'));
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->with('error', 'Access denied!');
        }

        return back()->with('error', 'Invalid credentials!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}