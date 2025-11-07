// Bootstrap Dropdown Fix
document.addEventListener('DOMContentLoaded', function () {
    // Initialize all dropdowns
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));

    // Fix dropdown issue in modals
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (dropdown) {
        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    // Mobile drawer is handled by navigation-slider.js (jQuery)
    // Removed duplicate handler to prevent conflicts
});

// Global Like Functionality
let currentProjectId = null;

// Toggle Like Function
async function toggleLike() {
    if (!currentProjectId) return;

    // Check if user is logged in
    try {
        const statusResponse = await fetch(`/projects/${currentProjectId}/like-status`);
        const status = await statusResponse.json();

        if (!status.is_authenticated) {
            alert('Please login to like projects');
            window.location.href = '/login?tab=login';
            return;
        }

        const likeResponse = await fetch(`/projects/${currentProjectId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
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

    if (!likeIcon || !likeText || !likeButton) return;

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

// Open Project Modal with dynamic data
async function openProjectModal(projectId) {
    currentProjectId = projectId;
    try {
        const response = await fetch(`/project/${projectId}`);
        const project = await response.json();

        // Set modal content
        document.getElementById('modalTitle').textContent = project.title;
        document.getElementById('modalImage').src = project.thumbnail ? `/storage/${project.thumbnail}` : 'https://via.placeholder.com/800x600';
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
        updateLikeButton(project.like_count || 0, project.is_liked || false);
        document.getElementById('likeCount').textContent = project.like_count || 0;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('portfolioModal'));
        modal.show();
    } catch (error) {
        console.error('Error fetching project:', error);
        alert('Failed to load project details');
    }
}
