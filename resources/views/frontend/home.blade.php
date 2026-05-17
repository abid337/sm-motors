@extends('layouts.app')

@section('title', 'SM-Autos - Buy & Sell Vehicles')

@section('content')

{{-- HERO SECTION --}}
<section class="hero-section text-white text-center py-5" aria-label="Search vehicles">
    <div class="container py-4">
        <h1 class="fw-bold mb-3">{{ setting('hero_title', 'Find Used Cars in Pakistan') }}</h1>
        <p class="lead mb-5">{{ setting('hero_subtitle', 'With thousands of vehicles, we have just the right one for you') }}</p>

        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="hero-search-wrapper">
                    <form action="{{ route('items.search') }}" method="GET" class="hero-search-form" id="mainSearchForm">
                        <div class="search-input-group" style="flex: 1;">
                            <input type="text" name="keyword" id="searchKeyword" class="search-input" placeholder="Ask AI: e.g. Civic in Lahore under 50 lacs" style="text-align: left; padding-left: 20px; font-size: 1.1rem;"/>
                        </div>
                        <button class="search-submit-btn" type="submit" id="searchSubmitBtn" style="background: linear-gradient(135deg, #e63946, #c1121f); font-weight: 700; border-radius: 0 50px 50px 0; padding: 0 40px;">
                            <i class="fas fa-sparkles me-2" id="searchIcon"></i> <span id="searchText">AI Search</span>
                            <div class="spinner-border spinner-border-sm text-white d-none" id="searchSpinner" role="status"></div>
                        </button>
                    </form>
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