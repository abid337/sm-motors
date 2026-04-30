@extends('layouts.admin')

@section('title', 'Dashboard - Smart CMS')
@section('page-title', 'Dashboard')

@section('content')

{{-- STATS --}}
<div class="row g-4 mb-4">
    <div class="col-lg-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon mb-3"><i class="fas fa-car"></i></div>
            <div class="stat-number">{{ $stats['total_items'] }}</div>
            <div class="stat-label">Total Items</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon mb-3"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $stats['published_items'] }}</div>
            <div class="stat-label">Published</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon mb-3"><i class="fas fa-edit"></i></div>
            <div class="stat-number">{{ $stats['draft_items'] }}</div>
            <div class="stat-label">Drafts</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon mb-3"><i class="fas fa-tags"></i></div>
            <div class="stat-number">{{ $stats['total_categories'] }}</div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon mb-3"><i class="fas fa-map-marker-alt"></i></div>
            <div class="stat-number">{{ $stats['total_cities'] }}</div>
            <div class="stat-label">Cities</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon mb-3"><i class="fas fa-envelope"></i></div>
            <div class="stat-number">{{ $stats['total_inquiries'] }}</div>
            <div class="stat-label">Inquiries</div>
        </div>
    </div>
</div>

{{-- RECENT ITEMS + INQUIRIES --}}
<div class="row g-4">

    {{-- Recent Items --}}
    <div class="col-lg-7">
        <div class="admin-table">
            <div class="d-flex align-items-center justify-content-between p-3"
                style="border-bottom:1px solid rgba(255,255,255,0.06)">
                <h6 class="mb-0 fw-bold text-white">Recent Items</h6>
                <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-danger">View All</a>
            </div>
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_items as $item)
                    <tr>
                        <td>
                            <a href="{{ route('admin.items.edit', $item) }}"
                                class="text-white text-decoration-none">
                                {{ Str::limit($item->title, 30) }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $item->category->name ?? '-' }}</td>
                        <td>Rs. {{ number_format($item->price) }}</td>
                        <td>
                            <span class="badge {{ $item->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No items yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Inquiries --}}
    <div class="col-lg-5">
        <div class="admin-table">
            <div class="d-flex align-items-center justify-content-between p-3"
                style="border-bottom:1px solid rgba(255,255,255,0.06)">
                <h6 class="mb-0 fw-bold text-white">Recent Inquiries</h6>
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-danger">View All</a>
            </div>
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Item</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_inquiries as $inquiry)
                    <tr>
                        <td>{{ $inquiry->name }}</td>
                        <td>{{ $inquiry->phone }}</td>
                        <td style="color:rgba(255,255,255,0.85)">{{ Str::limit($inquiry->message ?? '-', 20) }}</td>
                        <td class="text-muted">
                            {{ Str::limit($inquiry->item->title ?? '-', 15) }}
                        </td>
                        <td class="text-muted" style="font-size:0.8rem">
                            {{ $inquiry->created_at->format('d M Y') }}<br>
                            <span style="color:rgba(255,255,255,0.3)">
                                {{ $inquiry->created_at->format('h:i A') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No inquiries yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection