@extends('layouts.app')

@section('title', 'Search Vehicles - SM-Autos')

@section('content')

        <form action="{{ route('items.search') }}" method="GET" id="mainSearchFormPage">
            <div class="row g-2 align-items-end hero-search-form" style="background: rgba(0,0,0,0.2); border-radius: 12px; padding: 10px 20px; border: 1px solid rgba(255,255,255,0.05)">
                <div class="col-lg-10 col-md-8 search-input-group">
                    <div class="w-100">
                        <label class="form-label fw-semibold text-white small mb-1 ms-1">Ask AI to find your perfect vehicle</label>
                        <input type="text" class="search-input" name="keyword" id="searchKeywordPage"
                               placeholder="e.g. Alto in Karachi under 20 lacs"
                               value="{{ request('keyword') }}" style="text-align: left; padding-left: 20px; height: 50px; font-size: 1.1rem; border-radius: 8px;"/>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="w-100">
                        <label class="form-label d-none d-md-block mb-1">&nbsp;</label>
                        <button class="search-submit-btn w-100" type="submit" id="searchSubmitBtnPage" style="border-radius: 8px; height: 50px; background: linear-gradient(135deg, #e63946, #c1121f); font-weight: 700;">
                            <i class="fas fa-sparkles me-1" id="searchIconPage"></i> <span id="searchTextPage">AI Search</span>
                            <div class="spinner-border spinner-border-sm text-white d-none" id="searchSpinnerPage" role="status"></div>
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