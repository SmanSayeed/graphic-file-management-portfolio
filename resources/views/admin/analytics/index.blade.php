@extends('admin.layouts.admin')

@section('title', 'Analytics Overview')
@section('page-title', 'Analytics & Insights')

@php
    use Illuminate\Support\Str;
    $formatBytes = function ($bytes) {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = min(floor(log($bytes, 1024)), count($units) - 1);
        return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    };
@endphp

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stats-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Visitors (30d)</p>
                        <h3 class="fw-bold mb-0">{{ number_format($totals['visitors']) }}</h3>
                    </div>
                    <div class="stats-icon primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Downloads (30d)</p>
                        <h3 class="fw-bold mb-0">{{ number_format($totals['downloads']) }}</h3>
                    </div>
                    <div class="stats-icon success">
                        <i class="bi bi-download"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Local Bandwidth</p>
                        <h3 class="fw-bold mb-0">{{ $formatBytes($totals['bandwidth_local']) }}</h3>
                    </div>
                    <div class="stats-icon warning">
                        <i class="bi bi-hdd"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">S3 Bandwidth</p>
                        <h3 class="fw-bold mb-0">{{ $formatBytes($totals['bandwidth_s3']) }}</h3>
                    </div>
                    <div class="stats-icon danger">
                        <i class="bi bi-cloud-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="content-card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">30-Day Trends</h5>
                </div>
                <div class="card-body-custom">
                    <canvas id="metricsChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="content-card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">S3 Usage Snapshot</h5>
                    @if ($usageSnapshot['force_local'])
                        <span class="badge bg-danger">Limit Reached</span>
                    @endif
                </div>
                <div class="card-body-custom">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Storage Used</span>
                            <span>{{ $formatBytes($usageSnapshot['storage_bytes']) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>GET Requests</span>
                            <span>{{ number_format($usageSnapshot['get_requests']) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>PUT/COPY/POST/LIST</span>
                            <span>{{ number_format($usageSnapshot['put_requests']) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Data Transfer Out</span>
                            <span>{{ $formatBytes($usageSnapshot['egress_bytes']) }}</span>
                        </li>
                    </ul>
                    <small class="text-muted d-block mt-3">Data aggregated monthly. Monitor free tier thresholds to avoid unexpected charges.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">Top Projects by Downloads</h5>
                </div>
                <div class="card-body-custom">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Category</th>
                                <th>Downloads</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProjects as $project)
                                <tr>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->category?->name ?? '—' }}</td>
                                    <td>{{ number_format($project->download_count) }}</td>
                                    <td>{{ strtoupper($project->storage_type ?? 'local') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No projects recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Events</h5>
                    <span class="badge bg-light text-dark">{{ $recentEvents->count() }} tracked</span>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive">
                        <table class="table table-custom table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Context</th>
                                    <th>Bytes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentEvents as $event)
                                    <tr>
                                        <td>{{ $event->occurred_at?->diffForHumans() ?? $event->created_at->diffForHumans() }}</td>
                                        <td><span class="badge bg-light text-dark text-uppercase">{{ $event->event_type }}</span></td>
                                        <td>{{ Str::limit($event->context ?? '-', 60) }}</td>
                                        <td>{{ $formatBytes($event->bytes) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No analytics events recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted d-block mt-2">Event stream includes visits, uploads (ingress), and downloads (egress).</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const metrics = @json($metrics->reverse()->values());
        const ctx = document.getElementById('metricsChart').getContext('2d');

        const labels = metrics.map(item => item.metric_date);
        const visitors = metrics.map(item => item.visitors_total);
        const downloads = metrics.map(item => item.downloads_total);
        const s3Bandwidth = metrics.map(item => (item.bandwidth_s3_bytes / (1024 * 1024)).toFixed(2));
        const localBandwidth = metrics.map(item => (item.bandwidth_local_bytes / (1024 * 1024)).toFixed(2));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                        label: 'Visitors',
                        data: visitors,
                        borderColor: '#00B894',
                        backgroundColor: 'rgba(0, 184, 148, 0.2)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Downloads',
                        data: downloads,
                        borderColor: '#F5576C',
                        backgroundColor: 'rgba(245, 87, 108, 0.15)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'S3 Bandwidth (MB)',
                        data: s3Bandwidth,
                        borderColor: '#6C5CE7',
                        borderDash: [5, 5],
                        fill: false
                    },
                    {
                        label: 'Local Bandwidth (MB)',
                        data: localBandwidth,
                        borderColor: '#0984E3',
                        borderDash: [5, 5],
                        fill: false
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush

