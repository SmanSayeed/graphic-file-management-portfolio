@extends('admin.layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Edit User Details</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Name *</label>
                        <input type="text" name="name" class="form-control form-control-custom"
                            placeholder="Enter user name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Email *</label>
                        <input type="email" name="email" class="form-control form-control-custom"
                            placeholder="Enter email address" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Password</label>
                        <input type="password" name="password" class="form-control form-control-custom"
                            placeholder="Leave empty to keep current password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <small class="text-muted">Leave empty to keep current password. Minimum 8 characters if changing.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Role *</label>
                        <select name="role" class="form-select form-control-custom" required>
                            <option value="">Select Role</option>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            @if(Auth::user()->isRootAdmin())
                                <option value="root_admin" {{ old('role', $user->role) == 'root_admin' ? 'selected' : '' }}>Root Admin</option>
                            @endif
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                   id="isActive" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Active Status
                            </label>
                        </div>
                        <small class="text-muted">Inactive users cannot log in to the system</small>
                        @if($user->id === Auth::id())
                            <div class="alert alert-warning mt-2 mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>You cannot deactivate your own account.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

