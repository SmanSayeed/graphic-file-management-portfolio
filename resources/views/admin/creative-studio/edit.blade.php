@extends('admin.layouts.admin')

@section('title', 'Creative Studio')
@section('page-title', 'Creative Studio Section')

@section('content')
    <div class="content-card">
        <div class="card-header-custom d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0 fw-bold">About Creative Studio Content</h5>
                <small class="text-muted">Manage the homepage About Creative Studio section here.</small>
            </div>
        </div>

        <div class="card-body-custom">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong class="d-block mb-2">Please fix the errors below:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.creative-studio.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Section Title</label>
                            <input type="text" name="section_title" class="form-control form-control-custom"
                                   value="{{ old('section_title', $section->section_title) }}" placeholder="About Creative Studio">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Section Subtitle</label>
                            <input type="text" name="section_subtitle" class="form-control form-control-custom"
                                   value="{{ old('section_subtitle', $section->section_subtitle) }}"
                                   placeholder="Crafting exceptional designs...">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Headline</label>
                            <input type="text" name="profile_name" class="form-control form-control-custom"
                                   value="{{ old('profile_name', $section->profile_name) }}" placeholder="Hi, I'm Jane Doe">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Role / Tagline</label>
                            <input type="text" name="profile_role" class="form-control form-control-custom"
                                   value="{{ old('profile_role', $section->profile_role) }}"
                                   placeholder="Creative Graphic Designer & Brand Strategist">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label-custom">Description <span class="text-muted">(Rich Text)</span></label>
                            <textarea name="description" id="creative-description" class="form-control form-control-custom" rows="6">{{ old('description', $section->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom d-flex justify-content-between align-items-center">
                                <span>Highlight One</span>
                                <span class="text-muted small">e.g., Brand Identity</span>
                            </label>
                            <input type="text" name="highlight_one" class="form-control form-control-custom"
                                   value="{{ old('highlight_one', $section->highlight_one) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom d-flex justify-content-between align-items-center">
                                <span>Highlight Two</span>
                                <span class="text-muted small">e.g., Logo Design</span>
                            </label>
                            <input type="text" name="highlight_two" class="form-control form-control-custom"
                                   value="{{ old('highlight_two', $section->highlight_two) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom d-flex justify-content-between align-items-center">
                                <span>Highlight Three</span>
                                <span class="text-muted small">e.g., UI/UX Design</span>
                            </label>
                            <input type="text" name="highlight_three" class="form-control form-control-custom"
                                   value="{{ old('highlight_three', $section->highlight_three) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom d-flex justify-content-between align-items-center">
                                <span>Highlight Four</span>
                                <span class="text-muted small">e.g., Print Design</span>
                            </label>
                            <input type="text" name="highlight_four" class="form-control form-control-custom"
                                   value="{{ old('highlight_four', $section->highlight_four) }}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Call To Action Text</label>
                            <input type="text" name="cta_text" class="form-control form-control-custom"
                                   value="{{ old('cta_text', $section->cta_text) }}" placeholder="Hire Me">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Call To Action Link</label>
                            <input type="text" name="cta_link" class="form-control form-control-custom"
                                   value="{{ old('cta_link', $section->cta_link) }}" placeholder="#contact">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Section Image</label>
                            <input type="file" name="image" class="form-control form-control-custom">
                            <small class="text-muted d-block mt-2">Recommended size: 800x600px or higher. JPG, PNG, WEBP, AVIF up to 2MB.</small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        @if ($section->image_path)
                            <div class="current-image-preview">
                                <label class="form-label-custom d-block">Current Image Preview</label>
                                <img src="{{ asset('storage/' . $section->image_path) }}" alt="Creative Studio Image"
                                     class="img-fluid rounded shadow-sm" style="max-height: 220px; object-fit: cover;">
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-image me-2"></i>
                                <span>No image uploaded yet.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 220px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const descriptionField = document.querySelector('#creative-description');

            if (descriptionField) {
                ClassicEditor
                    .create(descriptionField, {
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                            'blockQuote', 'insertTable', 'undo', 'redo'
                        ]
                    })
                    .catch(error => console.error(error));
            }
        });
    </script>
@endpush

