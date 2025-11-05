@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Projects</p>
                        <h3 class="fw-bold mb-0">24</h3>
                    </div>
                    <div class="stats-icon primary">
                        <i class="bi bi-folder"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Categories</p>
                        <h3 class="fw-bold mb-0">5</h3>
                    </div>
                    <div class="stats-icon success">
                        <i class="bi bi-grid"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Downloads</p>
                        <h3 class="fw-bold mb-0">1,248</h3>
                    </div>
                    <div class="stats-icon warning">
                        <i class="bi bi-download"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Hero Sliders</p>
                        <h3 class="fw-bold mb-0">5</h3>
                    </div>
                    <div class="stats-icon danger">
                        <i class="bi bi-images"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Recent Projects</h5>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-admin-primary">View All</a>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Downloads</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <img src="https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=100&h=100&fit=crop"
                                    alt="Project" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td class="fw-semibold">Modern Logo Design</td>
                            <td><span class="badge bg-primary">Logo</span></td>
                            <td><span class="badge bg-success">FREE</span></td>
                            <td>2,543</td>
                            <td>
                                <a href="{{ route('admin.projects.edit', 1) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="https://images.unsplash.com/photo-1611262588024-d12430b98920?w=100&h=100&fit=crop"
                                    alt="Project" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td class="fw-semibold">Social Media Pack</td>
                            <td><span class="badge bg-info">Social</span></td>
                            <td><span class="badge bg-danger">PAID</span></td>
                            <td>1,876</td>
                            <td>
                                <a href="{{ route('admin.projects.edit', 2) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=100&h=100&fit=crop"
                                    alt="Project" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td class="fw-semibold">Promotional Banner</td>
                            <td><span class="badge bg-warning">Banner</span></td>
                            <td><span class="badge bg-success">FREE</span></td>
                            <td>1,432</td>
                            <td>
                                <a href="{{ route('admin.projects.edit', 3) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
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

    <!-- Quick Actions -->
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body-custom">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-admin-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add New Project
                        </a>
                        <a href="{{ route('admin.sliders.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Hero Slider
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-grid me-2"></i>Manage Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">System Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Storage Used</span>
                            <span class="fw-semibold">2.4 GB / 10 GB</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar"
                                style="width: 24%; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Files</span>
                            <span class="fw-semibold">156 Files</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Last Login</span>
                            <span class="fw-semibold">Today, 10:30 AM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

