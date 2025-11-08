@extends('admin.layouts.admin')

@section('title', 'Social Links')
@section('page-title', 'Social Links Management')

@section('content')
    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Edit Social Media Links</h5>
                <small class="text-muted">Toggle the platforms you want to display and provide their URLs.</small>
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

            <form action="{{ route('admin.social.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    @foreach ($platforms as $platform => $icon)
                        @php
                            $link = $socialLinks[$platform] ?? null;
                            $urlValue = old("links.$platform.url", $link?->url);
                            $isActive = old("links.$platform.is_active", $link?->is_active);
                            $label = ucfirst($platform);
                            $iconClass = $link?->icon ?? $icon;
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="social-card border rounded-3 h-100 p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="social-icon-preview me-3">
                                        <i class="bi {{ $iconClass }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $label }}</h6>
                                        <small class="text-muted text-uppercase">{{ $platform }}</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom small">Profile URL</label>
                                    <input type="url" name="links[{{ $platform }}][url]"
                                           class="form-control form-control-custom"
                                           value="{{ $urlValue }}"
                                           placeholder="https://{{ $platform }}.com/username">
                                </div>

                                <label class="toggle-switch mb-0">
                                    <input type="checkbox" name="links[{{ $platform }}][is_active]" value="1" {{ $isActive ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                    <span class="toggle-label ms-3">Display on website</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
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

@push('styles')
    <style>
        .social-card {
            background: #fff;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .social-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        }
        .social-icon-preview {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.12), rgba(111, 66, 193, 0.12));
            color: #0d6efd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
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
