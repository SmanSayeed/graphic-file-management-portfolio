@extends('layouts.premium')

@section('title', 'Edit Profile')
@section('description', 'Edit your profile information and change password')

@section('content')
<section class="section-premium" style="padding-top: 120px; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="mb-4">
                    <a href="{{ route('user.profile.show') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Profile
                    </a>
                </div>

                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-lg border-0 rounded-4">
                            <div class="card-header bg-white border-0 p-4 rounded-top-4">
                                <h4 class="mb-0 fw-bold">Profile Information</h4>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <label class="form-label fw-semibold mb-2">Profile Image</label>
                                            <div class="image-preview mb-3" id="avatarPreview" onclick="document.getElementById('avatar').click()" 
                                                 style="width: 100%; height: 200px; border: 2px dashed #ddd; border-radius: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa;">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="text-center text-muted">
                                                        <i class="bi bi-person-circle" style="font-size: 64px;"></i>
                                                        <p class="mb-0 mt-2">Click to Upload</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                            @error('avatar')
                                                <small class="text-danger d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Full Name *</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Email Address</label>
                                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                                <small class="text-muted">Email cannot be changed</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #00B894 0%, #F5576C 100%); border: none;">
                                            <i class="bi bi-save me-2"></i>Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-lg border-0 rounded-4" id="password">
                            <div class="card-header bg-white border-0 p-4 rounded-top-4">
                                <h5 class="mb-0 fw-bold">Change Password</h5>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" action="{{ route('user.profile.password.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Current Password *</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                        @error('current_password')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">New Password *</label>
                                        <input type="password" name="password" class="form-control" required>
                                        @error('password')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Confirm Password *</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-key me-2"></i>Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Avatar preview
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

