@extends('layouts.app')

@section('title', 'Edit Vehicle - SM-Autos')

@section('content')
<div class="container py-5">

    <div class="mb-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <h4 class="fw-bold mb-4">Edit Vehicle</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">

                {{-- Basic Info --}}
                <div class="card bg-dark border-0 shadow mb-4">
                    <div class="card-body p-4">
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
                                        <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
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
                                        <option value="{{ $city->id }}" {{ $item->city_id == $city->id ? 'selected' : '' }}>
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
                </div>

                {{-- Gallery Images --}}
                <div class="card bg-dark border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-white mb-4">Gallery Images</h6>

                        @if($item->media && $item->media->count() > 0)
                            <div class="row g-2 mb-3">
                                @foreach($item->media as $media)
                                <div class="col-4 position-relative">
                                    <img src="{{ $media->file_path }}"
                                         class="img-fluid rounded-2"
                                         style="height:100px; width:100%; object-fit:cover;"/>
                                    <form action="{{ route('user.items.media.delete', $media->id) }}"
                                          method="POST"
                                          style="position:absolute; top:0; right:0; margin:4px">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this image?')"
                                                style="padding: 2px 6px;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-white mb-3">No gallery images yet.</p>
                        @endif

                        <label class="form-label text-white">Add More Images</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple/>
                        <small class="text-white">You can select multiple images</small>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card bg-dark border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-white mb-3">Main Photo</h6>
                        @if($item->thumbnail)
                            <img src="{{ $item->thumbnail }}"
                                 class="img-fluid rounded-2 mb-3" style="max-height:150px"/>
                        @endif
                        <input type="file" name="thumbnail" class="form-control" accept="image/*"/>
                        <small class="text-white">Leave empty to keep current</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-5">
                    <i class="fas fa-save me-2"></i> Update Vehicle
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