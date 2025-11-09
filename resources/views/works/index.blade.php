@extends('layouts.premium')

@section('title', $query ? 'Search: ' . $query : 'All Works')
@section('description', optional($personalInfo)->short_bio ?? optional($personalInfo)->full_bio)

@section('content')
    @if (isset($sliders) && $sliders->count())
        @include('components.premium.hero-slider-new')
    @endif

    <section class="section-premium" style="padding-top: 140px; min-height: 100vh;">
        <div class="container">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
                <div>
                    <h1 class="section-title display-font mb-2">{{ $query ? 'Search Results' : 'All Works' }}</h1>
                    <p class="text-muted mb-0">
                        {{ $query ? 'Showing results for "' . e($query) . '"' : 'Browse the complete collection of works.' }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Home
                    </a>
                    <form class="d-flex search-form-page" action="{{ route('works.index') }}" method="GET">
                        <input type="text" class="form-control" name="q" placeholder="Search works..."
                            value="{{ $query }}" aria-label="Search works">
                        @if ($query)
                            <a href="{{ route('works.index') }}" class="btn btn-outline-secondary ms-2">Clear</a>
                        @endif
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="row portfolio-grid" style="margin-left: -10px; margin-right: -10px;">
                @forelse ($projects as $project)
                    <x-premium.portfolio-item :project="$project" />
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                        <p class="text-muted mb-3">No works matched your search. Try a different keyword.</p>
                        <a href="{{ route('works.index') }}" class="btn btn-admin-primary">View All Works</a>
                    </div>
                @endforelse
            </div>

            @if ($projects->hasPages())
                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .search-form-page input[type="text"] {
            min-width: 220px;
        }

        @media (max-width: 991.98px) {
            .search-form-page {
                width: 100%;
            }

            .search-form-page input[type="text"] {
                width: 100%;
            }
        }
    </style>
@endpush
