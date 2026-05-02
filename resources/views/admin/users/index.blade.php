@extends('layouts.admin')

@section('title', 'Users - Smart CMS')
@section('page-title', 'Users')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted-brand mb-0">Total: {{ $users->total() }} users</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="admin-table">
    <table class="table table-dark table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Items</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="text-muted">{{ $user->id }}</td>
                <td><strong>{{ $user->name }}</strong></td>
                <td class="text-white">{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-success' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="text-white">{{ $user->items_count }}</td>
                <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    @if(!$user->isAdmin())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                          onsubmit="return confirm('Delete this user and all their items?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">
        {{ $users->links() }}
    </div>
</div>

@endsection