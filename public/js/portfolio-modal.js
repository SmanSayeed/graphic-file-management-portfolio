/**
 * Portfolio Modal - Bootstrap 5 + jQuery
 * Simple and clean modal implementation
 */

(function($) {
    'use strict';

    // Check if jQuery and Bootstrap are available
    if (typeof $ === 'undefined' || typeof bootstrap === 'undefined') {
        console.error('jQuery or Bootstrap is not loaded!');
        return;
    }

    let currentProjectId = null;
    let modalInstance = null;

    // Convert YouTube URL to embed format
    function convertYouTubeUrl(url) {
        if (!url) return null;

        let videoId = null;

        // Standard YouTube URL: https://www.youtube.com/watch?v=VIDEO_ID
        if (url.includes('youtube.com/watch?v=')) {
            videoId = url.split('v=')[1].split('&')[0];
        }
        // Short YouTube URL: https://youtu.be/VIDEO_ID
        else if (url.includes('youtu.be/')) {
            videoId = url.split('youtu.be/')[1].split('?')[0];
        }
        // Already embed format
        else if (url.includes('youtube.com/embed/')) {
            return url;
        }

        if (videoId) {
            return 'https://www.youtube.com/embed/' + videoId;
        }

        return null;
    }

    // Open Project Modal
    window.openProjectModal = function(projectId) {
        currentProjectId = projectId;

        // Get or create modal instance
        const modalElement = $('#portfolioModal');
        if (!modalElement.length) {
            console.error('Modal element not found!');
            return;
        }

        // Initialize Bootstrap modal if not already initialized
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement[0], {
                backdrop: true,
                keyboard: true,
                focus: true
            });
        }

        // Show loading state
        $('#modalTitle').text('Loading...');
        $('#modalImageContainer').hide();
        $('#modalVideoContainer').hide();
        $('#modalFileTypeBadge').hide();
        $('#modalTypeBadge').hide();

        // Fetch project data
        $.ajax({
            url: '/project/' + projectId,
            method: 'GET',
            dataType: 'json',
            success: function(project) {
                // Populate modal with project data
                populateModal(project);
                
                // Show modal
                modalInstance.show();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching project:', error);
                alert('Failed to load project details. Please try again.');
            }
        });
    };

    // Populate Modal with Project Data
    function populateModal(project) {
        // Basic Information
        $('#modalTitle').text(project.title || 'Project Title');
        $('#modalCategory').text(project.category ? project.category.name : '-');
        $('#modalType').text(project.type ? project.type.toUpperCase() : '-');
        $('#modalFileType').text(project.file_type ? project.file_type.toUpperCase() : '-');
        $('#modalDescription').text(project.description || project.short_description || '-');

        // File Type Badge - Top Right (image/video)
        const fileTypeBadge = $('#modalFileTypeBadge');
        fileTypeBadge.removeClass('image video free paid');
        if (project.file_type) {
            const fileType = project.file_type.toLowerCase();
            fileTypeBadge.addClass(fileType).text(fileType.toUpperCase()).show();
        } else {
            fileTypeBadge.hide();
        }

        // Type Badge - Bottom Right (paid/free)
        const typeBadge = $('#modalTypeBadge');
        typeBadge.removeClass('image video free paid');
        if (project.type) {
            const type = project.type.toLowerCase();
            typeBadge.addClass(type).text(type.toUpperCase()).show();
        } else {
            typeBadge.hide();
        }

        // Image or Video Display
        const fileType = (project.file_type || '').toLowerCase();
        const videoLink = project.video_link;

        if (fileType === 'video' && videoLink) {
            // Show Video
            const embedUrl = convertYouTubeUrl(videoLink);
            if (embedUrl) {
                $('#modalVideo').attr('src', embedUrl);
                $('#modalImageContainer').hide();
                $('#modalVideoContainer').show();
            } else {
                // Invalid video URL, show image
                showImage(project);
            }
        } else {
            // Show Image
            showImage(project);
        }

        // Download Links
        setupDownloadLinks(project);

        // Like Button
        setupLikeButton(project);
    }

    // Show Image
    function showImage(project) {
        let imageSrc = '';
        if (project.thumbnail) {
            imageSrc = '/storage/' + project.thumbnail;
        } else if (project.image) {
            imageSrc = '/storage/' + project.image;
        } else {
            imageSrc = 'https://via.placeholder.com/800x600';
        }

        $('#modalImage').attr('src', imageSrc).attr('alt', project.title || 'Project Image');
        $('#modalImageContainer').show();
        $('#modalVideoContainer').hide();
        $('#modalVideo').attr('src', ''); // Clear video src
    }

    // Setup Download Links based on file_type
    function setupDownloadLinks(project) {
        // Reset all download links
        $('#downloadImage').hide();
        $('#downloadSource').hide();

        const projectId = project.id;
        const fileType = (project.file_type || '').toLowerCase();

        // Get file type icon helper
        function getFileIcon(extension) {
            const ext = (extension || '').toUpperCase();
            if (['PSD'].includes(ext)) {
                return 'bi-file-earmark-image text-info';
            } else if (['AI'].includes(ext)) {
                return 'bi-file-earmark-image text-warning';
            } else if (['ZIP', 'RAR', '7Z'].includes(ext)) {
                return 'bi-file-earmark-zip text-success';
            } else if (['MP4', 'AVI', 'MOV', 'WMV'].includes(ext)) {
                return 'bi-file-earmark-play text-danger';
            } else if (['PNG', 'JPG', 'JPEG'].includes(ext)) {
                return 'bi-file-earmark-image text-primary';
            }
            return 'bi-file-earmark text-secondary';
        }

        if (fileType === 'image') {
            // For image type: show image download (if exists) and source file (if exists)
            // Minimum 1 file (image), maximum 2 files (image + source)
            
            // Image Download - Prefer image over thumbnail
            if (project.image || project.thumbnail) {
                const imageUrl = '/projects/' + projectId + '/download?type=image';
                $('#downloadImage').attr('href', imageUrl);
                
                // Set image file name and type - prefer image extension over thumbnail
                const imageExt = project.image_extension || project.thumbnail_extension || '';
                const imageType = project.image_file_type || 
                    (imageExt ? imageExt.toUpperCase() + ' Image' : 'Image File');
                $('#imageFileName').text(imageType);
                $('#imageFileType').text(imageExt ? imageExt.toUpperCase() + ' Image' : 'High Quality Image');
                
                $('#downloadImage').show();
            }

            // Source File Download (for image projects)
            if (project.source_file) {
                const sourceUrl = '/projects/' + projectId + '/download?type=source';
                $('#downloadSource').attr('href', sourceUrl);
                
                // Set source file name and type
                const sourceExt = project.source_file_extension || 'File';
                const sourceType = project.source_file_type || (sourceExt + ' File');
                $('#sourceFileName').text(sourceType);
                $('#sourceFileType').text(sourceExt ? sourceExt.toUpperCase() + ' File' : 'Source File');
                
                // Set icon based on extension
                const iconClass = getFileIcon(sourceExt);
                $('#sourceFileIcon').removeClass().addClass('bi me-2').addClass(iconClass);
                
                $('#downloadSource').show();
            }
        } else if (fileType === 'video') {
            // For video type: show only source file (video file or source_file)
            // Priority: source_file if exists, otherwise video
            
            let videoFile = null;
            let videoExt = null;
            let videoType = null;
            let downloadType = 'source';

            if (project.source_file) {
                // Use source_file if available
                videoFile = project.source_file;
                videoExt = project.source_file_extension;
                videoType = project.source_file_type;
                downloadType = 'source';
            } else if (project.video) {
                // Fallback to video file
                videoFile = project.video;
                videoExt = project.video_extension;
                videoType = project.video_file_type;
                downloadType = 'video';
            }

            if (videoFile) {
                const videoUrl = '/projects/' + projectId + '/download?type=' + downloadType;
                $('#downloadSource').attr('href', videoUrl);
                
                // Set video file name and type
                const displayType = videoType || (videoExt ? videoExt.toUpperCase() + ' Video' : 'Video File');
                $('#sourceFileName').text(displayType);
                $('#sourceFileType').text(videoExt ? videoExt.toUpperCase() + ' Video File' : 'Video File');
                
                // Set icon based on extension
                const iconClass = getFileIcon(videoExt);
                $('#sourceFileIcon').removeClass().addClass('bi me-2').addClass(iconClass);
                
                $('#downloadSource').show();
            }
        }
    }

    // Custom Dropdown Functionality
    function initCustomDropdown() {
        // Use event delegation for dynamically added items
        $(document).on('click', '#downloadDropdownBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdown = $(this).closest('.custom-download-dropdown');
            dropdown.toggleClass('active');
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.custom-download-dropdown').length) {
                $('.custom-download-dropdown').removeClass('active');
            }
        });

        // Close dropdown when clicking on a download item (using event delegation)
        $(document).on('click', '.custom-download-item', function(e) {
            // Only close if it's not a # link (which means it's not set up yet)
            if ($(this).attr('href') === '#') {
                e.preventDefault();
            } else {
                // Allow the link to work, then close dropdown after a short delay
                setTimeout(function() {
                    $('.custom-download-dropdown').removeClass('active');
                }, 100);
            }
        });

        // Close dropdown when modal is closed
        $('#portfolioModal').on('hidden.bs.modal', function() {
            $('.custom-download-dropdown').removeClass('active');
        });

        // Prevent dropdown from closing when clicking inside the menu
        $(document).on('click', '#downloadDropdownMenu', function(e) {
            e.stopPropagation();
        });
    }

    // Setup Like Button
    function setupLikeButton(project) {
        const isLiked = project.is_liked || false;
        const likeCount = project.like_count || 0;

        const likeIcon = $('#likeIcon');
        const likeText = $('#likeText');
        const likeCountEl = $('#likeCount');

        if (isLiked) {
            likeIcon.removeClass('bi-heart').addClass('bi-heart-fill');
            likeText.text('Liked');
        } else {
            likeIcon.removeClass('bi-heart-fill').addClass('bi-heart');
            likeText.text('Like');
        }

        likeCountEl.text(likeCount);

        // Remove previous click handler and add new one
        $('#likeButton').off('click').on('click', function() {
            toggleLike(project.id);
        });
    }

    // Toggle Like
    function toggleLike(projectId) {
        if (!projectId) {
            projectId = currentProjectId;
        }

        if (!projectId) {
            console.error('No project ID available');
            return;
        }

        $.ajax({
            url: '/projects/' + projectId + '/like',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Update like button state
                const likeIcon = $('#likeIcon');
                const likeText = $('#likeText');
                const likeCountEl = $('#likeCount');

                if (response.is_liked) {
                    likeIcon.removeClass('bi-heart').addClass('bi-heart-fill');
                    likeText.text('Liked');
                } else {
                    likeIcon.removeClass('bi-heart-fill').addClass('bi-heart');
                    likeText.text('Like');
                }

                likeCountEl.text(response.like_count || 0);
            },
            error: function(xhr, status, error) {
                console.error('Error toggling like:', error);
                if (xhr.status === 401) {
                    alert('Please login to like projects.');
                } else {
                    alert('Failed to update like. Please try again.');
                }
            }
        });
    }

    // Cleanup on modal close - jQuery implementation
    $(document).ready(function() {
        // Initialize custom dropdown
        initCustomDropdown();

        const modalElement = $('#portfolioModal');

        // Cleanup function
        function cleanupModal() {
            // Clear video src to stop playback
            $('#modalVideo').attr('src', '');
            
            // Remove all backdrops
            $('.modal-backdrop').remove();
            
            // Remove modal-open class
            $('body').removeClass('modal-open');
            
            // Reset body styles only if drawer is not open
            const drawer = $('.mobile-drawer');
            if (!drawer.hasClass('active')) {
                $('body').css({
                    'overflow': '',
                    'overflow-x': '',
                    'overflow-y': '',
                    'padding-right': '',
                    'pointer-events': ''
                });
            }
        }

        // Cleanup when modal starts hiding
        modalElement.on('hide.bs.modal', function() {
            console.log('Modal hide event triggered');
        });

        // Cleanup when modal is fully hidden
        modalElement.on('hidden.bs.modal', function() {
            console.log('Modal hidden event triggered');
            
            // Immediate cleanup
            cleanupModal();
            
            // Clear modal content
            $('#modalTitle').text('');
            $('#modalImageContainer').hide();
            $('#modalVideoContainer').hide();
            currentProjectId = null;
            
            // Additional cleanup after a short delay
            setTimeout(function() {
                cleanupModal();
            }, 100);
            
            // Final cleanup
            setTimeout(function() {
                cleanupModal();
                // Ensure no backdrop remains
                if ($('.modal-backdrop').length > 0) {
                    $('.modal-backdrop').remove();
                }
            }, 300);
        });

        // Handle close button click explicitly
        modalElement.find('.btn-close').on('click', function() {
            console.log('Close button clicked');
            // Let Bootstrap handle the modal close, then cleanup
            setTimeout(function() {
                cleanupModal();
            }, 100);
            setTimeout(function() {
                cleanupModal();
            }, 300);
        });

        // Handle ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modalElement.hasClass('show')) {
                setTimeout(function() {
                    cleanupModal();
                }, 100);
                setTimeout(function() {
                    cleanupModal();
                }, 300);
            }
        });

        // Cleanup on page load if any backdrop exists
        if ($('.modal-backdrop').length > 0) {
            cleanupModal();
        }
    });

})(jQuery);

