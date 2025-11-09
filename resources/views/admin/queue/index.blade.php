@extends('admin.layouts.admin')

@section('title', 'Queue Monitor')
@section('page-title', 'Queue Monitor')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stats-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pending Jobs</p>
                        <h3 class="fw-bold mb-0" id="statPending">{{ number_format($pending) }}</h3>
                    </div>
                    <div class="stats-icon primary">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Failed Jobs</p>
                        <h3 class="fw-bold mb-0" id="statFailed">{{ number_format($failed) }}</h3>
                    </div>
                    <div class="stats-icon danger">
                        <i class="bi bi-bug"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Last Run Status</p>
                        <h3 class="fw-bold mb-0" id="statLastStatus">
                            {{ $latestRun ? ucfirst($latestRun->status) : '—' }}
                        </h3>
                    </div>
                    <div class="stats-icon success">
                        <i class="bi bi-activity"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4">
        <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0 fw-bold">Queue Controls</h5>
                <small class="text-muted">
                    Connection: <span class="fw-semibold">{{ config('queue.default') }}</span>
                </small>
            </div>
            <div class="d-flex gap-2">
                <input type="number" id="queueJobCount" class="form-control form-control-custom" value="5" min="1" max="25"
                    style="max-width: 120px;">
                <button class="btn btn-admin-primary" id="runQueueBtn">
                    <i class="bi bi-play-circle me-2"></i>Process Jobs
                </button>
                <button class="btn btn-outline-secondary" id="refreshStatsBtn">
                    <i class="bi bi-arrow-repeat me-2"></i>Refresh Stats
                </button>
            </div>
        </div>
        <div class="card-body-custom">
            <div id="queueRunResult" class="alert d-none mb-0"></div>
            <div class="mt-3">
                <h6 class="fw-bold mb-2">Latest Run</h6>
                <div class="border rounded p-3 bg-light" id="latestRunSummary">
                    @if ($latestRun)
                        <p class="mb-2">
                            <strong>Status:</strong> <span class="badge {{ $latestRun->status === 'completed' ? 'bg-success' : ($latestRun->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($latestRun->status) }}</span>
                        </p>
                        <p class="mb-2"><strong>Processed:</strong> {{ $latestRun->processed_count }} | <strong>Failed:</strong> {{ $latestRun->failed_count }}</p>
                        <p class="mb-2"><strong>Started:</strong> {{ optional($latestRun->started_at)->format('M d, Y H:i') ?? '—' }}</p>
                        <p class="mb-0"><strong>Finished:</strong> {{ optional($latestRun->finished_at)->format('M d, Y H:i') ?? '—' }}</p>
                    @else
                        <p class="text-muted mb-0">No queue runs recorded yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Queue Runs</h5>
                    <button class="btn btn-sm btn-outline-secondary" id="refreshRunsBtn">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive table-responsive-sm" style="max-width: 100%; overflow-x: auto;">
                        <table class="table table-custom table-sm mb-0" id="recentRunsTable">
                            <thead>
                                <tr>
                                    <th>Run At</th>
                                    <th>Status</th>
                                    <th>Processed</th>
                                    <th>Failed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentRuns as $run)
                                    <tr>
                                        <td>{{ $run->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge
                                                @if ($run->status === 'completed') bg-success
                                                @elseif ($run->status === 'failed') bg-danger
                                                @else bg-warning text-dark @endif">
                                                {{ ucfirst($run->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $run->processed_count }}</td>
                                        <td>{{ $run->failed_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No queue runs recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Failed Jobs</h5>
                    <a href="{{ route('admin.storage.logs.clear') }}" onclick="return confirm('Clear all logs including failed jobs?');"
                        class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive table-responsive-sm" style="max-width: 100%; overflow-x: auto;">
                        <table class="table table-custom table-sm mb-0" id="failedJobsTable">
                            <thead>
                                <tr>
                                    <th>Failed At</th>
                                    <th>Queue</th>
                                    <th>Exception</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($failedJobs as $job)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($job->failed_at)->format('M d, Y H:i') }}</td>
                                        <td>{{ $job->queue }}</td>
                                        <td>
                                            <details>
                                                <summary class="text-danger">View</summary>
                                                <pre class="mt-2 small">{{ Str::limit($job->exception, 500) }}</pre>
                                            </details>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No failed jobs 🎉</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
    <script>
        dayjs.extend(window.dayjs_plugin_relativeTime);
    </script>
    <script>
        (function() {
            const runQueueBtn = document.getElementById('runQueueBtn');
            const queueResult = document.getElementById('queueRunResult');
            const queuePending = document.getElementById('statPending');
            const queueFailed = document.getElementById('statFailed');
            const lastStatus = document.getElementById('statLastStatus');
            const latestRunSummary = document.getElementById('latestRunSummary');
            const refreshStatsBtn = document.getElementById('refreshStatsBtn');
            const refreshRunsBtn = document.getElementById('refreshRunsBtn');

            function showAlert(element, message, type) {
                element.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
                element.classList.add('alert', 'alert-' + type);
                element.innerHTML = message;
            }

            function updateLatestRun(run) {
                if (!run) {
                    latestRunSummary.innerHTML = '<p class="text-muted mb-0">No queue runs recorded yet.</p>';
                    lastStatus.textContent = '—';
                    return;
                }

                const statusBadgeClass = run.status === 'completed'
                    ? 'bg-success'
                    : (run.status === 'failed' ? 'bg-danger' : 'bg-warning text-dark');

                lastStatus.textContent = run.status ? run.status.charAt(0).toUpperCase() + run.status.slice(1) : '—';

                latestRunSummary.innerHTML = `
                    <p class="mb-2">
                        <strong>Status:</strong>
                        <span class="badge ${statusBadgeClass}">${run.status ? run.status.charAt(0).toUpperCase() + run.status.slice(1) : '—'}</span>
                    </p>
                    <p class="mb-2"><strong>Processed:</strong> ${run.processed_count ?? 0} | <strong>Failed:</strong> ${run.failed_count ?? 0}</p>
                    <p class="mb-2"><strong>Started:</strong> ${run.started_at ? dayjs(run.started_at).format('MMM D, YYYY HH:mm') : '—'}</p>
                    <p class="mb-0"><strong>Finished:</strong> ${run.finished_at ? dayjs(run.finished_at).format('MMM D, YYYY HH:mm') : '—'}</p>
                `;
            }

            function refreshStats() {
                fetch('{{ route('admin.queue.stats') }}', { headers: { 'Accept': 'application/json' } })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text || 'Failed to fetch queue stats.');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        queuePending.textContent = new Intl.NumberFormat().format(data.pending ?? 0);
                        queueFailed.textContent = new Intl.NumberFormat().format(data.failed ?? 0);
                        updateLatestRun(data.latest_run);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            function refreshRecentRuns() {
                fetch('{{ route('admin.storage.queue.logs') }}', { headers: { 'Accept': 'application/json' } })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text || 'Failed to load queue logs.');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        const tbody = document.querySelector('#recentRunsTable tbody');
                        tbody.innerHTML = '';
                        if (data.data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">No queue runs recorded yet.</td></tr>';
                            return;
                        }
                        data.data.forEach(log => {
                            const statusClass = log.status === 'completed'
                                ? 'bg-success'
                                : (log.status === 'failed' ? 'bg-danger' : 'bg-warning text-dark');
                            tbody.insertAdjacentHTML('beforeend', `
                                <tr>
                                    <td>${dayjs(log.created_at).format('MMM D, YYYY HH:mm')}</td>
                                    <td><span class="badge ${statusClass}">${log.status.charAt(0).toUpperCase() + log.status.slice(1)}</span></td>
                                    <td>${log.processed_count}</td>
                                    <td>${log.failed_count}</td>
                                </tr>
                            `);
                        });
                    })
                    .catch(error => console.error(error));
            }

            if (runQueueBtn) {
                runQueueBtn.addEventListener('click', function() {
                    const maxJobs = parseInt(document.getElementById('queueJobCount').value || '5', 10);

                    runQueueBtn.disabled = true;
                    showAlert(queueResult, 'Processing queued jobs...', 'warning');

                    fetch('{{ route('admin.storage.queue.run') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ max_jobs: maxJobs })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                showAlert(queueResult, `Processed ${data.processed} jobs successfully.`, 'success');
                                queuePending.textContent = new Intl.NumberFormat().format(data.pending);
                                queueFailed.textContent = new Intl.NumberFormat().format(data.failed);
                                refreshRecentRuns();
                                refreshStats();
                            } else {
                                showAlert(queueResult, data.message || 'Queue runner reported an error.', 'danger');
                            }
                        })
                        .catch(error => {
                            showAlert(queueResult, error.message || 'Unable to process jobs.', 'danger');
                        })
                        .finally(() => {
                            runQueueBtn.disabled = false;
                        });
                });
            }

            refreshStatsBtn?.addEventListener('click', function() {
                refreshStats();
                refreshRecentRuns();
            });

            refreshRunsBtn?.addEventListener('click', function() {
                refreshRecentRuns();
            });

            refreshStats();
            setInterval(refreshStats, 5000);
        })();
    </script>
@endpush

