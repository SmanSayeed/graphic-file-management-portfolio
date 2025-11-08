@extends('admin.layouts.admin')

@section('title', 'Skills Management')
@section('page-title', 'Skills Management')

@section('content')
    <div class="content-card mb-4">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Add New Skill</h5>
                <small class="text-muted">Create a new skill to display on the homepage.</small>
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

            <form action="{{ route('admin.skills.store') }}" method="POST" class="skill-form" novalidate>
                @csrf
                <div class="row g-4">
                    <div class="col-lg-4">
                        <label class="form-label-custom">Skill Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-custom"
                               value="{{ old('name') }}" placeholder="e.g., Adobe Illustrator" required>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label-custom">Icon Class <span class="text-danger">*</span></label>
                        <input type="text" name="icon" class="form-control form-control-custom"
                               value="{{ old('icon') }}" placeholder="e.g., bi-brush" required>
                        <small class="text-muted d-block mt-1">Use Bootstrap Icons class name (e.g., bi-brush).</small>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label-custom">Proficiency (%) <span class="text-danger">*</span></label>
                        <input type="number" name="percentage" class="form-control form-control-custom"
                               value="{{ old('percentage', 90) }}" min="0" max="100" required>
                    </div>
                    <div class="col-lg-8">
                        <label class="form-label-custom">Short Description</label>
                        <input type="text" name="description" class="form-control form-control-custom"
                               value="{{ old('description') }}" placeholder="Vector graphics & logo design">
                    </div>
                    <div class="col-lg-4 d-flex align-items-end">
                        <label class="toggle-switch mb-0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                            <span class="toggle-label ms-3">Display on website</span>
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-plus-circle me-2"></i>Save Skill
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Existing Skills</h5>
            <span class="badge bg-dark">{{ $skills->count() }} total</span>
        </div>
        <div class="card-body-custom">
            @if ($skills->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="bi bi-lightbulb fs-1 text-muted mb-3"></i>
                    <h5 class="fw-bold">No skills added yet</h5>
                    <p class="text-muted mb-0">Use the form above to create your first skill.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($skills as $skill)
                        <div class="col-lg-3 col-md-4">
                            <div class="skill-card p-3 border rounded-3 h-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="skill-icon-wrapper">
                                            <i class="bi {{ $skill->icon }}" aria-hidden="true"></i>
                                        </div>
                                        <h6 class="mt-3 fw-bold">{{ $skill->name }}</h6>
                                    </div>
                                    <span class="badge {{ $skill->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $skill->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                </div>

                                @if ($skill->description)
                                    <p class="text-muted small mb-3 mt-2">{{ $skill->description }}</p>
                                @endif

                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $skill->percentage }}%;"
                                         aria-valuenow="{{ $skill->percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted small">Proficiency</span>
                                    <strong>{{ $skill->percentage }}%</strong>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.skills.edit', $skill) }}"
                                       class="btn btn-sm btn-outline-primary flex-grow-1 custom-action-btn">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST"
                                          class="delete-skill-form flex-grow-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 custom-action-btn">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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
        .skill-icon-wrapper {
            display: inline-flex;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.12), rgba(111, 66, 193, 0.12));
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #0d6efd;
        }
        .custom-action-btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .custom-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-skill-form');

            deleteForms.forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const confirmed = confirm('Are you sure you want to delete this skill? This action cannot be undone.');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
