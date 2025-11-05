@extends('admin.layouts.admin')

@section('title', 'Hero Sliders')
@section('page-title', 'Hero Sliders Management')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Slider
        </a>
    </div>

    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">All Hero Sliders ({{ $sliders->total() }})</h5>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sliders as $slider)
                            <tr>
                                <td><i class="bi bi-grip-vertical"></i> {{ $slider->sort_order }}</td>
                                <td>
                                    @if($slider->image)
                                        <img src="{{ asset('storage/' . $slider->image) }}" 
                                             class="rounded" 
                                             style="width: 100px; height: 60px; object-fit: cover;"
                                             alt="{{ $slider->title }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 100px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $slider->title }}</td>
                                <td>{{ $slider->subtitle ?? '-' }}</td>
                                <td>
                                    @if($slider->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.sliders.edit', $slider->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.sliders.destroy', $slider->id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this slider?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No sliders found. <a href="{{ route('admin.sliders.create') }}">Create your first slider</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sliders->hasPages())
                <div class="mt-3">
                    {{ $sliders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection


