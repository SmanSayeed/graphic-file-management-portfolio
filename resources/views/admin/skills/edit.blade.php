@extends('admin.layouts.admin')

@section('title', 'Edit Skill')
@section('page-title', 'Edit Skill')

@section('content')
    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Update Skill</h5>
                <small class="text-muted">Make changes to the selected skill below.</small>
            </div>
            <a href="{{ route('admin.skills.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Skills
            </a>
        </div>
        <div class="card-body-custom">
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

            <form action="{{ route('admin.skills.update', $skill) }}" method="POST" class="skill-form" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-lg-6">
                        <label class="form-label-custom">Skill Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-custom"
                               value="{{ old('name', $skill->name) }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label-custom">Icon Class <span class="text-danger">*</span></label>
                        <input type="text" name="icon" class="form-control form-control-custom"
                               value="{{ old('icon', $skill->icon) }}" placeholder="e.g., bi-brush" required>
                        <small class="text-muted d-block mt-1">Use Bootstrap Icons class name (e.g., bi-brush).</small>
                    </div>
                    <div class="col-lg-8">
                        <label class="form-label-custom">Short Description</label>
                        <input type="text" name="description" class="form-control form-control-custom"
                               value="{{ old('description', $skill->description) }}" placeholder="Vector graphics & logo design">
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label-custom">Proficiency (%) <span class="text-danger">*</span></label>
                        <input type="number" name="percentage" class="form-control form-control-custom"
                               value="{{ old('percentage', $skill->percentage) }}" min="0" max="100" required>
                    </div>
                    <div class="col-12">
                        <label class="toggle-switch mb-0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $skill->is_active) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                            <span class="toggle-label ms-3">Display on website</span>
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Update Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .toggle-switch {
            position: relative;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }
        .toggle-switch input {
            display: none;
        }
        .toggle-slider {
            position: relative;
            width: 46px;
            height: 24px;
            background-color: #d1d5db;
            border-radius: 999px;
            transition: background-color 0.3s ease;
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #fff;
            top: 2px;
            left: 2px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .toggle-switch input:checked + .toggle-slider {
            background-color: #0d6efd;
        }
        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(22px);
        }
        .toggle-label {
            font-weight: 500;
            color: #343a40;
        }
    </style>
@endpush

