@extends('layouts.admin')

@section('title', 'Swap Session #'.$swapSession->id.' | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Swap Session #{{ $swapSession->id }}</h2>
    <p class="text-secondary small mb-0">Exchange details, video calls, reviews, and logs</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.swap-sessions.index') }}" class="text-decoration-none">Swap Sessions</a></li>
      <li class="breadcrumb-item active" aria-current="page">Session #{{ $swapSession->id }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row g-4">
  <!-- Participants Comparison -->
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-people me-2 text-primary"></i>Session Participants & Skills</h5>
      </div>
      <div class="card-body">
        <div class="row g-3 text-center align-items-center">
          <!-- Requester -->
          <div class="col-12 col-md-5">
            <div class="p-3 border rounded-3 bg-body-secondary">
              <span class="badge text-bg-primary mb-2">Requester</span>
              <h5 class="fw-bold mb-1">{{ $swapSession->requester->name }}</h5>
              <p class="text-secondary small mb-2">{{ $swapSession->requester->email }}</p>
              <div class="p-2 bg-body rounded border">
                <small class="text-muted d-block">Teaches:</small>
                <span class="fw-bold text-primary">{{ $swapSession->requesterSkill->name ?? 'Skill #'.$swapSession->requester_skill_id }}</span>
              </div>
            </div>
          </div>

          <!-- Swap Icon -->
          <div class="col-12 col-md-2">
            <div class="bg-primary text-white rounded-circle p-3 d-inline-flex shadow">
              <i class="bi bi-arrow-left-right fs-4"></i>
            </div>
          </div>

          <!-- Partner -->
          <div class="col-12 col-md-5">
            <div class="p-3 border rounded-3 bg-body-secondary">
              <span class="badge text-bg-success mb-2">Partner</span>
              <h5 class="fw-bold mb-1">{{ $swapSession->partner->name }}</h5>
              <p class="text-secondary small mb-2">{{ $swapSession->partner->email }}</p>
              <div class="p-2 bg-body rounded border">
                <small class="text-muted d-block">Teaches:</small>
                <span class="fw-bold text-success">{{ $swapSession->partnerSkill->name ?? 'Skill #'.$swapSession->partner_skill_id }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Video Calls Card -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-camera-video me-2 text-info"></i>Video Call Sessions</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-3">Room ID</th>
                <th>Status</th>
                <th>Duration</th>
                <th>Started</th>
                <th>Ended</th>
              </tr>
            </thead>
            <tbody>
              @forelse($swapSession->videoCalls as $call)
              <tr>
                <td class="ps-3 fw-semibold">{{ $call->room_id ?? 'N/A' }}</td>
                <td><span class="badge text-bg-primary">{{ $call->status }}</span></td>
                <td>{{ $call->duration_minutes ? $call->duration_minutes.' mins' : '-' }}</td>
                <td class="small text-secondary">{{ $call->started_at ? $call->started_at->format('M d, H:i') : '-' }}</td>
                <td class="small text-secondary">{{ $call->ended_at ? $call->ended_at->format('M d, H:i') : '-' }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-secondary py-3">No video calls initiated for this session.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Session Metadata & Controls -->
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-info-circle me-2 text-warning"></i>Session Status</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.swap-sessions.update-status', $swapSession) }}" method="POST">
          @csrf
          @method('PATCH')
          <div class="mb-3">
            <label class="form-label fw-semibold">Current Status</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
              @foreach(['pending', 'accepted', 'ongoing', 'completed', 'cancelled'] as $st)
                <option value="{{ $st }}" {{ $swapSession->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
              @endforeach
            </select>
          </div>
        </form>

        <ul class="list-group list-group-flush small">
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Match ID</span>
            <span class="fw-semibold">#{{ $swapSession->match_id ?? 'Direct Request' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Scheduled Time</span>
            <span class="fw-semibold">{{ $swapSession->scheduled_at ? $swapSession->scheduled_at->format('M d, Y H:i') : 'Unscheduled' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Started At</span>
            <span class="fw-semibold">{{ $swapSession->started_at ? $swapSession->started_at->format('M d, Y H:i') : '-' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Completed At</span>
            <span class="fw-semibold">{{ $swapSession->completed_at ? $swapSession->completed_at->format('M d, Y H:i') : '-' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Created Date</span>
            <span class="fw-semibold">{{ $swapSession->created_at ? $swapSession->created_at->format('M d, Y H:i') : '-' }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
