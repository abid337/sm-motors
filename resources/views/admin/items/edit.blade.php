@extends('layouts.admin')

@section('title', 'Edit Item - Smart CMS')
@section('page-title', 'Edit Item')

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <h6 class="fw-bold text-white mb-4">Basic Information</h6>
            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ $item->title }}" required/>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Price (Rs.) *</label>
                    <input type="number" name="price" class="form-control"
                           value="{{ $item->price }}" required/>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $item->category_id == $cat->id ? 'selected' : '' }}>
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
                                {{ $item->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Condition</label>
                    <select name="condition" class="form-select">
                        <option value="used" {{ $item->condition === 'used' ? 'selected' : '' }}>Used</option>
                        <option value="new" {{ $item->condition === 'new' ? 'selected' : '' }}>New</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"
                          rows="4">{{ $item->description }}</textarea>
            </div>
        </div>

        {{-- Properties --}}
        <div class="admin-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold text-white mb-0">Properties</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" id="add-prop">
                    <i class="fas fa-plus me-1"></i> Add
                </button>
            </div>
            <div id="properties-container">
                @foreach($item->properties as $prop)
                <div class="row g-2 mb-2 prop-row">
                    <div class="col-5">
                        <input type="text" name="prop_keys[]" class="form-control"
                               value="{{ $prop->key }}"/>
                    </div>
                    <div class="col-6">
                        <input type="text" name="prop_values[]" class="form-control"
                               value="{{ $prop->value }}"/>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-outline-danger remove-prop w-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Gallery Images --}}
        <div class="admin-card mb-4">
            <h6 class="fw-bold text-white mb-4">Gallery Images</h6>

            {{-- Existing Images --}}
            @if($item->media && $item->media->count() > 0)
                <div class="row g-2 mb-3">
                    @foreach($item->media as $media)
                    <div class="col-4 position-relative" id="media-{{ $media->id }}">
                        <img src="{{ $media->file_path }}"
                             class="img-fluid rounded-2"
                             style="height:100px; width:100%; object-fit:cover;"/>
                        <a href="{{ route('admin.items.media.delete', $media->id) }}"
                           class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                           onclick="return confirm('Delete this image?')"
                           style="padding: 2px 6px;">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-3">No gallery images yet.</p>
            @endif

            {{-- Upload New Images --}}
            <label class="form-label text-white">Add More Images</label>
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple/>
            <small class="text-muted">You can select multiple images</small>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <h6 class="fw-bold text-white mb-4">Settings</h6>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="published" {{ $item->status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ $item->status === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="form-check">
                <input type="checkbox" name="featured" class="form-check-input"
                       id="featured" value="1" {{ $item->featured ? 'checked' : '' }}/>
                <label class="form-check-label text-white" for="featured">Mark as Featured</label>
            </div>
        </div>

        <div class="admin-card mb-4">
            <h6 class="fw-bold text-white mb-3">Thumbnail</h6>
            @if($item->thumbnail)
                <img src="{{ $item->thumbnail }}"
                     class="img-fluid rounded-2 mb-3" style="max-height:150px"/>
            @endif
            <input type="file" name="thumbnail" class="form-control" accept="image/*"/>
            <small class="text-muted">Leave empty to keep current</small>
        </div>

        <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-5">
            <i class="fas fa-save me-2"></i> Update Item
        </button>
    </div>
</div>

</form>

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