@extends('admin.layouts.admin')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Edit Project Details</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" enctype="multipart/form-data"
                data-project-form="true">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Thumbnail Image -->
                    <div class="col-md-4 mb-4" id="thumbnailSection">
                        <label class="form-label-custom">
                            Thumbnail Image
                            <span id="thumbnailRequired" style="color: red; display: none;">*</span>
                        </label>
                        <small class="text-muted d-block mb-2" id="thumbnailHint">
                            {{ $project->thumbnail ? 'Existing thumbnail will be kept unless you upload a new one.' : 'Required for image projects' }}
                        </small>
                        @php
                            $thumbnailUrl =
                                $project->thumbnail_url ?? 'https://via.placeholder.com/600x400?text=Thumbnail';
                        @endphp
                        <div class="image-preview mb-2" id="thumbnailPreview"
                            onclick="document.getElementById('thumbnailImage').click()">
                            <img src="{{ $thumbnailUrl }}" alt="Thumbnail"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <input type="file" id="thumbnailImage" name="thumbnail" class="d-none" accept="image/*"
                            data-has-existing="{{ $project->thumbnail ? 'true' : 'false' }}">
                        @if ($project->thumbnail)
                            <small class="text-muted d-block">Current thumbnail in use.</small>
                        @endif
                        @error('thumbnail')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label-custom">Project Title *</label>
                                <input type="text" name="title" class="form-control form-control-custom"
                                    value="{{ old('title', $project->title) }}" required>
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
                                            {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <option value="free" {{ old('type', $project->type) == 'free' ? 'selected' : '' }}>
                                        Free</option>
                                    <option value="paid" {{ old('type', $project->type) == 'paid' ? 'selected' : '' }}>
                                        Paid</option>
                                </select>
                                @error('type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="priceField"
                                style="display: {{ old('type', $project->type) == 'paid' ? 'block' : 'none' }};">
                                <label class="form-label-custom">Price ($)</label>
                                <input type="number" name="price" class="form-control form-control-custom" step="0.01"
                                    placeholder="0.00" value="{{ old('price', $project->price) }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">File Type *</label>
                                <select name="file_type" class="form-select form-control-custom" id="fileType" required>
                                    <option value="">Select File Type</option>
                                    <option value="image"
                                        {{ old('file_type', $project->file_type) == 'image' ? 'selected' : '' }}>Image
                                    </option>
                                    <option value="video"
                                        {{ old('file_type', $project->file_type) == 'video' ? 'selected' : '' }}>Video
                                    </option>
                                </select>
                                @error('file_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label-custom d-flex align-items-center gap-2">
                                    Storage Location *
                                    @if (!$s3Available)
                                        <span class="badge bg-secondary">S3 disabled</span>
                                    @endif
                                    @if ($storageSettings->avoid_s3)
                                        <span class="badge bg-warning text-dark">Avoiding S3</span>
                                    @endif
                                </label>
                                @php
                                    $currentStorageType = old('storage_type', $project->storage_type);
                                    $usage = $s3Snapshot;
                                    $formatBytes = function ($bytes) {
                                        if ($bytes <= 0) {
                                            return '0 B';
                                        }
                                        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                        $power = min(floor(log($bytes, 1024)), count($units) - 1);
                                        return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
                                    };
                                @endphp
                                <select name="storage_type" class="form-select form-control-custom"
                                    {{ !$s3Available ? 'disabled' : '' }}>
                                    <option value="local" {{ $currentStorageType === 'local' ? 'selected' : '' }}>
                                        Local Server Storage
                                    </option>
                                    <option value="s3"
                                        {{ !$s3Available || $storageSettings->avoid_s3 ? 'disabled' : '' }}
                                        {{ $currentStorageType === 's3' && $s3Available && !$storageSettings->avoid_s3 ? 'selected' : '' }}>
                                        AWS S3 Bucket
                                    </option>
                                </select>
                                @if (!$s3Available || $storageSettings->avoid_s3)
                                    <input type="hidden" name="storage_type" value="{{ $currentStorageType }}">
                                @endif
                                @if (!$s3Available)
                                    <small class="text-danger d-block mt-1">
                                        Provide S3 credentials and ensure limits are within Free Tier to enable S3 uploads.
                                    </small>
                                @endif
                                <div class="alert alert-light border mt-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">S3 Monthly Usage (Free Tier)</span>
                                        @if ($usage['force_local'])
                                            <span class="badge bg-danger">Limit Reached</span>
                                        @endif
                                    </div>
                                    <ul class="list-unstyled small mb-0 mt-2">
                                        <li>
                                            Storage: {{ $formatBytes($usage['storage_bytes']) }} /
                                            {{ $formatBytes($usage['limits']['storage_bytes']) }}
                                            @if ($usage['threshold_triggered']['storage'])
                                                <span class="text-danger fw-semibold ms-2">Near limit</span>
                                            @endif
                                        </li>
                                        <li>
                                            GET requests: {{ number_format($usage['get_requests']) }} /
                                            {{ number_format($usage['limits']['get_requests']) }}
                                            @if ($usage['threshold_triggered']['get'])
                                                <span class="text-danger fw-semibold ms-2">Near limit</span>
                                            @endif
                                        </li>
                                        <li>
                                            PUT/COPY/POST/LIST requests: {{ number_format($usage['put_requests']) }} /
                                            {{ number_format($usage['limits']['put_requests']) }}
                                            @if ($usage['threshold_triggered']['put'])
                                                <span class="text-danger fw-semibold ms-2">Near limit</span>
                                            @endif
                                        </li>
                                        <li>
                                            Data Transfer Out: {{ $formatBytes($usage['egress_bytes']) }} /
                                            {{ $formatBytes($usage['limits']['egress_bytes']) }}
                                            @if ($usage['threshold_triggered']['egress'])
                                                <span class="text-danger fw-semibold ms-2">Near limit</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Short Description *</label>
                        <input type="text" name="short_description" class="form-control form-control-custom"
                            maxlength="255" value="{{ old('short_description', $project->short_description) }}" required>
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
                    <div class="col-md-6 mb-4" id="imageFileSection"
                        style="display: {{ old('file_type', $project->file_type) == 'image' ? 'block' : 'none' }};">
                        <label class="form-label-custom">PNG/JPG Image File</label>
                        <div class="image-preview" style="height: 150px;" id="imagePreview"
                            onclick="document.getElementById('imageFile').click()">
                            @if ($project->image_url)
                                <img src="{{ $project->image_url }}" alt="{{ $project->title }}"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-file-earmark-image" style="font-size: 36px;"></i>
                                    <p class="mb-0 mt-2 small">Upload Image (PNG/JPG)</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="imageFile" name="image" class="d-none" accept="image/*">
                        @if ($project->image)
                            <small class="text-muted d-block mt-2">
                                Current file:
                                <a href="{{ route('projects.download', ['project' => $project->id, 'type' => 'image']) }}"
                                    target="_blank">Download existing image</a>
                            </small>
                        @endif
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Video Section -->
                    <div class="col-md-6 mb-4" id="videoFileSection"
                        style="display: {{ old('file_type', $project->file_type) == 'video' ? 'block' : 'none' }};">
                        <label class="form-label-custom">Video File or Video Link</label>
                        <div class="image-preview" style="height: 120px;" id="videoPreview"
                            onclick="document.getElementById('videoFile').click()">
                            @if ($project->video)
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-file-earmark-play text-danger me-3" style="font-size: 32px;"></i>
                                    <div class="text-start">
                                        <p class="mb-0 fw-semibold">{{ basename($project->video) }}</p>
                                        <small class="text-muted">Existing video file</small>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-file-earmark-play" style="font-size: 24px;"></i>
                                    <p class="mb-0 mt-1 small">Upload Video</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="videoFile" name="video" class="d-none" accept="video/*">
                        <small class="text-muted d-block mt-2">Supported formats: MP4, AVI, MOV, WMV (max 50 MB)</small>
                        <div class="mt-2">
                            <label class="form-label-custom small">Or Enter Video Link (YouTube Only)</label>
                            <input type="url" name="video_link" class="form-control form-control-custom"
                                placeholder="https://www.youtube.com/watch?v=..."
                                value="{{ old('video_link', $project->video_link) }}">
                            <small class="text-muted">Only YouTube links are supported. Formats: youtube.com/watch?v=... or
                                youtu.be/...</small>
                        </div>
                        @if ($project->video)
                            <small class="text-muted d-block mt-2">
                                Current file:
                                <a href="{{ route('projects.download', ['project' => $project->id, 'type' => 'video']) }}"
                                    target="_blank">Download existing video</a>
                            </small>
                        @endif
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
                        <div class="image-preview" style="height: 150px;" id="sourcePreview"
                            onclick="document.getElementById('sourceFile').click()">
                            @if ($project->source_file)
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-file-earmark-zip text-success me-3" style="font-size: 32px;"></i>
                                    <div class="text-start">
                                        <p class="mb-0 fw-semibold">{{ basename($project->source_file) }}</p>
                                        <small class="text-muted">Existing source file</small>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-file-earmark-zip" style="font-size: 36px;"></i>
                                    <p class="mb-0 mt-2 small">Upload Source File</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="sourceFile" name="source_file" class="d-none"
                            accept=".ai,.psd,.zip,.rar,.7z">
                        <small class="text-muted">Accepted: .ai, .psd, .zip, .rar, .7z</small>
                        @if ($project->source_file)
                            <small class="text-muted d-block mt-2">
                                Current file:
                                <a href="{{ route('projects.download', ['project' => $project->id, 'type' => 'source']) }}"
                                    target="_blank">Download existing source file</a>
                            </small>
                        @endif
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
    @include('admin.projects.partials.form-scripts')
@endpush
