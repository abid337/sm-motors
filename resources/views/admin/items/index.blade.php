@extends('layouts.admin')

@section('title', 'Items - Smart CMS')
@section('page-title', 'Items')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-white-brand mb-0">Total: {{ $items->total() }} items</p>
    <a href="{{ route('admin.items.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Add New Item
    </a>
</div>

<div class="admin-table">
    <table class="table table-dark table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Category</th>
                <th>City</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td class="text-white">{{ $item->id }}</td>
                <td>
                    @if($item->thumbnail)
                        <img src="{{ $item->thumbnail }}"
                             style="width:50px;height:40px;object-fit:cover;border-radius:6px"/>
                    @else
                        <div style="width:50px;height:40px;background:#242424;border-radius:6px;display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-car text-white"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <strong>{{ Str::limit($item->title, 35) }}</strong>
                    @if($item->featured)
                        <span class="badge bg-danger ms-1" style="font-size:0.65rem">Featured</span>
                    @endif
                </td>
                <td class="text-white">{{ $item->category->name ?? '-' }}</td>
                <td class="text-white">{{ $item->city->name ?? '-' }}</td>
                <td>Rs. {{ number_format($item->price) }}</td>
                <td>
                    <span class="badge {{ $item->status === 'published' ? 'badge-published' : 'badge-draft' }}">
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
                        <a href="{{ route('admin.items.edit', $item) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.items.destroy', $item) }}" method="POST"
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
                    No items found. <a href="{{ route('admin.items.create') }}" class="text-danger">Add first item</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">
        {{ $items->links() }}
    </div>
</div>

@endsection