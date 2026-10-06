@extends('layouts.admin')

@section('title', 'Skill Matches | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Skill Matches</h2>
    <p class="text-secondary small mb-0">AI-driven and manual pairings between teachers and learners</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Matches</li>
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
          <span class="text-secondary small text-uppercase fw-semibold">Total Matches</span>
          <h3 class="fw-bold mb-0 text-primary">{{ $totalMatches }}</h3>
        </div>
        <div class="bg-primary-subtle text-primary rounded-circle p-3">
          <i class="bi bi-intersect fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Accepted Pairs</span>
          <h3 class="fw-bold mb-0 text-success">{{ $acceptedMatches }}</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-hand-thumbs-up fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-info border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">AI Suggested</span>
          <h3 class="fw-bold mb-0 text-info">{{ $suggestedMatches }}</h3>
        </div>
        <div class="bg-info-subtle text-info rounded-circle p-3">
          <i class="bi bi-cpu fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.matches.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-5">
        <select name="status" class="form-select">
          <option value="">All Match Statuses</option>
          <option value="suggested" {{ request('status') === 'suggested' ? 'selected' : '' }}>Suggested</option>
          <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
          <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
          <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
      </div>
      <div class="col-12 col-md-4">
        <select name="source" class="form-select">
          <option value="">All Match Sources</option>
          <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>Manual Pairing</option>
          <option value="ai_knn" {{ request('source') === 'ai_knn' ? 'selected' : '' }}>AI (k-NN Embedding)</option>
          <option value="ai_llm" {{ request('source') === 'ai_llm' ? 'selected' : '' }}>AI (LLM Reasoning)</option>
        </select>
      </div>
      <div class="col-12 col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
        @if(request()->anyFilled(['status', 'source']))
          <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
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
            <th>Member A (Offers)</th>
            <th>Member B (Offers)</th>
            <th>Match Score</th>
            <th>Source / AI Reason</th>
            <th>Status</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($matches as $match)
          <tr>
            <td class="ps-3 fw-bold text-secondary">#{{ $match->id }}</td>
            <td>
              <div class="fw-semibold text-body">{{ $match->userA->name ?? 'User #'.$match->user_a_id }}</div>
              <small class="badge bg-primary-subtle text-primary">{{ $match->skillA->name ?? 'Skill #'.$match->skill_a_id }}</small>
            </td>
            <td>
              <div class="fw-semibold text-body">{{ $match->userB->name ?? 'User #'.$match->user_b_id }}</div>
              <small class="badge bg-success-subtle text-success">{{ $match->skillB->name ?? 'Skill #'.$match->skill_b_id }}</small>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <span class="fw-bold me-2">{{ number_format($match->match_score, 0) }}%</span>
                <div class="progress flex-grow-1" style="height: 6px; width: 70px;">
                  <div class="progress-bar bg-success" style="width: {{ $match->match_score }}%"></div>
                </div>
              </div>
            </td>
            <td>
              <div>
                <span class="badge text-bg-light border text-uppercase">{{ $match->match_source }}</span>
              </div>
              <small class="text-secondary">{{ Str::limit($match->ai_reason, 45) ?: '-' }}</small>
            </td>
            <td>
              @php
                $matchColor = match(strtolower($match->status)) {
                  'accepted' => 'text-bg-success',
                  'suggested' => 'text-bg-info',
                  'rejected' => 'text-bg-danger',
                  'expired' => 'text-bg-secondary',
                  default => 'text-bg-light'
                };
              @endphp
              <div class="dropdown d-inline">
                <button class="btn btn-sm badge {{ $matchColor }} dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                  {{ strtoupper($match->status) }}
                </button>
                <ul class="dropdown-menu">
                  @foreach(['suggested', 'accepted', 'rejected', 'expired'] as $st)
                    <li>
                      <form action="{{ route('admin.matches.update-status', $match) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $st }}">
                        <button type="submit" class="dropdown-item small text-capitalize {{ $match->status === $st ? 'active' : '' }}">
                          Mark as {{ $st }}
                        </button>
                      </form>
                    </li>
                  @endforeach
                </ul>
              </div>
            </td>
            <td class="text-end pe-3">
              <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" onsubmit="return confirm('Delete match #{{ $match->id }}?')" class="d-inline">
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
            <td colspan="7" class="text-center text-secondary py-4">No matches found matching criteria.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($matches->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $matches->links() }}
  </div>
  @endif
</div>
@endsection
