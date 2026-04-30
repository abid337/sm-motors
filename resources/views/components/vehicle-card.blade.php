<div class="col-lg-3 col-md-6">
    <a href="{{ route('items.show', $item->slug) }}" class="text-decoration-none">
        <div class="vehicle-card card h-100">
            {{-- Image --}}
            <div class="vehicle-img-wrap">
                @if($item->thumbnail)
                    <img src="{{ asset('storage/' . $item->thumbnail) }}"
                         alt="{{ $item->title }}"
                         class="vehicle-img"/>
                @else
                    <div class="vehicle-img-placeholder d-flex align-items-center justify-content-center">
                        <i class="fas fa-car fa-3x text-muted"></i>
                    </div>
                @endif
                {{-- Badges --}}
                <div class="vehicle-badges">
                    <span class="badge {{ $item->condition === 'new' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ strtoupper($item->condition) }}
                    </span>
                    @if($item->featured)
                        <span class="badge bg-danger ms-1">FEATURED</span>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="card-body">
                <h6 class="vehicle-title">{{ $item->title }}</h6>
                <div class="vehicle-price">
                    Rs. {{ number_format($item->price) }}
                </div>
                <div class="vehicle-meta mt-2">
                    @if($item->city)
                        <span><i class="fas fa-map-marker-alt me-1 text-red"></i>{{ $item->city->name }}</span>
                    @endif
                    @if($item->category)
                        <span class="ms-2"><i class="fas fa-tag me-1 text-red"></i>{{ $item->category->name }}</span>
                    @endif
                </div>

                {{-- Properties (mileage, year etc) --}}
                @if($item->properties && $item->properties->count() > 0)
                <div class="vehicle-props mt-2 d-flex flex-wrap gap-2">
                    @foreach($item->properties->take(3) as $prop)
                        <span class="prop-badge">
                            {{ ucfirst($prop->key) }}: {{ $prop->value }}
                        </span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </a>
</div>