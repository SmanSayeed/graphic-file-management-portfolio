@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @php
        $totalProjects = $stats['total_projects'] ?? 0;
        $activeProjects = $stats['active_projects'] ?? 0;
        $activePercentage = $totalProjects > 0 ? round(($activeProjects / $totalProjects) * 100) : 0;

        $paidProjects = $stats['paid_projects'] ?? 0;
        $freeProjects = $stats['free_projects'] ?? 0;
        $projectTypeTotal = $paidProjects + $freeProjects;
        $paidPercentage = $projectTypeTotal > 0 ? round(($paidProjects / $projectTypeTotal) * 100) : 0;
        $freePercentage = $projectTypeTotal > 0 ? round(($freeProjects / $projectTypeTotal) * 100) : 0;

        $imageProjects = $stats['image_projects'] ?? 0;
        $videoProjects = $stats['video_projects'] ?? 0;
        $fileTypeTotal = $imageProjects + $videoProjects;
        $imagePercentage = $fileTypeTotal > 0 ? round(($imageProjects / $fileTypeTotal) * 100) : 0;
        $videoPercentage = $fileTypeTotal > 0 ? round(($videoProjects / $fileTypeTotal) * 100) : 0;

        $storagePercentage = $systemStats['storage_percentage'] ?? 0;
    @endphp

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Projects</p>
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_projects'] ?? 0) }}</h3>
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
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_categories'] ?? 0) }}</h3>
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
                        <h3 class="fw-bold mb-0">{{ number_format($stats['total_downloads'] ?? 0) }}</h3>
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
                        <h3 class="fw-bold mb-0">{{ number_format($stats['hero_sliders'] ?? 0) }}</h3>
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
                        @forelse ($recentProjects as $project)
                            @php
                                $imagePath = $project->thumbnail ?? $project->image;
                                $imageUrl = $imagePath ? asset('storage/' . $imagePath) : 'https://via.placeholder.com/100x100?text=No+Image';
                                $typeBadgeClass = $project->type === 'paid' ? 'bg-danger' : 'bg-success';
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $imageUrl }}" alt="{{ $project->title }}" class="rounded"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td class="fw-semibold">{{ $project->title }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $project->category?->name ?? 'Uncategorized' }}</span>
                                </td>
                                <td><span class="badge {{ $typeBadgeClass }}">{{ strtoupper($project->type) }}</span></td>
                                <td>{{ number_format($project->download_count ?? 0) }}</td>
                                <td>
                                    <a href="{{ route('admin.projects.edit', $project) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit project">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this project?');" title="Delete project">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No projects found.</td>
                            </tr>
                        @endforelse
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
                            <span class="fw-semibold">
                                {{ $systemStats['storage_used_formatted'] ?? '0 B' }}
                                /
                                {{ $systemStats['storage_capacity_formatted'] ?? '—' }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar"
                                style="width: {{ $storagePercentage }}%; background: linear-gradient(135deg, #00B894 0%, #F5576C 100%);">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Files</span>
                            <span class="fw-semibold">{{ number_format($systemStats['total_files'] ?? 0) }} Files</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Active Projects</span>
                            <span class="fw-semibold">
                                {{ number_format($activeProjects) }} / {{ number_format($totalProjects) }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $activePercentage }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Paid Projects</span>
                            <span class="fw-semibold">{{ number_format($paidProjects) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: {{ $paidPercentage }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Free Projects</span>
                            <span class="fw-semibold">{{ number_format($freeProjects) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $freePercentage }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Image / Video Projects</span>
                            <span class="fw-semibold">
                                {{ number_format($imageProjects) }} / {{ number_format($videoProjects) }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $imagePercentage }}%;"></div>
                            <div class="progress-bar bg-info" style="width: {{ $videoPercentage }}%;"></div>
                        </div>
                    </div>
                    <div class="pt-2 border-top">
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted">Last Profile Update</span>
                            <span class="fw-semibold">
                                @if (!empty($systemStats['last_login']))
                                    {{ $systemStats['last_login']->timezone(config('app.timezone'))->format('M d, Y g:i A') }}
                                @else
                                    —
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

