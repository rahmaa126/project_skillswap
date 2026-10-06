@extends('layouts.admin')

@section('title', 'User Details: '.$user->name.' | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">{{ $user->name }}</h2>
    <p class="text-secondary small mb-0">User profile, offered/wanted skills, and platform history</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none">Users</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row g-4">
  <!-- Profile Summary Card -->
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm border-0 text-center p-3 mb-4">
      <div class="card-body">
        <img
          src="{{ $user->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($user->id % 4) + 1).'.png') }}"
          class="rounded-circle border shadow-sm mb-3"
          width="110"
          height="110"
          alt="{{ $user->name }}"
        />
        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
        <p class="text-secondary small mb-2">{{ $user->email }}</p>

        <div class="d-flex justify-content-center gap-2 mb-3">
          <span class="badge {{ $user->role === 'admin' ? 'text-bg-danger' : 'text-bg-primary' }} text-uppercase">
            {{ $user->role }}
          </span>
          <span class="badge {{ $user->is_active ? 'text-bg-success' : 'text-bg-danger' }}">
            {{ $user->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>

        <p class="text-muted small fst-italic mb-3">
          "{{ $user->profile?->bio ?: 'No bio provided yet.' }}"
        </p>

        <ul class="list-group list-group-flush text-start small mb-3">
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary"><i class="bi bi-geo-alt me-1"></i> City</span>
            <span class="fw-semibold">{{ $user->profile?->city ?? 'N/A' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary"><i class="bi bi-telephone me-1"></i> Phone</span>
            <span class="fw-semibold">{{ $user->profile?->phone ?? 'N/A' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary"><i class="bi bi-star-fill text-warning me-1"></i> Avg Rating</span>
            <span class="fw-semibold">{{ $user->profile?->avg_rating ?? '0.00' }} / 5.0</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary"><i class="bi bi-arrow-left-right me-1"></i> Total Swaps</span>
            <span class="fw-semibold">{{ $user->profile?->total_swaps ?? 0 }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary"><i class="bi bi-calendar3 me-1"></i> Joined</span>
            <span class="fw-semibold">{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</span>
          </li>
        </ul>

        <div class="d-grid gap-2">
          <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Edit Profile & Role
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Detailed Tabs Card -->
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
          <li class="nav-item">
            <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-skills" type="button">
              <i class="bi bi-stars me-1 text-primary"></i> Skills ({{ $user->offeredSkills->count() + $user->wantedSkills->count() }})
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-scores" type="button">
              <i class="bi bi-graph-up me-1 text-success"></i> Skill Scores ({{ $user->skillScores->count() }})
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-reviews" type="button">
              <i class="bi bi-chat-quote me-1 text-warning"></i> Reviews ({{ $user->receivedReviews->count() }})
            </button>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <!-- Skills Tab -->
          <div class="tab-pane fade show active" id="tab-skills">
            <h6 class="fw-bold text-success mb-2"><i class="bi bi-arrow-up-circle me-1"></i> Skills Offered to Teach</h6>
            <div class="d-flex flex-wrap gap-2 mb-4">
              @forelse($user->offeredSkills as $offered)
                <span class="badge text-bg-light border p-2">
                  <span class="fw-semibold text-body">{{ $offered->skill->name }}</span>
                  <small class="text-secondary ms-1">({{ $offered->skill->category?->name ?? 'General' }})</small>
                </span>
              @empty
                <p class="text-secondary small">No offered skills listed.</p>
              @endforelse
            </div>

            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-arrow-down-circle me-1"></i> Skills Wanted to Learn</h6>
            <div class="d-flex flex-wrap gap-2">
              @forelse($user->wantedSkills as $wanted)
                <span class="badge text-bg-light border p-2">
                  <span class="fw-semibold text-body">{{ $wanted->skill->name }}</span>
                  <small class="text-secondary ms-1">({{ $wanted->skill->category?->name ?? 'General' }})</small>
                </span>
              @empty
                <p class="text-secondary small">No wanted skills listed.</p>
              @endforelse
            </div>
          </div>

          <!-- Scores Tab -->
          <div class="tab-pane fade" id="tab-scores">
            <div class="table-responsive">
              <table class="table table-sm align-middle">
                <thead>
                  <tr>
                    <th>Skill</th>
                    <th>Score</th>
                    <th>Battles Won</th>
                    <th>Battles Played</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($user->skillScores as $score)
                  <tr>
                    <td class="fw-semibold">{{ $score->skill->name }}</td>
                    <td>
                      <div class="progress" style="height: 18px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $score->score }}%">
                          {{ $score->score }} pts
                        </div>
                      </div>
                    </td>
                    <td><span class="badge text-bg-success">{{ $score->battles_won ?? 0 }}</span></td>
                    <td><span class="badge text-bg-secondary">{{ $score->battles_played ?? 0 }}</span></td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center text-secondary py-3">No skill scores calculated yet.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Reviews Tab -->
          <div class="tab-pane fade" id="tab-reviews">
            @forelse($user->receivedReviews as $review)
              <div class="p-3 border rounded mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-semibold">{{ $review->reviewer->name ?? 'Anonymous' }}</span>
                  <div>
                    @for($i = 1; $i <= 5; $i++)
                      <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                    @endfor
                  </div>
                </div>
                <p class="small text-secondary mb-1">{{ $review->comment ?: 'No written comment.' }}</p>
                <small class="text-muted">{{ $review->created_at ? $review->created_at->diffForHumans() : '' }}</small>
              </div>
            @empty
              <p class="text-secondary text-center py-3">No reviews received yet.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
