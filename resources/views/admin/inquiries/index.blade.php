@extends('layouts.admin')
@section('title', 'Inquiries')
@section('page-title', 'Inquiries')

@section('content')

<div class="admin-table">
    <table class="table table-dark table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Message</th>
                <th>Item</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inquiries as $inquiry)
            <tr>
                <td class="text-muted">{{ $inquiry->id }}</td>
                <td><strong>{{ $inquiry->name }}</strong></td>
                <td>
                    <a href="tel:{{ $inquiry->phone }}" class="text-white text-decoration-none">
                        {{ $inquiry->phone }}
                    </a>
                </td>
                <td>
                    @if($inquiry->email)
                        <a href="mailto:{{ $inquiry->email }}" class="text-danger text-decoration-none">
                            {{ $inquiry->email }}
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
               <td style="color:rgba(255,255,255,0.85)">{{ Str::limit($inquiry->message ?? '-', 35) }}</td>
                <td>
                    @if($inquiry->item)
                        <a href="{{ route('items.show', $inquiry->item->slug) }}"
                           class="text-danger" target="_blank">
                            {{ Str::limit($inquiry->item->title, 20) }}
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-muted">
                    <div>{{ $inquiry->created_at->format('d M Y') }}</div>
                    <small style="color:rgba(255,255,255,0.3)">
                        {{ $inquiry->created_at->format('h:i A') }}
                    </small>
                </td>
                <td>
                    <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}"
                          method="POST" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">No inquiries yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">{{ $inquiries->links() }}</div>
</div>

@endsection