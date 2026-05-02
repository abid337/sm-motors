@extends('layouts.app')

@section('title', 'Add Vehicle - SM-Autos')

@section('content')
<div class="container py-5">

    <div class="mb-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <h4 class="fw-bold mb-4">Add New Vehicle</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('user.items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">

                {{-- Basic Info --}}
                <div class="card bg-dark border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-white mb-4">Basic Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title') }}" placeholder="e.g. Honda CD 70 2024" required/>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Price (Rs.) *</label>
                                <input type="number" name="price" class="form-control"
                                       value="{{ old('price') }}" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <select name="city_id" class="form-select">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Condition</label>
                                <select name="condition" class="form-select">
                                    <option value="used">Used</option>
                                    <option value="new">New</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control"
                                      rows="4" placeholder="Describe your vehicle...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Properties --}}
                <div class="card bg-dark border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fw-bold text-white mb-0">Properties</h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="add-prop">
                                <i class="fas fa-plus me-1"></i> Add
                            </button>
                        </div>
                        <div id="properties-container">
                            <div class="row g-2 mb-2 prop-row">
                                <div class="col-5">
                                    <input type="text" name="prop_keys[]" class="form-control" placeholder="e.g. Mileage"/>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="prop_values[]" class="form-control" placeholder="e.g. 5000 km"/>
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-outline-danger remove-prop w-100">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gallery Images --}}
                <div class="card bg-dark border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-white mb-4">Gallery Images</h6>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple/>
                        <small class="text-muted">You can select multiple images</small>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card bg-dark border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-white mb-3">Main Photo</h6>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*"/>
                        <small class="text-muted">Main display image</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-5">
                    <i class="fas fa-plus me-2"></i> Add Vehicle
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('add-prop').addEventListener('click', function() {
    const container = document.getElementById('properties-container');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 prop-row';
    row.innerHTML = `
        <div class="col-5">
            <input type="text" name="prop_keys[]" class="form-control" placeholder="Key"/>
        </div>
        <div class="col-6">
            <input type="text" name="prop_values[]" class="form-control" placeholder="Value"/>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-outline-danger remove-prop w-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-prop')) {
        e.target.closest('.prop-row').remove();
    }
});
</script>
@endpush