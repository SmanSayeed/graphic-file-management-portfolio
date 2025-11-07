<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Graphic Portfolio - Premium Design Showcase')</title>
    <meta name="description" content="@yield('description', 'Professional graphic design portfolio with premium quality designs, creative works, and downloadable resources.')">
    <meta name="keywords" content="graphic design, portfolio, logo design, branding, creative agency">
    <meta name="author" content="Graphic Portfolio">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- AOS Animation CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Premium Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/premium-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation-premium.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('css/isotope-custom.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div id="preloader"
        style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: #fff; z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Navigation -->
    @php
        // Ensure categories are available for navigation
        if (!isset($categories)) {
            $categories = \App\Models\Category::active()->get();
        }
    @endphp
    @include('components.premium.navigation-new', ['categories' => $categories ?? []])

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.premium.footer')

    <!-- Back to Top -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"
        style="position: fixed; right: 20px; bottom: 20px; width: 50px; height: 50px; background: linear-gradient(135deg, #00B894 0%, #F5576C 100%); color: #fff; border-radius: 50%; display: none; z-index: 999; transition: all 0.3s;">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Isotope JS -->
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

    <!-- ImagesLoaded JS -->
    <script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Common JS for Bootstrap fixes -->
    <script src="{{ asset('js/common.js') }}"></script>

    <!-- Custom User Dropdown JS -->
    <script src="{{ asset('js/custom-user-dropdown.js') }}"></script>

    <!-- Category Dropdown JS -->
    <script src="{{ asset('js/category-dropdown.js') }}"></script>

    <!-- Premium Theme JS -->
    <script src="{{ asset('js/premium-theme.js') }}"></script>
    <script src="{{ asset('js/navigation-slider.js') }}"></script>
    <script src="{{ asset('js/modal-dropdown-fix.js') }}"></script>

    <!-- Page-specific scripts -->
    @stack('scripts')
</body>

</html>
