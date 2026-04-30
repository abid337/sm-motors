@extends('layouts.app')

@section('title', 'SM-Autos - Buy & Sell Vehicles')

@section('content')

{{-- HERO SECTION --}}
<section class="hero-section text-white text-center py-5" aria-label="Search vehicles">
    <div class="container py-4">
        <h1 class="fw-bold mb-3">Find Used Cars in Pakistan</h1>
        <p class="lead mb-5">With thousands of vehicles, we have just the right one for you</p>

        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="hero-search-card card">
                    <div class="card-body p-0">
                        <form action="{{ route('items.search') }}" method="GET" role="search">
                            <div class="row g-0">
                                <div class="col-lg-4">
                                    <input
                                        type="text"
                                        class="form-control form-control-lg"
                                        name="keyword"
                                        placeholder="Car Make or Model"
                                    />
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-select form-select-lg" name="city_id">
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-select form-select-lg" name="price_range">
                                        <option value="">Select Price Range</option>
                                        <option value="0-500000">Under 5 Lac</option>
                                        <option value="500000-1500000">5 - 15 Lac</option>
                                        <option value="1500000-3000000">15 - 30 Lac</option>
                                        <option value="3000000-6000000">30 - 60 Lac</option>
                                        <option value="6000000-999999999">Above 60 Lac</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn search-btn w-100 h-100 fw-bold fs-5">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURED ITEMS --}}
@if($featured_items->count() > 0)
<section class="py-5 bg-light-brand">
    <div class="container">
        <h2 class="section-title">Featured Vehicles</h2>
        <div class="row g-4">
            @foreach($featured_items as $item)
                @include('components.vehicle-card', ['item' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- LATEST ITEMS BY CATEGORY --}}
@foreach($categories as $category)
    @if($category->items_count > 0)
    <section class="py-5 {{ $loop->even ? '' : 'bg-light-brand' }}">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="section-title mb-0">
                    @if($category->icon)
                        <i class="{{ $category->icon }} me-2 text-red"></i>
                    @endif
                    {{ $category->name }}
                </h2>
                <a href="{{ route('items.search', ['category' => $category->slug]) }}"
                   class="btn btn-outline-danger btn-sm">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach($category->items->take(4) as $item)
                    @include('components.vehicle-card', ['item' => $item])
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endforeach

@endsection