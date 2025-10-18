<!-- Portfolio Gallery -->
<section id="portfolio" class="py-5 portfolio-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Portfolio Gallery</h2>
                <p class="lead text-muted">Explore our collection of creative designs and download high-quality assets
                </p>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" class="filter-btn active" data-filter="all">All</button>
                    <button type="button" class="filter-btn" data-filter="logo">Logos</button>
                    <button type="button" class="filter-btn" data-filter="social">Social Media</button>
                    <button type="button" class="filter-btn" data-filter="banner">Banners</button>
                    <button type="button" class="filter-btn" data-filter="ecommerce">E-commerce</button>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(73).webp"
                    class="w-100 shadow-1-strong rounded mb-4 gallery-img" alt="Boat on Calm Water"
                    data-bs-toggle="modal" data-bs-target="#imageModal" />
                <img src="https://mdbcdn.b-cdn.net/img/Photos/Vertical/mountain1.webp"
                    class="w-100 shadow-1-strong rounded mb-4 gallery-img" alt="Wintry Mountain Landscape"
                    data-bs-toggle="modal" data-bs-target="#imageModal" />
            </div>

            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/Vertical/mountain2.webp"
                    class="w-100 shadow-1-strong rounded mb-4 gallery-img" alt="Mountains in the Clouds"
                    data-bs-toggle="modal" data-bs-target="#imageModal" />
                <img src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(73).webp"
                    class="w-100 shadow-1-strong rounded mb-4 gallery-img" alt="Boat on Calm Water"
                    data-bs-toggle="modal" data-bs-target="#imageModal" />
            </div>

            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(18).webp"
                    class="w-100 shadow-1-strong rounded mb-4 gallery-img" alt="Waves at Sea" data-bs-toggle="modal"
                    data-bs-target="#imageModal" />
                <img src="https://mdbcdn.b-cdn.net/img/Photos/Vertical/mountain3.webp"
                    class="w-100 shadow-1-strong rounded mb-4 gallery-img" alt="Yosemite National Park"
                    data-bs-toggle="modal" data-bs-target="#imageModal" />
            </div>
        </div>

        <!-- Project Cards Section -->
        <div class="row g-4 mt-5" id="projectsGrid">
            <!-- Project 1 -->
            <div class="col-lg-4 col-md-6 project-item" data-category="logo">
                <div class="project-card card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1611224923853-80b023f02d71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            class="card-img-top" alt="Logo Design" style="height: 250px; object-fit: cover;">
                        <span class="tag-badge badge bg-success">FREE</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Modern Logo Design</h5>
                        <p class="card-text text-muted">Clean and professional logo perfect for tech startups and modern
                            businesses.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">2.5k downloads</small>
                            <button class="btn btn-sm btn-primary" onclick="openProjectModal(1)">
                                <i class="bi bi-eye me-1"></i>View
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project 2 -->
            <div class="col-lg-4 col-md-6 project-item" data-category="social">
                <div class="project-card card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1611262588024-d12430b98920?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            class="card-img-top" alt="Social Media Post" style="height: 250px; object-fit: cover;">
                        <span class="tag-badge badge bg-danger">PAID</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Social Media Template</h5>
                        <p class="card-text text-muted">Instagram post template with modern typography and vibrant
                            colors.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">$15.00</small>
                            <button class="btn btn-sm btn-primary" onclick="openProjectModal(2)">
                                <i class="bi bi-eye me-1"></i>View
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project 3 -->
            <div class="col-lg-4 col-md-6 project-item" data-category="banner">
                <div class="project-card card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            class="card-img-top" alt="Banner Design" style="height: 250px; object-fit: cover;">
                        <span class="tag-badge badge bg-success">FREE</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Promotional Banner</h5>
                        <p class="card-text text-muted">Eye-catching banner design for marketing campaigns and
                            promotions.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">1.8k downloads</small>
                            <button class="btn btn-sm btn-primary" onclick="openProjectModal(3)">
                                <i class="bi bi-eye me-1"></i>View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid rounded" alt="Modal Image">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Download</button>
            </div>
        </div>
    </div>
</div>
