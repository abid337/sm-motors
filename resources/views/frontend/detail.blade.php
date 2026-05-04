@extends('layouts.app')

@section('title', $item->title . ' - SM-Autos')

@section('content')

{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.search') }}">Search</a></li>
                <li class="breadcrumb-item active">{{ $item->title }}</li>
            </ol>
        </nav>
    </div>
</div>

{{-- DETAIL --}}
<main class="py-5 bg-light-brand">
    <div class="container">
        <div class="row g-4">

            {{-- LEFT: Images + Details --}}
            <div class="col-lg-8">

                {{-- Main Image --}}
                <div class="detail-img-wrap mb-3">
                    @if($item->thumbnail)
                    <img src="{{ $item->thumbnail }}"
                        alt="{{ $item->title }}"
                        class="img-fluid rounded-3 w-100"
                        id="main-image"
                        style="height:400px; object-fit:cover; transition: all 0.3s" />
                    @else
                    <div class="rounded-3 w-100 d-flex align-items-center justify-content-center"
                        style="height:400px; background:#1a1a1a">
                        <i class="fas fa-car fa-4x text-white"></i>
                    </div>
                    @endif
                </div>

                {{-- Gallery Thumbnails --}}
                @php
                $hasThumb = $item->thumbnail ? true : false;
                $hasMedia = $item->media->count() > 0;
                @endphp

                @if($hasThumb || $hasMedia)
                <div class="d-flex gap-2 flex-wrap mb-3">
                    @if($hasThumb)
                    <img src="{{ $item->thumbnail }}"
                        class="gallery-thumb"
                        style="width:85px; height:65px; object-fit:cover; cursor:pointer;
                                border-radius:8px; border:2px solid #e63946; transition: all 0.2s"
                        onclick="changeImage(this, '{{ $item->thumbnail }}')"
                        alt="thumbnail" />
                    @endif

                    @foreach($item->media as $media)
                    <img src="{{ $media->file_path }}"
                        class="gallery-thumb"
                        style="width:85px; height:65px; object-fit:cover; cursor:pointer;
                                border-radius:8px; border:2px solid transparent; transition: all 0.2s"
                        onclick="changeImage(this, '{{ $media->file_path }}')"
                        alt="image {{ $loop->iteration }}" />
                    @endforeach
                </div>
                @endif

                {{-- Views Counter --}}
                <div class="mb-4">
                    <small class="text-white">
                        <i class="fas fa-eye me-1"></i> {{ number_format($item->views) }} views
                    </small>
                </div>

                {{-- ===== CAR DETAILS NEECHE GALLERY KE ===== --}}
                <div class="detail-info-card card p-4">

                    {{-- Badges --}}
                    <div class="mb-3">
                        <span class="badge {{ $item->condition === 'new' ? 'bg-success' : 'bg-warning text-dark' }} me-2">
                            {{ strtoupper($item->condition) }}
                        </span>
                        @if($item->featured)
                        <span class="badge bg-danger">FEATURED</span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h2 class="fw-bold mb-2">{{ $item->title }}</h2>

                    {{-- Price --}}
                    <div class="detail-price mb-3">
                        Rs. {{ number_format($item->price) }}
                    </div>

                    {{-- Location & Category --}}
                    <div class="d-flex gap-3 mb-4 flex-wrap">
                        @if($item->city)
                        <span class="text-muted-brand">
                            <i class="fas fa-map-marker-alt text-red me-1"></i>
                            {{ $item->city->name }}
                        </span>
                        @endif
                        @if($item->category)
                        <span class="text-white-brand">
                            <i class="fas fa-tag text-red me-1"></i>
                            {{ $item->category->name }}
                        </span>
                        @endif
                    </div>

                    {{-- Properties --}}
                    @if($item->properties->count() > 0)
                    <div class="properties-grid mb-4">
                        @foreach($item->properties as $prop)
                        <div class="prop-item">
                            <span class="prop-key">{{ ucfirst(str_replace('_', ' ', $prop->key)) }}</span>
                            <span class="prop-value">{{ $prop->value }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Description --}}
                    @if($item->description)
                    <p class="text-white-brand mb-0">{{ $item->description }}</p>
                    @endif

                </div>
            </div>

            {{-- RIGHT: Sticky Seller Info + Inquiry Form --}}
            <div class="col-lg-4">
                <div class="sticky-sidebar">

                    {{-- Seller Info Card --}}
                    <div class="card p-4 mb-3">
                        <h6 class="fw-bold mb-3" style="color: #1a1a1a !important">
                            <i class="fas fa-user-circle me-2 text-danger"></i>Seller Information
                        </h6>

                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center 
                    justify-content-center me-3 fw-bold"
                                style="width:48px; height:48px; font-size:18px; flex-shrink:0;">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="mb-0 fw-bold" style="color: #1a1a1a !important">{{ $item->user->name }}</p>
                                @if($item->user->city)
                                <small class="text-muted">{{ $item->user->city }}</small>
                                @endif
                            </div>
                        </div>

                        {{-- Call Button --}}
                        @if($item->user->phone)
                        <a href="tel:{{ $item->user->phone }}"
                            class="btn btn-danger w-100 fw-bold mb-2">
                            <i class="fas fa-phone me-2"></i> Call Seller
                        </a>
                        @endif

                        {{-- WhatsApp Button --}}
                        @if($item->user->whatsapp)
                        <a href="https://wa.me/92{{ ltrim($item->user->whatsapp, '0') }}?text=Hi, I'm interested in your listing: {{ $item->title }}"
                            target="_blank"
                            class="btn btn-success w-100 fw-bold">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp
                        </a>
                        @endif
                    </div>

                    {{-- Inquiry Form Card --}}
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3" style="color: #1a1a1a !important">
                            <i class="fas fa-envelope me-2 text-danger"></i>Send Inquiry
                        </h6>

                        {{-- Success Message --}}
                        @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                        @endif

                        {{-- Errors --}}
                        @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                            <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                        @endif

                        <form action="{{ route('items.inquiry') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}" />

                            <div class="mb-3">
                                <input type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Your Name *"
                                    value="{{ old('name') }}"
                                    required />
                            </div>

                            <div class="mb-3">
                                <input type="text"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Phone Number *"
                                    value="{{ old('phone') }}"
                                    required />
                            </div>

                            <div class="mb-3">
                                <input type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email Address (Optional)"
                                    value="{{ old('email') }}" />
                            </div>

                            <div class="mb-3">
                                <textarea name="message"
                                    class="form-control @error('message') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Your Message (Optional)">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary-brand w-100 py-2 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i> Send Inquiry
                            </button>
                        </form>

                        {{-- Report Button --}}
                        <div class="mt-3 text-center">
                            <button class="btn btn-link text-muted small p-0"
                                data-bs-toggle="modal" data-bs-target="#reportModal">
                                <i class="fas fa-flag me-1"></i> Report this listing
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>

{{-- RELATED VEHICLES --}}
@if($related->count() > 0)
<section class="py-5">
    <div class="container">
        <h3 class="section-title">Related Vehicles</h3>
        <div class="row g-4">
            @foreach($related as $relItem)
            @include('components.vehicle-card', ['item' => $relItem])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- REPORT MODAL --}}
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-flag me-2 text-danger"></i>Report Listing</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('items.report') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}" />
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason *</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Select Reason</option>
                            <option value="Wrong Information">Wrong Information</option>
                            <option value="Fake Listing">Fake Listing</option>
                            <option value="Wrong Photos">Wrong Photos</option>
                            <option value="Already Sold">Already Sold</option>
                            <option value="Spam">Spam</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Tell us more..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-flag me-1"></i> Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('styles')
<style>
    .sticky-sidebar {
        position: sticky;
        top: 20px;
    }
</style>
@endpush
@push('scripts')
<script>
    function changeImage(clickedThumb, src) {
        document.getElementById('main-image').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(function(thumb) {
            thumb.style.border = '2px solid transparent';
        });
        clickedThumb.style.border = '2px solid #e63946';
    }
</script>
@endpush