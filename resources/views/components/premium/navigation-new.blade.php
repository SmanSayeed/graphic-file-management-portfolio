<!-- Premium Fixed Sticky Header -->
<header class="header-premium">
    <div class="container">
        <nav class="navbar-premium-new">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Logo (Left) -->
                <a href="#home" class="logo-premium">
                    <div class="logo-icon">
                        <i class="bi bi-brush"></i>
                    </div>
                    <span class="display-font">Graphic<span style="font-weight: 300;">Portfolio</span></span>
                </a>

                <!-- Desktop Navigation (Right) -->
                <ul class="nav-menu-premium">
                    <li class="nav-item-premium">
                        <a href="#home" class="nav-link-premium active">Home</a>
                    </li>
                    <li class="nav-item-premium">
                        <a href="#about" class="nav-link-premium">About</a>
                    </li>
                    <li class="nav-item-premium">
                        <a href="#portfolio" class="nav-link-premium">Works</a>
                    </li>
                    <li class="nav-item-premium">
                        <a href="#contact" class="nav-link-premium">Contact</a>
                    </li>
                    @auth
                        <li class="nav-item-premium">
                            <div class="dropdown">
                                <button class="btn-nav-cta dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('home') }}#contact">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('home') }}#contact">Change Password</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('auth.logout') }}" method="POST" class="dropdown-item mb-0">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-link p-0 text-start w-100 text-decoration-none"
                                                style="color: inherit;">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="nav-item-premium">
                            <a href="{{ route('auth.login') }}" class="btn-nav-cta-secondary">Login</a>
                        </li>
                        <li class="nav-item-premium">
                            <a href="{{ route('auth.register') }}?tab=register" class="btn-nav-cta">Register</a>
                        </li>
                    @endauth
                </ul>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </nav>
    </div>
</header>

<!-- Mobile Drawer Navigation -->
<div class="mobile-drawer">
    <!-- Drawer Header -->
    <div class="drawer-header">
        <div class="drawer-logo">
            <i class="bi bi-brush me-2"></i>
            GraphicPortfolio
        </div>
        <button class="drawer-close">
            <i class="bi bi-x"></i>
        </button>
    </div>

    <!-- Drawer Menu -->
    <ul class="drawer-menu">
        <li class="nav-item-premium">
            <a href="#home" class="nav-link-premium active">
                <i class="bi bi-house-door"></i>
                Home
            </a>
        </li>
        <li class="nav-item-premium">
            <a href="#about" class="nav-link-premium">
                <i class="bi bi-person"></i>
                About
            </a>
        </li>
        <li class="nav-item-premium">
            <a href="#portfolio" class="nav-link-premium">
                <i class="bi bi-grid-3x3-gap"></i>
                Works
            </a>
        </li>
        <li class="nav-item-premium">
            <a href="#contact" class="nav-link-premium">
                <i class="bi bi-envelope"></i>
                Contact
            </a>
        </li>
    </ul>

    <!-- Drawer Footer -->
    <div class="drawer-footer">
        @auth
            <a href="{{ route('home') }}#contact" class="drawer-cta">
                <i class="bi bi-person-circle me-2"></i>
                {{ Auth::user()->name }}
            </a>
            <form action="{{ route('auth.logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="drawer-cta-secondary">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
            </form>
        @else
            <a href="{{ route('auth.login') }}" class="drawer-cta-secondary">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Login
            </a>
            <a href="{{ route('auth.register') }}?tab=register" class="drawer-cta mt-2">
                <i class="bi bi-person-plus me-2"></i>
                Register
            </a>
        @endauth

        <div class="drawer-social mt-4">
            <a href="#"><i class="bi bi-twitter"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-dribbble"></i></a>
        </div>
    </div>
</div>

<!-- Drawer Overlay -->
<div class="drawer-overlay"></div>
