@extends('layouts.admin')
@section('title', 'Edit City')
@section('page-title', 'Edit City')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="admin-card">
            <form action="{{ route('admin.cities.update', $city) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="form-label">City Name *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $city->name }}" required/>
                </div>
                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                    <i class="fas fa-save me-2"></i> Update City
                </button>
            </form>
        </div>
    </div>
</div>
@endsection