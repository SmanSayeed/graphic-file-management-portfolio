@extends('admin.layouts.admin')

@section('title', 'Social Links')
@section('page-title', 'Social Links Management')

@section('content')
    <div class="content-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Edit Social Media Links</h5>
        </div>
        <div class="card-body-custom">
            <form>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-twitter text-info me-2"></i>Twitter URL
                        </label>
                        <input type="url" class="form-control form-control-custom" value="https://twitter.com/yourhandle"
                            placeholder="https://twitter.com/yourhandle">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-instagram text-danger me-2"></i>Instagram URL
                        </label>
                        <input type="url" class="form-control form-control-custom"
                            value="https://instagram.com/yourhandle" placeholder="https://instagram.com/yourhandle">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-linkedin text-primary me-2"></i>LinkedIn URL
                        </label>
                        <input type="url" class="form-control form-control-custom"
                            value="https://linkedin.com/in/yourprofile" placeholder="https://linkedin.com/in/yourprofile">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-dribbble text-danger me-2"></i>Dribbble URL
                        </label>
                        <input type="url" class="form-control form-control-custom"
                            value="https://dribbble.com/yourprofile" placeholder="https://dribbble.com/yourprofile">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-behance text-primary me-2"></i>Behance URL
                        </label>
                        <input type="url" class="form-control form-control-custom"
                            value="https://behance.net/yourprofile" placeholder="https://behance.net/yourprofile">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-github text-dark me-2"></i>GitHub URL
                        </label>
                        <input type="url" class="form-control form-control-custom"
                            value="https://github.com/yourusername" placeholder="https://github.com/yourusername">
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


