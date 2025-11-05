@extends('admin.layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
    <div class="mb-4">
        <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle me-2"></i>Add New Category
        </button>
    </div>

    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">All Categories</h5>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Projects Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded p-2 me-3">
                                        <i class="bi bi-brush"></i>
                                    </div>
                                    <span class="fw-semibold">Logo Design</span>
                                </div>
                            </td>
                            <td class="text-muted">logo</td>
                            <td><span class="badge bg-primary">8 projects</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-info text-white rounded p-2 me-3">
                                        <i class="bi bi-share"></i>
                                    </div>
                                    <span class="fw-semibold">Social Media</span>
                                </div>
                            </td>
                            <td class="text-muted">social</td>
                            <td><span class="badge bg-info">5 projects</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning text-white rounded p-2 me-3">
                                        <i class="bi bi-badge-ad"></i>
                                    </div>
                                    <span class="fw-semibold">Banners</span>
                                </div>
                            </td>
                            <td class="text-muted">banner</td>
                            <td><span class="badge bg-warning">4 projects</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded p-2 me-3">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <span class="fw-semibold">E-commerce</span>
                                </div>
                            </td>
                            <td class="text-muted">ecommerce</td>
                            <td><span class="badge bg-success">7 projects</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Category Name</label>
                        <input type="text" class="form-control form-control-custom" placeholder="e.g., Print Design">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Slug (auto-generated)</label>
                        <input type="text" class="form-control form-control-custom" placeholder="print-design" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Icon (Bootstrap Icon)</label>
                        <input type="text" class="form-control form-control-custom" placeholder="bi-printer"
                            value="bi-printer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Color</label>
                        <input type="color" class="form-control form-control-custom" value="#667EEA">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-admin-primary">Add Category</button>
                </div>
            </div>
        </div>
    </div>
@endsection


