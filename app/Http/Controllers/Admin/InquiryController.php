<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with('item')->latest()->paginate(10);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function destroy($id)
    {
        Inquiry::findOrFail($id)->delete();
        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted!');
    }
}