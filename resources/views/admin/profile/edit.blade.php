@extends('admin.layouts.admin')

@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8 mb-4">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">Profile Information</h5>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label-custom">Profile Image</label>
                                @if($user->avatar)
                                    <div class="image-preview mb-3" id="avatarPreview">
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="image-preview" id="avatarPreview" onclick="document.getElementById('avatar').click()">
                                        <div class="text-center text-muted">
                                            <i class="bi bi-person-circle" style="font-size: 48px;"></i>
                                            <p class="mb-0 mt-2">Upload Avatar</p>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2" onclick="document.getElementById('avatar').click()">
                                    <i class="bi bi-upload me-2"></i>Change Photo
                                </button>
                                @error('avatar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label-custom">Full Name *</label>
                                        <input type="text" name="name" class="form-control form-control-custom" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label-custom">Email Address</label>
                                        <input type="email" class="form-control form-control-custom" value="{{ $user->email }}" disabled>
                                        <small class="text-muted">Use the email change form below to update your email</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <button type="submit" class="btn btn-admin-primary">
                                <i class="bi bi-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-lg-4 mb-4">
            <!-- Change Email -->
            <div class="content-card mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">Change Email</h5>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('admin.profile.email.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label-custom">New Email Address</label>
                            <input type="email" name="email" class="form-control form-control-custom" value="{{ old('email') }}" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-envelope me-2"></i>Update Email
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">Change Password</h5>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="{{ route('admin.profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label-custom">Current Password</label>
                            <input type="password" name="current_password" class="form-control form-control-custom" required>
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">New Password</label>
                            <input type="password" name="password" class="form-control form-control-custom" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-custom" required>
                        </div>

                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-key me-2"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information Display -->
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Account Information</h5>
        </div>
        <div class="card-body-custom">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>Role:</strong>
                    <p class="mb-0">
                        @if($user->role === 'root_admin')
                            <span class="badge bg-danger">ROOT ADMIN</span>
                        @elseif($user->role === 'admin')
                            <span class="badge bg-warning">ADMIN</span>
                        @else
                            <span class="badge bg-secondary">USER</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>Status:</strong>
                    <p class="mb-0">
                        @if($user->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>Member Since:</strong>
                    <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>Last Updated:</strong>
                    <p class="mb-0">{{ $user->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('avatarPreview');
                    preview.innerHTML = `<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush


