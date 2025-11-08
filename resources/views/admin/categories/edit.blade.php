@extends('admin.layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

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
            <h5 class="mb-0 fw-bold">Edit Category Details</h5>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Category Name *</label>
                        <input type="text" name="name" class="form-control form-control-custom" 
                               placeholder="e.g., Print Design" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Slug</label>
                        <input type="text" name="slug" class="form-control form-control-custom" 
                               placeholder="Auto-generated from name" value="{{ old('slug', $category->slug) }}" id="slugInput">
                        <small class="text-muted">Leave empty to auto-generate from name</small>
                        @error('slug')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom d-flex justify-content-between align-items-center">
                            <span>Icon (Bootstrap Icon) *</span>
                            <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener" class="small text-decoration-none">
                                Browse Icons <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <span class="icon-preview rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i id="categoryIconPreview" class="bi {{ old('icon', $category->icon) }}"></i>
                            </span>
                            <input type="text" name="icon" class="form-control form-control-custom"
                                   placeholder="e.g., bi-printer" value="{{ old('icon', $category->icon) }}"
                                   list="bootstrap-icon-list" required>
                        </div>
                        <small class="text-muted d-block mt-2">Enter a Bootstrap Icons class name (e.g., bi-printer, bi-image, bi-palette)</small>
                        @error('icon')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Color *</label>
                        <div class="input-group">
                            <input type="color" name="color" class="form-control form-control-color" 
                                   value="{{ old('color', $category->color) }}" id="colorPicker">
                            <input type="text" class="form-control form-control-custom" 
                                   value="{{ old('color', $category->color) }}" id="colorInput" 
                                   placeholder="#00B894" maxlength="7">
                        </div>
                        <small class="text-muted">Choose a color for the category icon</small>
                        @error('color')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea name="description" class="form-control form-control-custom" rows="3" 
                                  placeholder="Category description (optional)">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                   id="isActive" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Active Status
                            </label>
                        </div>
                        <small class="text-muted">Inactive categories won't be displayed on the frontend</small>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .icon-preview {
        width: 48px;
        height: 48px;
        background: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
        font-size: 20px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-generate slug from name (only if name changed)
    const nameInput = document.querySelector('input[name="name"]');
    const originalName = nameInput.value;
    const slugInput = document.getElementById('slugInput');
    const originalSlug = slugInput.value;

    nameInput.addEventListener('input', function() {
        if (this.value !== originalName) {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            if (slugInput.value === originalSlug) {
                slugInput.value = slug;
            }
        }
    });

    // Sync color picker and text input
    document.getElementById('colorPicker').addEventListener('input', function() {
        document.getElementById('colorInput').value = this.value;
    });

    document.getElementById('colorInput').addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            document.getElementById('colorPicker').value = this.value;
        }
    });

    const iconInput = document.querySelector('input[name="icon"]');
    const iconPreview = document.getElementById('categoryIconPreview');
    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function () {
            iconPreview.className = `bi ${this.value.trim() || 'bi-grid'}`;
        });
    }
</script>
<datalist id="bootstrap-icon-list">
    <option value="bi-brush"></option>
    <option value="bi-images"></option>
    <option value="bi-grid"></option>
    <option value="bi-palette"></option>
    <option value="bi-vector-pen"></option>
    <option value="bi-camera"></option>
    <option value="bi-printer"></option>
    <option value="bi-droplet"></option>
    <option value="bi-badge-hd"></option>
    <option value="bi-easel"></option>
</datalist>
@endpush
