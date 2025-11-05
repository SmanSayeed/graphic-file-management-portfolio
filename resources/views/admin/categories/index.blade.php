@extends('admin.layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Category
        </a>
    </div>

    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">All Categories ({{ $categories->total() }})</h5>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Projects Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded p-2 me-3" style="background-color: {{ $category->color }};">
                                            <i class="{{ $category->icon }} text-white"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $category->slug }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $category->color }};">
                                        {{ $category->projects_count }} {{ Str::plural('project', $category->projects_count) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input status-toggle" 
                                               type="checkbox" 
                                               role="switch" 
                                               data-category-id="{{ $category->id }}"
                                               {{ $category->is_active ? 'checked' : '' }}
                                               id="statusSwitch{{ $category->id }}">
                                        <label class="form-check-label" for="statusSwitch{{ $category->id }}">
                                            <span class="badge ms-2 {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}" id="statusBadge{{ $category->id }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this category? This will fail if there are projects assigned to it.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted mb-0">No categories found. <a href="{{ route('admin.categories.create') }}">Create your first category</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle status toggle
        document.querySelectorAll('.status-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const categoryId = this.getAttribute('data-category-id');
                const badge = document.getElementById('statusBadge' + categoryId);
                const checkbox = this;

                // Show loading state
                const originalChecked = this.checked;
                this.disabled = true;

                // Make AJAX request
                fetch(`/admin/categories/${categoryId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update badge
                        if (data.is_active) {
                            badge.className = 'badge ms-2 bg-success';
                            badge.textContent = 'Active';
                        } else {
                            badge.className = 'badge ms-2 bg-secondary';
                            badge.textContent = 'Inactive';
                        }
                        
                        // Show success message
                        showAlert('Category status updated successfully', 'success');
                    } else {
                        // Revert checkbox
                        checkbox.checked = !originalChecked;
                        showAlert(data.message || 'Failed to update category status', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    checkbox.checked = !originalChecked;
                    showAlert('An error occurred while updating category status', 'danger');
                })
                .finally(() => {
                    checkbox.disabled = false;
                });
            });
        });
    });

    function showAlert(message, type) {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at the top of content
        const content = document.querySelector('.admin-main');
        if (content) {
            content.insertBefore(alertDiv, content.firstChild);
            
            // Auto dismiss after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    }
</script>
@endpush
