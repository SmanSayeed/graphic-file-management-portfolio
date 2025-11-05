@extends('admin.layouts.admin')

@section('title', 'Edit Hero Slider')
@section('page-title', 'Edit Hero Slider')

@section('content')
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
            <h5 class="mb-0 fw-bold">Edit Slider Details</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.sliders.update', $slider->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom">Slider Image</label>
                        <div class="alert alert-info mb-3" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Recommended Resolution:</strong> 1920 x 630 pixels (16:5.25 aspect ratio)
                            <br>
                            <small>Images will be automatically adjusted to fit the slider dimensions. For best quality, use landscape orientation images.</small>
                        </div>
                        @if($slider->image)
                            <div class="image-preview mb-3" id="imagePreview" onclick="document.getElementById('sliderImage').click()">
                                <img src="{{ asset('storage/' . $slider->image) }}" alt="Slider Image" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('sliderImage').click()">
                                <div class="text-center text-muted">
                                    <i class="bi bi-cloud-upload" style="font-size: 48px;"></i>
                                    <p class="mb-0 mt-2">Click to upload image</p>
                                    <small>1920 x 630px recommended</small>
                                </div>
                            </div>
                        @endif
                        <input type="file" id="sliderImage" name="image" class="d-none" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                        @error('image')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control form-control-custom" 
                                   value="{{ old('sort_order', $slider->sort_order) }}" min="0">
                            @error('sort_order')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                       id="isActive" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">
                                    Active Status
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Update Slider
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Image preview for slider image
    document.getElementById('sliderImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('imagePreview');
                preview.innerHTML = `<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

