@extends('admin.layouts.admin')

@section('title', 'Contact Information')
@section('page-title', 'Contact Information')

@section('content')
    <div class="content-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Edit Contact Information</h5>
                <small class="text-muted">Update the contact details displayed on the homepage.</small>
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

            <form action="{{ route('admin.contact.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Phone Number</label>
                        <input type="tel" name="phone" class="form-control form-control-custom"
                               value="{{ old('phone', $contact->phone) }}" placeholder="+1 (555) 123-4567">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-custom"
                               value="{{ old('email', $contact->email) }}" placeholder="hello@example.com">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label-custom">Physical Address</label>
                        <textarea name="address" class="form-control form-control-custom" rows="3"
                                  placeholder="123 Design Street, Creative City, CC 12345">{{ old('address', $contact->address) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Alternative Email</label>
                        <input type="email" name="alternative_email" class="form-control form-control-custom"
                               value="{{ old('alternative_email', $contact->alternative_email) }}" placeholder="support@example.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Website URL</label>
                        <input type="url" name="website" class="form-control form-control-custom"
                               value="{{ old('website', $contact->website) }}" placeholder="https://example.com">
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ url('/') }}#contact" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-up-right me-2"></i>View Section
                    </a>
                    <button type="submit" class="btn btn-admin-primary">
                        <i class="bi bi-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
