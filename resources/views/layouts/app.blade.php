<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SM-Autos - Buy & Sell Vehicles')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fs-3 fw-bold" href="{{ route('home') }}">
                <div class="logo rounded-circle d-flex align-items-center justify-content-center fs-4">
                    <i class="fas fa-car"></i>
                </div>
                SM-Autos
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('items.search', ['category' => 'new-bikes']) }}">New Bikes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('items.search', ['category' => 'used-bikes']) }}">Used Bikes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('items.search', ['category' => 'new-cars']) }}">New Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('items.search', ['category' => 'used-cars']) }}">Used Cars</a>
                    </li>

                    {{-- Post Your Ad Button --}}
                    <li class="nav-item ms-3">
                        @auth
                        <a href="{{ route('user.items.create') }}"
                            class="btn btn-danger fw-bold px-4">
                            <i class="fas fa-plus me-1"></i> Post Your Ad
                        </a>
                        @else
                        <a href="{{ route('user.login') }}"
                            class="btn btn-danger fw-bold px-4">
                            <i class="fas fa-plus me-1"></i> Post Your Ad
                        </a>
                        @endauth
                    </li>

                    {{-- User Menu --}}
                    @auth
                    <li class="nav-item ms-2 dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-1"
                            href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fs-5"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="background:#1a1a1a; border:1px solid rgba(255,255,255,0.1)">
                            <li>
                                <a class="dropdown-item text-white" href="{{ route('user.dashboard') }}">
                                    <i class="fas fa-th-large me-2 text-danger"></i> My Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-white" href="{{ route('user.items.create') }}">
                                    <i class="fas fa-plus me-2 text-danger"></i> Add Vehicle
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider" style="border-color:rgba(255,255,255,0.1)">
                            </li>
                            <li>
                                <form action="{{ route('user.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-white">
                                        <i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- PAGE CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    <footer>
        <div class="container">
            <div class="row g-4">

                {{-- Brand --}}
                <div class="col-lg-3 col-md-6">
                    <a class="d-flex align-items-center gap-2 fw-bold mb-3 text-decoration-none text-white fs-4" href="{{ route('home') }}">
                        <div class="logo rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-car"></i>
                        </div>
                        SM-Autos
                    </a>
                    <p class="text-white-brand">
                        Pakistan's #1 platform for buying and selling vehicles. Find your dream car or bike today!
                    </p>
                </div>

                {{-- Contact Us --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-4">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center gap-2 text-white-brand">
                            <i class="fas fa-phone-alt" style="color:#e63946; width:16px"></i>
                            +92 309 6527842
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-2 text-white-brand">
                            <i class="fas fa-envelope" style="color:#e63946; width:16px"></i>
                            abid6527842@gmail.com
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-2 text-white-brand">
                            <i class="fas fa-map-marker-alt" style="color:#e63946; width:16px"></i>
                            Lahore, Punjab, Pakistan
                        </li>
                    </ul>
                </div>

                {{-- Quick Links --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-4">Quick Links</h5>
                    <a href="{{ route('items.search', ['category' => 'new-bikes']) }}" class="footer-link">New Bikes</a>
                    <a href="{{ route('items.search', ['category' => 'used-bikes']) }}" class="footer-link">Used Bikes</a>
                    <a href="{{ route('items.search', ['category' => 'new-cars']) }}" class="footer-link">New Cars</a>
                    <a href="{{ route('items.search', ['category' => 'used-cars']) }}" class="footer-link">Used Cars</a>
                </div>

                {{-- Follow Us --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-4">Follow Us</h5>
                    <div class="social-links">
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

            </div>

            <hr class="footer-divider">

            <p class="text-center footer-copy mb-0">
                &copy; {{ date('Y') }} SM-Autos. All Rights Reserved.
                <span class="mx-2">·</span>
                <a href="{{ route('admin.login') }}"
                    style="color:rgba(255,255,255,0.2); text-decoration:none; font-size:0.75rem"
                    onmouseover="this.style.color='rgba(255,255,255,0.6)'"
                    onmouseout="this.style.color='rgba(255,255,255,0.2)'">
                    Admin
                </a>
            </p>
        </div>
    </footer>

    {{-- WHATSAPP BUTTON --}}
    <a href="https://wa.me/923096527842" target="_blank" class="whatsapp-float" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>