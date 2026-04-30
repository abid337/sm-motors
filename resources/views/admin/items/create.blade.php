@extends('layouts.admin')

@section('title', 'Add Item - Smart CMS')
@section('page-title', 'Add New Item')

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Basic Info --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">Basic Information</h6>

                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control"
                        placeholder="e.g. Toyota Corolla GLi 2021"
                        value="{{ old('title') }}" required />
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Price (Rs.) *</label>
                        <input type="number" name="price" class="form-control"
                            placeholder="e.g. 4500000"
                            value="{{ old('price') }}" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                            <option value="{{ $city->id }}"
                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
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
                    <textarea name="description" class="form-control" rows="4"
                        placeholder="Vehicle description...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Properties --}}
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fw-bold text-white mb-0">Properties</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="add-prop">
                        <i class="fas fa-plus me-1"></i> Add Property
                    </button>
                </div>
                <div id="properties-container">
                    <div class="row g-2 mb-2 prop-row">
                        <div class="col-5">
                            <input type="text" name="prop_keys[]" class="form-control"
                                placeholder="Key (e.g. mileage)" />
                        </div>
                        <div class="col-6">
                            <input type="text" name="prop_values[]" class="form-control"
                                placeholder="Value (e.g. 50,000 km)" />
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

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- Status --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">Settings</h6>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="featured" class="form-check-input"
                        id="featured" value="1" />
                    <label class="form-check-label text-white" for="featured">
                        Mark as Featured
                    </label>
                </div>
            </div>

            {{-- Thumbnail --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-3">Thumbnail Image</h6>
                <input type="file"
                    name="thumbnail"
                    class="form-control"
                    accept="image/*"
                    id="thumbnail-input" />
                <div class="mt-3" id="thumbnail-preview"></div>
            </div>

            {{-- Extra Images --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-3">Extra Images</h6>
                <input type="file"
                    name="images[]"
                    class="form-control"
                    accept="image/*"
                    multiple
                    id="extra-images" />
                <small class="mt-2 d-block" style="color:rgba(255,255,255,0.7)">
                    <i class="fas fa-info-circle me-1 text-danger"></i>
                    <strong>Press</strong> ctrl and select multiple images
                </small>
                {{-- Selected count --}}
                <div id="images-count" class="mt-2"></div>
                {{-- Preview --}}
                <div id="extra-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-5">
                <i class="fas fa-save me-2"></i> Save Item
            </button>

        </div>
    </div>

</form>

@endsection

@push('scripts')
<script>
    // Add Property Row
    document.getElementById('add-prop').addEventListener('click', function() {
        const container = document.getElementById('properties-container');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 prop-row';
        row.innerHTML = `
        <div class="col-5">
            <input type="text" name="prop_keys[]" class="form-control"
                   placeholder="Key (e.g. engine)"/>
        </div>
        <div class="col-6">
            <input type="text" name="prop_values[]" class="form-control"
                   placeholder="Value (e.g. 1800cc)"/>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-outline-danger remove-prop w-100">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
        container.appendChild(row);
    });

    // Remove Property Row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-prop')) {
            e.target.closest('.prop-row').remove();
        }
    });

    // Thumbnail Preview
    document.getElementById('thumbnail-input').addEventListener('change', function() {
        const preview = document.getElementById('thumbnail-preview');
        if (this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `
                <img src="${e.target.result}"
                     class="img-fluid rounded-2"
                     style="max-height:150px; border:2px solid rgba(255,255,255,0.1)"/>
            `;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Extra Images Preview
    document.getElementById('extra-images').addEventListener('change', function() {
        const preview = document.getElementById('extra-preview');
        const countDiv = document.getElementById('images-count');
        preview.innerHTML = '';
        countDiv.innerHTML = '';

        if (this.files.length === 0) return;

        // Show count
        countDiv.innerHTML = `
        <span style="color:#e63946; font-size:0.85rem; font-weight:600;">
            <i class="fas fa-images me-1"></i>
            ${this.files.length} image(s) selected
        </span>
    `;

        // Make Preview
        Array.from(this.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative; display:inline-block';
                div.innerHTML = `
                <img src="${e.target.result}"
                     style="width:75px; height:60px; object-fit:cover;
                            border-radius:6px;
                            border:2px solid rgba(255,255,255,0.15)"/>
                <span style="position:absolute; top:-6px; right:-6px;
                             background:#e63946; color:white; border-radius:50%;
                             width:20px; height:20px; font-size:0.7rem;
                             display:flex; align-items:center; justify-content:center;
                             font-weight:700">
                    ${index + 1}
                </span>
            `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush