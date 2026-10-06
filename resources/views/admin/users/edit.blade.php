@extends('layouts.admin')

@section('title', 'Edit User: '.$user->name.' | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Edit User</h2>
    <p class="text-secondary small mb-0">Modify user role, account status, and profile information</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none">Users</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit {{ $user->name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-person-gear me-2 text-primary"></i>User Account Details</h5>
      </div>
      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
              <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User (Regular Member)</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Full Platform Access)</option>
              </select>
              @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
              <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Inactive / Suspended</option>
              </select>
              @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">New Password <small class="text-secondary fw-normal">(leave blank to keep current password)</small></label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <hr class="my-2">
              <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-card-text me-1"></i> Profile Information</h6>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">City / Location</label>
              <input type="text" name="city" class="form-control" value="{{ old('city', $user->profile?->city) }}">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Phone Number</label>
              <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->profile?->phone) }}">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Bio</label>
              <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->profile?->bio) }}</textarea>
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent border-top d-flex justify-content-between p-3">
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
