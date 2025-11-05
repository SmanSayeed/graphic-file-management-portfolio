@extends('admin.layouts.admin')

@section('title', 'View Project')
@section('page-title', 'View Project')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Projects
        </a>
        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-admin-primary">
            <i class="bi bi-pencil me-2"></i>Edit Project
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="content-card mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">{{ $project->title }}</h5>
                </div>
                <div class="card-body-custom">
                    @if($project->file_type === 'video' && $project->video_link)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Video Preview</h6>
                            @php
                                $embedUrl = $project->video_link;
                                if (str_contains($embedUrl, 'youtube.com/watch?v=')) {
                                    $videoId = explode('v=', $embedUrl)[1];
                                    $videoId = explode('&', $videoId)[0];
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                } elseif (str_contains($embedUrl, 'youtu.be/')) {
                                    $videoId = explode('youtu.be/', $embedUrl)[1];
                                    $videoId = explode('?', $videoId)[0];
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                }
                            @endphp
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    @elseif($project->thumbnail)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Thumbnail</h6>
                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="img-fluid rounded">
                        </div>
                    @endif

                    @if($project->image)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Project Image</h6>
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" class="img-fluid rounded">
                        </div>
                    @endif

                    <div class="mb-3">
                        <h6 class="fw-bold mb-2">Description</h6>
                        <p class="mb-0">{{ $project->description }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold mb-2">Short Description</h6>
                        <p class="mb-0">{{ $project->short_description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">Project Details</h5>
                </div>
                <div class="card-body-custom">
                    <div class="mb-3">
                        <strong>Category:</strong>
                        <span class="badge bg-primary ms-2">{{ $project->category->name }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Type:</strong>
                        <span class="badge {{ $project->type === 'paid' ? 'bg-danger' : 'bg-success' }} ms-2">
                            {{ strtoupper($project->type) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>File Type:</strong>
                        <span class="badge {{ $project->file_type === 'image' ? 'bg-info' : 'bg-warning' }} ms-2">
                            {{ strtoupper($project->file_type) }}
                        </span>
                    </div>

                    @if($project->price)
                        <div class="mb-3">
                            <strong>Price:</strong>
                            <span class="ms-2">${{ number_format($project->price, 2) }}</span>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge {{ $project->is_active ? 'bg-success' : 'bg-secondary' }} ms-2">
                            {{ $project->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Downloads:</strong>
                        <span class="badge bg-info ms-2">{{ number_format($project->download_count) }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Likes:</strong>
                        <span class="badge bg-danger ms-2">{{ number_format($project->like_count) }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Created:</strong>
                        <span class="ms-2">{{ $project->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Updated:</strong>
                        <span class="ms-2">{{ $project->updated_at->format('M d, Y') }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Created By:</strong>
                        <span class="ms-2">{{ $project->user->name }}</span>
                    </div>
                </div>
            </div>

            @if($project->video || $project->source_file)
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0 fw-bold">Files</h5>
                    </div>
                    <div class="card-body-custom">
                        @if($project->video)
                            <div class="mb-3">
                                <strong>Video File:</strong>
                                <a href="{{ asset('storage/' . $project->video) }}" download class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            </div>
                        @endif

                        @if($project->source_file)
                            <div class="mb-3">
                                <strong>Source File:</strong>
                                <a href="{{ asset('storage/' . $project->source_file) }}" download class="btn btn-sm btn-outline-success ms-2">
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            </div>
                        @endif

                        @if($project->image)
                            <div class="mb-3">
                                <strong>Image File:</strong>
                                <a href="{{ asset('storage/' . $project->image) }}" download class="btn btn-sm btn-outline-info ms-2">
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

