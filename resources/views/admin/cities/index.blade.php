@extends('layouts.admin')
@section('title', 'Cities')
@section('page-title', 'Cities')

@section('content')

<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.cities.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Add City
    </a>
</div>

<div class="admin-table">
    <table class="table table-dark table-hover mb-0">
        <thead>
            <tr><th>#</th><th>City Name</th><th>Items</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($cities as $city)
            <tr>
                <td class="text-white">{{ $city->id }}</td>
                <td><strong>{{ $city->name }}</strong></td>
                <td>{{ $city->items_count }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.cities.edit', $city) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.cities.destroy', $city) }}"
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
                <td colspan="4" class="text-center py-4 text-white">No cities yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection