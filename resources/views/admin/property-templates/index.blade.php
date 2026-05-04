@extends('layouts.admin')

@section('title', 'Property Templates - Smart CMS')
@section('page-title', 'Property Templates')

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Add New Property Form --}}
<div class="admin-card mb-4">
    <h6 class="fw-bold text-white mb-4">Add New Property</h6>
    <form action="{{ route('admin.property-templates.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Label *</label>
                <input type="text" name="label" class="form-control"
                    placeholder="e.g. Mileage" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Placeholder</label>
                <input type="text" name="placeholder" class="form-control"
                    placeholder="e.g. Enter mileage in km" />
            </div>
            <div class="col-md-1">
                <label class="form-label">Order</label>
                <input type="number" name="sort_order" class="form-control"
                    placeholder="0" value="0" />
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input type="checkbox" name="required" class="form-check-input" id="required" />
                    <label class="form-check-label text-white" for="required">Required</label>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Properties by Category --}}
@foreach($categories as $category)
<div class="admin-card mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold text-white mb-0">
            <i class="fas fa-tag me-2 text-danger"></i>{{ $category->name }}
        </h6>
        <span class="badge bg-secondary">{{ $category->propertyTemplates->count() }} properties</span>
    </div>

    @if($category->propertyTemplates->count() > 0)
    <div class="admin-table">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Placeholder</th>
                    <th>Required</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($category->propertyTemplates as $template)
                <tr>
                    <td class="text-white">{{ $template->label }}</td>
                    <td class="text-muted">{{ $template->placeholder ?? '—' }}</td>
                    <td>
                        @if($template->required)
                        <span class="badge bg-danger">Yes</span>
                        @else
                        <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td class="text-white">{{ $template->sort_order }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            {{-- Edit Button --}}
                            <button class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $template->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            {{-- Delete --}}
                            <form action="{{ route('admin.property-templates.destroy', $template) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this property?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $template->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title">Edit Property</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.property-templates.update', $template) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Label *</label>
                                        <input type="text" name="label" class="form-control"
                                            value="{{ $template->label }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Placeholder</label>
                                        <input type="text" name="placeholder" class="form-control"
                                            value="{{ $template->placeholder }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Order</label>
                                        <input type="number" name="sort_order" class="form-control"
                                            value="{{ $template->sort_order }}" />
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="required" class="form-check-input"
                                            id="req{{ $template->id }}"
                                            {{ $template->required ? 'checked' : '' }} />
                                        <label class="form-check-label" for="req{{ $template->id }}">Required</label>
                                    </div>
                                </div>
                                <div class="modal-footer border-secondary">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-muted mb-0">No properties defined for this category yet.</p>
    @endif
</div>
@endforeach

@endsection