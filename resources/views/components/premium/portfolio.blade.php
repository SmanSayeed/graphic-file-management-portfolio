@php
    // Projects come from HomeController
    $projects = $projects ?? [];
@endphp

<section id="portfolio" class="section-premium">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="section-title display-font">Featured Works</h2>
                <p class="section-subtitle">Explore our collection of creative projects and premium design assets</p>
            </div>
        </div>

        <!-- Portfolio Filters -->
        <div class="portfolio-filters">
            <button class="filter-btn-premium active" data-filter="*">All Projects</button>
            @if (isset($categories) && $categories->count() > 0)
                @foreach ($categories as $category)
                    <button class="filter-btn-premium"
                        data-filter=".{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            @endif
        </div>

        <!-- Portfolio Grid -->
        <div class="row portfolio-grid" style="margin-left: -10px; margin-right: -10px;">
            @foreach ($projects as $project)
                <x-premium.portfolio-item :project="$project" />
            @endforeach
        </div>

        @if ($projects->count() === 0)
            <div class="text-center py-5">
                <p class="text-muted">No projects available at the moment.</p>
            </div>
        @endif
    </div>
</section>

<!-- Portfolio Modal -->
<x-premium.portfolio-modal />

@push('scripts')
    <script src="{{ asset('js/portfolio-modal.js') }}"></script>
@endpush
