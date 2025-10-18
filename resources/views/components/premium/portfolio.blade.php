@php
    $projects = [
        [
            'id' => 1,
            'title' => 'Modern Logo Design',
            'category' => 'logo',
            'categoryName' => 'Brand Identity',
            'description' =>
                'Clean and professional logo perfect for tech startups and modern businesses. Includes multiple variations and color schemes for versatile usage across different platforms.',
            'image' =>
                'https://images.unsplash.com/photo-1611224923853-80b023f02d71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'free',
            'downloads' => [
                'png' => '#download-png-1',
                'source' => '#download-ai-1',
                'sourceType' => 'Adobe Illustrator (AI)',
            ],
        ],
        [
            'id' => 2,
            'title' => 'Social Media Pack',
            'category' => 'social',
            'categoryName' => 'Instagram Templates',
            'description' =>
                'Instagram post template with modern typography and vibrant colors. Perfect for social media marketing campaigns and brand consistency.',
            'image' =>
                'https://images.unsplash.com/photo-1611262588024-d12430b98920?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'paid',
            'downloads' => [
                'png' => '#download-png-2',
                'source' => '#download-psd-2',
                'sourceType' => 'Photoshop (PSD)',
            ],
        ],
        [
            'id' => 3,
            'title' => 'Promotional Banner',
            'category' => 'banner',
            'categoryName' => 'Marketing Design',
            'description' =>
                'Eye-catching banner design for marketing campaigns and promotions. Optimized for both web and print usage with high resolution output.',
            'image' =>
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'free',
            'downloads' => [
                'png' => '#download-png-3',
                'source' => '#download-ai-3',
                'sourceType' => 'Adobe Illustrator (AI)',
            ],
        ],
        [
            'id' => 4,
            'title' => 'E-commerce Mockup',
            'category' => 'ecommerce',
            'categoryName' => 'Product Design',
            'description' =>
                'Professional product mockup template for online stores. Includes smart objects for easy customization and multiple angle views.',
            'image' =>
                'https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'paid',
            'downloads' => [
                'png' => '#download-png-4',
                'source' => '#download-psd-4',
                'sourceType' => 'Photoshop (PSD)',
            ],
        ],
        [
            'id' => 5,
            'title' => 'Creative Logo Set',
            'category' => 'logo',
            'categoryName' => 'Brand Identity',
            'description' =>
                'Collection of creative logos suitable for various industries. Each logo is fully editable and comes with usage guidelines.',
            'image' =>
                'https://images.unsplash.com/photo-1558655146-9f40138edfeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'free',
            'downloads' => [
                'png' => '#download-png-5',
                'source' => '#download-ai-5',
                'sourceType' => 'Adobe Illustrator (AI)',
            ],
        ],
        [
            'id' => 6,
            'title' => 'Social Media Bundle',
            'category' => 'social',
            'categoryName' => 'Facebook & Instagram',
            'description' =>
                'Complete social media bundle with templates for Facebook, Instagram, and Twitter. Includes stories, posts, and cover images.',
            'image' =>
                'https://images.unsplash.com/photo-1634942537064-7cb3e007b0f9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'paid',
            'downloads' => [
                'png' => '#download-png-6',
                'source' => '#download-psd-6',
                'sourceType' => 'Photoshop (PSD)',
            ],
        ],
        [
            'id' => 7,
            'title' => 'Web Banner Pack',
            'category' => 'banner',
            'categoryName' => 'Digital Advertising',
            'description' =>
                'Professional web banner pack for digital advertising. Multiple sizes included for various ad platforms and placements.',
            'image' =>
                'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'free',
            'downloads' => [
                'png' => '#download-png-7',
                'source' => '#download-ai-7',
                'sourceType' => 'Adobe Illustrator (AI)',
            ],
        ],
        [
            'id' => 8,
            'title' => 'Product Showcase',
            'category' => 'ecommerce',
            'categoryName' => 'E-commerce Template',
            'description' =>
                'Elegant product showcase template for e-commerce websites. Features clean layout and professional product presentation.',
            'image' =>
                'https://images.unsplash.com/photo-1556740714-a8395b3bf30f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'paid',
            'downloads' => [
                'png' => '#download-png-8',
                'source' => '#download-psd-8',
                'sourceType' => 'Photoshop (PSD)',
            ],
        ],
        [
            'id' => 9,
            'title' => 'Minimal Logo Collection',
            'category' => 'logo',
            'categoryName' => 'Startup Branding',
            'description' =>
                'Minimal and modern logo collection perfect for startups and tech companies. Clean design with versatile applications.',
            'image' =>
                'https://images.unsplash.com/photo-1626785774573-4b799315345d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'type' => 'free',
            'downloads' => [
                'png' => '#download-png-9',
                'source' => '#download-ai-9',
                'sourceType' => 'Adobe Illustrator (AI)',
            ],
        ],
    ];
@endphp

<section id="portfolio" class="section-premium">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="section-title display-font">Featured Works</h2>
                <p class="section-subtitle">Explore our collection of creative projects and premium design assets</p>
            </div>
        </div>

        <!-- Portfolio Filters -->
        <div class="portfolio-filters">
            <button class="filter-btn-premium active" data-filter="*">All Projects</button>
            <button class="filter-btn-premium" data-filter=".logo">Logos</button>
            <button class="filter-btn-premium" data-filter=".social">Social Media</button>
            <button class="filter-btn-premium" data-filter=".banner">Banners</button>
            <button class="filter-btn-premium" data-filter=".ecommerce">E-commerce</button>
        </div>

        <!-- Portfolio Grid -->
        <div class="row g-4 portfolio-grid">
            @foreach ($projects as $project)
                <x-premium.portfolio-item :project="$project" />
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-5" id="loadMoreSection">
            <button type="button" class="btn btn-premium btn-premium-primary btn-lg" id="loadMoreBtn"
                onclick="loadMoreProjects()">
                Load More Projects
                <i class="bi bi-arrow-down ms-2"></i>
            </button>
            <p class="text-muted mt-3 d-none" id="noMoreProjects">
                <i class="bi bi-check-circle me-2"></i>All projects loaded!
            </p>
        </div>
    </div>
</section>

<!-- Portfolio Modal -->
<x-premium.portfolio-modal />

<script>
    // Store projects data globally for modal
    window.projectsData = @json($projects);

    // Additional projects for load more functionality
    window.additionalProjects = [{
            id: 10,
            title: 'Business Card Design',
            category: 'logo',
            categoryName: 'Brand Identity',
            description: 'Professional business card design with modern layout. Double-sided design with QR code integration.',
            image: 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            type: 'free',
            downloads: {
                png: '#download-png-10',
                source: '#download-ai-10',
                sourceType: 'Adobe Illustrator (AI)'
            }
        },
        {
            id: 11,
            title: 'Instagram Story Templates',
            category: 'social',
            categoryName: 'Social Media',
            description: 'Complete Instagram story template pack with 15 unique designs. Perfect for influencers and brands.',
            image: 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            type: 'paid',
            downloads: {
                png: '#download-png-11',
                source: '#download-psd-11',
                sourceType: 'Photoshop (PSD)'
            }
        },
        {
            id: 12,
            title: 'YouTube Thumbnail Pack',
            category: 'banner',
            categoryName: 'Video Marketing',
            description: 'Eye-catching YouTube thumbnail designs to boost your video views. 20 customizable templates included.',
            image: 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            type: 'free',
            downloads: {
                png: '#download-png-12',
                source: '#download-psd-12',
                sourceType: 'Photoshop (PSD)'
            }
        }
    ];

    let currentLoadIndex = 0;
    const loadMoreCount = 3;

    // Open Portfolio Modal Function
    function openPortfolioModal(project) {
        // Set modal content
        document.getElementById('modalTitle').textContent = project.title;
        document.getElementById('modalImage').src = project.image;
        document.getElementById('modalImage').alt = project.title;
        document.getElementById('modalCategory').textContent = project.categoryName;
        document.getElementById('modalType').textContent = project.type.toUpperCase();
        document.getElementById('modalDescription').textContent = project.description;

        // Set type badge
        const badge = document.getElementById('modalTypeBadge');
        badge.textContent = project.type.toUpperCase();
        badge.className = 'modal-type-badge ' + project.type;

        // Set download links
        document.getElementById('downloadPNG').href = project.downloads.png;
        document.getElementById('downloadSource').href = project.downloads.source;
        document.getElementById('sourceFileType').textContent = project.downloads.sourceType;
        document.getElementById('sourceFileName').textContent = project.downloads.sourceType.includes('AI') ?
            'Illustrator File' : 'Photoshop File';

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('portfolioModal'));
        modal.show();
    }

    // Load More Projects Function
    function loadMoreProjects() {
        const portfolioGrid = document.querySelector('.portfolio-grid');
        const loadMoreBtn = document.getElementById('loadMoreBtn');

        // Calculate how many projects to load
        const endIndex = Math.min(currentLoadIndex + loadMoreCount, window.additionalProjects.length);

        // Add new projects
        for (let i = currentLoadIndex; i < endIndex; i++) {
            const project = window.additionalProjects[i];

            // Create portfolio item HTML
            const colDiv = document.createElement('div');
            colDiv.className = `col-lg-4 col-md-6 portfolio-item ${project.category}`;
            colDiv.style.opacity = '0';

            colDiv.innerHTML = `
                <div class="position-relative portfolio-card">
                    <span class="portfolio-badge badge-${project.type === 'free' ? 'free' : 'paid'}">
                        ${project.type.toUpperCase()}
                    </span>
                    <img src="${project.image}"
                         alt="${project.title}"
                         class="portfolio-image img-fluid rounded-4">
                    <div class="portfolio-overlay">
                        <h4>${project.title}</h4>
                        <p class="portfolio-category">${project.categoryName}</p>
                        <button class="btn-view-premium"
                                onclick='openPortfolioModal(${JSON.stringify(project).replace(/'/g, "&apos;")})'>
                            <i class="bi bi-eye me-2"></i>
                            View Details
                        </button>
                    </div>
                </div>
            `;

            portfolioGrid.appendChild(colDiv);

            // Fade in animation
            setTimeout(() => {
                colDiv.style.transition = 'opacity 0.5s ease';
                colDiv.style.opacity = '1';
            }, 100 * (i - currentLoadIndex));
        }

        currentLoadIndex = endIndex;

        // Reinitialize Isotope
        if (typeof $ !== 'undefined' && $.fn.isotope) {
            $('.portfolio-grid').isotope('reloadItems').isotope();
        }

        // Check if all projects are loaded
        if (currentLoadIndex >= window.additionalProjects.length) {
            loadMoreBtn.classList.add('d-none');
            document.getElementById('noMoreProjects').classList.remove('d-none');
        }
    }
</script>
