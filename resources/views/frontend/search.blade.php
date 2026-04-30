@extends('layouts.app')

@section('title', 'Search Vehicles - SM-Autos')

@section('content')

{{-- FILTER BAR --}}
<div class="filter-bar">
    <div class="container">
        <form action="{{ route('items.search') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-semibold text-muted-brand small mb-1">Search Keyword</label>
                    <input type="text" class="form-control" name="keyword"
                           placeholder="Car Make or Model"
                           value="{{ request('keyword') }}"/>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold text-muted-brand small mb-1">City</label>
                    <select class="form-select" name="city_id">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold text-muted-brand small mb-1">Price Range</label>
                    <select class="form-select" name="price_range">
                        <option value="">All Prices</option>
                        <option value="0-500000" {{ request('price_range') == '0-500000' ? 'selected' : '' }}>Under 5 Lac</option>
                        <option value="500000-1500000" {{ request('price_range') == '500000-1500000' ? 'selected' : '' }}>5 - 15 Lac</option>
                        <option value="1500000-3000000" {{ request('price_range') == '1500000-3000000' ? 'selected' : '' }}>15 - 30 Lac</option>
                        <option value="3000000-6000000" {{ request('price_range') == '3000000-6000000' ? 'selected' : '' }}>30 - 60 Lac</option>
                        <option value="6000000-999999999" {{ request('price_range') == '6000000-999999999' ? 'selected' : '' }}>Above 60 Lac</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <button class="btn btn-primary-brand w-100 py-2" type="submit">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- RESULTS --}}
<main class="py-5 bg-light-brand">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h2 class="section-title mb-0">Search Results</h2>
            <div class="text-muted-brand small">{{ $items->total() }} vehicles found</div>
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