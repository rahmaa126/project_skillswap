@extends('layouts.admin')

@section('title', 'Skill Battles | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Skill Battles Management</h2>
    <p class="text-secondary small mb-0">Competitive skill assessments, questions, and scores</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Battles</li>
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
          <span class="text-secondary small text-uppercase fw-semibold">Total Battles</span>
          <h3 class="fw-bold mb-0 text-danger">{{ $totalBattles }}</h3>
        </div>
        <div class="bg-danger-subtle text-danger rounded-circle p-3">
          <i class="bi bi-lightning-charge fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Finished</span>
          <h3 class="fw-bold mb-0 text-success">{{ $finishedBattles }}</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-trophy fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-warning border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">In Progress</span>
          <h3 class="fw-bold mb-0 text-warning">{{ $ongoingBattles }}</h3>
        </div>
        <div class="bg-warning-subtle text-warning rounded-circle p-3">
          <i class="bi bi-controller fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.battles.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-8">
        <select name="status" class="form-select">
          <option value="">All Battle Statuses</option>
          <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>Waiting for Player</option>
          <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
          <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>Finished</option>
          <option value="abandoned" {{ request('status') === 'abandoned' ? 'selected' : '' }}>Abandoned</option>
        </select>
      </div>
      <div class="col-12 col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
        @if(request()->filled('status'))
          <a href="{{ route('admin.battles.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
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
            <th>Skill Tested</th>
            <th>Player 1 (Challenger)</th>
            <th>Player 2 (Opponent)</th>
            <th>Winner</th>
            <th>Status</th>
            <th>Started / Finished</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($battles as $battle)
          <tr>
            <td class="ps-3 fw-bold text-secondary">#{{ $battle->id }}</td>
            <td>
              <div class="fw-bold">{{ $battle->skill->name ?? 'Skill #'.$battle->skill_id }}</div>
              <small class="text-secondary">{{ $battle->skill->category?->name ?? 'General' }}</small>
            </td>
            <td>
              <div class="fw-semibold">{{ $battle->player1->name ?? 'Player 1' }}</div>
            </td>
            <td>
              <div class="fw-semibold">{{ $battle->player2->name ?? 'Waiting for opponent...' }}</div>
            </td>
            <td>
              @if($battle->winner)
                <span class="badge text-bg-success">
                  <i class="bi bi-trophy-fill me-1"></i>{{ $battle->winner->name }}
                </span>
              @else
                <span class="text-secondary small fst-italic">None / Draw</span>
              @endif
            </td>
            <td>
              @php
                $battleStatusColor = match(strtolower($battle->status)) {
                  'finished' => 'text-bg-success',
                  'ongoing' => 'text-bg-warning',
                  'waiting' => 'text-bg-info',
                  'abandoned' => 'text-bg-danger',
                  default => 'text-bg-secondary'
                };
              @endphp
              <span class="badge {{ $battleStatusColor }} text-uppercase">{{ $battle->status }}</span>
            </td>
            <td class="small text-secondary">
              <div>{{ $battle->started_at ? $battle->started_at->format('M d, H:i') : '-' }}</div>
              @if($battle->finished_at)
                <div class="text-success">{{ $battle->finished_at->format('M d, H:i') }}</div>
              @endif
            </td>
            <td class="text-end pe-3">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.battles.show', $battle) }}" class="btn btn-outline-info" title="View battle">
                  <i class="bi bi-eye"></i>
                </a>
                <form action="{{ route('admin.battles.destroy', $battle) }}" method="POST" onsubmit="return confirm('Delete battle #{{ $battle->id }}?')" class="d-inline">
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
            <td colspan="8" class="text-center text-secondary py-4">No battles found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($battles->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $battles->links() }}
  </div>
  @endif
</div>
@endsection
