<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id'     => 'required|exists:items,id',
            'reason'      => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Report::create([
            'item_id'     => $request->item_id,
            'user_id'     => auth()->id() ?? null,
            'reason'      => $request->reason,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Report submitted! We will review it shortly.');
    }
}