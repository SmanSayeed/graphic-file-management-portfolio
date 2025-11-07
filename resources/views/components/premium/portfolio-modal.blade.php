<!-- Portfolio Modal - Bootstrap 5 + jQuery -->
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content portfolio-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="portfolioModalLabel">
                    <span id="modalTitle">Project Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Desktop Layout: Image/Video (60%) + Details (40%) -->
                <div class="row">
                    <!-- Left Side: Image/Video (60%) -->
                    <div class="col-lg-7 col-12 mb-4 mb-lg-0">
                        <div class="modal-media-wrapper">
                            <!-- Image Container -->
                            <div id="modalImageContainer" class="modal-media-item">
                                <img id="modalImage" src="" class="img-fluid rounded-4 w-100"
                                    alt="Project Image">
                            </div>
                            <!-- Video Container -->
                            <div id="modalVideoContainer" class="modal-media-item" style="display: none;">
                                <div class="ratio ratio-16x9">
                                    <iframe id="modalVideo" src="" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen class="rounded-4"></iframe>
                                </div>
                            </div>
                            <!-- File Type Badge - Top Right -->
                            <span id="modalFileTypeBadge" class="modal-type-badge modal-badge-file-type"></span>
                            <!-- Type Badge - Bottom Right -->
                            <span id="modalTypeBadge" class="modal-type-badge modal-badge-type"></span>
                        </div>
                    </div>

                    <!-- Right Side: Details (40%) -->
                    <div class="col-lg-5 col-12">
                        <div class="project-details-sidebar">
                            <!-- Category, Type, File Type -->
                            <div class="mb-4">
                                <div class="mb-3">
                                    <h6 class="detail-label">Category</h6>
                                    <p class="fw-semibold mb-0" id="modalCategory">-</p>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <h6 class="detail-label">Type</h6>
                                        <p class="fw-semibold mb-0" id="modalType">-</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6 class="detail-label">File Type</h6>
                                        <p class="fw-semibold mb-0" id="modalFileType">-</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Download Button - Custom Dropdown -->
                            <div class="custom-download-dropdown mb-3">
                                <button class="custom-download-btn" type="button" id="downloadDropdownBtn">
                                    <i class="bi bi-download me-2"></i>
                                    <span>Download Files</span>
                                    <i class="bi bi-chevron-down ms-2 dropdown-arrow"></i>
                                </button>
                                <div class="custom-download-menu" id="downloadDropdownMenu">
                                    <!-- Image Download (for file_type = image) -->
                                    <a href="#" class="custom-download-item" id="downloadImage"
                                        style="display: none;">
                                        <i class="bi bi-file-earmark-image text-primary me-2"></i>
                                        <div class="download-item-content">
                                            <strong class="d-block" id="imageFileName">Image File</strong>
                                            <small class="text-muted" id="imageFileType">Image</small>
                                        </div>
                                    </a>
                                    <!-- Source File Download -->
                                    <a href="#" class="custom-download-item" id="downloadSource"
                                        style="display: none;">
                                        <i class="bi bi-file-earmark-zip text-success me-2" id="sourceFileIcon"></i>
                                        <div class="download-item-content">
                                            <strong class="d-block" id="sourceFileName">Source File</strong>
                                            <small class="text-muted d-block" id="sourceFileType">Source File</small>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Like Button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-outline-danger btn-lg w-100" id="likeButton">
                                    <i class="bi bi-heart" id="likeIcon"></i>
                                    <span id="likeText">Like</span>
                                    <span class="badge bg-danger" id="likeCount">0</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description: Full Width Below -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="project-description-section">
                            <h6 class="detail-label">Description</h6>
                            <p class="mb-0" id="modalDescription">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .portfolio-modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        background: linear-gradient(135deg, #00B894 0%, #F5576C 100%);
        color: white;
        padding: 20px 30px;
        border: none;
    }

    .modal-header .modal-title {
        color: white !important;
        font-size: 22px;
        font-weight: 700;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 1;
    }

    .modal-media-wrapper {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        min-height: 400px;
        background: #f8f9fa;
    }

    .modal-media-item {
        width: 100%;
        height: 100%;
        min-height: 400px;
    }

    #modalImageContainer {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    #modalImageContainer img {
        max-height: 100%;
        object-fit: contain;
        border-radius: 15px;
    }

    #modalVideoContainer {
        background: #000;
        padding: 0;
    }

    #modalVideoContainer .ratio {
        min-height: 400px;
    }

    .modal-type-badge {
        position: absolute;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    /* File Type Badge - Top Right */
    .modal-badge-file-type {
        top: 15px;
        right: 15px;
        bottom: auto;
    }

    /* Type Badge - Bottom Right */
    .modal-badge-type {
        top: auto;
        bottom: 15px;
        right: 15px;
    }

    .modal-type-badge.image {
        background: #17a2b8;
        color: white;
    }

    .modal-type-badge.video {
        background: #ffc107;
        color: #212529;
    }

    .modal-type-badge.free {
        background: #00B894;
        color: white;
    }

    .modal-type-badge.paid {
        background: #FD79A8;
        color: white;
    }

    .project-details-sidebar {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .detail-label {
        font-size: 16px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .project-description-section {
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    /* Custom Download Dropdown Styles */
    .custom-download-dropdown {
        position: relative;
        width: 100%;
    }

    .custom-download-btn {
        width: 100%;
        background-color: #00B894;
        border: 2px solid #00B894;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .custom-download-btn:hover {
        background-color: #00a085;
        border-color: #00a085;
    }

    .custom-download-btn .dropdown-arrow {
        transition: transform 0.3s ease;
        font-size: 14px;
    }

    .custom-download-dropdown.active .custom-download-btn .dropdown-arrow {
        transform: rotate(180deg);
    }

    .custom-download-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        padding: 8px;
        margin-top: 5px;
        z-index: 1055;
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        max-height: 300px;
        overflow-y: auto;
    }

    .custom-download-dropdown.active .custom-download-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .custom-download-item {
        display: flex;
        align-items: flex-start;
        padding: 12px 15px;
        border-radius: 6px;
        text-decoration: none;
        color: #212529;
        transition: background-color 0.2s ease;
        gap: 10px;
    }

    .custom-download-item:hover {
        background-color: #f8f9fa;
        color: #212529;
    }

    .custom-download-item i {
        font-size: 22px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .download-item-content {
        flex: 1;
    }

    .download-item-content strong {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .download-item-content small {
        font-size: 12px;
        color: #6c757d;
    }

    .custom-download-divider {
        margin: 5px 0;
        border: 0;
        border-top: 1px solid #e0e0e0;
    }

    @media (max-width: 991.98px) {
        .modal-media-wrapper {
            min-height: 300px;
        }

        .modal-media-item {
            min-height: 300px;
        }

        #modalVideoContainer .ratio {
            min-height: 300px;
        }
    }

    @media (max-width: 576px) {
        .modal-body {
            padding: 20px !important;
        }

        .modal-media-wrapper {
            min-height: 250px;
        }

        .modal-media-item {
            min-height: 250px;
        }

        #modalVideoContainer .ratio {
            min-height: 250px;
        }
    }
</style>
