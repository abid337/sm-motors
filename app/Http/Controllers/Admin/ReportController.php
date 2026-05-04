<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Item;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // All Reports
    public function index()
    {
        $reports = Report::with('item', 'user')
            ->latest()
            ->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    // Mark as Reviewed
    public function reviewed(Report $report)
    {
        $report->update(['status' => 'reviewed']);
        return back()->with('success', 'Report marked as reviewed!');
    }

    // Mark as Resolved
    public function resolved(Report $report)
    {
        $report->update(['status' => 'resolved']);
        return back()->with('success', 'Report marked as resolved!');
    }

    // Delete Reported Item
    public function deleteItem(Report $report)
    {
        $item = Item::findOrFail($report->item_id);
        $item->media()->delete();
        $item->properties()->delete();
        $item->reports()->delete();
        $item->delete();
        return redirect()->route('admin.reports.index')
            ->with('success', 'Item deleted successfully!');
    }

    // Delete Report
    public function destroy(Report $report)
    {
        $report->delete();
        return back()->with('success', 'Report dismissed!');
    }
}