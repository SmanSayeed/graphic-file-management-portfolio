@extends('admin.layouts.admin')

@section('title', 'Footer Content')
@section('page-title', 'Footer Content Management')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Edit Footer Content</h5>
        </div>
        <div class="card-body-custom">
            <form>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label-custom">About Text</label>
                        <textarea class="form-control form-control-custom" rows="3">Creating stunning designs that captivate and inspire. Professional graphic design services for businesses and individuals worldwide.</textarea>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label-custom">Services (One per line)</label>
                        <textarea class="form-control form-control-custom" rows="5" placeholder="Enter services, one per line">Logo Design
Brand Identity
Social Media Graphics
Print Design
UI/UX Design</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Copyright Text</label>
                        <input type="text" class="form-control form-control-custom"
                            value="© 2025 Graphic Portfolio. All rights reserved.">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Privacy Policy URL</label>
                        <input type="url" class="form-control form-control-custom"
                            placeholder="https://yoursite.com/privacy">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Terms of Service URL</label>
                        <input type="url" class="form-control form-control-custom"
                            placeholder="https://yoursite.com/terms">
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
