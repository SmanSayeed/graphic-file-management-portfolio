@extends('admin.layouts.admin')

@section('title', 'Projects')
@section('page-title', 'Projects Management')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.projects.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Project
        </a>
    </div>

    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">All Projects ({{ $projects->total() }})</h5>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>File Type</th>
                            <th>Downloads</th>
                            <th>Likes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>
                                    @if ($project->thumbnail_url)
                                        <img src="{{ $project->thumbnail_url }}" class="rounded"
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                            alt="{{ $project->title }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $project->title }}</td>
                                <td><span class="badge bg-primary">{{ $project->category->name }}</span></td>
                                <td>
                                    @if ($project->type === 'paid')
                                        <span class="badge bg-danger">PAID</span>
                                    @else
                                        <span class="badge bg-success">FREE</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($project->file_type === 'image')
                                        <span class="badge bg-info">IMAGE</span>
                                    @else
                                        <span class="badge bg-warning">VIDEO</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ number_format($project->download_count) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ number_format($project->like_count) }}</span>
                                </td>
                                <td>
                                    @if ($project->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.projects.show', $project->id) }}"
                                            class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this project?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-muted mb-0">No projects found. <a
                                            href="{{ route('admin.projects.create') }}">Create your first project</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($projects->hasPages())
                <div class="mt-3">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
