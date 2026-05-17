@extends('layouts.app')

@section('title', 'Search Vehicles - SM-Autos')

@section('content')

{{-- FILTER BAR --}}
<div class="filter-bar">
    <div class="container">
        <!-- AI SMART SEARCH -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <form id="aiSearchFormPage" style="background: rgba(0,0,0,0.2); border-radius: 12px; padding: 10px; border: 1px solid rgba(255,255,255,0.1); display: flex; gap: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <div style="flex: 1; display: flex; align-items: center; padding-left: 10px;">
                        <i class="fas fa-robot text-danger fs-5 me-2"></i>
                        <input type="text" id="aiSearchInputPage" class="search-input" placeholder="Ask AI: e.g. Alto in Karachi under 20 lacs" style="text-align: left; padding: 0 10px; border: none !important; background: transparent !important; color: white !important; width: 100%; outline: none;" />
                    </div>
                    <button class="btn btn-danger" type="submit" id="aiSearchBtnPage" style="border-radius: 8px; padding: 0 20px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                        <span id="aiSearchTextPage">Ask AI</span>
                        <div class="spinner-border spinner-border-sm text-white d-none" id="aiSearchSpinnerPage" role="status"></div>
                    </button>
                </form>
            </div>
        </div>

        <form action="{{ route('items.search') }}" method="GET" id="mainSearchFormPage">
            <div class="row g-2 align-items-end hero-search-form" style="background: rgba(0,0,0,0.2); border-radius: 12px; padding: 10px 20px; border: 1px solid rgba(255,255,255,0.05)">
                <div class="col-lg-4 col-md-6 search-input-group">
                    <div class="w-100">
                        <label class="form-label fw-semibold text-white small mb-1 ms-1">Search Keyword</label>
                        <input type="text" class="search-input" name="keyword"
                               placeholder="Car Make or Model"
                               value="{{ request('keyword') }}" style="text-align: left; padding-left: 15px; height: 50px;"/>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 search-input-group">
                    <div class="w-100">
                        <label class="form-label fw-semibold text-white small mb-1 ms-1">City</label>
                        <select class="search-select" name="city_id" style="text-align: left; padding-left: 15px; height: 50px;">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 search-input-group">
                    <div class="w-100">
                        <label class="form-label fw-semibold text-white small mb-1 ms-1">Price Range</label>
                        <select class="search-select" name="price_range" style="text-align: left; padding-left: 15px; height: 50px;">
                            <option value="">All Prices</option>
                            <option value="0-500000" {{ request('price_range') == '0-500000' ? 'selected' : '' }}>Under 5 Lac</option>
                            <option value="500000-1500000" {{ request('price_range') == '500000-1500000' ? 'selected' : '' }}>5 - 15 Lac</option>
                            <option value="1500000-3000000" {{ request('price_range') == '1500000-3000000' ? 'selected' : '' }}>15 - 30 Lac</option>
                            <option value="3000000-6000000" {{ request('price_range') == '3000000-6000000' ? 'selected' : '' }}>30 - 60 Lac</option>
                            <option value="6000000-999999999" {{ request('price_range') == '6000000-999999999' ? 'selected' : '' }}>Above 60 Lac</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="w-100">
                        <label class="form-label d-none d-lg-block mb-1">&nbsp;</label>
                        <button class="search-submit-btn w-100" type="submit" style="border-radius: 8px; height: 50px;">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- RESULTS --}}
<main class="py-5" style="background: #0a0a0a;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h2 class="section-title mb-0 text-white">Search Results</h2>
            <div class="text-white small opacity-75">{{ $items->total() }} vehicles found</div>
        </div>

        @if($items->count() > 0)
            <div class="row g-4">
                @foreach($items as $item)
                    @include('components.vehicle-card', ['item' => $item])
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">
                {{ $items->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-car fa-4x text-muted mb-3"></i>
                <h4 class="text-muted-brand">No vehicles found</h4>
                <p class="text-muted-brand">Try different search filters</p>
            </div>
        @endif
    </div>
</main>

@endsection