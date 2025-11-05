<div class="admin-topbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-link d-lg-none me-3" onclick="toggleSidebar()">
            <i class="bi bi-list" style="font-size: 24px;"></i>
        </button>
        <h1 class="topbar-title mb-0">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm" target="_blank">
            <i class="bi bi-globe me-2"></i>
            View Site
        </a>

        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2"></i>
                Admin
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                        <i class="bi bi-person me-2"></i>Profile
                    </a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('auth.logout') }}" method="POST" class="dropdown-item mb-0">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-start w-100 text-decoration-none"
                            style="color: inherit;">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
