@extends('layouts.admin')

@section('title', 'Settings - Smart CMS')
@section('page-title', 'Site Settings')

@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">

            {{-- General Settings --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-globe me-2 text-danger"></i> General Settings
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Site Name *</label>
                        <input type="text" name="site_name" class="form-control"
                               value="{{ $settings['site_name'] ?? '' }}" required/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Site Email</label>
                        <input type="email" name="site_email" class="form-control"
                               value="{{ $settings['site_email'] ?? '' }}"/>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Site Tagline</label>
                        <input type="text" name="site_tagline" class="form-control"
                               value="{{ $settings['site_tagline'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="site_phone" class="form-control"
                               value="{{ $settings['site_phone'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="site_address" class="form-control"
                               value="{{ $settings['site_address'] ?? '' }}"/>
                    </div>
                </div>
            </div>

            {{-- Hero Section --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-home me-2 text-danger"></i> Homepage Hero Section
                </h6>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Hero Title</label>
                        <input type="text" name="hero_title" class="form-control"
                               value="{{ $settings['hero_title'] ?? '' }}"/>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" class="form-control"
                               value="{{ $settings['hero_subtitle'] ?? '' }}"/>
                    </div>
                </div>
            </div>

            {{-- Footer Settings --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-shoe-prints me-2 text-danger"></i> Footer Settings
                </h6>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Footer About Text</label>
                        <textarea name="footer_about" class="form-control" rows="3">{{ $settings['footer_about'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Copyright Text</label>
                        <input type="text" name="footer_copyright" class="form-control"
                               value="{{ $settings['footer_copyright'] ?? '' }}"/>
                    </div>
                </div>
            </div>

            {{-- Social Media --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-share-alt me-2 text-danger"></i> Social Media & WhatsApp
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-facebook me-1"></i> Facebook URL</label>
                        <input type="text" name="facebook_url" class="form-control"
                               value="{{ $settings['facebook_url'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-instagram me-1"></i> Instagram URL</label>
                        <input type="text" name="instagram_url" class="form-control"
                               value="{{ $settings['instagram_url'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-youtube me-1"></i> YouTube URL</label>
                        <input type="text" name="youtube_url" class="form-control"
                               value="{{ $settings['youtube_url'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-tiktok me-1"></i> TikTok URL</label>
                        <input type="text" name="tiktok_url" class="form-control"
                               value="{{ $settings['tiktok_url'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-whatsapp me-1"></i> WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control"
                               placeholder="923001234567"
                               value="{{ $settings['whatsapp_number'] ?? '' }}"/>
                        <small class="text-white">923096527842</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">

            {{-- Logo & Favicon --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-image me-2 text-danger"></i> Logo & Favicon
                </h6>

                {{-- Current Logo --}}
                @if(!empty($settings['site_logo']))
                    <div class="mb-3">
                        <label class="form-label">Current Logo</label>
                        <div>
                            <img src="{{ $settings['site_logo'] }}"
                                 style="max-height:60px; background:#fff; padding:5px; border-radius:8px"/>
                        </div>
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Upload New Logo</label>
                    <input type="file" name="site_logo" class="form-control" accept="image/*"/>
                    <small class="text-white">PNG with transparent background recommended</small>
                </div>

                {{-- Current Favicon --}}
                @if(!empty($settings['site_favicon']))
                    <div class="mb-3">
                        <label class="form-label">Current Favicon</label>
                        <div>
                            <img src="{{ $settings['site_favicon'] }}"
                                 style="max-height:32px; background:#fff; padding:3px; border-radius:4px"/>
                        </div>
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Upload New Favicon</label>
                    <input type="file" name="site_favicon" class="form-control" accept="image/*"/>
                    <small class="text-white">32x32 or 64x64 PNG recommended</small>
                </div>
            </div>

            {{-- Theme Colors --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-palette me-2 text-danger"></i> Theme Colors
                </h6>
                <div class="mb-4">
                    <label class="form-label">Primary Color</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="color" id="primary_color_picker" class="form-control form-control-color"
                               value="{{ $settings['primary_color'] ?? '#e63946' }}"
                               style="width:60px; height:40px; padding:2px"/>
                        <input type="text" name="primary_color" id="primary_color_hex" class="form-control"
                               value="{{ $settings['primary_color'] ?? '#e63946' }}"
                               placeholder="#e63946"/>
                    </div>
                    <small class="text-white">Site main color — buttons, links, etc.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Secondary Color (Navbar)</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="color" id="secondary_color_picker" class="form-control form-control-color"
                               value="{{ $settings['secondary_color'] ?? '#1a1a1a' }}"
                               style="width:60px; height:40px; padding:2px"/>
                        <input type="text" name="secondary_color" id="secondary_color_hex" class="form-control"
                               value="{{ $settings['secondary_color'] ?? '#1a1a1a' }}"
                               placeholder="#1a1a1a"/>
                    </div>
                    <small class="text-white">Used for Navbar background.</small>
                </div>
            </div>

            {{-- Save Button --}}
            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-5">
                <i class="fas fa-save me-2"></i> Save All Settings
            </button>

        </div>
    </div>

</form>

@endsection

@push('scripts')
<script>
    // Primary Color Sync
    const pPicker = document.getElementById('primary_color_picker');
    const pHex = document.getElementById('primary_color_hex');
    pPicker.addEventListener('input', () => pHex.value = pPicker.value);
    pHex.addEventListener('input', () => pPicker.value = pHex.value);

    // Secondary Color Sync
    const sPicker = document.getElementById('secondary_color_picker');
    const sHex = document.getElementById('secondary_color_hex');
    sPicker.addEventListener('input', () => sHex.value = sPicker.value);
    sHex.addEventListener('input', () => sPicker.value = sHex.value);
</script>
@endpush