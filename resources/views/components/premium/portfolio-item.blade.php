@props(['project'])

<div class="col-lg-4 col-md-6 portfolio-item {{ $project['category'] }}">
    <div class="position-relative portfolio-card">
        <span class="portfolio-badge badge-{{ $project['type'] === 'free' ? 'free' : 'paid' }}">
            {{ strtoupper($project['type']) }}
        </span>
        <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}" class="portfolio-image img-fluid rounded-4">
        <div class="portfolio-overlay">
            <h4>{{ $project['title'] }}</h4>
            <p class="portfolio-category">{{ $project['categoryName'] }}</p>
            <button class="btn-view-premium" onclick="openPortfolioModal({{ json_encode($project) }})">
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
    }

    .portfolio-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
