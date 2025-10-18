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

                    <!-- Download Button - Below Category, Above Description -->
                    <div class="mb-4">
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-download me-2"></i>
                                Download Files
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li>
                                    <a class="dropdown-item" href="#" id="downloadPNG">
                                        <i class="bi bi-file-earmark-image text-primary me-2"></i>
                                        <span>
                                            <strong>PNG Image</strong>
                                            <small class="d-block text-muted">High Quality Preview</small>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="downloadSource">
                                        <i class="bi bi-file-earmark-zip text-success me-2"></i>
                                        <span>
                                            <strong id="sourceFileName">Source File</strong>
                                            <small class="d-block text-muted" id="sourceFileType">AI / PSD File</small>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
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

    /* Download Button - Normal Style, No Gradient */
    .btn-group {
        display: flex;
    }

    .btn-outline-primary {
        border: 2px solid #667EEA;
        color: #667EEA;
        background: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .btn-outline-primary:hover {
        background: #667EEA;
        color: white;
        border-color: #667EEA;
    }

    .btn-outline-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    .dropdown-toggle::after {
        margin-left: auto;
    }

    .dropdown-menu {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        padding: 8px;
        margin-top: 5px !important;
        width: 100%;
    }

    .dropdown-item {
        padding: 12px 15px;
        border-radius: 6px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
    }

    .dropdown-item i {
        font-size: 20px;
    }

    .dropdown-item strong {
        font-size: 14px;
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

        .btn-outline-primary {
            padding: 10px 15px;
            font-size: 13px;
        }

        .dropdown-item {
            padding: 10px 12px;
        }
    }
</style>
