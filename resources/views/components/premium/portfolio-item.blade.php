@props(['project'])

@php
    // Handle both array and object data
    $projectId = is_object($project) ? $project->id : $project['id'];
    $projectTitle = is_object($project) ? $project->title : $project['title'];
    $projectCategory = is_object($project) ? $project->category->slug ?? 'default' : $project['category'] ?? 'default';
    $projectCategoryName = is_object($project)
        ? $project->category->name ?? 'Uncategorized'
        : $project['categoryName'] ?? 'Uncategorized';
    $projectType = is_object($project) ? $project->type : $project['type'];
    $projectImage = is_object($project)
        ? asset('storage/' . $project->thumbnail)
        : $project['image'] ?? 'https://via.placeholder.com/800x600';
@endphp

<div class="col-lg-4 col-md-6 portfolio-item {{ $projectCategory }}" style="padding-left: 10px; padding-right: 10px;">
    <div class="position-relative portfolio-card">
        <span class="portfolio-badge badge-{{ $projectType === 'free' ? 'free' : 'paid' }}">
            {{ strtoupper($projectType) }}
        </span>
        <img src="{{ $projectImage }}" alt="{{ $projectTitle }}" class="portfolio-image img-fluid rounded-4">
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

    .btn-view-premium {
        background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
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
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-view-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    .portfolio-category {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }
</style>
