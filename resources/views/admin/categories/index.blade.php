@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')

<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Add Category
    </a>
</div>

<div class="admin-table">
    <table class="table table-dark table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Items</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr>
                <td class="text-white">{{ $cat->id }}</td>
                <td><strong>{{ $cat->name }}</strong></td>
                <td>
                    @if($cat->icon)
                        <i class="{{ $cat->icon }}"></i>
                    @else
                        <span class="text-white">-</span>
                    @endif
                </td>
                <td>{{ $cat->items_count }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.categories.edit', $cat) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}"
                              method="POST" onsubmit="return confirm('Delete?')">
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
                <td colspan="5" class="text-center py-4 text-white">No categories yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection