@extends('admin.layouts.admin')

@section('title', 'Add User')
@section('page-title', 'Add New User')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">User Details</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Name *</label>
                        <input type="text" name="name" class="form-control form-control-custom"
                            placeholder="Enter user name" value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Email *</label>
                        <input type="email" name="email" class="form-control form-control-custom"
                            placeholder="Enter email address" value="{{ old('email') }}" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Password *</label>
                        <input type="password" name="password" class="form-control form-control-custom"
                            placeholder="Enter password (min 8 characters)" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Role *</label>
                        <select name="role" class="form-select form-control-custom" required>
                            <option value="">Select Role</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            @if(Auth::user()->isRootAdmin())
                                <option value="root_admin" {{ old('role') == 'root_admin' ? 'selected' : '' }}>Root Admin</option>
                            @endif
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                   id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Active Status
                            </label>
                        </div>
                        <small class="text-muted">Inactive users cannot log in to the system</small>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

