@extends('layouts.admin')

@section('title', 'Users Management | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Users Management</h2>
    <p class="text-secondary small mb-0">Manage registered members, administrators, and profile statuses</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Metric Cards -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-primary border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Total Users</span>
          <h3 class="fw-bold mb-0 text-primary">{{ $totalUsers }}</h3>
        </div>
        <div class="bg-primary-subtle text-primary rounded-circle p-3">
          <i class="bi bi-people fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Active Members</span>
          <h3 class="fw-bold mb-0 text-success">{{ $activeCount }}</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-check2-circle fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-warning border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Admins</span>
          <h3 class="fw-bold mb-0 text-warning">{{ $adminCount }}</h3>
        </div>
        <div class="bg-warning-subtle text-warning rounded-circle p-3">
          <i class="bi bi-shield-lock fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filter & Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-body-secondary border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or email..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-6 col-md-3">
        <select name="role" class="form-select">
          <option value="">All Roles</option>
          <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
          <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
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
        @if(request()->anyFilled(['search', 'role', 'status']))
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-3">User</th>
            <th>Role</th>
            <th>City / Phone</th>
            <th>Avg Rating</th>
            <th>Total Swaps</th>
            <th>Status</th>
            <th>Registered</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr>
            <td class="ps-3">
              <div class="d-flex align-items-center">
                <img
                  src="{{ $user->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($user->id % 4) + 1).'.png') }}"
                  class="rounded-circle me-3 border"
                  alt="{{ $user->name }}"
                  width="40"
                  height="40"
                />
                <div>
                  <div class="fw-bold">{{ $user->name }}</div>
                  <small class="text-secondary">{{ $user->email }}</small>
                </div>
              </div>
            </td>
            <td>
              <span class="badge {{ $user->role === 'admin' ? 'text-bg-danger' : 'text-bg-primary' }} text-uppercase">
                {{ $user->role }}
              </span>
            </td>
            <td>
              <div class="small">
                <div><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $user->profile?->city ?? 'N/A' }}</div>
                <div class="text-secondary"><i class="bi bi-telephone me-1 text-muted"></i>{{ $user->profile?->phone ?? 'N/A' }}</div>
              </div>
            </td>
            <td>
              <span class="badge bg-warning-subtle text-warning-emphasis">
                <i class="bi bi-star-fill text-warning me-1"></i>{{ $user->profile?->avg_rating ?? '0.00' }}
              </span>
            </td>
            <td>
              <span class="badge text-bg-secondary">{{ $user->profile?->total_swaps ?? 0 }}</span>
            </td>
            <td>
              <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm badge {{ $user->is_active ? 'text-bg-success' : 'text-bg-danger' }} border-0" title="Click to toggle status">
                  {{ $user->is_active ? 'Active' : 'Inactive' }}
                </button>
              </form>
            </td>
            <td class="text-secondary small">
              {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
            </td>
            <td class="text-end pe-3">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info" title="View details">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary" title="Edit user">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete user {{ $user->name }}?')" class="d-inline">
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
            <td colspan="8" class="text-center text-secondary py-4">No users found matching current filters.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($users->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $users->links() }}
  </div>
  @endif
</div>
@endsection
