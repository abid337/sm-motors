@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="admin-card">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $category->name }}" required/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon Class</label>
                    <input type="text" name="icon" class="form-control"
                           value="{{ $category->icon }}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-select">
                        <option value="">None</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}"
                                {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">Order</label>
                    <input type="number" name="order" class="form-control"
                           value="{{ $category->order }}"/>
                </div>
                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                    <i class="fas fa-save me-2"></i> Update Category
                </button>
            </form>
        </div>
    </div>
</div>

@endsection