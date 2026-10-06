@extends('layouts.admin')

@section('title', 'Add New Skill | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Add New Skill</h2>
    <p class="text-secondary small mb-0">Register a new skill into the platform catalog</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.skills.index') }}" class="text-decoration-none">Skills</a></li>
      <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-stars me-2 text-primary"></i>Skill Information</h5>
      </div>
      <form action="{{ route('admin.skills.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12 col-md-8">
              <label class="form-label fw-semibold">Skill Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Flutter Development, UI/UX Design..." value="{{ old('name') }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Minimum Score for Matching (pts) <span class="text-danger">*</span></label>
              <input type="number" name="min_score" class="form-control @error('min_score') is-invalid @enderror" min="0" max="100" value="{{ old('min_score', 70) }}" required>
              <small class="text-secondary">Minimum score required for users to be matched or participate in battles.</small>
              @error('min_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Initial Status <span class="text-danger">*</span></label>
              <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active (Available immediately)</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive / Draft</option>
              </select>
              @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Brief explanation of skill scope, topics covered, and prerequisites...">{{ old('description') }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent border-top d-flex justify-content-between p-3">
          <a href="{{ route('admin.skills.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Create Skill</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
