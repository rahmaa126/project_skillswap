@extends('layouts.admin')

@section('title', 'Swap Sessions | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Swap Sessions Management</h2>
    <p class="text-secondary small mb-0">Monitor peer-to-peer learning sessions, schedules, and statuses</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Swap Sessions</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Metric Cards -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-warning border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Total Sessions</span>
          <h3 class="fw-bold mb-0 text-warning">{{ $totalSessions }}</h3>
        </div>
        <div class="bg-warning-subtle text-warning rounded-circle p-3">
          <i class="bi bi-arrow-left-right fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Completed</span>
          <h3 class="fw-bold mb-0 text-success">{{ $completedSessions }}</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-check-all fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-primary border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Ongoing / Active</span>
          <h3 class="fw-bold mb-0 text-primary">{{ $ongoingSessions }}</h3>
        </div>
        <div class="bg-primary-subtle text-primary rounded-circle p-3">
          <i class="bi bi-play-circle fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-info border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Pending Requests</span>
          <h3 class="fw-bold mb-0 text-info">{{ $pendingSessions }}</h3>
        </div>
        <div class="bg-info-subtle text-info rounded-circle p-3">
          <i class="bi bi-hourglass-split fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.swap-sessions.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-body-secondary border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control border-start-0" placeholder="Search by participant name..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-6 col-md-4">
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
          <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
      </div>
      <div class="col-6 col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
        @if(request()->anyFilled(['search', 'status']))
          <a href="{{ route('admin.swap-sessions.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
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
            <th>Requester (Teaches)</th>
            <th>Partner (Teaches)</th>
            <th>Scheduled / Started</th>
            <th>Status</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($sessions as $session)
          <tr>
            <td class="ps-3 fw-bold text-secondary">#{{ $session->id }}</td>
            <td>
              <div class="fw-semibold text-body">{{ $session->requester->name ?? 'User #'.$session->requester_id }}</div>
              <small class="badge bg-primary-subtle text-primary">
                <i class="bi bi-stars me-1"></i>{{ $session->requesterSkill->name ?? 'Skill #'.$session->requester_skill_id }}
              </small>
            </td>
            <td>
              <div class="fw-semibold text-body">{{ $session->partner->name ?? 'User #'.$session->partner_id }}</div>
              <small class="badge bg-success-subtle text-success">
                <i class="bi bi-stars me-1"></i>{{ $session->partnerSkill->name ?? 'Skill #'.$session->partner_skill_id }}
              </small>
            </td>
            <td class="small">
              <div><i class="bi bi-calendar3 me-1 text-muted"></i>{{ $session->scheduled_at ? $session->scheduled_at->format('M d, Y H:i') : 'Not scheduled' }}</div>
              @if($session->completed_at)
                <div class="text-success"><i class="bi bi-check2-circle me-1"></i>Done {{ $session->completed_at->format('M d, H:i') }}</div>
              @endif
            </td>
            <td>
              @php
                $statusColor = match(strtolower($session->status)) {
                  'completed' => 'text-bg-success',
                  'ongoing' => 'text-bg-primary',
                  'accepted' => 'text-bg-info',
                  'pending' => 'text-bg-warning',
                  'cancelled' => 'text-bg-danger',
                  default => 'text-bg-secondary'
                };
              @endphp
              <div class="dropdown d-inline">
                <button class="btn btn-sm badge {{ $statusColor }} dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ strtoupper($session->status) }}
                </button>
                <ul class="dropdown-menu">
                  @foreach(['pending', 'accepted', 'ongoing', 'completed', 'cancelled'] as $st)
                    <li>
                      <form action="{{ route('admin.swap-sessions.update-status', $session) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $st }}">
                        <button type="submit" class="dropdown-item small text-capitalize {{ $session->status === $st ? 'active' : '' }}">
                          Mark as {{ $st }}
                        </button>
                      </form>
                    </li>
                  @endforeach
                </ul>
              </div>
            </td>
            <td class="text-end pe-3">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.swap-sessions.show', $session) }}" class="btn btn-outline-info" title="View details">
                  <i class="bi bi-eye"></i>
                </a>
                <form action="{{ route('admin.swap-sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('Delete swap session #{{ $session->id }}?')" class="d-inline">
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
            <td colspan="6" class="text-center text-secondary py-4">No swap sessions recorded yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($sessions->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $sessions->links() }}
  </div>
  @endif
</div>
@endsection
