<footer class="footer-premium">
    <div class="container">
        @php
            $footerDetails = $footerContent ?? null;
            $footerDescription = $footerDetails->description ?? 'Creating stunning designs that captivate and inspire.';
            $footerCopyright = $footerDetails->copyright_text ?? '© ' . date('Y') . ' Graphic Portfolio. All rights reserved.';
            $activeSocialLinks = isset($socialLinks) ? $socialLinks->filter(fn($link) => $link->is_active && $link->url) : collect();
            $availableCategories = isset($categories) ? $categories : \App\Models\Category::active()->orderBy('name')->get();
        @endphp

        <div class="row g-4">
            <div class="col-lg-4 mb-4">
                <a href="{{ route('home') }}#home" style="text-decoration: none; color: inherit;">
                    @if(isset($siteSettings) && $siteSettings->logo)
                        <img src="{{ asset('storage/' . $siteSettings->logo) }}" alt="{{ $siteSettings->site_name ?? 'Logo' }}"
                             class="mb-3" style="height: 50px; width: auto; object-fit: contain;">
                    @else
                        <h3 class="display-font mb-3"
                            style="background: linear-gradient(135deg, #00B894 0%, #F5576C 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $siteSettings->site_name ?? 'Graphic' }}<span style="font-weight: 300;">Portfolio</span>
                        </h3>
                    @endif
                </a>
                <p class="mb-4">{{ $footerDescription }}</p>
                @if ($activeSocialLinks->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($activeSocialLinks as $social)
                            <a href="{{ $social->url }}" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener"
                               aria-label="{{ ucfirst($social->platform ?? 'social') }}">
                                <i class="bi {{ $social->icon ?? 'bi-share' }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}#home">Home</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}#about">About</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}#portfolio">Works</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}#contact">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="mb-3">Categories</h5>
                <ul class="list-unstyled">
                    @forelse ($availableCategories as $category)
                        <li class="mb-2">
                            <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                        </li>
                    @empty
                        <li class="text-muted">No categories available</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="row mt-4 pt-4 border-top border-secondary align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">{{ $footerCopyright }}</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span class="text-white-50">Developed by <a href="https://sman.dev" target="_blank" rel="noopener"
                        class="text-white text-decoration-none">sman.dev</a></span>
            </div>
        </div>
    </div>
</footer>
