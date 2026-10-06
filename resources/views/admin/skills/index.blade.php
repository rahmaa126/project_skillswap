@extends('layouts.admin')

@section('title', 'Skills Catalog | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Skills Management</h2>
    <p class="text-secondary small mb-0">Platform skills inventory, requirements, and categories</p>
  </div>
  <div class="col-sm-6">
    <div class="float-sm-end d-flex gap-2">
      <a href="{{ route('admin.skills.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add New Skill
      </a>
    </div>
  </div>
</div>
@endsection

@section('content')
<!-- Metric Cards -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Total Skills</span>
          <h3 class="fw-bold mb-0 text-success">{{ $totalSkills }}</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-stars fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-primary border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Active Skills</span>
          <h3 class="fw-bold mb-0 text-primary">{{ $activeSkills }}</h3>
        </div>
        <div class="bg-primary-subtle text-primary rounded-circle p-3">
          <i class="bi bi-check-circle fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-info border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Categories</span>
          <h3 class="fw-bold mb-0 text-info">{{ $categories->count() }}</h3>
        </div>
        <div class="bg-info-subtle text-info rounded-circle p-3">
          <i class="bi bi-grid fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-secondary border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Disabled</span>
          <h3 class="fw-bold mb-0 text-secondary">{{ $totalSkills - $activeSkills }}</h3>
        </div>
        <div class="bg-secondary-subtle text-secondary rounded-circle p-3">
          <i class="bi bi-pause-circle fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.skills.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-body-secondary border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control border-start-0" placeholder="Search skill name..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-6 col-md-3">
        <select name="category_id" class="form-select">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">All Status</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <div class="col-12 col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
        @if(request()->anyFilled(['search', 'category_id', 'status']))
          <a href="{{ route('admin.skills.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-3">Skill Name</th>
            <th>Category</th>
            <th>Min Score</th>
            <th>Members Enrolled</th>
            <th>Status</th>
            <th>Added On</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($skills as $skill)
          <tr>
            <td class="ps-3">
              <div class="fw-bold">{{ $skill->name }}</div>
              <small class="text-secondary">{{ Str::limit($skill->description, 50) ?: 'No description provided.' }}</small>
            </td>
            <td>
              <span class="badge text-bg-light border">
                {{ $skill->category?->name ?? 'Uncategorized' }}
              </span>
            </td>
            <td>
              <span class="badge bg-warning-subtle text-warning-emphasis">
                <i class="bi bi-shield-check me-1"></i>{{ $skill->min_score }} pts
              </span>
            </td>
            <td>
              <span class="badge text-bg-secondary">
                <i class="bi bi-people me-1"></i>{{ $skill->user_skills_count ?? 0 }}
              </span>
            </td>
            <td>
              <form action="{{ route('admin.skills.toggle-status', $skill) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm badge {{ $skill->is_active ? 'text-bg-success' : 'text-bg-danger' }} border-0" title="Click to toggle status">
                  {{ $skill->is_active ? 'Active' : 'Inactive' }}
                </button>
              </form>
            </td>
            <td class="text-secondary small">
              {{ $skill->created_at ? $skill->created_at->format('M d, Y') : '-' }}
            </td>
            <td class="text-end pe-3">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.skills.edit', $skill) }}" class="btn btn-outline-primary" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Delete skill {{ $skill->name }}?')" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-secondary py-4">No skills found matching current filter.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($skills->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $skills->links() }}
  </div>
  @endif
</div>
@endsection
