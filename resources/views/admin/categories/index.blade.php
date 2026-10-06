@extends('layouts.admin')

@section('title', 'Skill Categories | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Skill Categories</h2>
    <p class="text-secondary small mb-0">Manage classifications for skills exchange</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Categories</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row g-4">
  <!-- Create Category Card -->
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-plus-circle me-1 text-primary"></i> Add New Category</h5>
      </div>
      <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Mobile Development, Design..." required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
        <div class="card-footer bg-transparent border-top text-end">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Category</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Categories List -->
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-grid me-1 text-info"></i> All Categories ({{ $categories->count() }})</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-3">#</th>
                <th>Category Name</th>
                <th>Skills Count</th>
                <th class="text-end pe-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($categories as $category)
              <tr>
                <td class="ps-3 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                <td class="fw-bold text-body">
                  <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded p-2 me-2">
                      <i class="bi bi-tag-fill"></i>
                    </div>
                    <span>{{ $category->name }}</span>
                  </div>
                </td>
                <td>
                  <span class="badge text-bg-info rounded-pill px-3 py-1">
                    {{ $category->skills_count }} skills
                  </span>
                </td>
                <td class="text-end pe-3">
                  <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete category {{ $category->name }}?')" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>

                  <!-- Edit Modal -->
                  <div class="modal fade text-start" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="editModalLabel{{ $category->id }}">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <label class="form-label fw-semibold">Category Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-secondary py-4">No categories created yet.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
