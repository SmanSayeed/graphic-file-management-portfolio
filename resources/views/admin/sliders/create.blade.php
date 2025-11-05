@extends('admin.layouts.admin')

@section('title', 'Add Hero Slider')
@section('page-title', 'Add New Hero Slider')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Slider Details</h5>
        </div>
        <div class="card-body-custom">
            <form>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom">Slider Image</label>
                        <div class="image-preview" onclick="document.getElementById('sliderImage').click()">
                            <div class="text-center text-muted">
                                <i class="bi bi-cloud-upload" style="font-size: 48px;"></i>
                                <p class="mb-0 mt-2">Click to upload image</p>
                                <small>Recommended: 1920x1080px</small>
                            </div>
                        </div>
                        <input type="file" id="sliderImage" class="d-none" accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label-custom">Sort Order</label>
                            <input type="number" class="form-control form-control-custom" value="1" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Status</label>
                            <select class="form-select form-control-custom">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Save Slider
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


