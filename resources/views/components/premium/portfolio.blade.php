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
            @if (isset($categories) && $categories->count() > 0)
                @foreach ($categories as $category)
                    <button class="filter-btn-premium"
                        data-filter=".{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            @endif
        </div>

        <!-- Portfolio Grid -->
        <div class="row portfolio-grid" style="margin-left: -10px; margin-right: -10px;">
            @foreach ($projects as $project)
                <x-premium.portfolio-item :project="$project" />
            @endforeach
        </div>

        @if ($projects->count() === 0)
            <div class="text-center py-5">
                <p class="text-muted">No projects available at the moment.</p>
            </div>
        @endif
    </div>
</section>

<!-- Portfolio Modal -->
<x-premium.portfolio-modal />

<script>
    // Store projects data globally for modal
    window.projectsData = @json($projects);
    let currentProjectId = null;
    let modalInstance = null;

    // Cleanup function to remove modal backdrop
    function cleanupModalBackdrop() {
        // Remove any lingering backdrop elements
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());

        // Remove modal-open class from body
        document.body.classList.remove('modal-open');

        // Reset body styles
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
    }

    // Cleanup on page load in case of any lingering backdrops
    document.addEventListener('DOMContentLoaded', function() {
        cleanupModalBackdrop();
    });

    // Convert YouTube URL to embed format
    function convertYouTubeUrl(url) {
        if (!url) return null;

        // Handle different YouTube URL formats
        let videoId = null;

        // Standard YouTube URL: https://www.youtube.com/watch?v=VIDEO_ID
        if (url.includes('youtube.com/watch?v=')) {
            videoId = url.split('v=')[1].split('&')[0];
        }
        // Short YouTube URL: https://youtu.be/VIDEO_ID
        else if (url.includes('youtu.be/')) {
            videoId = url.split('youtu.be/')[1].split('?')[0];
        }
        // Already embed format: https://www.youtube.com/embed/VIDEO_ID
        else if (url.includes('youtube.com/embed/')) {
            return url;
        }

        if (videoId) {
            return `https://www.youtube.com/embed/${videoId}`;
        }

        return null;
    }

    // Open Project Modal with dynamic data
    async function openProjectModal(projectId) {
        currentProjectId = projectId;
        try {
            const response = await fetch(`/project/${projectId}`);
            const project = await response.json();

            // Set modal content
            document.getElementById('modalTitle').textContent = project.title;
            document.getElementById('modalCategory').textContent = project.category?.name || 'Uncategorized';
            document.getElementById('modalType').textContent = project.type.toUpperCase();
            document.getElementById('modalFileType').textContent = (project.file_type || 'IMAGE').toUpperCase();
            document.getElementById('modalDescription').textContent = project.description;

            // Set type badge (file_type instead of paid/free)
            const badge = document.getElementById('modalTypeBadge');
            const fileType = project.file_type || 'image';
            badge.textContent = fileType.toUpperCase();
            badge.className = 'modal-type-badge ' + fileType;

            // Handle image or video display
            const imageContainer = document.getElementById('modalImageContainer');
            const videoContainer = document.getElementById('modalVideoContainer');
            const modalImage = document.getElementById('modalImage');
            const modalVideo = document.getElementById('modalVideo');

            if (fileType === 'video' && project.video_link) {
                // Show video
                const embedUrl = convertYouTubeUrl(project.video_link);
                if (embedUrl) {
                    modalVideo.src = embedUrl;
                    imageContainer.style.display = 'none';
                    videoContainer.style.display = 'block';
                } else {
                    // Fallback to thumbnail if video link is invalid
                    modalImage.src = project.thumbnail ? `/storage/${project.thumbnail}` :
                        'https://via.placeholder.com/800x600';
                    modalImage.alt = project.title;
                    imageContainer.style.display = 'block';
                    videoContainer.style.display = 'none';
                }
            } else {
                // Show image
                modalImage.src = project.thumbnail ? `/storage/${project.thumbnail}` :
                    'https://via.placeholder.com/800x600';
                modalImage.alt = project.title;
                imageContainer.style.display = 'block';
                videoContainer.style.display = 'none';
                modalVideo.src = ''; // Clear video src
            }

            // Set download links with download tracking
            if (project.image) {
                const downloadPNG = document.getElementById('downloadPNG');
                downloadPNG.href = `/projects/${projectId}/download?type=image`;
                downloadPNG.style.display = 'block';
                // Download is tracked automatically when the file is served
            } else {
                document.getElementById('downloadPNG').style.display = 'none';
            }

            if (project.video) {
                const downloadVideo = document.getElementById('downloadVideo');
                downloadVideo.href = `/projects/${projectId}/download?type=video`;
                downloadVideo.style.display = 'block';
                // Show divider if there are other download options
                const divider = document.getElementById('downloadDivider');
                if (project.image || project.source_file) {
                    divider.style.display = 'block';
                }
            } else {
                document.getElementById('downloadVideo').style.display = 'none';
            }

            if (project.source_file) {
                const downloadSource = document.getElementById('downloadSource');
                downloadSource.href = `/projects/${projectId}/download?type=source`;
                downloadSource.style.display = 'block';
                // Download is tracked automatically when the file is served
            } else {
                document.getElementById('downloadSource').style.display = 'none';
            }

            // Update like button
            updateLikeButton(project.like_count || 0, project.is_liked || false);
            document.getElementById('likeCount').textContent = project.like_count || 0;

            // Cleanup any existing backdrop first
            cleanupModalBackdrop();

            // Get modal element
            const modalElement = document.getElementById('portfolioModal');

            // Dispose existing modal instance if any
            if (modalInstance) {
                try {
                    modalInstance.dispose();
                } catch (e) {
                    // Ignore disposal errors
                }
            }

            // Create new modal instance
            modalInstance = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });

            // Clean up backdrop when modal is fully hidden (after animation)
            modalElement.addEventListener('hidden.bs.modal', function cleanup() {
                cleanupModalBackdrop();
                // Remove this listener to prevent memory leaks
                modalElement.removeEventListener('hidden.bs.modal', cleanup);
            });

            // Show modal
            modalInstance.show();
        } catch (error) {
            console.error('Error fetching project:', error);
            alert('Failed to load project details');
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
