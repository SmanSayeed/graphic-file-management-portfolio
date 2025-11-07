// Portfolio Modal JavaScript - Reusable Component
// This script handles the portfolio modal functionality

let currentProjectId = null;
let modalInstance = null;

// Cleanup function to remove modal backdrop
function cleanupModalBackdrop() {
    // Remove all modal backdrops (Bootstrap may create multiple)
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => {
        backdrop.remove();
    });

    // Check if drawer is open
    const drawer = document.querySelector('.mobile-drawer');
    const isDrawerOpen = drawer && drawer.classList.contains('active');

    // Always remove modal-open class
    if (document.body.classList.contains('modal-open')) {
        document.body.classList.remove('modal-open');
    }

    // Reset body styles based on drawer state
    if (!isDrawerOpen) {
        // Drawer is closed, so reset all body styles
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
        document.body.style.overflowX = '';
        document.body.style.overflowY = '';
    } else {
        // Drawer is open, only reset padding-right but keep overflow hidden
        document.body.style.paddingRight = '';
        // Don't change overflow, let drawer handle it
    }

    // Force remove any inline styles that might cause issues
    const computedStyle = window.getComputedStyle(document.body);
    if (computedStyle.overflow === 'hidden' && !isDrawerOpen) {
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('overflow-x');
        document.body.style.removeProperty('overflow-y');
    }
}

// Cleanup on page load in case of any lingering backdrops
document.addEventListener('DOMContentLoaded', function () {
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

        // Debug: Log the FULL project data to see everything
        console.log('=== FULL Project data received ===');
        console.log(JSON.stringify(project, null, 2));
        console.log('file_type value:', project.file_type, 'type:', typeof project.file_type);
        console.log('video_link value:', project.video_link, 'type:', typeof project.video_link);

        // Ensure we have the modal elements
        const modalTitleEl = document.getElementById('modalTitle');
        const modalCategoryEl = document.getElementById('modalCategory');
        const modalTypeEl = document.getElementById('modalType');
        const modalFileTypeEl = document.getElementById('modalFileType');
        const modalDescriptionEl = document.getElementById('modalDescription');
        const badgeEl = document.getElementById('modalTypeBadge');

        if (!modalTitleEl || !modalCategoryEl || !modalTypeEl || !modalFileTypeEl || !modalDescriptionEl || !badgeEl) {
            console.error('Missing modal elements!', {
                modalTitleEl: !!modalTitleEl,
                modalCategoryEl: !!modalCategoryEl,
                modalTypeEl: !!modalTypeEl,
                modalFileTypeEl: !!modalFileTypeEl,
                modalDescriptionEl: !!modalDescriptionEl,
                badgeEl: !!badgeEl
            });
        }

        // Set modal content - with safety checks
        if (modalTitleEl) modalTitleEl.textContent = project.title || 'Untitled';
        if (modalCategoryEl) modalCategoryEl.textContent = project.category?.name || 'Uncategorized';
        if (modalTypeEl) modalTypeEl.textContent = project.type ? String(project.type).toUpperCase() : 'FREE';

        // Set file_type - convert to string and ensure it's properly displayed
        const fileTypeValue = project.file_type ? String(project.file_type).trim() : 'image';
        if (modalFileTypeEl) {
            modalFileTypeEl.textContent = fileTypeValue.toUpperCase();
            console.log('✓ File type displayed:', fileTypeValue.toUpperCase());
        } else {
            console.error('✗ modalFileType element not found!');
        }

        if (modalDescriptionEl) modalDescriptionEl.textContent = project.description || 'No description available.';

        // Set type badge - Show FILE_TYPE (image/video), not type (paid/free)
        const fileTypeForBadge = fileTypeValue.toLowerCase();
        if (badgeEl) {
            badgeEl.textContent = fileTypeForBadge.toUpperCase();
            badgeEl.className = 'modal-type-badge ' + fileTypeForBadge;
            badgeEl.style.display = 'block';
            badgeEl.style.visibility = 'visible';
            console.log('✓ Badge set to:', fileTypeForBadge.toUpperCase());
        } else {
            console.error('✗ modalTypeBadge element not found!');
        }

        // Handle image or video display
        const imageContainer = document.getElementById('modalImageContainer');
        const videoContainer = document.getElementById('modalVideoContainer');
        const modalImage = document.getElementById('modalImage');
        const modalVideo = document.getElementById('modalVideo');

        // Reset containers to default state first
        // Remove ALL inline styles to let CSS handle everything
        if (imageContainer) {
            imageContainer.removeAttribute('style');
        }

        if (videoContainer) {
            // Start with hidden state
            videoContainer.classList.add('modal-video-container-hidden');
            videoContainer.classList.remove('show');
            videoContainer.removeAttribute('style');
        }

        // Clear previous video src to prevent playback issues
        if (modalVideo) {
            modalVideo.src = '';
            modalVideo.removeAttribute('style');
        }

        // Check if it's a video type project with a video link
        // Convert fileType to string and lowercase for comparison
        // Handle both string and any other type that might come from the API
        const rawFileType = project.file_type;
        const normalizedFileType = rawFileType ? String(rawFileType).toLowerCase().trim() : 'image';

        console.log('=== Video Display Check ===');
        console.log('Raw file_type:', rawFileType, 'Type:', typeof rawFileType);
        console.log('Normalized file_type:', normalizedFileType);
        console.log('Video link:', project.video_link);
        console.log('Has video link:', !!project.video_link);
        console.log('Elements found:', {
            modalVideo: !!modalVideo,
            videoContainer: !!videoContainer,
            imageContainer: !!imageContainer
        });

        // Check if it's a video type project - STRICT comparison
        if (normalizedFileType === 'video') {
            console.log('=== VIDEO TYPE DETECTED ===');
            console.log('File type is VIDEO, processing video display...');

            // Show video - ensure we have a valid video link
            const videoLink = project.video_link ? String(project.video_link).trim() : '';
            console.log('Raw video_link from API:', project.video_link);
            console.log('Processed video_link:', videoLink);
            console.log('Video link length:', videoLink.length);

            if (videoLink && videoLink.length > 0) {
                console.log('✓ Video link is valid:', videoLink);
                const embedUrl = convertYouTubeUrl(videoLink);
                console.log('Converted embed URL:', embedUrl);

                if (embedUrl && modalVideo && videoContainer && imageContainer) {
                    console.log('✓ All elements exist, proceeding with video display');
                    console.log('Elements:', {
                        modalVideo: modalVideo ? 'EXISTS' : 'MISSING',
                        videoContainer: videoContainer ? 'EXISTS' : 'MISSING',
                        imageContainer: imageContainer ? 'EXISTS' : 'MISSING'
                    });

                    // Set video source FIRST before showing container
                    const finalEmbedUrl = embedUrl + (embedUrl.includes('?') ? '&' : '?') + 'rel=0';
                    console.log('Setting iframe src to:', finalEmbedUrl);
                    modalVideo.src = finalEmbedUrl;
                    console.log('✓ Video iframe src set');

                    // HIDE image container completely - use inline styles for reliability
                    imageContainer.style.display = 'none';
                    imageContainer.style.visibility = 'hidden';
                    imageContainer.style.opacity = '0';
                    imageContainer.style.height = '0';
                    imageContainer.style.overflow = 'hidden';
                    console.log('✓ Image container hidden');

                    // SHOW video container - use CSS classes
                    // Remove ALL inline styles first to let CSS handle it
                    videoContainer.removeAttribute('style');
                    modalVideo.removeAttribute('style');
                    console.log('✓ Removed inline styles from containers');

                    // CRITICAL: Remove hidden class FIRST, then add show class
                    videoContainer.classList.remove('modal-video-container-hidden');
                    console.log('✓ Removed modal-video-container-hidden class');

                    // Add show class
                    videoContainer.classList.add('show');
                    console.log('✓ Added show class');
                    console.log('Current classes:', videoContainer.className);

                    // Ensure iframe is properly positioned (should be absolute per CSS)
                    if (modalVideo) {
                        // Iframe should be absolute - let CSS handle it, but ensure no conflicts
                        const iframeStyles = window.getComputedStyle(modalVideo);
                        console.log('Iframe computed position:', iframeStyles.position);
                    }

                    // Force multiple reflows to ensure styles are applied
                    void videoContainer.offsetHeight;
                    void videoContainer.offsetWidth;
                    void modalVideo.offsetHeight;

                    // Wait a tiny bit and check again
                    setTimeout(() => {
                        const computedStyles = window.getComputedStyle(videoContainer);
                        console.log('=== VIDEO CONTAINER STATE CHECK ===');
                        console.log('Classes:', videoContainer.className);
                        console.log('Computed styles:', {
                            display: computedStyles.display,
                            visibility: computedStyles.visibility,
                            opacity: computedStyles.opacity,
                            width: computedStyles.width,
                            height: computedStyles.height,
                            paddingBottom: computedStyles.paddingBottom,
                            position: computedStyles.position
                        });

                        // If still not visible, force it (last resort)
                        if (computedStyles.display === 'none' || computedStyles.visibility === 'hidden') {
                            console.warn('⚠️ Video container still hidden, forcing display with inline styles');
                            videoContainer.style.setProperty('display', 'block', 'important');
                            videoContainer.style.setProperty('visibility', 'visible', 'important');
                            videoContainer.style.setProperty('opacity', '1', 'important');
                            console.log('✓ Forced display with inline styles');
                        } else {
                            console.log('✓✓✓ Video container is VISIBLE!');
                        }
                    }, 50);
                } else {
                    console.error('Video display failed - missing elements or invalid URL:', {
                        embedUrl: embedUrl,
                        modalVideo: !!modalVideo,
                        videoContainer: !!videoContainer,
                        imageContainer: !!imageContainer
                    });
                    // Fallback: video link conversion failed or elements missing
                    // Fallback to thumbnail if video link is invalid
                    const fallbackImage = project.thumbnail ? `/storage/${project.thumbnail}` :
                        (project.image ? `/storage/${project.image}` : 'https://via.placeholder.com/800x600');
                    if (modalImage) {
                        modalImage.src = fallbackImage;
                        modalImage.alt = project.title;
                    }
                    if (imageContainer) {
                        imageContainer.style.display = 'block';
                        imageContainer.style.visibility = 'visible';
                        imageContainer.style.opacity = '1';
                    }
                    // Hide video container - add hidden class
                    if (videoContainer) {
                        videoContainer.classList.add('modal-video-container-hidden');
                        videoContainer.classList.remove('show');
                        if (modalVideo) modalVideo.src = '';
                    }
                }
            } else {
                // No video link but file_type is video, show thumbnail
                console.log('Video type but no video_link, showing thumbnail');
                const fallbackImage = project.thumbnail ? `/storage/${project.thumbnail}` :
                    (project.image ? `/storage/${project.image}` : 'https://via.placeholder.com/800x600');
                if (modalImage) {
                    modalImage.src = fallbackImage;
                    modalImage.alt = project.title;
                }
                // Show image container, hide video container
                if (imageContainer) {
                    imageContainer.style.display = 'flex';
                    imageContainer.style.visibility = 'visible';
                    imageContainer.style.opacity = '1';
                    imageContainer.style.height = 'auto';
                    imageContainer.style.overflow = 'visible';
                }
                if (videoContainer) {
                    videoContainer.classList.add('modal-video-container-hidden');
                    videoContainer.classList.remove('show');
                    if (modalVideo) modalVideo.src = '';
                }
            }
        } else {
            // Show image (file_type is 'image')
            console.log('File type is IMAGE, showing image');
            const imageSrc = project.thumbnail ? `/storage/${project.thumbnail}` :
                (project.image ? `/storage/${project.image}` : 'https://via.placeholder.com/800x600');
            if (modalImage) {
                modalImage.src = imageSrc;
                modalImage.alt = project.title;
            }
            // Show image container, hide video container
            if (imageContainer) {
                imageContainer.style.display = 'flex';
                imageContainer.style.visibility = 'visible';
                imageContainer.style.opacity = '1';
                imageContainer.style.height = 'auto';
                imageContainer.style.overflow = 'visible';
            }
            if (videoContainer) {
                videoContainer.classList.add('modal-video-container-hidden');
                videoContainer.classList.remove('show');
                if (modalVideo) {
                    modalVideo.src = '';
                }
            }
        }

        // Set download links with download tracking
        const downloadPNG = document.getElementById('downloadPNG');
        const downloadVideo = document.getElementById('downloadVideo');
        const downloadSource = document.getElementById('downloadSource');
        const downloadDivider = document.getElementById('downloadDivider');

        if (project.image || project.thumbnail) {
            if (downloadPNG) {
                downloadPNG.href = `/projects/${projectId}/download?type=image`;
                downloadPNG.style.display = 'block';
            }
        } else {
            if (downloadPNG) downloadPNG.style.display = 'none';
        }

        if (project.video) {
            if (downloadVideo) {
                downloadVideo.href = `/projects/${projectId}/download?type=video`;
                downloadVideo.style.display = 'block';
            }
            // Show divider if there are other download options
            if (downloadDivider && (project.image || project.thumbnail || project.source_file)) {
                downloadDivider.style.display = 'block';
            }
        } else {
            if (downloadVideo) downloadVideo.style.display = 'none';
        }

        if (project.source_file) {
            if (downloadSource) {
                downloadSource.href = `/projects/${projectId}/download?type=source`;
                downloadSource.style.display = 'block';
                const fileName = project.source_file.split('/').pop();
                const sourceFileName = document.getElementById('sourceFileName');
                if (sourceFileName) sourceFileName.textContent = fileName;
            }
        } else {
            if (downloadSource) downloadSource.style.display = 'none';
        }

        // Update like button
        updateLikeButton(project.like_count || 0, project.is_liked || false);
        const likeCountElement = document.getElementById('likeCount');
        if (likeCountElement) {
            likeCountElement.textContent = project.like_count || 0;
        }

        // Cleanup any existing backdrop first
        cleanupModalBackdrop();

        // Get modal element
        const modalElement = document.getElementById('portfolioModal');
        if (!modalElement) {
            console.error('Modal element not found');
            return;
        }

        // Dispose existing modal instance if any
        if (modalInstance) {
            try {
                // Hide modal first if it's showing
                if (modalInstance._isShown || modalElement.classList.contains('show')) {
                    modalInstance.hide();
                }
                // Wait a bit before disposing
                setTimeout(() => {
                    try {
                        modalInstance.dispose();
                    } catch (e) {
                        // Ignore disposal errors
                    }
                }, 100);
            } catch (e) {
                // Ignore disposal errors
            }
        }

        // Cleanup any existing backdrop before creating new modal
        cleanupModalBackdrop();

        // Create new modal instance
        modalInstance = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });

        // Enhanced cleanup function
        const performCleanup = function () {
            console.log('Performing modal cleanup...');

            // Stop video playback
            const videoEl = document.getElementById('modalVideo');
            if (videoEl) {
                videoEl.src = '';
            }

            // Cleanup backdrop immediately
            cleanupModalBackdrop();

            // Additional cleanup after a short delay
            setTimeout(function () {
                cleanupModalBackdrop();

                // Force remove any remaining backdrops
                const remainingBackdrops = document.querySelectorAll('.modal-backdrop');
                remainingBackdrops.forEach(backdrop => {
                    console.log('Removing lingering backdrop');
                    backdrop.remove();
                });

                // Ensure body is clickable
                const drawer = document.querySelector('.mobile-drawer');
                if (!drawer || !drawer.classList.contains('active')) {
                    document.body.style.overflow = '';
                    document.body.style.overflowX = '';
                    document.body.style.overflowY = '';
                    document.body.style.paddingRight = '';
                    document.body.style.pointerEvents = '';
                    document.body.classList.remove('modal-open');
                }

                console.log('Cleanup completed');
            }, 250);
        };

        // Setup cleanup handlers - MUST be done before showing modal
        // Use a more aggressive cleanup approach

        // Setup MutationObserver to watch for backdrop creation and remove it immediately
        // This observer will be active only while the modal is open
        let backdropObserver = null;

        const startBackdropObserver = function () {
            backdropObserver = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    mutation.addedNodes.forEach(function (node) {
                        if (node.nodeType === 1 && node.classList && node.classList.contains('modal-backdrop')) {
                            console.log('⚠️ Backdrop detected by observer! Removing immediately...');
                            // Small delay to let Bootstrap finish its setup, then remove
                            setTimeout(function () {
                                if (node.parentNode) {
                                    node.remove();
                                }
                            }, 10);
                        }
                    });
                });

                // Also check body for any backdrops after mutations
                const allBackdrops = document.querySelectorAll('.modal-backdrop');
                if (allBackdrops.length > 0) {
                    console.log(`⚠️ Observer found ${allBackdrops.length} backdrop(s) - removing`);
                    allBackdrops.forEach(function (backdrop) {
                        setTimeout(function () {
                            if (backdrop.parentNode) {
                                backdrop.remove();
                            }
                        }, 10);
                    });
                }
            });

            // Start observing body for backdrop additions
            backdropObserver.observe(document.body, {
                childList: true,
                subtree: false // Only watch direct children of body for performance
            });
            console.log('Backdrop observer started');
        };

        const stopBackdropObserver = function () {
            if (backdropObserver) {
                backdropObserver.disconnect();
                backdropObserver = null;
                console.log('Backdrop observer stopped');
            }
        };

        // Start observer when modal opens
        startBackdropObserver();

        // Cleanup function that runs on modal close
        const aggressiveCleanup = function () {
            console.log('=== Aggressive Cleanup Started ===');

            // Stop video playback
            const videoEl = document.getElementById('modalVideo');
            if (videoEl) {
                videoEl.src = '';
            }

            // Remove ALL backdrops immediately - MULTIPLE PASSES
            let passCount = 0;
            const removeBackdrops = function () {
                passCount++;
                const allBackdrops = document.querySelectorAll('.modal-backdrop');
                console.log(`Cleanup pass ${passCount}: Found ${allBackdrops.length} backdrop(s)`);
                allBackdrops.forEach((backdrop, index) => {
                    console.log(`Removing backdrop ${index + 1}`);
                    backdrop.remove();
                });

                // Also check if backdrop is in the process of being added
                const bodyChildren = Array.from(document.body.children);
                bodyChildren.forEach(function (child) {
                    if (child.classList && child.classList.contains('modal-backdrop')) {
                        console.log('Found backdrop in body children, removing...');
                        child.remove();
                    }
                });
            };

            // Multiple immediate passes
            removeBackdrops();
            removeBackdrops();
            removeBackdrops();

            // Remove modal-open class
            document.body.classList.remove('modal-open');

            // Reset all body styles
            const drawer = document.querySelector('.mobile-drawer');
            if (!drawer || !drawer.classList.contains('active')) {
                // Remove all inline styles
                document.body.style.overflow = '';
                document.body.style.overflowX = '';
                document.body.style.overflowY = '';
                document.body.style.paddingRight = '';
                document.body.style.pointerEvents = '';
                document.body.style.position = '';
                // Try to remove style attribute completely
                if (document.body.getAttribute('style') === '') {
                    document.body.removeAttribute('style');
                }
            }

            // Continue cleanup at intervals
            setTimeout(removeBackdrops, 50);
            setTimeout(removeBackdrops, 100);
            setTimeout(removeBackdrops, 200);
            setTimeout(removeBackdrops, 350);
            setTimeout(removeBackdrops, 500);

            console.log('=== Cleanup Completed ===');
        };

        // Override close button behavior - COMPLETE MANUAL CONTROL
        const closeButton = modalElement.querySelector('.btn-close');
        if (closeButton) {
            // Remove Bootstrap's data-bs-dismiss attribute
            closeButton.removeAttribute('data-bs-dismiss');

            // Remove any existing event listeners by cloning
            const newCloseButton = closeButton.cloneNode(true);
            closeButton.parentNode.replaceChild(newCloseButton, closeButton);

            // Add our custom handler that manually closes the modal
            newCloseButton.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('=== CLOSE BUTTON CLICKED ===');

                // Stop the observer first
                stopBackdropObserver();

                // Immediately remove any existing backdrops BEFORE hiding
                const existingBackdrops = document.querySelectorAll('.modal-backdrop');
                console.log(`Found ${existingBackdrops.length} existing backdrop(s) before hide`);
                existingBackdrops.forEach(b => {
                    console.log('Removing existing backdrop before hide');
                    b.remove();
                });

                // Manually hide the modal using Bootstrap's API
                if (modalInstance) {
                    console.log('Calling modalInstance.hide()');

                    // IMMEDIATELY start cleanup BEFORE hiding (to catch any backdrop creation during hide)
                    console.log('Starting immediate cleanup');
                    aggressiveCleanup();

                    // Hide the modal
                    modalInstance.hide();

                    // Continue aggressive cleanup at short intervals
                    setTimeout(() => {
                        console.log('Cleanup at 50ms');
                        aggressiveCleanup();
                    }, 50);

                    setTimeout(() => {
                        console.log('Cleanup at 100ms');
                        aggressiveCleanup();
                    }, 100);

                    setTimeout(() => {
                        console.log('Cleanup at 200ms');
                        aggressiveCleanup();
                    }, 200);

                    setTimeout(() => {
                        console.log('Cleanup at 350ms');
                        aggressiveCleanup();
                    }, 350);

                    setTimeout(() => {
                        console.log('Cleanup at 500ms');
                        aggressiveCleanup();
                    }, 500);

                    setTimeout(() => {
                        console.log('Final cleanup at 750ms');
                        aggressiveCleanup();
                        // Final aggressive pass
                        const finalBackdrops = document.querySelectorAll('.modal-backdrop');
                        if (finalBackdrops.length > 0) {
                            console.log(`Found ${finalBackdrops.length} backdrop(s) - removing`);
                            finalBackdrops.forEach(b => {
                                console.log('Final removal of backdrop');
                                b.remove();
                            });
                        }
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.overflowX = '';
                        document.body.style.overflowY = '';
                        document.body.style.paddingRight = '';
                        document.body.style.pointerEvents = '';
                        document.body.style.position = '';
                    }, 750);

                    // One last check after animation completes
                    setTimeout(() => {
                        console.log('Ultimate cleanup check at 1000ms');
                        const ultimateBackdrops = document.querySelectorAll('.modal-backdrop');
                        if (ultimateBackdrops.length > 0) {
                            console.log(`⚠️ Still found ${ultimateBackdrops.length} backdrop(s) - removing`);
                            ultimateBackdrops.forEach(b => b.remove());
                        }
                        document.body.classList.remove('modal-open');
                        // Reset all body styles
                        const drawer = document.querySelector('.mobile-drawer');
                        if (!drawer || !drawer.classList.contains('active')) {
                            document.body.style.cssText = '';
                            document.body.removeAttribute('style');
                        }
                        console.log('✅ Ultimate cleanup complete');
                    }, 1000);
                }
            });
        }

        // Listen to Bootstrap modal events - AGGRESSIVE CLEANUP
        const hiddenHandler = function () {
            console.log('=== Bootstrap hidden.bs.modal event fired ===');
            stopBackdropObserver(); // Stop observer when modal is fully hidden
            aggressiveCleanup();
            // Extra cleanup after event - multiple passes
            setTimeout(aggressiveCleanup, 50);
            setTimeout(aggressiveCleanup, 150);
            setTimeout(aggressiveCleanup, 300);
            setTimeout(aggressiveCleanup, 500);
        };

        const hideHandler = function () {
            console.log('=== Bootstrap hide.bs.modal event fired ===');
            aggressiveCleanup();
            // Start cleanup early
            setTimeout(aggressiveCleanup, 50);
            setTimeout(aggressiveCleanup, 200);
        };

        modalElement.addEventListener('hidden.bs.modal', hiddenHandler, false);
        modalElement.addEventListener('hide.bs.modal', hideHandler, false);

        // Also listen to the transition end event
        modalElement.addEventListener('transitionend', function (e) {
            if (e.target === modalElement && !modalElement.classList.contains('show')) {
                console.log('=== Modal transition ended ===');
                stopBackdropObserver();
                aggressiveCleanup();
            }
        }, false);

        // Handle backdrop click
        modalElement.addEventListener('click', function (e) {
            if (e.target === modalElement) {
                console.log('Backdrop clicked');
                aggressiveCleanup();
                setTimeout(aggressiveCleanup, 200);
                setTimeout(aggressiveCleanup, 500);
            }
        });

        // Handle ESC key
        const escHandler = function (e) {
            if (e.key === 'Escape' && modalElement.classList.contains('show')) {
                console.log('ESC key pressed');
                aggressiveCleanup();
                setTimeout(aggressiveCleanup, 200);
                setTimeout(aggressiveCleanup, 500);
            }
        };
        document.addEventListener('keydown', escHandler);

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
            const likeCountElement = document.getElementById('likeCount');
            if (likeCountElement) {
                likeCountElement.textContent = data.like_count;
            }
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

// Make functions globally available
window.openProjectModal = openProjectModal;
window.toggleLike = toggleLike;
window.cleanupModalBackdrop = cleanupModalBackdrop;
window.convertYouTubeUrl = convertYouTubeUrl;



