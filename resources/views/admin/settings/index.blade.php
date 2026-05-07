@extends('layouts.admin')

@section('title', 'Settings - Smart CMS')
@section('page-title', 'Site Settings')

@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">

        {{-- LEFT COLUMN: MAIN CONFIG --}}
        <div class="col-lg-8">

            {{-- General Settings --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-globe me-2 text-danger"></i> General & Branding
                </h6>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label text-white-50 small uppercase fw-bold">Site Name *</label>
                        <input type="text" name="site_name" class="form-control"
                               value="{{ $settings['site_name'] ?? '' }}" required/>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white-50 small uppercase fw-bold">Site Tagline</label>
                        <input type="text" name="site_tagline" class="form-control"
                               value="{{ $settings['site_tagline'] ?? '' }}"/>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white-50 small uppercase fw-bold">Footer Description</label>
                        <textarea name="footer_about" class="form-control" rows="3">{{ $settings['footer_about'] ?? '' }}</textarea>
                        <div class="form-text text-white small">This text appears under the logo in your website footer.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold">Copyright Text</label>
                        <input type="text" name="footer_copyright" class="form-control"
                               value="{{ $settings['footer_copyright'] ?? '' }}"/>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold">Site Email</label>
                        <input type="email" name="site_email" class="form-control"
                               value="{{ $settings['site_email'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold">Phone Number</label>
                        <input type="text" name="site_phone" class="form-control"
                               value="{{ $settings['site_phone'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold">Physical Address</label>
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
                        <label class="form-label text-white-50 small uppercase fw-bold">Hero Title</label>
                        <input type="text" name="hero_title" class="form-control"
                               value="{{ $settings['hero_title'] ?? '' }}"/>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-white-50 small uppercase fw-bold">Hero Subtitle</label>
                        <textarea name="hero_subtitle" class="form-control" rows="2">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
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
                        <label class="form-label text-white-50 small uppercase fw-bold"><i class="fab fa-whatsapp me-1"></i> WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control"
                               placeholder="923001234567"
                               value="{{ $settings['whatsapp_number'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold"><i class="fab fa-facebook me-1"></i> Facebook URL</label>
                        <input type="text" name="facebook_url" class="form-control"
                               value="{{ $settings['facebook_url'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold"><i class="fab fa-instagram me-1"></i> Instagram URL</label>
                        <input type="text" name="instagram_url" class="form-control"
                               value="{{ $settings['instagram_url'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold"><i class="fab fa-youtube me-1"></i> YouTube URL</label>
                        <input type="text" name="youtube_url" class="form-control"
                               value="{{ $settings['youtube_url'] ?? '' }}"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small uppercase fw-bold"><i class="fab fa-tiktok me-1"></i> TikTok URL</label>
                        <input type="text" name="tiktok_url" class="form-control"
                               value="{{ $settings['tiktok_url'] ?? '' }}"/>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: VISUALS --}}
        <div class="col-lg-4">

            {{-- Logo & Favicon --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-image me-2 text-danger"></i> Logo & Favicon
                </h6>

                {{-- Current Logo --}}
                @if(!empty($settings['site_logo']))
                    <div class="mb-3 text-center">
                        <label class="form-label d-block">Current Logo</label>
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(255,255,255,0.1);">
                            <img src="{{ $settings['site_logo'] }}"
                                 style="max-height:60px; object-fit: contain;"/>
                        </div>
                    </div>
                @endif
                <div class="mb-4">
                    <label class="form-label text-white-50 small uppercase fw-bold">Upload New Logo</label>
                    <input type="file" name="site_logo" class="form-control" accept="image/*"/>
                </div>

                {{-- Current Favicon --}}
                @if(!empty($settings['site_favicon']))
                    <div class="mb-3 text-center">
                        <label class="form-label d-block">Current Favicon</label>
                        <div class="p-2 d-inline-block rounded" style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(255,255,255,0.1);">
                            <img src="{{ $settings['site_favicon'] }}" style="max-height:32px;"/>
                        </div>
                    </div>
                @endif
                <div class="mb-0">
                    <label class="form-label text-white-50 small uppercase fw-bold">Upload New Favicon</label>
                    <input type="file" name="site_favicon" class="form-control" accept="image/*"/>
                </div>
            </div>

            {{-- Theme Colors --}}
            <div class="admin-card mb-4">
                <h6 class="fw-bold text-white mb-4">
                    <i class="fas fa-palette me-2 text-danger"></i> Theme Colors
                </h6>
                <div class="mb-4">
                    <label class="form-label text-white-50 small uppercase fw-bold">Primary Color</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="color" id="primary_color_picker" class="form-control form-control-color"
                               value="{{ $settings['primary_color'] ?? '#e63946' }}"
                               style="width:60px; height:45px; padding:2px"/>
                        <input type="text" name="primary_color" id="primary_color_hex" class="form-control"
                               value="{{ $settings['primary_color'] ?? '#e63946' }}"/>
                    </div>
                    <small class="text-white-50 small">Main theme color (Buttons, Icons)</small>
                </div>

                <div class="mb-0">
                    <label class="form-label text-white-50 small uppercase fw-bold">Secondary Color</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="color" id="secondary_color_picker" class="form-control form-control-color"
                               value="{{ $settings['secondary_color'] ?? '#1a1a1a' }}"
                               style="width:60px; height:45px; padding:2px"/>
                        <input type="text" name="secondary_color" id="secondary_color_hex" class="form-control"
                               value="{{ $settings['secondary_color'] ?? '#1a1a1a' }}"/>
                    </div>
                    <small class="text-white-50 small">Navbar & Footer background</small>
                </div>
            </div>

            {{-- Save Button --}}
            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-5 shadow-lg" style="border-radius: 12px;">
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