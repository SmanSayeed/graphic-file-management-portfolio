@props(['project'])

@php
    // Handle both array and object data
    $projectId = is_object($project) ? $project->id : $project['id'];
    $projectTitle = is_object($project) ? $project->title : $project['title'];
    $projectCategory = is_object($project) ? $project->category->slug ?? 'default' : $project['category'] ?? 'default';
    $projectCategoryName = is_object($project)
        ? $project->category->name ?? 'Uncategorized'
        : $project['categoryName'] ?? 'Uncategorized';
    $projectType = is_object($project) ? $project->type : $project['type'] ?? 'free';
    $projectFileType = is_object($project) ? $project->file_type : $project['file_type'] ?? 'image';
    $projectThumbnail = is_object($project) ? $project->thumbnail_url : $project['thumbnail_url'] ?? null;
    $projectVideoLink = is_object($project) ? $project->video_link : $project['video_link'] ?? null;
    $projectImage =
        $projectThumbnail
            ? $projectThumbnail
            : (is_object($project) ? ($project->image_url ?? 'https://via.placeholder.com/800x600') : ($project['image'] ?? 'https://via.placeholder.com/800x600'));
@endphp

<div class="col-lg-4 col-md-6 portfolio-item {{ $projectCategory }}" style="padding-left: 10px; padding-right: 10px;">
    <div class="position-relative portfolio-card">
        <!-- File Type Badge - Top Right -->
        <span
            class="portfolio-badge portfolio-badge-file-type badge-{{ $projectFileType === 'image' ? 'image' : 'video' }}">
            {{ strtoupper($projectFileType) }}
        </span>
        <!-- Type Badge - Bottom Right -->
        <span class="portfolio-badge portfolio-badge-type badge-{{ $projectType === 'free' ? 'free' : 'paid' }}">
            {{ strtoupper($projectType) }}
        </span>
        @if ($projectFileType === 'video' && $projectVideoLink)
            @php
                $embedUrl = $projectVideoLink;
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
            <div class="portfolio-video-wrapper">
                <iframe src="{{ $embedUrl }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen class="portfolio-image img-fluid rounded-4"></iframe>
            </div>
        @else
            <img src="{{ $projectImage }}" alt="{{ $projectTitle }}" class="portfolio-image img-fluid rounded-4">
        @endif
        <div class="portfolio-overlay">
            <h4>{{ $projectTitle }}</h4>
            <p class="portfolio-category">{{ $projectCategoryName }}</p>
            <button class="btn-view-premium" onclick="openProjectModal({{ $projectId }})">
                <i class="bi bi-eye me-2"></i>
                View Details
            </button>
        </div>
    </div>
</div>

<style>
    .portfolio-card {
        overflow: hidden;
        border-radius: 20px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .portfolio-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .portfolio-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        display: block;
    }

    .portfolio-video-wrapper {
        width: 100%;
        height: 300px;
        position: relative;
        overflow: hidden;
    }

    .portfolio-video-wrapper iframe {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Portfolio Badge Base - override premium-theme.css */
    .portfolio-card .portfolio-badge {
        position: absolute;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        z-index: 2;
    }

    /* File Type Badge - Top Right */
    .portfolio-badge-file-type {
        top: 20px !important;
        right: 20px !important;
        bottom: auto !important;
    }

    /* Type Badge - Bottom Right */
    .portfolio-badge-type {
        top: auto !important;
        bottom: 20px !important;
        right: 20px !important;
    }

    .portfolio-badge.badge-image {
        background: #17a2b8;
        color: white;
    }

    .portfolio-badge.badge-video {
        background: #ffc107;
        color: #212529;
    }

    .portfolio-badge.badge-free {
        background: #00B894;
        color: white;
    }

    .portfolio-badge.badge-paid {
        background: #FD79A8;
        color: white;
    }

    .btn-view-premium {
        background: linear-gradient(135deg, #00B894 0%, #F5576C 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
    }

    .btn-view-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 184, 148, 0.5);
    }

    .portfolio-category {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }
</style>
