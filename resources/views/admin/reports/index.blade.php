@extends('layouts.admin')

@section('title', 'Community Reports & Disputes | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Reports & Disputes</h2>
    <p class="text-secondary small mb-0">Review reported users, complaints, and moderation actions</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Reports</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Metric Cards -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-danger border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Total Reports</span>
          <h3 class="fw-bold mb-0 text-danger">{{ $totalReports }}</h3>
        </div>
        <div class="bg-danger-subtle text-danger rounded-circle p-3">
          <i class="bi bi-flag fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-warning border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Pending Review</span>
          <h3 class="fw-bold mb-0 text-warning">{{ $pendingReports }}</h3>
        </div>
        <div class="bg-warning-subtle text-warning rounded-circle p-3">
          <i class="bi bi-clock-history fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Resolved</span>
          <h3 class="fw-bold mb-0 text-success">{{ $resolvedReports }}</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-shield-check fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-8">
        <select name="status" class="form-select">
          <option value="">All Report Statuses</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="investigating" {{ request('status') === 'investigating' ? 'selected' : '' }}>Investigating</option>
          <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
          <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
        </select>
      </div>
      <div class="col-12 col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
        @if(request()->filled('status'))
          <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-3">ID</th>
            <th>Reporter</th>
            <th>Reported User</th>
            <th>Reason / Complaint</th>
            <th>Status</th>
            <th>Handled By</th>
            <th>Reported Date</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reports as $report)
          <tr>
            <td class="ps-3 fw-bold text-secondary">#{{ $report->id }}</td>
            <td class="fw-semibold">
              {{ $report->reporter->name ?? 'User #'.$report->reporter_id }}
            </td>
            <td>
              <span class="fw-semibold text-danger">
                {{ $report->reported->name ?? 'User #'.$report->reported_id }}
              </span>
            </td>
            <td>
              <div class="small">{{ $report->reason }}</div>
            </td>
            <td>
              @php
                $repColor = match(strtolower($report->status)) {
                  'resolved' => 'text-bg-success',
                  'investigating' => 'text-bg-warning',
                  'pending' => 'text-bg-danger',
                  'dismissed' => 'text-bg-secondary',
                  default => 'text-bg-light'
                };
              @endphp
              <div class="dropdown d-inline">
                <button class="btn btn-sm badge {{ $repColor }} dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                  {{ strtoupper($report->status) }}
                </button>
                <ul class="dropdown-menu">
                  @foreach(['pending', 'investigating', 'resolved', 'dismissed'] as $rst)
                    <li>
                      <form action="{{ route('admin.reports.update-status', $report) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $rst }}">
                        <button type="submit" class="dropdown-item small text-capitalize {{ $report->status === $rst ? 'active' : '' }}">
                          Mark as {{ $rst }}
                        </button>
                      </form>
                    </li>
                  @endforeach
                </ul>
              </div>
            </td>
            <td>
              <small class="text-secondary">{{ $report->handler->name ?? 'Unassigned' }}</small>
            </td>
            <td class="small text-secondary">
              {{ $report->created_at ? $report->created_at->format('M d, Y') : '-' }}
            </td>
            <td class="text-end pe-3">
              <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Delete this report?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-secondary py-4">No reports recorded.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($reports->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $reports->links() }}
  </div>
  @endif
</div>
@endsection
