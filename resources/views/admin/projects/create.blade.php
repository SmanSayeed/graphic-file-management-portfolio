@extends('admin.layouts.admin')

@section('title', 'Add Project')
@section('page-title', 'Add New Project')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Project Details</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Thumbnail Image -->
                    <div class="col-md-4 mb-4" id="thumbnailSection">
                        <label class="form-label-custom">Thumbnail Image <span id="thumbnailRequired" style="color: red;">*</span></label>
                        <div class="image-preview" id="thumbnailPreview"
                            onclick="document.getElementById('thumbnailImage').click()">
                            <div class="text-center text-muted">
                                <i class="bi bi-image" style="font-size: 48px;"></i>
                                <p class="mb-0 mt-2">Upload Thumbnail</p>
                                <small>800x600px recommended</small>
                            </div>
                        </div>
                        <input type="file" id="thumbnailImage" name="thumbnail" class="d-none" accept="image/*">
                        <small class="text-muted" id="thumbnailHint">Required for image projects</small>
                        @error('thumbnail')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label-custom">Project Title *</label>
                                <input type="text" name="title" class="form-control form-control-custom"
                                    placeholder="Enter project title" value="{{ old('title') }}" required>
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Category *</label>
                                <select name="category_id" class="form-select form-control-custom" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Type *</label>
                                <select name="type" class="form-select form-control-custom" id="projectType" required>
                                    <option value="">Select Type</option>
                                    <option value="free" {{ old('type') == 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="paid" {{ old('type') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                                @error('type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="priceField" style="display: none;">
                                <label class="form-label-custom">Price ($)</label>
                                <input type="number" name="price" class="form-control form-control-custom" step="0.01"
                                    placeholder="0.00" value="{{ old('price') }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">File Type *</label>
                                <select name="file_type" class="form-select form-control-custom" id="fileType" required>
                                    <option value="">Select File Type</option>
                                    <option value="image" {{ old('file_type') == 'image' ? 'selected' : '' }}>Image
                                    </option>
                                    <option value="video" {{ old('file_type') == 'video' ? 'selected' : '' }}>Video
                                    </option>
                                </select>
                                @error('file_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Short Description *</label>
                        <input type="text" name="short_description" class="form-control form-control-custom"
                            maxlength="255" placeholder="Brief description (max 255 characters)"
                            value="{{ old('short_description') }}" required>
                        @error('short_description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label-custom">Full Description *</label>
                        <textarea name="description" class="form-control form-control-custom" rows="5"
                            placeholder="Detailed project description" required>{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Downloadable Files -->
                    <div class="col-12">
                        <h6 class="fw-bold mb-3">Downloadable Files</h6>
                    </div>

                    <!-- Image File Section -->
                    <div class="col-md-6 mb-4" id="imageFileSection">
                        <label class="form-label-custom">PNG/JPG Image File</label>
                        <div class="image-preview" style="height: 150px;" id="imagePreview"
                            onclick="document.getElementById('imageFile').click()">
                            <div class="text-center text-muted">
                                <i class="bi bi-file-earmark-image" style="font-size: 36px;"></i>
                                <p class="mb-0 mt-2 small">Upload Image (PNG/JPG)</p>
                            </div>
                        </div>
                        <input type="file" id="imageFile" name="image" class="d-none" accept="image/*">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Video Section -->
                    <div class="col-md-6 mb-4" id="videoFileSection" style="display: none;">
                        <label class="form-label-custom">Video File or Video Link</label>
                        <div class="mb-2">
                            <label class="form-label-custom small">Upload Video File</label>
                            <div class="image-preview" style="height: 100px;" id="videoPreview"
                                onclick="document.getElementById('videoFile').click()">
                                <div class="text-center text-muted">
                                    <i class="bi bi-file-earmark-play" style="font-size: 24px;"></i>
                                    <p class="mb-0 mt-1 small">Upload Video</p>
                                </div>
                            </div>
                            <input type="file" id="videoFile" name="video" class="d-none" accept="video/*">
                        </div>
                        <div class="mt-2">
                            <label class="form-label-custom small">Or Enter Video Link (YouTube Only)</label>
                            <input type="url" name="video_link" class="form-control form-control-custom"
                                placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_link') }}">
                            <small class="text-muted">Only YouTube links are supported. Formats: youtube.com/watch?v=... or youtu.be/...</small>
                        </div>
                        @error('video')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @error('video_link')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Source File Section -->
                    <div class="col-md-6 mb-4" id="sourceFileSection" style="display: none;">
                        <label class="form-label-custom">Source File (AI/PSD/ZIP)</label>
                        <div class="image-preview" style="height: 150px;" id="sourcePreview"
                            onclick="document.getElementById('sourceFile').click()">
                            <div class="text-center text-muted">
                                <i class="bi bi-file-earmark-zip" style="font-size: 36px;"></i>
                                <p class="mb-0 mt-2 small">Upload Source File</p>
                            </div>
                        </div>
                        <input type="file" id="sourceFile" name="source_file" class="d-none"
                            accept=".ai,.psd,.zip,.rar,.7z">
                        <small class="text-muted">Accepted: .ai, .psd, .zip, .rar, .7z</small>
                        @error('source_file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Create Project
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Show/hide price field based on type
        document.getElementById('projectType').addEventListener('change', function() {
            const priceField = document.getElementById('priceField');
            if (this.value === 'paid') {
                priceField.style.display = 'block';
            } else {
                priceField.style.display = 'none';
            }
        });

        // Show/hide file sections based on file type
        document.getElementById('fileType').addEventListener('change', function() {
            const imageSection = document.getElementById('imageFileSection');
            const videoSection = document.getElementById('videoFileSection');
            const sourceSection = document.getElementById('sourceFileSection');
            const thumbnailSection = document.getElementById('thumbnailSection');
            const thumbnailInput = document.getElementById('thumbnailImage');
            const thumbnailRequired = document.getElementById('thumbnailRequired');
            const thumbnailHint = document.getElementById('thumbnailHint');

            if (this.value === 'image') {
                imageSection.style.display = 'block';
                videoSection.style.display = 'none';
                thumbnailSection.style.display = 'block';
                thumbnailInput.required = true;
                thumbnailRequired.style.display = 'inline';
                thumbnailHint.textContent = 'Required for image projects';
            } else if (this.value === 'video') {
                imageSection.style.display = 'none';
                videoSection.style.display = 'block';
                thumbnailSection.style.display = 'block';
                thumbnailInput.required = false;
                thumbnailRequired.style.display = 'none';
                thumbnailHint.textContent = 'Optional - used as fallback if video link fails';
            }

            // Always show source file section
            sourceSection.style.display = 'block';
        });

        // Image preview for thumbnail
        document.getElementById('thumbnailImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('thumbnailPreview');
                    preview.innerHTML =
                        `<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Image preview for image file
        document.getElementById('imageFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML =
                        `<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
