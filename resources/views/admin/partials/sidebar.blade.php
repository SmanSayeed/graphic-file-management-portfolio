<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            @php
                $siteSettings = \App\Models\SiteSetting::getSettings();
            @endphp
            @if($siteSettings->logo)
                <img src="{{ asset('storage/' . $siteSettings->logo) }}" alt="{{ $siteSettings->site_name ?? 'Logo' }}" 
                     style="height: 35px; width: auto; object-fit: contain;">
            @else
                <i class="bi bi-brush me-2"></i>
                <span class="brand-text">{{ $siteSettings->site_name ?? 'GraphicPortfolio' }}</span>
            @endif
        </a>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}"
            class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="{{ route('admin.sliders.index') }}"
            class="menu-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
            <i class="bi bi-images"></i>
            <span class="menu-text">Hero Sliders</span>
        </a>

        <a href="{{ route('admin.profile.edit') }}"
            class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span class="menu-text">Personal Info</span>
        </a>

        <a href="{{ route('admin.skills.index') }}"
            class="menu-item {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i>
            <span class="menu-text">Skills</span>
        </a>

        <a href="{{ route('admin.creative-studio.edit') }}"
            class="menu-item {{ request()->routeIs('admin.creative-studio.*') ? 'active' : '' }}">
            <i class="bi bi-brush"></i>
            <span class="menu-text">Creative Studio</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            <span class="menu-text">Categories</span>
        </a>

        <a href="{{ route('admin.projects.index') }}"
            class="menu-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
            <i class="bi bi-folder"></i>
            <span class="menu-text">Projects</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span class="menu-text">Users</span>
        </a>

        <a href="{{ route('admin.contact.edit') }}"
            class="menu-item {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i>
            <span class="menu-text">Contact Info</span>
        </a>

        <a href="{{ route('admin.social.edit') }}"
            class="menu-item {{ request()->routeIs('admin.social.*') ? 'active' : '' }}">
            <i class="bi bi-share"></i>
            <span class="menu-text">Social Links</span>
        </a>

        <a href="{{ route('admin.footer.edit') }}"
            class="menu-item {{ request()->routeIs('admin.footer.*') ? 'active' : '' }}">
            <i class="bi bi-layout-text-sidebar-reverse"></i>
            <span class="menu-text">Footer Content</span>
        </a>

        <a href="{{ route('admin.settings.edit') }}"
            class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span class="menu-text">Site Settings</span>
        </a>

        <a href="{{ route('admin.optimization.index') }}"
            class="menu-item {{ request()->routeIs('admin.optimization.*') ? 'active' : '' }}">
            <i class="bi bi-lightning-charge"></i>
            <span class="menu-text">Optimization</span>
        </a>

        <hr style="border-color: rgba(255,255,255,0.1); margin: 20px;">

        <a href="{{ url('/') }}" class="menu-item" target="_blank">
            <i class="bi bi-globe"></i>
            <span class="menu-text">View Website</span>
        </a>

        <a href="{{ route('admin.login') }}" class="menu-item">
            <i class="bi bi-box-arrow-right"></i>
            <span class="menu-text">Logout</span>
        </a>
    </nav>
</aside>


