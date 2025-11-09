@extends('admin.layouts.admin')

@section('title', 'Storage Management')
@section('page-title', 'Storage & Queue Management')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="content-card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Storage Configuration</h5>
                    <span class="badge bg-light text-dark">Default: {{ ucfirst($settings->default_storage_type) }}</span>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('admin.storage.settings.update') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Default Storage *</label>
                                <select name="default_storage_type" class="form-select form-control-custom">
                                    <option value="local"
                                        {{ $settings->default_storage_type === 'local' ? 'selected' : '' }}>
                                        Local Server Storage
                                    </option>
                                    <option value="s3"
                                        {{ $settings->default_storage_type === 's3' && !$settings->avoid_s3 ? 'selected' : '' }}
                                        {{ $settings->avoid_s3 ? 'disabled' : '' }}>
                                        AWS S3 Bucket
                                    </option>
                                </select>
                                <small class="text-muted">This applies to new project uploads.</small>
                            </div>

                            <div class="col-md-6 mb-3 d-flex flex-column justify-content-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="avoidS3" name="avoid_s3"
                                        value="1" {{ $settings->avoid_s3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="avoidS3">
                                        Avoid using S3 for new uploads
                                    </label>
                                </div>
                                @if ($usageSnapshot['force_local'] && !$settings->avoid_s3)
                                    <div class="alert alert-warning mt-3 mb-0 py-2">
                                        <strong>Warning:</strong> S3 free tier limits exceeded. Enable "Avoid S3" to prevent
                                        extra charges.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold">AWS S3 Credentials</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Access Key</label>
                                <input type="text" name="s3_access_key" class="form-control form-control-custom"
                                    value="{{ old('s3_access_key', $settings->s3_access_key) }}" autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Secret Key</label>
                                <input type="password" name="s3_secret_key" class="form-control form-control-custom"
                                    value="{{ old('s3_secret_key', $settings->s3_secret_key) }}"
                                    autocomplete="new-password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Region</label>
                                <input type="text" name="s3_region" class="form-control form-control-custom"
                                    value="{{ old('s3_region', $settings->s3_region) }}" placeholder="e.g., ap-south-1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Bucket</label>
                                <input type="text" name="s3_bucket" class="form-control form-control-custom"
                                    value="{{ old('s3_bucket', $settings->s3_bucket) }}" placeholder="Bucket name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Custom Endpoint</label>
                                <input type="url" name="s3_endpoint" class="form-control form-control-custom"
                                    value="{{ old('s3_endpoint', $settings->s3_endpoint) }}"
                                    placeholder="https://s3.amazonaws.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Key Prefix (optional)</label>
                                <input type="text" name="s3_prefix" class="form-control form-control-custom"
                                    value="{{ old('s3_prefix', $settings->s3_prefix) }}" placeholder="projects/">
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="pathStyle"
                                        name="s3_use_path_style_endpoint" value="1"
                                        {{ $settings->s3_use_path_style_endpoint ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pathStyle">Use path style endpoint</label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="usageGuard"
                                        name="s3_enable_usage_guard" value="1"
                                        {{ $settings->s3_enable_usage_guard ? 'checked' : '' }}>
                                    <label class="form-check-label" for="usageGuard">Enable Free Tier guard rails</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold">Queue Settings</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Queue Connection *</label>
                                <select name="queue_connection" class="form-select form-control-custom">
                                    <option value="database"
                                        {{ $settings->queue_connection === 'database' ? 'selected' : '' }}>Database Queue
                                    </option>
                                    <option value="sync" {{ $settings->queue_connection === 'sync' ? 'selected' : '' }}>
                                        Sync (no queue)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Max Attempts *</label>
                                <input type="number" name="queue_max_attempts" min="1" max="10"
                                    class="form-control form-control-custom"
                                    value="{{ old('queue_max_attempts', $settings->queue_max_attempts) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Backoff (seconds) *</label>
                                <input type="number" name="queue_backoff" min="0" max="120"
                                    class="form-control form-control-custom"
                                    value="{{ old('queue_backoff', $settings->queue_backoff) }}">
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="analyticsEnabled"
                                        name="analytics_enabled" value="1"
                                        {{ $settings->analytics_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="analyticsEnabled">
                                        Enable visitor & bandwidth analytics tracking
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-admin-primary">
                                <i class="bi bi-save me-2"></i>Save Settings
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="testS3Connection">
                                <i class="bi bi-cloud-check me-2"></i>Test S3 Connection
                            </button>
                        </div>
                        <div id="testConnectionResult" class="mt-3 d-none"></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="content-card mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">S3 Free Tier Usage</h5>
                </div>
                <div class="card-body-custom">
                    @php
                        $formatBytes = function ($bytes) {
                            if ($bytes <= 0) {
                                return '0 B';
                            }
                            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                            $power = min(floor(log($bytes, 1024)), count($units) - 1);
                            return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
                        };
                        $limits = $usageSnapshot['limits'];
                        $triggered = $usageSnapshot['threshold_triggered'];
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Storage Used</span>
                            <span>{{ $formatBytes($usageSnapshot['storage_bytes']) }} /
                                {{ $formatBytes($limits['storage_bytes']) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $storagePercent =
                                    $limits['storage_bytes'] > 0
                                        ? ($usageSnapshot['storage_bytes'] / $limits['storage_bytes']) * 100
                                        : 0;
                            @endphp
                            <div class="progress-bar {{ $triggered['storage'] ? 'bg-danger' : 'bg-success' }}"
                                style="width: {{ min(100, round($storagePercent, 2)) }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>GET Requests</span>
                            <span>{{ number_format($usageSnapshot['get_requests']) }} /
                                {{ number_format($limits['get_requests']) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $getPercent =
                                    $limits['get_requests'] > 0
                                        ? ($usageSnapshot['get_requests'] / $limits['get_requests']) * 100
                                        : 0;
                            @endphp
                            <div class="progress-bar {{ $triggered['get'] ? 'bg-danger' : 'bg-info' }}"
                                style="width: {{ min(100, round($getPercent, 2)) }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>PUT/COPY/POST/LIST Requests</span>
                            <span>{{ number_format($usageSnapshot['put_requests']) }} /
                                {{ number_format($limits['put_requests']) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $putPercent =
                                    $limits['put_requests'] > 0
                                        ? ($usageSnapshot['put_requests'] / $limits['put_requests']) * 100
                                        : 0;
                            @endphp
                            <div class="progress-bar {{ $triggered['put'] ? 'bg-danger' : 'bg-warning' }}"
                                style="width: {{ min(100, round($putPercent, 2)) }}%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>Data Transfer Out</span>
                            <span>{{ $formatBytes($usageSnapshot['egress_bytes']) }} /
                                {{ $formatBytes($limits['egress_bytes']) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $egressPercent =
                                    $limits['egress_bytes'] > 0
                                        ? ($usageSnapshot['egress_bytes'] / $limits['egress_bytes']) * 100
                                        : 0;
                            @endphp
                            <div class="progress-bar {{ $triggered['egress'] ? 'bg-danger' : 'bg-primary' }}"
                                style="width: {{ min(100, round($egressPercent, 2)) }}%;"></div>
                        </div>
                    </div>
                    @if ($usageSnapshot['force_local'])
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong>Action required:</strong> S3 usage is beyond the free tier. Switch to local storage or
                            upgrade your AWS plan to avoid charges.
                        </div>
                    @endif
                </div>
            </div>

            <div class="content-card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Queue Management</h5>
                    <div class="badge bg-light text-dark">
                        Pending: <span id="queuePending">{{ number_format($queueStats['pending']) }}</span> /
                        Failed: <span id="queueFailed">{{ number_format($queueStats['failed']) }}</span>
                    </div>
                </div>
                <div class="card-body-custom">
                    <p class="text-muted small">
                        Use this panel to process queued jobs without SSH access. Jobs will run using the database queue
                        worker.
                    </p>
                    <div class="d-flex gap-2 mb-3">
                        <input type="number" id="queueJobCount" class="form-control form-control-custom" value="5"
                            min="1" max="25" style="max-width: 120px;">
                        <button class="btn btn-admin-primary" id="runQueueBtn">
                            <i class="bi bi-play-circle me-2"></i>Process Jobs
                        </button>
                    </div>
                    <div id="queueRunResult" class="alert d-none mb-0"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="content-card">
                <div class="card-header-custom d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Storage Operations</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-secondary" id="refreshUsageLogs">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.storage.logs.clear') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Clear all storage and queue logs?');">
                                <i class="bi bi-trash"></i>
                                Clear Logs
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive table-responsive-sm" style="max-width: 100%; overflow-x: auto;">
                        <table class="table table-custom table-sm mb-0" id="usageLogTable">
                            <thead>
                                <tr>
                                    <th>When</th>
                                    <th>Project</th>
                                    <th>Action</th>
                                    <th>Storage</th>
                                    <th>Bytes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsage as $log)
                                    <tr>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                        <td>{{ $log->project?->title ?? '#' . $log->project_id }}</td>
                                        <td><span
                                                class="badge bg-light text-dark text-uppercase">{{ $log->action }}</span>
                                        </td>
                                        <td>{{ strtoupper($log->storage_type) }}</td>
                                        <td>{{ $formatBytes($log->bytes) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No usage logs captured yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted d-block mt-2">Logs older than 30 days are pruned automatically.</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-card mb-4">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Queue Run History</h5>
                    <a href="#" class="btn btn-sm btn-outline-secondary" id="refreshQueueLogs">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive table-responsive-sm" style="max-width: 100%; overflow-x: auto;">
                        <table class="table table-custom table-sm mb-0" id="queueLogTable">
                            <thead>
                                <tr>
                                    <th>Started</th>
                                    <th>Status</th>
                                    <th>Processed</th>
                                    <th>Failed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($queueLogs as $log)
                                    <tr>
                                        <td>{{ optional($log->started_at)->format('M d, Y H:i') ?? $log->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                                @if ($log->status === 'completed') bg-success
                                                @elseif($log->status === 'failed') bg-danger
                                                @else bg-warning text-dark @endif">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->processed_count }}</td>
                                        <td>{{ $log->failed_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No queue runs recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">Failed Jobs</h5>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive table-responsive-sm" style="max-width: 100%; overflow-x: auto;">
                        <table class="table table-custom table-sm mb-0">
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
            const testBtn = document.getElementById('testS3Connection');
            const testResult = document.getElementById('testConnectionResult');
            const runQueueBtn = document.getElementById('runQueueBtn');
            const queueResult = document.getElementById('queueRunResult');
            const queuePending = document.getElementById('queuePending');
            const queueFailed = document.getElementById('queueFailed');

            function showAlert(element, message, type) {
                element.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
                element.classList.add('alert', 'alert-' + type);
                element.innerHTML = message;
            }

            if (testBtn) {
                testBtn.addEventListener('click', function() {
                    const form = testBtn.closest('form');
                    const formData = new FormData(form);

                    fetch('{{ route('admin.storage.test') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(text || 'Server error');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            showAlert(testResult, data.message, data.status === 'success' ? 'success' :
                                'danger');
                        })
                        .catch(error => {
                            showAlert(testResult, error.message || 'Unable to test connection.', 'danger');
                        });
                });
            }

            function refreshUsageLogs() {
                fetch('{{ route('admin.storage.logs') }}', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text || 'Failed to load storage logs.');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        const tbody = document.querySelector('#usageLogTable tbody');
                        tbody.innerHTML = '';
                        if (data.data.length === 0) {
                            tbody.innerHTML =
                                '<tr><td colspan="5" class="text-center text-muted py-3">No usage logs captured yet.</td></tr>';
                            return;
                        }
                        data.data.forEach(log => {
                            tbody.insertAdjacentHTML('beforeend',
                                `<tr>
                                    <td>${window.dayjs(log.created_at).fromNow()}</td>
                                    <td>${log.project ? log.project.title : '#' + (log.project_id ?? '—')}</td>
                                    <td><span class="badge bg-light text-dark text-uppercase">${log.action}</span></td>
                                    <td>${(log.storage_type || '').toUpperCase()}</td>
                                    <td>${(log.bytes / (1024 * 1024)).toFixed(2)} MB</td>
                                </tr>`);
                        });
                    });
            }

            function refreshQueueLogs() {
                fetch('{{ route('admin.storage.queue.logs') }}', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text || 'Failed to load queue logs.');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        const tbody = document.querySelector('#queueLogTable tbody');
                        tbody.innerHTML = '';
                        if (data.data.length === 0) {
                            tbody.innerHTML =
                                '<tr><td colspan="4" class="text-center text-muted py-3">No queue runs recorded yet.</td></tr>';
                            return;
                        }
                        data.data.forEach(log => {
                            const statusClass = log.status === 'completed' ? 'bg-success' : (log.status ===
                                'failed' ? 'bg-danger' : 'bg-warning text-dark');
                            tbody.insertAdjacentHTML('beforeend',
                                `<tr>
                                    <td>${window.dayjs(log.started_at || log.created_at).format('MMM D, YYYY HH:mm')}</td>
                                    <td><span class="badge ${statusClass}">${log.status.charAt(0).toUpperCase() + log.status.slice(1)}</span></td>
                                    <td>${log.processed_count}</td>
                                    <td>${log.failed_count}</td>
                                </tr>`);
                        });
                    });
            }

            document.getElementById('refreshUsageLogs')?.addEventListener('click', function(e) {
                e.preventDefault();
                refreshUsageLogs();
            });

            document.getElementById('refreshQueueLogs')?.addEventListener('click', function(e) {
                e.preventDefault();
                refreshQueueLogs();
            });

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
                            body: JSON.stringify({
                                max_jobs: maxJobs
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                showAlert(queueResult, `Processed ${data.processed} jobs successfully.`,
                                    'success');
                                queuePending.textContent = new Intl.NumberFormat().format(data.pending);
                                queueFailed.textContent = new Intl.NumberFormat().format(data.failed);
                                refreshQueueLogs();
                            } else {
                                showAlert(queueResult, data.message || 'Queue runner reported an error.',
                                    'danger');
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
        })();
    </script>
@endpush
