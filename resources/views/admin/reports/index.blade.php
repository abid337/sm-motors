@extends('layouts.admin')

@section('title', 'Reports - Smart CMS')
@section('page-title', 'Reports')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted-brand mb-0">Total: {{ $reports->total() }} reports</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="admin-table">
    <table class="table table-dark table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Listing</th>
                <th>Reported By</th>
                <th>Reason</th>
                <th>Description</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr>
                <td class="text-muted">{{ $report->id }}</td>
                <td>
                    @if($report->item)
                        <a href="{{ route('items.show', $report->item->slug) }}"
                           target="_blank"
                           class="text-danger text-decoration-none">
                            {{ Str::limit($report->item->title, 25) }}
                        </a>
                    @else
                        <span class="text-muted">Deleted</span>
                    @endif
                </td>
                <td class="text-white">{{ $report->user->name ?? 'Guest' }}</td>
                <td>
                    <span class="badge bg-warning text-dark">{{ $report->reason }}</span>
                </td>
                <td class="text-muted">{{ Str::limit($report->description, 30) ?? '—' }}</td>
                <td>
                    @if($report->status === 'pending')
                        <span class="badge bg-danger">Pending</span>
                    @elseif($report->status === 'reviewed')
                        <span class="badge bg-warning text-dark">Reviewed</span>
                    @else
                        <span class="badge bg-success">Resolved</span>
                    @endif
                </td>
                <td class="text-muted">{{ $report->created_at->format('d M Y') }}</td>
                <td>
                    <div class="d-flex gap-1 flex-wrap">

                        {{-- Mark Reviewed --}}
                        @if($report->status === 'pending')
                        <form action="{{ route('admin.reports.reviewed', $report) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-warning" title="Mark Reviewed">
                                <i class="fas fa-eye"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Mark Resolved --}}
                        @if($report->status !== 'resolved')
                        <form action="{{ route('admin.reports.resolved', $report) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-success" title="Mark Resolved">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Delete Item --}}
                        @if($report->item)
                        <form action="{{ route('admin.reports.delete-item', $report) }}" method="POST"
                              onsubmit="return confirm('Delete this listing permanently?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Delete Listing">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Dismiss Report --}}
                        <form action="{{ route('admin.reports.destroy', $report) }}" method="POST"
                              onsubmit="return confirm('Dismiss this report?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary" title="Dismiss Report">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    No reports found. 
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">
        {{ $reports->links() }}
    </div>
</div>

@endsection