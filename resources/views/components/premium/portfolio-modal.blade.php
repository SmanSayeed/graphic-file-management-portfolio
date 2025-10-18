<!-- Portfolio Modal -->
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content portfolio-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="portfolioModalLabel">
                    <span id="modalTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Project Image -->
                <div class="modal-image-wrapper mb-4">
                    <img id="modalImage" src="" class="img-fluid rounded-4 w-100" alt="Project Image">
                    <span id="modalTypeBadge" class="modal-type-badge"></span>
                </div>

                <!-- Project Details -->
                <div class="project-details">
                    <div class="row mb-4">
                        <div class="col-6">
                            <h6 class="detail-label">Category</h6>
                            <p class="fw-semibold mb-0" id="modalCategory"></p>
                        </div>
                        <div class="col-6">
                            <h6 class="detail-label">Type</h6>
                            <p class="fw-semibold mb-0" id="modalType"></p>
                        </div>
                    </div>

                    <!-- Download Button - Bootstrap Default Dropdown -->
                    <div class="dropdown mb-4">
                        <button class="btn btn-primary dropdown-toggle w-100" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download me-2"></i>
                            Download Files
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                            <li>
                                <a class="dropdown-item" href="#" id="downloadPNG">
                                    <i class="bi bi-file-earmark-image text-primary me-2"></i>
                                    <div class="d-inline-block">
                                        <strong class="d-block">PNG Image</strong>
                                        <small class="text-muted">High Quality Preview</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" id="downloadSource">
                                    <i class="bi bi-file-earmark-zip text-success me-2"></i>
                                    <div class="d-inline-block">
                                        <strong class="d-block" id="sourceFileName">Source File</strong>
                                        <small class="text-muted d-block" id="sourceFileType">AI / PSD File</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="detail-label">Description</h6>
                        <p class="mb-0" id="modalDescription"></p>
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
        background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
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

    .modal-image-wrapper {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
        background-color: #667EEA !important;
        border-color: #667EEA !important;
        padding: 12px 20px;
        font-weight: 600;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #5568d3 !important;
        border-color: #5568d3 !important;
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

    /* Responsive */
    @media (max-width: 576px) {
        .modal-body {
            padding: 20px !important;
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
