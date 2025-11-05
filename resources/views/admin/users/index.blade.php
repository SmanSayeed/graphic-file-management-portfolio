@extends('admin.layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users Management')

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
        <a href="{{ route('admin.users.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New User
        </a>
    </div>

    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">All Users ({{ $users->total() }})</h5>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'root_admin')
                                        <span class="badge bg-danger">ROOT ADMIN</span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge bg-warning">ADMIN</span>
                                    @else
                                        <span class="badge bg-secondary">USER</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input status-toggle" 
                                               type="checkbox" 
                                               role="switch" 
                                               data-user-id="{{ $user->id }}"
                                               data-current-user="{{ Auth::id() }}"
                                               {{ $user->is_active ? 'checked' : '' }}
                                               {{ $user->id === Auth::id() ? 'disabled' : '' }}
                                               id="statusSwitch{{ $user->id }}">
                                        <label class="form-check-label" for="statusSwitch{{ $user->id }}">
                                            <span class="badge ms-2 {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}" id="statusBadge{{ $user->id }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No users found. <a href="{{ route('admin.users.create') }}">Create your first user</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-3">
                    {{ $users->links() }}
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
                const userId = this.getAttribute('data-user-id');
                const currentUserId = this.getAttribute('data-current-user');
                const badge = document.getElementById('statusBadge' + userId);
                const checkbox = this;
                
                // Prevent toggling own account
                if (userId === currentUserId) {
                    this.checked = true;
                    return;
                }

                // Show loading state
                const originalChecked = this.checked;
                this.disabled = true;

                // Make AJAX request
                fetch(`/admin/users/${userId}/toggle-status`, {
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
                        showAlert('User status updated successfully', 'success');
                    } else {
                        // Revert checkbox
                        checkbox.checked = !originalChecked;
                        showAlert(data.message || 'Failed to update user status', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    checkbox.checked = !originalChecked;
                    showAlert('An error occurred while updating user status', 'danger');
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
