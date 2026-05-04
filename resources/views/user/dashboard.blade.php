@extends('layouts.app')

@section('title', 'My Dashboard - SM-Autos')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">My Dashboard</h4>
            <p class="text-white mb-0">Welcome, {{ auth()->user()->name }}!</p>
        </div>
        <a href="{{ route('user.items.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-2"></i> Add New Vehicle
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow text-center p-4">
                <div class="mb-2">
                    <i class="fas fa-car fa-2x text-danger"></i>
                </div>
                <h3 class="fw-bold text-white mb-0">{{ $totalItems }}</h3>
                <p class="text-white mb-0">Total Listings</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow text-center p-4">
                <div class="mb-2">
                    <i class="fas fa-eye fa-2x text-danger"></i>
                </div>
                <h3 class="fw-bold text-white mb-0">{{ number_format($totalViews) }}</h3>
                <p class="text-white mb-0">Total Views</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-0 shadow text-center p-4">
                <div class="mb-2">
                    <i class="fas fa-envelope fa-2x text-danger"></i>
                </div>
                <h3 class="fw-bold text-white mb-0">{{ $totalInquiries }}</h3>
                <p class="text-white mb-0">Total Inquiries</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Items Table --}}
    <div class="card bg-dark border-0 shadow">
        <div class="card-body p-0">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Views</th>
                        <th>Inquiries</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>
                            @if($item->thumbnail)
                            <img src="{{ $item->thumbnail }}"
                                style="width:50px;height:40px;object-fit:cover;border-radius:6px" />
                            @else
                            <div style="width:50px;height:40px;background:#242424;border-radius:6px;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-car text-white"></i>
                            </div>
                            @endif
                        </td>
                        <td><strong>{{ Str::limit($item->title, 30) }}</strong></td>
                        <td class="text-white">{{ $item->category->name ?? '-' }}</td>
                        <td>Rs. {{ number_format($item->price) }}</td>
                        <td>
                            <span class="text-white">
                                <i class="fas fa-eye me-1 text-danger"></i>
                                {{ number_format($item->views) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-white">
                                <i class="fas fa-envelope me-1 text-danger"></i>
                                {{ $item->inquiries_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('items.show', $item->slug) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('user.items.edit', $item) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('user.items.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Delete this item?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-white">
                            No items yet.
                            <a href="{{ route('user.items.create') }}" class="text-danger">Add your first vehicle!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">
                {{ $items->links() }}
            </div>
        </div>
    </div>

    {{-- Inquiries Section --}}
    <div class="mt-5">
        <h5 class="fw-bold mb-3">Recent Inquiries on My Listings</h5>
        <div class="card bg-dark border-0 shadow">
            <div class="card-body p-0">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $inquiries = \App\Models\Inquiry::whereHas('item', function($q) {
                        $q->where('user_id', auth()->id());
                        })->with('item')->latest()->take(10)->get();
                        @endphp
                        @forelse($inquiries as $inquiry)
                        <tr>
                            <td>
                                <a href="{{ route('items.show', $inquiry->item->slug) }}"
                                    class="text-danger text-decoration-none" target="_blank">
                                    {{ Str::limit($inquiry->item->title, 25) }}
                                </a>
                            </td>
                            <td class="text-white">{{ $inquiry->name }}</td>
                            <td class="text-white">{{ $inquiry->phone }}</td>
                            <td class="text-muted">{{ Str::limit($inquiry->message, 30) ?? '—' }}</td>
                            <td class="text-muted">{{ $inquiry->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-white">No inquiries yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection