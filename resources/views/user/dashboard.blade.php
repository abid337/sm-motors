@extends('layouts.app')

@section('title', 'My Dashboard - SM-Autos')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">My Dashboard</h4>
            <p class="text-muted mb-0">Welcome, {{ auth()->user()->name }}!</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('user.items.create') }}" class="btn btn-danger">
                <i class="fas fa-plus me-2"></i> Add New Vehicle
            </a>
            <form action="{{ route('user.logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
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
                                     style="width:50px;height:40px;object-fit:cover;border-radius:6px"/>
                            @else
                                <div style="width:50px;height:40px;background:#242424;border-radius:6px;display:flex;align-items:center;justify-content:center">
                                    <i class="fas fa-car text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ Str::limit($item->title, 35) }}</strong></td>
                        <td class="text-white">{{ $item->category->name ?? '-' }}</td>
                        <td>Rs. {{ number_format($item->price) }}</td>
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
                        <td colspan="6" class="text-center py-5 text-muted">
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
</div>
@endsection