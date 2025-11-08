@extends('admin.layouts.admin')

@section('title', 'Footer Content')
@section('page-title', 'Footer Content Management')

@section('content')
    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Edit Footer Content</h5>
                <small class="text-muted">Update the footer description and copyright notice.</small>
            </div>
        </div>
        <div class="card-body-custom">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong class="d-block mb-2">Please fix the errors below:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.footer.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label-custom">Footer Description</label>
                    <textarea name="description" class="form-control form-control-custom" rows="4"
                              placeholder="Short description shown in the footer">{{ old('description', $footer->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Copyright Text</label>
                    <input type="text" name="copyright_text" class="form-control form-control-custom"
                           value="{{ old('copyright_text', $footer->copyright_text) }}"
                           placeholder="© {{ date('Y') }} Graphic Portfolio. All rights reserved.">
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
