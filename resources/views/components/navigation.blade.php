<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#home">
            <i class="bi bi-palette me-2"></i>Graphic Portfolio
        </a>

        <!-- Desktop Navigation -->
        <div class="d-none d-lg-block">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#portfolio">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>
        </div>

        <!-- Mobile Menu Button -->
        <button class="btn btn-outline-primary d-lg-none" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#mobileNav">
            <i class="bi bi-list"></i>
        </button>
    </div>
</nav>

<!-- Mobile Navigation Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileNav">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold">
            <i class="bi bi-palette me-2"></i>Graphic Portfolio
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link mobile-nav-link py-3" href="#home">
                    <i class="bi bi-house me-2"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mobile-nav-link py-3" href="#about">
                    <i class="bi bi-person me-2"></i>About
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mobile-nav-link py-3" href="#portfolio">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Portfolio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mobile-nav-link py-3" href="#contact">
                    <i class="bi bi-envelope me-2"></i>Contact
                </a>
            </li>
        </ul>

        <div class="mt-4 pt-4 border-top">
            <h6 class="text-muted mb-3">Follow Us</h6>
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-linkedin"></i>
                </a>
            </div>
        </div>
    </div>
</div>
