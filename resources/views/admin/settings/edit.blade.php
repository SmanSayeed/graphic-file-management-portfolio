@extends('admin.layouts.admin')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')

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

    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Site Configuration</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Logo Upload -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom">Site Logo *</label>
                        <div class="image-preview mb-3" id="logoPreview"
                            onclick="document.getElementById('logoFile').click()">
                            @if($settings->logo)
                                <img src="{{ asset('storage/' . $settings->logo) }}" alt="Site Logo" 
                                     style="width: 100%; height: 100%; object-fit: contain; max-height: 200px;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-image" style="font-size: 48px;"></i>
                                    <p class="mb-0 mt-2">Upload Logo</p>
                                    <small>Recommended: 200x60px (PNG with transparent background)</small>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="logoFile" name="logo" class="d-none" accept="image/*">
                        <small class="text-muted">Recommended: 200x60px PNG with transparent background. Logo will be displayed in navigation, footer, and admin panel.</small>
                        @error('logo')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Favicon Upload -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom">Favicon</label>
                        <div class="image-preview mb-3 d-flex align-items-center gap-3">
                            <div class="favicon-preview-wrapper"
                                style="width: 64px; height: 64px; border: 1px dashed #ced4da; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                                onclick="document.getElementById('faviconFile').click()">
                                @if ($settings->favicon)
                                    <img src="{{ asset('storage/' . $settings->favicon) }}" alt="Site Favicon"
                                        id="faviconPreviewImage"
                                        style="width: 32px; height: 32px; object-fit: contain;">
                                @else
                                    <i class="bi bi-file-earmark-image text-muted" style="font-size: 24px;"></i>
                                @endif
                            </div>
                            <div>
                                <p class="mb-1 text-muted small">Recommended: square PNG or ICO (32×32 px or 64×64 px)</p>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="document.getElementById('faviconFile').click()">Upload Favicon</button>
                                <p class="mb-0 mt-2 small text-muted">This icon appears in the browser tab.</p>
                            </div>
                        </div>
                        <input type="file" id="faviconFile" name="favicon" class="d-none" accept="image/*">
                        @error('favicon')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Site Name -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom">Site Name *</label>
                        <input type="text" name="site_name" class="form-control form-control-custom" 
                               placeholder="e.g., Creative Studio" value="{{ old('site_name', $settings->site_name) }}" required>
                        <small class="text-muted">This name will appear in the "About" section title on the home page</small>
                        @error('site_name')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Update Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Logo preview
    document.getElementById('logoFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('logoPreview');
                preview.innerHTML = `<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: contain; max-height: 200px;">`;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('faviconFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const previewImage = document.getElementById('faviconPreviewImage');
                if (previewImage) {
                    previewImage.src = event.target.result;
                } else {
                    const wrapper = document.querySelector('.favicon-preview-wrapper');
                    wrapper.innerHTML = `<img src="${event.target.result}" id="faviconPreviewImage" style="width: 32px; height: 32px; object-fit: contain;">`;
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

