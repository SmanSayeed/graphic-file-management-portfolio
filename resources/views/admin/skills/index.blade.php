@extends('admin.layouts.admin')

@section('title', 'Skills Management')
@section('page-title', 'Skills Management')

@section('content')
    <div class="mb-4">
        <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
            <i class="bi bi-plus-circle me-2"></i>Add New Skill
        </button>
    </div>

    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">All Skills</h5>
        </div>
        <div class="card-body-custom">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="skill-card p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <i class="bi bi-brush text-primary" style="font-size: 32px;"></i>
                                <h6 class="mt-2 fw-bold">Adobe Illustrator</h6>
                                <p class="text-muted small mb-0">Vector graphics & logo design</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a>
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="#"><i
                                                class="bi bi-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="skill-card p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <i class="bi bi-palette text-success" style="font-size: 32px;"></i>
                                <h6 class="mt-2 fw-bold">Photoshop</h6>
                                <p class="text-muted small mb-0">Photo editing & manipulation</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a>
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="#"><i
                                                class="bi bi-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="skill-card p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <i class="bi bi-app-indicator text-warning" style="font-size: 32px;"></i>
                                <h6 class="mt-2 fw-bold">Figma</h6>
                                <p class="text-muted small mb-0">UI/UX & prototyping</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a>
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="#"><i
                                                class="bi bi-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="skill-card p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <i class="bi bi-laptop text-danger" style="font-size: 32px;"></i>
                                <h6 class="mt-2 fw-bold">Web Design</h6>
                                <p class="text-muted small mb-0">Responsive & modern layouts</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a>
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="#"><i
                                                class="bi bi-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Skill Modal -->
    <div class="modal fade" id="addSkillModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Skill Name</label>
                        <input type="text" class="form-control form-control-custom"
                            placeholder="e.g., Adobe Illustrator">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <input type="text" class="form-control form-control-custom"
                            placeholder="e.g., Vector graphics & logo design">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Icon (Bootstrap Icon class)</label>
                        <input type="text" class="form-control form-control-custom" placeholder="e.g., bi-brush"
                            value="bi-brush">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-admin-primary">Add Skill</button>
                </div>
            </div>
        </div>
    </div>
@endsection


