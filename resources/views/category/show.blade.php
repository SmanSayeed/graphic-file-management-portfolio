@extends('layouts.premium')

@section('title', $category->name . ' - Projects')
@section('description', 'Explore ' . $category->name . ' projects')

@section('content')
<section class="section-premium" style="padding-top: 120px; min-height: 100vh;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('home') }}#portfolio">Works</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <!-- Category Header -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <div class="mb-3">
                    <i class="bi {{ $category->icon ?? 'bi-folder' }}" style="font-size: 48px; color: {{ $category->color ?? '#00B894' }};"></i>
                </div>
                <h2 class="section-title display-font">{{ $category->name }}</h2>
                @if($category->description)
                    <p class="section-subtitle">{{ $category->description }}</p>
                @endif
            </div>
        </div>

        <!-- Portfolio Grid -->
        <div class="row portfolio-grid" style="margin-left: -10px; margin-right: -10px;">
            @foreach ($projects as $project)
                <x-premium.portfolio-item :project="$project" />
            @endforeach
        </div>

        @if ($projects->count() === 0)
            <div class="text-center py-5">
                <i class="bi bi-folder-x" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                <p class="text-muted">No projects available in this category at the moment.</p>
                <a href="{{ route('home') }}#portfolio" class="btn btn-primary mt-3" style="background: linear-gradient(135deg, #00B894 0%, #F5576C 100%); border: none;">
                    <i class="bi bi-arrow-left me-2"></i>Back to All Works
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Portfolio Modal -->
<x-premium.portfolio-modal />

@push('scripts')
<script src="{{ asset('js/portfolio-modal.js') }}"></script>
@endpush

@push('styles')
<style>
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 20px;
    }

    .breadcrumb-item a {
        color: #00B894;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #2D3436;
    }
</style>
@endpush
@endsection

