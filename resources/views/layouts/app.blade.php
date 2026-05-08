<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', setting('site_name', 'SM-Autos') . (setting('site_tagline') ? ' - ' . setting('site_tagline') : ' - Buy & Sell Vehicles'))</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Dynamic Favicon --}}
    @if(setting('site_favicon'))
    <link rel="icon" type="image/png" href="{{ setting('site_favicon') }}" />
    @endif

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />

    {{-- Dynamic Theme Colors --}}
    @php
    $primary = setting('primary_color', '#e63946');
    $secondary = setting('secondary_color', '#1a1a1a');

    // Extract RGB for translucent effects
    list($r, $g, $b) = sscanf($primary, "#%02x%02x%02x");
    $primaryRGB = "$r, $g, $b";
    @endphp
    <style>
        :root {
            --primary: {
                    {
                    $primary
                }
            }

            ;

            --primary-rgb: {
                    {
                    $primaryRGB
                }
            }

            ;
            --font: 'Outfit',
            sans-serif;
        }

        .navbar {
            background-color: {
                    {
                    $secondary
                }
            }

            !important;
        }

        .btn-danger,
        .logo {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .text-danger,
        .text-red {
            color: var(--primary) !important;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fs-3 fw-bold" href="{{ route('home') }}">
                @if(setting('site_logo'))
                <img src="{{ setting('site_logo') }}" style="max-height:45px; object-fit:contain" />
                @else
                <div class="logo rounded-circle d-flex align-items-center justify-content-center fs-4">
                    <i class="fas fa-car"></i>
                </div>
                {{ setting('site_name', 'SM-Autos') }}
                @endif
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
                        <a href="{{ route('user.items.create') }}" class="btn btn-danger fw-bold px-4">
                            <i class="fas fa-plus me-1"></i> Post Your Ad
                        </a>
                        @else
                        <a href="{{ route('user.login') }}" class="btn btn-danger fw-bold px-4">
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

    {{-- ALERTS --}}
    @if(session('success') || session('error') || $errors->any())
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <ul class="mb-0 list-unstyled">
                @foreach($errors->all() as $error)
                <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>
    @endif

    {{-- PAGE CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    <footer style="padding: 40px 0 20px;">
        <div class="container">
            <div class="row g-4 align-items-start">

                {{-- Column 1: Brand --}}
                <div class="col-lg-3 col-md-6">
                    <div class="footer-logo mb-3 d-flex align-items-center">
                        <div class="logo me-2" style="width:32px; height:32px; font-size:1rem"><i class="fas fa-car text-white"></i></div>
                        <span class="h5 mb-0 fw-bold text-white">{{ setting('site_name', 'SM-Autos') }}</span>
                    </div>
                    <p class="text-white opacity-75 small mb-0">
                        {{ setting('footer_about', "Pakistan's #1 platform for buying and selling vehicles. Find your dream car or bike today!") }}
                    </p>
                </div>

                {{-- Column 2: Contact Us --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold text-white mb-3">Contact Us</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="tel:{{ setting('site_phone', '923096527842') }}" class="d-flex align-items-center gap-2 text-white-brand text-decoration-none footer-contact-link small">
                                <i class="fas fa-phone-alt" style="color:{{ setting('primary_color', '#e63946') }}; width:16px"></i>
                                {{ setting('site_phone', '+92 309 6527842') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="mailto:{{ setting('site_email', 'abid6527842@gmail.com') }}" class="d-flex align-items-center gap-2 text-white-brand text-decoration-none footer-contact-link small">
                                <i class="fas fa-envelope" style="color:{{ setting('primary_color', '#e63946') }}; width:16px"></i>
                                {{ setting('site_email', 'abid6527842@gmail.com') }}
                            </a>
                        </li>
                        <li class="d-flex align-items-center gap-2 text-white-brand small">
                            <i class="fas fa-map-marker-alt" style="color:{{ setting('primary_color', '#e63946') }}; width:16px"></i>
                            {{ setting('site_address', 'Lahore, Punjab, Pakistan') }}
                        </li>
                    </ul>
                </div>

                {{-- Column 3: Quick Links --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold text-white mb-3">Quick Links</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{ route('items.search', ['category' => 'new-bikes']) }}" class="footer-link small mb-1">New Bikes</a></li>
                        <li><a href="{{ route('items.search', ['category' => 'used-bikes']) }}" class="footer-link small mb-1">Used Bikes</a></li>
                        <li><a href="{{ route('items.search', ['category' => 'new-cars']) }}" class="footer-link small mb-1">New Cars</a></li>
                        <li><a href="{{ route('items.search', ['category' => 'used-cars']) }}" class="footer-link small mb-1">Used Cars</a></li>
                    </ul>
                </div>

                {{-- Column 4: Follow Us --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold text-white mb-3">Follow Us</h5>
                    <div class="social-links">
                        <a href="{{ setting('youtube_url', '#') }}" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="{{ setting('instagram_url', '#') }}" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="{{ setting('facebook_url', '#') }}" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ setting('tiktok_url', '#') }}" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

            </div>

            <hr class="footer-divider">

            <p class="text-center footer-copy mb-0">
                &copy; {{ date('Y') }} {{ setting('footer_copyright', 'SM-Autos. All Rights Reserved.') }}
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

    {{-- FLOATING ACTIONS --}}
    <div class="floating-container" id="floating-menu">
        <!-- Chat Window -->
        <div class="chat-window" id="chat-window">
            <div class="chat-header">
                <div class="d-flex align-items-center">
                    <div class="chat-avatar me-2">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">SM-Autos AI</h6>
                        <small class="opacity-75">Online</small>
                    </div>
                </div>
                <button class="btn-close btn-close-white small" id="close-chat"></button>
            </div>
            <div class="chat-body" id="chat-body">
                <div class="chat-msg bot">
                    <p>Hi! I'm your SM-Autos assistant. How can I help you today?</p>
                </div>
                <!-- Typing Indicator (Hidden by default) -->
                <div class="typing-indicator" id="typing-indicator" style="display: none;">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="chat-footer">
                <input type="text" placeholder="Type a message..." class="chat-input" id="chat-input">
                <button class="chat-send" id="send-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>

        <!-- Sub Buttons -->
        <div class="sub-buttons">
            <button class="float-btn chatbot-btn" title="Chat with AI" id="chatbot-toggle">
                <i class="fas fa-robot"></i>
            </button>
            <a href="https://wa.me/{{ setting('whatsapp_number', '923096527842') }}" target="_blank" class="float-btn whatsapp-btn" aria-label="WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>

        <!-- Main Toggle Button -->
        <button class="main-float-btn" id="menu-toggle" aria-label="Toggle Menu">
            <i class="fas fa-comments main-icon"></i>
            <i class="fas fa-times close-icon"></i>
        </button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    {{-- FLOATING MENU & CHAT TOGGLE JS --}}
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const floatingMenu = document.getElementById('floating-menu');
        const chatbotToggle = document.getElementById('chatbot-toggle');
        const chatWindow = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const chatBody = document.getElementById('chat-body');
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');
        const typingIndicator = document.getElementById('typing-indicator');

        // Toggle Main Menu
        menuToggle.addEventListener('click', function() {
            floatingMenu.classList.toggle('active');
        });

        // Open Chat Window with Typing Animation
        chatbotToggle.addEventListener('click', function(e) {
            e.preventDefault();
            chatWindow.classList.toggle('show');

            if (chatWindow.classList.contains('show')) {
                showTyping();
                setTimeout(() => {
                    hideTyping();
                    scrollToBottom();
                }, 1500);
            }
        });

        // Close Chat Window
        closeChat.addEventListener('click', function() {
            chatWindow.classList.remove('show');
        });

        // Send Message 
        function sendMessage() {
            const text = chatInput.value.trim();
            if (text === '') return;

            // Add User Message
            const userMsg = document.createElement('div');
            userMsg.className = 'chat-msg user';
            userMsg.innerHTML = `<p>${text}</p>`;
            chatBody.insertBefore(userMsg, typingIndicator);

            chatInput.value = '';
            scrollToBottom();

            //  AI Response
            showTyping();
            scrollToBottom();

            fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: text
                    })
                })
                .then(res => res.json())
                .then(data => {
                    hideTyping();
                    const botMsg = document.createElement('div');
                    botMsg.className = 'chat-msg bot';
                    botMsg.innerHTML = `<p>${data.reply}</p>`;
                    chatBody.insertBefore(botMsg, typingIndicator);
                    scrollToBottom();
                })
                .catch(() => {
                    hideTyping();
                    const botMsg = document.createElement('div');
                    botMsg.className = 'chat-msg bot';
                    botMsg.innerHTML = `<p>Network error! Please try again.</p>`;
                    chatBody.insertBefore(botMsg, typingIndicator);
                    scrollToBottom();
                });
        }

        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        function showTyping() {
            typingIndicator.style.display = 'flex';
        }

        function hideTyping() {
            typingIndicator.style.display = 'none';
        }

        function scrollToBottom() {
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        // Auto dismiss alerts
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            });
        }, 3000);
    </script>

    @stack('scripts')
</body>

</html>