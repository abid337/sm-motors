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
                    <!-- AI Search Form -->
                    <form id="aiSearchForm" class="mb-4" style="background: rgba(0, 0, 0, 0.45); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 100px; padding: 5px; display: flex; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);">
                        <div style="flex: 1; display: flex; align-items: center; padding-left: 20px;">
                            <i class="fas fa-robot text-danger fs-5 me-2"></i>
                            <input type="text" id="aiSearchInput" class="search-input" placeholder="Ask AI: e.g. Honda Civic in Lahore under 50 lacs" style="text-align: left; padding: 0 10px; width: 100%; outline: none;" />
                        </div>
                        <button class="search-submit-btn" type="submit" id="aiSearchBtn" style="border-radius: 50px; padding: 0 25px; display: flex; align-items: center; gap: 8px;">
                            <span id="aiSearchText">Ask AI</span>
                            <div class="spinner-border spinner-border-sm text-white d-none" id="aiSearchSpinner" role="status"></div>
                        </button>
                    </form>

                    <div class="text-white opacity-50 mb-4 small fw-bold tracking-widest">- OR MANUAL SEARCH -</div>

                    <form action="{{ route('items.search') }}" method="GET" class="hero-search-form" id="mainSearchForm">
                        <div class="search-input-group">
                            <input type="text" name="keyword" id="searchKeyword" class="search-input" placeholder="Car Make or Model (or ask AI...)" />
                        </div>
                        <div class="search-input-group">
                            <select name="city_id" id="searchCity" class="search-select">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="search-input-group">
                            <select name="price_range" id="searchPrice" class="search-select">
                                <option value="">All Prices</option>
                                <option value="0-500000">Under 5 Lac</option>
                                <option value="500000-1500000">5 - 15 Lac</option>
                                <option value="1500000-3000000">15 - 30 Lac</option>
                                <option value="3000000-6000000">30 - 60 Lac</option>
                                <option value="6000000-999999999">Above 60 Lac</option>
                            </select>
                        </div>
                        <button class="search-submit-btn" type="submit" id="searchSubmitBtn">
                            <i class="fas fa-search" id="searchIcon"></i>
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