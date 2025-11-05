@extends('admin.layouts.admin')

@section('title', 'Hero Sliders')
@section('page-title', 'Hero Sliders Management')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Slider
        </a>
    </div>

    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">All Hero Sliders</h5>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="bi bi-grip-vertical"></i> 1</td>
                            <td>
                                <img src="https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=100&h=60&fit=crop"
                                    class="rounded" style="width: 100px; height: 60px; object-fit: cover;">
                            </td>
                            <td class="fw-semibold">Creative Design Showcase</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-grip-vertical"></i> 2</td>
                            <td>
                                <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?w=100&h=60&fit=crop"
                                    class="rounded" style="width: 100px; height: 60px; object-fit: cover;">
                            </td>
                            <td class="fw-semibold">Logo Design Excellence</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


