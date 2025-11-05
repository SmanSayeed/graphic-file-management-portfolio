@extends('admin.layouts.admin')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Edit Project Details</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Thumbnail Image -->
                    <div class="col-md-4 mb-4">
                        <label class="form-label-custom">Thumbnail Image *</label>
                        @if($project->thumbnail)
                            <div class="image-preview mb-3" id="thumbnailPreview" onclick="document.getElementById('thumbnailImage').click()">
                                <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div class="image-preview" id="thumbnailPreview" onclick="document.getElementById('thumbnailImage').click()">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image" style="font-size: 48px;"></i>
                                    <p class="mb-0 mt-2">Upload Thumbnail</p>
                                </div>
                            </div>
                        @endif
                        <input type="file" id="thumbnailImage" name="thumbnail" class="d-none" accept="image/*">
                        @error('thumbnail')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label-custom">Project Title *</label>
                                <input type="text" name="title" class="form-control form-control-custom" value="{{ old('title', $project->title) }}" required>
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Category *</label>
                                <select name="category_id" class="form-select form-control-custom" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <option value="free" {{ old('type', $project->type) == 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="paid" {{ old('type', $project->type) == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                                @error('type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="priceField" style="display: {{ old('type', $project->type) == 'paid' ? 'block' : 'none' }};">
                                <label class="form-label-custom">Price ($)</label>
                                <input type="number" name="price" class="form-control form-control-custom" step="0.01" placeholder="0.00" value="{{ old('price', $project->price) }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">File Type *</label>
                                <select name="file_type" class="form-select form-control-custom" id="fileType" required>
                                    <option value="">Select File Type</option>
                                    <option value="image" {{ old('file_type', $project->file_type) == 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="video" {{ old('file_type', $project->file_type) == 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                                @error('file_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Short Description *</label>
                        <input type="text" name="short_description" class="form-control form-control-custom" maxlength="255" value="{{ old('short_description', $project->short_description) }}" required>
                        @error('short_description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label-custom">Full Description *</label>
                        <textarea name="description" class="form-control form-control-custom" rows="5" required>{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Downloadable Files -->
                    <div class="col-12">
                        <h6 class="fw-bold mb-3">Downloadable Files</h6>
                    </div>

                    <!-- Image File Section -->
                    <div class="col-md-6 mb-4" id="imageFileSection" style="display: {{ old('file_type', $project->file_type) == 'image' ? 'block' : 'none' }};">
                        <label class="form-label-custom">PNG/JPG Image File</label>
                        @if($project->image)
                            <div class="border rounded p-3 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-image text-primary me-3" style="font-size: 32px;"></i>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-semibold">{{ basename($project->image) }}</p>
                                        <small class="text-muted">Image File</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="image-preview" style="height: 150px; {{ $project->image ? 'display: none;' : '' }}" id="imagePreview" onclick="document.getElementById('imageFile').click()">
                            <div class="text-center text-muted">
                                <i class="bi bi-file-earmark-image" style="font-size: 36px;"></i>
                                <p class="mb-0 mt-2 small">Upload Image (PNG/JPG)</p>
                            </div>
                        </div>
                        @if(!$project->image)
                            <input type="file" id="imageFile" name="image" class="d-none" accept="image/*">
                        @endif
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Video Section -->
                    <div class="col-md-6 mb-4" id="videoFileSection" style="display: {{ old('file_type', $project->file_type) == 'video' ? 'block' : 'none' }};">
                        <label class="form-label-custom">Video File or Video Link</label>
                        @if($project->video)
                            <div class="border rounded p-3 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-play text-danger me-3" style="font-size: 32px;"></i>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-semibold">{{ basename($project->video) }}</p>
                                        <small class="text-muted">Video File</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!$project->video_link)
                            <div class="mb-2">
                                <label class="form-label-custom small">Upload Video File</label>
                                <div class="image-preview" style="height: 100px;" id="videoPreview" onclick="document.getElementById('videoFile').click()">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-file-earmark-play" style="font-size: 24px;"></i>
                                        <p class="mb-0 mt-1 small">Upload Video</p>
                                    </div>
                                </div>
                                <input type="file" id="videoFile" name="video" class="d-none" accept="video/*">
                            </div>
                        @endif
                        <div class="mt-2">
                            <label class="form-label-custom small">Or Enter Video Link (YouTube Only)</label>
                            <input type="url" name="video_link" class="form-control form-control-custom" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_link', $project->video_link) }}">
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
                    <div class="col-md-6 mb-4" id="sourceFileSection">
                        <label class="form-label-custom">Source File (AI/PSD/ZIP)</label>
                        @if($project->source_file)
                            <div class="border rounded p-3 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-zip text-success me-3" style="font-size: 32px;"></i>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-semibold">{{ basename($project->source_file) }}</p>
                                        <small class="text-muted">Source File</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="image-preview" style="height: 150px; {{ $project->source_file ? 'display: none;' : '' }}" id="sourcePreview" onclick="document.getElementById('sourceFile').click()">
                            <div class="text-center text-muted">
                                <i class="bi bi-file-earmark-zip" style="font-size: 36px;"></i>
                                <p class="mb-0 mt-2 small">Upload Source File</p>
                            </div>
                        </div>
                        @if(!$project->source_file)
                            <input type="file" id="sourceFile" name="source_file" class="d-none" accept=".ai,.psd,.zip,.rar,.7z">
                        @endif
                        <small class="text-muted">Accepted: .ai, .psd, .zip, .rar, .7z</small>
                        @error('source_file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Update Project
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
            const thumbnailInput = document.getElementById('thumbnailImage');
            
            if (this.value === 'image') {
                imageSection.style.display = 'block';
                videoSection.style.display = 'none';
                if (thumbnailInput) {
                    thumbnailInput.required = false; // Not required on edit if already exists
                }
            } else if (this.value === 'video') {
                imageSection.style.display = 'none';
                videoSection.style.display = 'block';
                if (thumbnailInput) {
                    thumbnailInput.required = false;
                }
            }
        });
    </script>
@endpush


