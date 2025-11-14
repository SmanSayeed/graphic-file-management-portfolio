<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteTitle = optional($siteSettings)->site_name ?? config('app.name', 'Graphic Portfolio');
        $faviconUrl = optional($siteSettings)->favicon_url ?? asset('favicon.ico');
    @endphp
    <title>@yield('title', 'Admin Dashboard') - {{ $siteTitle }}</title>
    <link rel="icon" href="{{ $faviconUrl }}">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="admin-content">
            <!-- Topbar -->
            @include('admin.partials.topbar')

            <!-- Main Content Area -->
            <div class="admin-main">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Queue Processing Modal -->
    <div class="modal fade" id="queueProcessingModal" tabindex="-1" aria-labelledby="queueProcessingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="mb-2" id="queueProcessingTitle">Processing Files...</h5>
                    <p class="text-muted mb-0" id="queueProcessingMessage">Please wait while files are being uploaded. This may take a few moments.</p>
                    <div class="mt-3">
                        <small class="text-muted" id="queueProcessingStatus">Queued for processing...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Common JS for Bootstrap fixes -->
    <script src="{{ asset('js/common.js') }}"></script>

    <!-- Admin JS -->
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.admin-sidebar').classList.toggle('show');
        }

        // Collapse sidebar
        function collapseSidebar() {
            document.querySelector('.admin-sidebar').classList.toggle('collapsed');
        }
    </script>

    @stack('scripts')
</body>

</html>
