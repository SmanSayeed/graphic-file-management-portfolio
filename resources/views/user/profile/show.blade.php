@extends('layouts.premium')

@section('title', 'My Profile')
@section('description', 'View your profile information')

@section('content')
<section class="section-premium" style="padding-top: 120px; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Home
                    </a>
                    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Profile
                    </a>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="row align-items-center mb-4">
                            <div class="col-md-3 text-center mb-4 mb-md-0">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                         class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #00B894;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 150px; height: 150px; background: linear-gradient(135deg, #00B894 0%, #F5576C 100%);">
                                        <span class="text-white" style="font-size: 48px; font-weight: bold;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h2 class="mb-2">{{ $user->name }}</h2>
                                <p class="text-muted mb-3">
                                    <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                                </p>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="badge bg-info ms-2">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Member Since</h6>
                                <p class="mb-0">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Last Updated</h6>
                                <p class="mb-0">{{ $user->updated_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

