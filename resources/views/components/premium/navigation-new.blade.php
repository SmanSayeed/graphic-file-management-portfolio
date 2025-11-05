<!-- Premium Fixed Sticky Header -->
<header class="header-premium">
    <div class="container">
        <nav class="navbar-premium-new">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Logo (Left) -->
                <a href="#home" class="logo-premium">
                    @if (isset($siteSettings) && $siteSettings->logo)
                        <img src="{{ asset('storage/' . $siteSettings->logo) }}"
                            alt="{{ $siteSettings->site_name ?? 'Logo' }}"
                            style="height: 40px; width: auto; object-fit: contain;">
                    @else
                        <div class="logo-icon">
                            <i class="bi bi-brush"></i>
                        </div>
                        <span class="display-font">{{ $siteSettings->site_name ?? 'Graphic' }}<span
                                style="font-weight: 300;">Portfolio</span></span>
                    @endif
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
                            <div class="custom-user-dropdown">
                                <button class="custom-user-btn" type="button" id="userDropdownBtn">
                                    <i class="bi bi-person-circle me-2"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="bi bi-chevron-down ms-2 dropdown-arrow"></i>
                                </button>
                                <div class="custom-dropdown-menu" id="userDropdownMenu">
                                    @if (Auth::user()->isAdmin())
                                        <a href="{{ route('admin.profile.edit') }}" target="_blank"
                                            class="custom-dropdown-item">
                                            <i class="bi bi-person me-2"></i>
                                            Profile
                                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 12px;"></i>
                                        </a>
                                        <a href="{{ route('admin.profile.edit') }}#password" target="_blank"
                                            class="custom-dropdown-item">
                                            <i class="bi bi-key me-2"></i>
                                            Change Password
                                            <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 12px;"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('user.profile.show') }}" class="custom-dropdown-item">
                                            <i class="bi bi-person me-2"></i>
                                            Profile
                                        </a>
                                        <a href="{{ route('user.profile.edit') }}#password" class="custom-dropdown-item">
                                            <i class="bi bi-key me-2"></i>
                                            Change Password
                                        </a>
                                    @endif
                                    <div class="custom-dropdown-divider"></div>
                                    <form action="{{ route('auth.logout') }}" method="POST"
                                        class="custom-dropdown-item-form">
                                        @csrf
                                        <button type="submit" class="custom-dropdown-item logout-btn">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
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
        <div class="drawer-logo" style="display: flex; align-items: center;">
            @if (isset($siteSettings) && $siteSettings->logo)
                <img src="{{ asset('storage/' . $siteSettings->logo) }}"
                    alt="{{ $siteSettings->site_name ?? 'Logo' }}"
                    style="height: 30px; width: auto; object-fit: contain;">
            @else
                <i class="bi bi-brush me-2" style="color: #00B894;"></i>
                <span
                    style="background: linear-gradient(135deg, #00B894 0%, #F5576C 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    {{ $siteSettings->site_name ?? 'GraphicPortfolio' }}
                </span>
            @endif
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
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.profile.edit') }}" target="_blank" class="drawer-menu-link">
                    <i class="bi bi-person me-2"></i>
                    Profile
                    <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 12px;"></i>
                </a>
                <a href="{{ route('admin.profile.edit') }}#password" target="_blank" class="drawer-menu-link">
                    <i class="bi bi-key me-2"></i>
                    Change Password
                    <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 12px;"></i>
                </a>
            @else
                <a href="{{ route('user.profile.show') }}" class="drawer-menu-link">
                    <i class="bi bi-person me-2"></i>
                    Profile
                </a>
                <a href="{{ route('user.profile.edit') }}#password" class="drawer-menu-link">
                    <i class="bi bi-key me-2"></i>
                    Change Password
                </a>
            @endif
            <div class="drawer-menu-divider"></div>
            <form action="{{ route('auth.logout') }}" method="POST" class="drawer-logout-form">
                @csrf
                <button type="submit" class="drawer-menu-link logout-link">
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
    </div>
</div>

<!-- Drawer Overlay -->
<div class="drawer-overlay"></div>
