<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $siteTitle = optional($siteSettings ?? null)->site_name ?? config('app.name', 'Graphic Portfolio');
        $portfolioInfo = \App\Models\PersonalInfo::first();
        $defaultDescription =
            $portfolioInfo?->short_bio ??
            ($portfolioInfo?->full_bio ??
                'Professional graphic design portfolio showcasing creative works, logos, social media designs, and downloadable resources.');

        $pageTitle = trim($__env->yieldContent('title')) ?: $siteTitle;
        $pageDescription = trim($__env->yieldContent('description')) ?: $defaultDescription;
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>

<body>
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mobile navigation toggle
        function toggleMobileNav() {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('mobileNav'));
            offcanvas.toggle();
        }

        // Close mobile nav when clicking on links
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('mobileNav'));
                if (offcanvas) {
                    offcanvas.hide();
                }
            });
        });
    </script>
</body>

</html>
