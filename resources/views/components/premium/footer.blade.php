<footer class="footer-premium">
    <div class="container">
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
                <p class="mb-4">Creating stunning designs that captivate and inspire. Professional graphic design
                    services for businesses and individuals worldwide.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-dribbble"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}#home">Home</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}#about">About</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}#portfolio">Portfolio</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}#contact">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="mb-3">Services</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">Logo Design</a></li>
                    <li class="mb-2"><a href="#">Brand Identity</a></li>
                    <li class="mb-2"><a href="#">Social Media Graphics</a></li>
                    <li class="mb-2"><a href="#">Print Design</a></li>
                    <li class="mb-2"><a href="#">UI/UX Design</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="mb-3">Newsletter</h5>
                <p class="mb-3">Subscribe to get the latest updates and free resources.</p>
                <form class="d-flex">
                    <input type="email" class="form-control me-2" placeholder="Your email"
                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                    <button type="submit" class="btn btn-light">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0">&copy; 2025 Graphic Portfolio. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="me-3">Privacy Policy</a>
                <a href="#" class="me-3">Terms of Service</a>
                <a href="#">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
