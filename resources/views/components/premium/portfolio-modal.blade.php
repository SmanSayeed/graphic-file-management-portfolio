<!-- Portfolio Modal -->
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel" aria-hidden="true"
    data-bs-backdrop="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content portfolio-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="portfolioModalLabel">
                    <span id="modalTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="modalCloseBtn"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Desktop Layout: Image/Video (60%) + Details (40%) -->
                <div class="row modal-main-content">
                    <!-- Left Side: Image/Video (60%) -->
                    <div class="col-lg-7 col-12 modal-media-section">
                        <div class="modal-image-wrapper">
                            <div id="modalImageContainer">
                                <img id="modalImage" src="" class="img-fluid rounded-4 w-100"
                                    alt="Project Image">
                            </div>
                            <div id="modalVideoContainer" class="modal-video-container-hidden">
                                <iframe id="modalVideo" src="" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen class="img-fluid rounded-4 w-100"
                                    style="width: 100%; height: 100%;"></iframe>
                            </div>
                            <span id="modalTypeBadge" class="modal-type-badge"></span>
                        </div>
                    </div>

                    <!-- Right Side: Details (40%) -->
                    <div class="col-lg-5 col-12 modal-details-section">
                        <div class="project-details-sidebar">
                            <!-- Category, Type, File Type -->
                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <h6 class="detail-label">Category</h6>
                                    <p class="fw-semibold mb-0" id="modalCategory"></p>
                                </div>
                                <div class="col-6 mb-3">
                                    <h6 class="detail-label">Type</h6>
                                    <p class="fw-semibold mb-0" id="modalType"></p>
                                </div>
                                <div class="col-6 mb-3">
                                    <h6 class="detail-label">File Type</h6>
                                    <p class="fw-semibold mb-0" id="modalFileType"></p>
                                </div>
                            </div>

                            <!-- Download Button - Bootstrap Default Dropdown -->
                            <div class="dropdown mb-3">
                                <button class="btn btn-primary dropdown-toggle w-100" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-download me-2"></i>
                                    Download Files
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item" href="#" id="downloadPNG" style="display: none;">
                                            <i class="bi bi-file-earmark-image text-primary me-2"></i>
                                            <div class="d-inline-block">
                                                <strong class="d-block">PNG Image</strong>
                                                <small class="text-muted">High Quality Preview</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" id="downloadVideo"
                                            style="display: none;">
                                            <i class="bi bi-file-earmark-play text-danger me-2"></i>
                                            <div class="d-inline-block">
                                                <strong class="d-block">Video File</strong>
                                                <small class="text-muted">Download Video</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" id="downloadDivider" style="display: none;">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" id="downloadSource"
                                            style="display: none;">
                                            <i class="bi bi-file-earmark-zip text-success me-2"></i>
                                            <div class="d-inline-block">
                                                <strong class="d-block" id="sourceFileName">Source File</strong>
                                                <small class="text-muted d-block" id="sourceFileType">AI / PSD
                                                    File</small>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Like Button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-outline-danger btn-lg w-100" id="likeButton"
                                    onclick="toggleLike()">
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
                            <p class="mb-0" id="modalDescription"></p>
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
    }

    .modal-header .modal-title {
        color: white !important;
        font-size: 22px;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-main-content {
        margin: 0;
    }

    .modal-media-section {
        padding-right: 20px;
    }

    .modal-details-section {
        padding-left: 20px;
    }

    .project-details-sidebar {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .modal-image-wrapper {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        min-height: 450px;
        display: flex;
        align-items: stretch;
    }

    #modalImageContainer {
        width: 100%;
        min-height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 15px;
    }

    #modalImageContainer img {
        width: 100%;
        height: auto;
        max-height: 100%;
        object-fit: contain;
        border-radius: 15px;
    }

    #modalVideoContainer {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: #000;
        border-radius: 15px;
        min-height: 450px;
        display: none;
        /* Default hidden */
        visibility: hidden;
        opacity: 0;
    }

    .modal-video-container-hidden {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        min-height: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Show class MUST override hidden class - MAXIMUM SPECIFICITY */
    #modalVideoContainer.show {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Use aspect ratio for desktop screens - ONLY when showing */
    @media (min-width: 992px) {
        #modalVideoContainer.show {
            height: 0 !important;
            padding-bottom: 56.25% !important;
            /* 16:9 aspect ratio */
            min-height: 0 !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    }

    #modalVideoContainer iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 15px;
        background: #000;
    }

    /* For mobile, use fixed height - ONLY when showing */
    @media (max-width: 991.98px) {
        #modalVideoContainer.show {
            height: 300px !important;
            padding-bottom: 0 !important;
            min-height: 300px !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        #modalVideoContainer.show iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    }

    .modal-type-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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

    .project-details {
        padding: 10px 0;
    }

    .project-description-section {
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    /* Detail Labels - Bigger and Bolder */
    .detail-label {
        font-size: 16px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Bootstrap Default Dropdown Styles */
    .btn-primary {
        background-color: #00B894 !important;
        border-color: #00B894 !important;
        padding: 12px 20px;
        font-weight: 600;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #00a085 !important;
        border-color: #00a085 !important;
    }

    .dropdown-menu {
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        padding: 8px;
        z-index: 9999 !important;
        position: absolute !important;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        padding: 12px 15px;
        border-radius: 6px;
        transition: background-color 0.2s ease;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: #f8f9fa;
    }

    .dropdown-item i {
        font-size: 22px;
        margin-top: 2px;
    }

    .dropdown-item strong {
        font-size: 14px;
        font-weight: 600;
    }

    .dropdown-item small {
        font-size: 12px;
    }

    .dropdown-divider {
        margin: 5px 0;
    }

    /* Responsive - Mobile View */
    @media (max-width: 991.98px) {
        .modal-media-section {
            padding-right: 0;
            margin-bottom: 20px;
        }

        .modal-details-section {
            padding-left: 0;
        }

        .modal-image-wrapper {
            min-height: 300px;
        }

        #modalImageContainer {
            min-height: 300px;
        }

        #modalVideoContainer {
            min-height: 300px;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio for mobile */
            height: 0;
        }

        #modalVideoContainer iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .project-details-sidebar {
            height: auto;
        }
    }

    @media (max-width: 576px) {
        .modal-body {
            padding: 20px !important;
        }

        .modal-image-wrapper {
            min-height: 250px;
        }

        #modalImageContainer {
            min-height: 250px;
        }

        #modalVideoContainer {
            min-height: 250px;
        }

        .detail-label {
            font-size: 14px;
        }

        .btn-primary {
            padding: 10px 15px;
            font-size: 14px;
        }

        .dropdown-item {
            padding: 10px 12px;
        }
    }
</style>
