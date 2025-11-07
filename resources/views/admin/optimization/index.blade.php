@extends('admin.layouts.admin')

@section('title', 'Site Optimization')
@section('page-title', 'Site Optimization')

@section('content')
    <div class="row">
        <!-- Storage Link Section -->
        <div class="col-md-6 mb-4">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-folder-symlink me-2"></i>Storage Link
                    </h5>
                </div>
                <div class="card-body-custom">
                    <p class="text-muted">
                        Create a symbolic link from <code>public/storage</code> to <code>storage/app/public</code>
                        to make stored files accessible via the web.
                    </p>
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        @if($storageLinkExists)
                            <span class="badge bg-success ms-2">
                                <i class="bi bi-check-circle me-1"></i>Link Exists
                            </span>
                        @else
                            <span class="badge bg-warning ms-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>Link Not Found
                            </span>
                        @endif
                    </div>

                    <button type="button" class="btn btn-admin-primary w-100" id="createStorageLinkBtn">
                        <i class="bi bi-link-45deg me-2"></i>Create Storage Link
                    </button>

                    <div id="storageLinkResult" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Clear Optimization Section -->
        <div class="col-md-6 mb-4">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-eraser me-2"></i>Clear Optimization
                    </h5>
                </div>
                <div class="card-body-custom">
                    <p class="text-muted">
                        Clear all cached files including configuration, routes, views, events, and application cache.
                    </p>

                    <button type="button" class="btn btn-warning w-100" id="clearOptimizationBtn">
                        <i class="bi bi-x-circle me-2"></i>Clear All Caches
                    </button>

                    <div id="clearOptimizationResult" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Database Migrations Section -->
        <div class="col-md-6 mb-4">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-database me-2"></i>Database Migrations
                    </h5>
                </div>
                <div class="card-body-custom">
                    <p class="text-muted">
                        Run database migrations to create or update database tables. This is required after first deployment.
                    </p>
                    
                    @if(isset($hasPendingMigrations) && $hasPendingMigrations)
                        <div class="mb-3">
                            <span class="badge bg-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>Pending Migrations
                            </span>
                        </div>
                    @else
                        <div class="mb-3">
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>All Migrations Up to Date
                            </span>
                        </div>
                    @endif

                    <button type="button" class="btn btn-primary w-100" id="runMigrationsBtn">
                        <i class="bi bi-arrow-repeat me-2"></i>Run Migrations
                    </button>

                    <div id="migrationsResult" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Cache Optimization Section -->
        <div class="col-md-6 mb-4">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge me-2"></i>Cache Optimization
                    </h5>
                </div>
                <div class="card-body-custom">
                    <p class="text-muted">
                        Cache configuration, routes, views, and events for better performance in production.
                    </p>

                    <button type="button" class="btn btn-success w-100" id="cacheOptimizationBtn">
                        <i class="bi bi-speedometer2 me-2"></i>Optimize & Cache
                    </button>

                    <div id="cacheOptimizationResult" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Create Storage Link
        $('#createStorageLinkBtn').on('click', function() {
            const btn = $(this);
            const resultDiv = $('#storageLinkResult');
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');

            $.ajax({
                url: '{{ route("admin.optimization.storage-link") }}',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        resultDiv.html(
                            '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<strong>Success!</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>'
                        ).show();
                        // Reload page after 2 seconds to update status
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        resultDiv.html(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<strong>Error!</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>'
                        ).show();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to create storage link.';
                    resultDiv.html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> ' + message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    ).show();
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="bi bi-link-45deg me-2"></i>Create Storage Link');
                }
            });
        });

        // Run Migrations
        $('#runMigrationsBtn').on('click', function() {
            const btn = $(this);
            const resultDiv = $('#migrationsResult');
            
            if (!confirm('Are you sure you want to run database migrations? This will modify your database structure.')) {
                return;
            }

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Running...');

            $.ajax({
                url: '{{ route("admin.optimization.migrate") }}',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        resultDiv.html(
                            '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<strong>Success!</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>'
                        ).show();
                        // Reload page after 2 seconds to update status
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        resultDiv.html(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<strong>Error!</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>'
                        ).show();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to run migrations.';
                    resultDiv.html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> ' + message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    ).show();
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="bi bi-arrow-repeat me-2"></i>Run Migrations');
                }
            });
        });

        // Clear Optimization
        $('#clearOptimizationBtn').on('click', function() {
            const btn = $(this);
            const resultDiv = $('#clearOptimizationResult');
            
            if (!confirm('Are you sure you want to clear all caches? This may temporarily slow down the application.')) {
                return;
            }

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Clearing...');

            $.ajax({
                url: '{{ route("admin.optimization.clear") }}',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        let html = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<strong>Success!</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>';
                        
                        if (response.commands) {
                            html += '<div class="mt-3"><h6>Commands Executed:</h6><ul class="list-group">';
                            response.commands.forEach(function(cmd) {
                                let statusClass = 'secondary';
                                if (cmd.status === 'success') {
                                    statusClass = 'success';
                                } else if (cmd.status === 'warning') {
                                    statusClass = 'warning';
                                } else {
                                    statusClass = 'danger';
                                }
                                html += '<li class="list-group-item">' +
                                    '<strong>' + cmd.description + ':</strong> ' +
                                    '<span class="badge bg-' + statusClass + ' ms-2">' + cmd.status + '</span>';
                                if (cmd.message) {
                                    html += '<br><small class="text-muted">' + cmd.message + '</small>';
                                }
                                html += '</li>';
                            });
                            html += '</ul></div>';
                        }
                        
                        resultDiv.html(html).show();
                    } else {
                        resultDiv.html(
                            '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                            '<strong>Warning!</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>'
                        ).show();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to clear optimization.';
                    resultDiv.html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> ' + message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    ).show();
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="bi bi-x-circle me-2"></i>Clear All Caches');
                }
            });
        });

        // Cache Optimization
        $('#cacheOptimizationBtn').on('click', function() {
            const btn = $(this);
            const resultDiv = $('#cacheOptimizationResult');
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Optimizing...');

            $.ajax({
                url: '{{ route("admin.optimization.cache") }}',
                method: 'POST',
                success: function(response) {
                    let html = '';
                    // Determine alert type based on success status
                    if (response.success) {
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<strong>Success!</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>';
                    } else {
                        // Check if there are any successful commands
                        const hasSuccess = response.commands && response.commands.some(cmd => cmd.status === 'success');
                        const alertClass = hasSuccess ? 'warning' : 'danger';
                        const alertTitle = hasSuccess ? 'Warning!' : 'Error!';
                        html = '<div class="alert alert-' + alertClass + ' alert-dismissible fade show" role="alert">' +
                            '<strong>' + alertTitle + '</strong> ' + response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>';
                    }
                    
                    if (response.commands) {
                        html += '<div class="mt-3"><h6>Commands Executed:</h6><ul class="list-group">';
                        response.commands.forEach(function(cmd) {
                            let statusClass = 'secondary';
                            if (cmd.status === 'success') {
                                statusClass = 'success';
                            } else if (cmd.status === 'warning') {
                                statusClass = 'warning';
                            } else {
                                statusClass = 'danger';
                            }
                            html += '<li class="list-group-item">' +
                                '<strong>' + cmd.description + ':</strong> ' +
                                '<span class="badge bg-' + statusClass + ' ms-2">' + cmd.status + '</span>';
                            if (cmd.message) {
                                html += '<br><small class="text-muted">' + cmd.message + '</small>';
                            }
                            html += '</li>';
                        });
                        html += '</ul></div>';
                    }
                    
                    resultDiv.html(html).show();
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to cache optimization.';
                    resultDiv.html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> ' + message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    ).show();
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="bi bi-speedometer2 me-2"></i>Optimize & Cache');
                }
            });
        });
    });
</script>
@endpush

