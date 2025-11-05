@extends('admin.layouts.admin')

@section('title', 'Contact Information')
@section('page-title', 'Contact Information')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Edit Contact Information</h5>
        </div>
        <div class="card-body-custom">
            <form>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Phone Number</label>
                        <input type="tel" class="form-control form-control-custom" value="+1 (555) 123-4567">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Email Address</label>
                        <input type="email" class="form-control form-control-custom" value="hello@graphicportfolio.com">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label-custom">Physical Address</label>
                        <textarea class="form-control form-control-custom" rows="3">123 Design Street, Creative City, CC 12345</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Alternative Email</label>
                        <input type="email" class="form-control form-control-custom" value="support@graphicportfolio.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Website URL</label>
                        <input type="url" class="form-control form-control-custom" value="https://graphicportfolio.com">
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


