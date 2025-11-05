@extends('admin.layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">User Information</h5>
            <div>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-admin-primary me-2">
                    <i class="bi bi-pencil me-2"></i>Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Users
                </a>
            </div>
        </div>
        <div class="card-body-custom">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                 class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px; background: linear-gradient(135deg, #00B894 0%, #F5576C 100%);">
                                <span class="text-white" style="font-size: 48px; font-weight: bold;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold">{{ $user->name }}</h4>
                    @if($user->role === 'root_admin')
                        <span class="badge bg-danger">ROOT ADMIN</span>
                    @elseif($user->role === 'admin')
                        <span class="badge bg-warning">ADMIN</span>
                    @else
                        <span class="badge bg-secondary">USER</span>
                    @endif
                </div>

                <div class="col-md-9">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom text-muted">Email Address</label>
                            <p class="fw-semibold">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom text-muted">Status</label>
                            <p>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom text-muted">Created At</label>
                            <p class="fw-semibold">{{ $user->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom text-muted">Last Updated</label>
                            <p class="fw-semibold">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($user->projects->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label-custom text-muted">Total Projects</label>
                                <p class="fw-semibold">{{ $user->projects->count() }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-admin-primary">
                    <i class="bi bi-pencil me-2"></i>Edit User
                </a>
            </div>
        </div>
    </div>
@endsection

