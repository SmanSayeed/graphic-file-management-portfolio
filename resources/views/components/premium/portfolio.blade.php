@php
    // Projects come from HomeController
    $projects = $projects ?? [];
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
        <div class="row portfolio-grid" style="margin-left: -10px; margin-right: -10px;">
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
    let currentProjectId = null;

    // Open Project Modal with dynamic data
    async function openProjectModal(projectId) {
        currentProjectId = projectId;
        try {
            const response = await fetch(`/project/${projectId}`);
            const project = await response.json();

            // Set modal content
            document.getElementById('modalTitle').textContent = project.title;
            document.getElementById('modalImage').src = project.thumbnail ? `/storage/${project.thumbnail}` :
                'https://via.placeholder.com/800x600';
            document.getElementById('modalImage').alt = project.title;
            document.getElementById('modalCategory').textContent = project.category?.name || 'Uncategorized';
            document.getElementById('modalType').textContent = project.type.toUpperCase();
            document.getElementById('modalDescription').textContent = project.description;

            // Set type badge
            const badge = document.getElementById('modalTypeBadge');
            badge.textContent = project.type.toUpperCase();
            badge.className = 'modal-type-badge ' + project.type;

            // Set download links
            if (project.image) {
                document.getElementById('downloadPNG').href = `/storage/${project.image}`;
                document.getElementById('downloadPNG').style.display = 'block';
            } else {
                document.getElementById('downloadPNG').style.display = 'none';
            }

            if (project.source_file) {
                document.getElementById('downloadSource').href = `/storage/${project.source_file}`;
                document.getElementById('downloadSource').style.display = 'block';
            } else {
                document.getElementById('downloadSource').style.display = 'none';
            }

            // Update like button
            updateLikeButton(project.like_count, project.is_liked);
            document.getElementById('likeCount').textContent = project.like_count || 0;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('portfolioModal'));
            modal.show();
        } catch (error) {
            console.error('Error fetching project:', error);
            alert('Failed to load project details');
        }
    }

    // Open Portfolio Modal Function (for static data)
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

        // Initialize dropdown after modal is shown
        setTimeout(function() {
            const dropdownButton = document.getElementById('dropdownMenuButton');
            if (dropdownButton && typeof bootstrap !== 'undefined') {
                new bootstrap.Dropdown(dropdownButton);
            }
        }, 100);
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

    // Toggle Like Function
    async function toggleLike() {
        if (!currentProjectId) return;

        // Check if user is logged in
        const response = await fetch(`/projects/${currentProjectId}/like-status`);
        const status = await response.json();

        if (!status.is_authenticated) {
            alert('Please login to like projects');
            window.location.href = '/login?tab=login';
            return;
        }

        try {
            const likeResponse = await fetch(`/projects/${currentProjectId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({})
            });

            const data = await likeResponse.json();

            if (data.success) {
                updateLikeButton(data.like_count, data.is_liked);
                document.getElementById('likeCount').textContent = data.like_count;
            }
        } catch (error) {
            console.error('Error toggling like:', error);
            alert('Failed to update like');
        }
    }

    // Update Like Button
    function updateLikeButton(likeCount, isLiked) {
        const likeIcon = document.getElementById('likeIcon');
        const likeText = document.getElementById('likeText');
        const likeButton = document.getElementById('likeButton');

        if (isLiked) {
            likeIcon.className = 'bi bi-heart-fill';
            likeText.textContent = 'Liked';
            likeButton.classList.add('btn-danger');
            likeButton.classList.remove('btn-outline-danger');
        } else {
            likeIcon.className = 'bi bi-heart';
            likeText.textContent = 'Like';
            likeButton.classList.remove('btn-danger');
            likeButton.classList.add('btn-outline-danger');
        }
    }
</script>
